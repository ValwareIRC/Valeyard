<?
/*
	This module does NOT generate stats.
	This module is made directly for the WIP UnrealIRCd module by k4be: `wwwstats`
	Readme: https://github.com/pirc-pl/unrealircd-modules/blob/master/README.md#wwwstats-mysql

*/

hook::func("privmsg", function($u){
	global $gw,$sql,$me;

    // convenience identifiers
    $nick = $u['nick'];
    $parv = explode(" ",$u['parc']);
    $cmd = $parv[0];
    $target = $u['dest'];
	
	//get message tag
	$tags = explode(";",$u['mtags']);
	for ($i = 0; $tags[$i];){
		$tag = explode("=",$tags[$i]);
		if ($tag[0] != "msgid") { $i++; }
		else {
			$msgid = $tag[1]; break;
		}
	}
    // if it's a PM, ignore it
    if ($target == $me) { return; }
	
	if ($cmd !== "!chanstats"){ return; }
	if (!($chan = $parv[1])){ return; }
	
	$gw->msg($target,"Channel stats for $chan");
	$gw->msg($target,"Topic: ".wwwchantopic($chan));
	$gw->msg($target,"Created ".wwwchancreate($chan));
	$gw->msg($target,"Users on $chan: ".wwwchanusercount($chan));
});


// Function to search the tables
function wwwstat($table,$column,$search,$acolumn){
	global $sql;
	
	// return if incorrect params, you moron
	if (($table !== "chanlist" && $table !== "stat") || !isset($table) || !isset($column) || !isset($search)) { return; }
	
	$result = NULL; // set for later
	$return = NULL; // set for later
	
	$query = "SELECT * FROM $table WHERE $column='$search'";
	$result = $sql->query($query);
	
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)){
			$return = $row[$acolumn] ?? false;
		}
	}
	mysqli_free_result($result);
	
	return $return;
}

// chanstats
function wwwchanstat($channel,$search){
	if (!$channel || !$search){ return; }
	$result = NULL;
	if (!($result = wwwstat("chanlist","name",$channel,$search))){ return; }
	return $result;
}

function wwwchantopic($channel){
	if (!$channel){ return; }
	$result = NULL;
	if (!($result = wwwchanstat($channel,"topic"))){ return; }
	return $result;
}
function wwwchancreate($channel){
	if (!$channel){ return; }
	$result = NULL;
	if (!($result = wwwchanstat($channel,"date"))){ return; }
	$result = gmdate("Y-m-d\TH:i:s\Z", $result);
	return $result;
}
function wwwchanusercount($channel){
	if (!$channel){ return; }
	$result = NULL;
	if (!($result = wwwchanstat($channel,"users"))){ return; }
	return $result;
}
function wwwchanmsgcount($channel){
	if (!$channel){ return; }
	$result = NULL;
	if (!($result = wwwchanstat($channel,"messages"))){ return; }
	return $result;
}


//
