<?php

/*

	Description:

	This module aims to fix a problem that occurs when using
	Anope's m_sql_authentication
	
	Problem:
	
	When a user registers with the chosen registration
	software, Anope does not pick it up and create an account
	for the user until they first identify with NickServ,
	only after which Anope Services will recognise them as
	a user.
	
	Solution:
	
	We do some checks on connecting users assuming you've
	compiled Anope in English.**
	
	First, we listen for the server to tell us that a user is
	connecting. If they SASL with an account, we let them pass.
	
	If they do not sasl we check:
	
	-If they are registered in Anope, Anope will ask them to
		identify, we let them pass.
	
	-If they are not registered in Anope, we check if they are
		registered in WordPress.
		
	-If they are not registered in WordPress either, it means
		they are a guest, we let them pass.
		
	-If they are registered in WordPress, it means they have
		not yet identified to NickServ for the first time,
		and NickServ does not recognise them as a user.
		This is the error.
		
		Upon this, we send a notice to the user in the same
		way NickServ does, in the case where NickServ SHOULD
		but DOESN'T
		
		Like NickServ, if they do not identify after a certain
		amount of time, their nick is changed to a guest nick
		
	
	**If you compiled in another language, feel free to request 
		one that works in your language.
	
	
	Requirements:
	
	UnrealIRCd 5.0.9 or later
	Bot to have an O-Line
	MySQL database properly configured
	Anope with m_sql_authentication properly configured

*/


// make sure our tables done exist
hook::func("preconnect", function($u){

	global $sql;
	
	$query = "CREATE TABLE IF NOT EXISTS valeyard_anopefix_timer (
	
		UUID VARCHAR(20) NOT NULL,
		nick VARCHAR(60) NOT NULL,
		nick_ts VARCHAR(12) NOT NULL
		
	)";
	$sql::query($query);
	$query = "TRUNCATE TABLE valeyard_anopefix_timer";
	$sql::query($query);
});


// make it so we can view nick changes on all servers lol
hook::func("mode", function($u){
	
	global $gw,$me;
	$parv = explode(" ",$u['parc']);
	
	if ($u['dest'] == $me){ return; }
	
	if ($parv[0][0] !== "+" && $parv[0][0] !== "-"){ return; }
	
	if ($parv[0][0] == "-"){
		
		if (strpos($parv[0],"+") === false) { return; }
		
		$tok = explode("+",$parv[0]);
		$mode = $tok[1];
	}
	elseif ($parv[0][0] == "+"){
		$mode = ltrim($parv[0],"+");
	}
	
	
	// the modes we have are modes that have been SET and not UNSET
	// we're here because we want to view local and global nick changes fam
	
	if (strpos($mode,"o") === false){ return; }
	
	$gw->sendraw("MODE $me +s +nN");
	
	
});



hook::func("notice", function($u){
	
	global $gw,$ns,$cf,$sql;
	// if is not a server notice, return
	if (!IsServer($u['nick'])){ return; }
	
	// explodem
	$parv = explode(" ",$u['parc']);
	
	// Grab nick of connecting user
	$nick = $parv[3];
	
	// check the notice is a connection notice (requires o-line)
	if ($parv[0] !== "***"){ return; }
	if ($parv[1] !== "Client"){ return; }
	if ($parv[2] !== "connecting:"){ return; }
	
	// if they logged in
	if (strpos($u['parc'],"[account") !== false){ return; }
	
	// if they are already an anope user, let them pass, NickServ will handle
	if (IsAnopeUser($nick)){ return; }

	// if they are not a wordpress user, they are a guest, let them pass
	if (!WPIsUser(WPLogin2ID($nick))){ return; }

	// YOU SHALL NOT PASS
	
	// two minutes into le future
	$time = time() + 60;
	sleep(1);
	vyAnopefixInsert(UUID($nick),$nick,$time);
	
	$gw->notice($nick,"That nickname is registered and protected. If it is your");
	$gw->notice($nick,"nick, type /msg NickServ IDENTIFY password. Otherwise,");
	$gw->notice($nick,"please choose a different nick.");
	$gw->notice($nick,"If you do not identify, I will change your nick.");
	
	return;
});

hook::func("notice", function($u){
	
	global $gw,$ns,$cf,$sql;
	// if is not a server notice, return
	if (!IsServer($u['nick'])){ return; }
	
	// explodem
	$parv = explode(" ",$u['parc']);
	if (!isset($parv[8])){ return; }
	// Grab nick of connecting user
	$nick = $parv[1];
	
	if (!($toCheck = $parv[3]." ".$parv[4]." ".$parv[5]." ".$parv[6]." ".$parv[7])){ return; }
	if ($toCheck !== "has changed their nickname to"){ return; }
	
	$newNick = $parv[8];
	
	$query = "SELECT * FROM valeyard_anopefix_timer WHERE nick = '$nick'";
	if (!($result = $sql::query($query))){ return; }
	
	
	if (!IsAnopeUser($newNick)){
	
		vyAnopefixDelete($nick);
		return; 
	}

	if (WPIsUser(WPLogin2ID($nick))){
	
		vyAnopefixDelete($nick);
		return; 
	}
	
	$time = time() + 60;
	
	
	vyAnopefixUpdate($nick,$newNick,$time);
	
});

hook::func("notice", function($u){
	
	global $gw,$ns,$cf,$sql;
	// if is not a server notice, return
	if (!IsServer($u['nick'])){ return; }
	
	// explodem
	$parv = explode(" ",$u['parc']);
	if (!isset($parv[8])){ return; }
	// Grab nick of connecting user
	$nick = $parv[1];
	
	if (!($toCheck = $parv[3]." ".$parv[4]." ".$parv[5]." ".$parv[6]." ".$parv[7])){ return; }
	if ($toCheck !== "has changed their nickname to"){ return; }
	
	$newNick = $parv[8];
	
	
	$query = "SELECT * FROM valeyard_anopefix_timer WHERE nick = '$nick'";
	$result = $sql::query($query);
	if (mysqli_num_rows($result) > 0){ return; }
	
	
	if (IsAnopeUser($newNick)){ return; }

	// if they are a wordpress user, need to identify
	if (!WPIsUser(WPLogin2ID($newNick))){ return; }

	// YOU SHALL NOT PASS
	
	// two minutes into le future
	$time = time() + 60;
	sleep(1);
	vyAnopefixInsert(UUID($newNick),$newNick,$time);
	
	$gw->notice($newNick,"That nickname is registered and protected. If it is your");
	$gw->notice($newNick,"nick, type /msg NickServ IDENTIFY password. Otherwise,");
	$gw->notice($newNick,"please choose a different nick.");
	$gw->notice($newNick,"If you do not identify, I will change your nick.");
	
	return;
	
});
	

hook::func("ping", function($u){
	
	global $sql,$gw;
	
	$query = "SELECT * FROM valeyard_anopefix_timer";
	if (!($result = $sql::query($query))){ return; }
	if (mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
			
			if ($row['nick_ts'] <= time()){
				vyAnopefixDelete($row['nick']);
				if (!account($row['nick'])){ $gw->os("SVSNICK ".$row['nick']." Guest".rand(1111,9999)); }
				
			}
		}
	}
	mysqli_free_result($result);
});
				
	

	

// quick sql funcs
function vyAnopefixInsert($uuid,$nick,$ts){
	
	global $sql;
	
	$query = "INSERT INTO valeyard_anopefix_timer (UUID, nick, nick_ts) VALUES ('$uuid','$nick','$ts')";
	$sql::query($query);
	
}

function vyAnopefixUpdate($oldNick,$nick,$ts){
	
	global $sql;
	
	$query = "UPDATE valeyard_anopefix_timer SET nick='$nick',nick_ts='$ts' WHERE nick='$oldNick'";
	$sql::query($query);
	
}

function vyAnopefixDelete($nick){
	
	global $sql;
	
	$query = "DELETE FROM valeyard_anopefix_timer WHERE nick='$nick'";
	$sql::query($query);
	
}