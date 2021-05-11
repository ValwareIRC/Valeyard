<?php

// command !stats for examp0l

hook::func("privmsg", function($u){
	global $gw,$sql;
	$parv = explode(" ",$u['parc']);
	$cmd = $parv[0];
	
	if ($cmd == "!stats"){
		$prefix = $cf['unrealtable'] ?? "unreal_";
		$table = $prefix."stats";
		$query = "SELECT * FROM $table";
		$result = $sql::query($query);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$gw->msg($u['dest'],"Current users: ".$row['currentusers']);
				$gw->msg($u['dest'],"Max users: ".$row['maxusers']);
				$gw->msg($u['dest'],"Server uptime: ".$row['uptime']);
				$gw->msg($u['dest'],"Channels formed: ".$row['channels']);				
			}
		}
		$table = $prefix."channel";
		$query = "SELECT * FROM $table";
		$result = $sql::query($query);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
				$gw->msg($u['dest'],"Channel: ".$row['channel']." - Users: ".$row['usercount']." - Modes: ".$row['modes']);
			}
		}
		mysqli_free_result($result);
	}
});

// runs every ping, because ping frequency is usually a good hitting point
hook::func("ping", function($u){
	sendstatshit();
});
// on connect too
hook::func("connect", function($u){
	sendstatshit();
});

function sendstatshit(){
	global $gw,$cf,$sql;
	sqlGo(); // SQL, I choose you! *throws PokSQLéball*
	// clear 0p s0me tab0ls f0rst, you mor0n!

	$prefix = $cf['unrealtable'] ?? "unreal_";
	$query = "DELETE FROM ".$prefix."gstats WHERE id IS NOT NULL";
	$sql::query($query);
	$query = "DELETE FROM ".$prefix."cmdstat WHERE id IS NOT NULL";
	$sql::query($query);
	
	// stats to grab, obviously, you idiot!
	$gw->sendraw("LUSERS");
	$gw->sendraw("STATS u");
	$gw->sendraw("LIST");
	// we wait until we have oper for cmdstat and gstats
}
hook::func("numeric", function($u){
	// glow-balls
	global $gw,$cf,$sql;
	$prefix = $cf['unrealtable'] ?? "unreal_";
	// our numeric = $n
	$n = $u['numeric'];
	$parv = explode(" ",$u['parc']);
	if ($n == 266){
		$table = $prefix."stats";
		$query = "UPDATE $table SET currentusers='".$parv[3]."', maxusers='".$parv[4]."'";
		$sql::query($query);
	}
	elseif ($n == 242){
		$table = $prefix."stats";
		$query = "UPDATE $table SET uptime='".$parv[5]."'";
		$sql::query($query);
	}
	elseif ($n == 254){
		$table = $prefix."stats";
		$query = "UPDATE $table SET channels='".$parv[3]."'";
		$sql::query($query);
	}
	elseif ($n == 322){
		$table = $prefix."channel";
		$chan = $parv[3];
		$usercount = $parv[4];
		$modes = trim($parv[5],":[]");
		if (!IsUnrealChan($chan)){
			$query = "INSERT INTO $table (channel, usercount, modes) VALUES ('$chan','$usercount','$modes')";
		}
		else {
			$query = "UPDATE $table SET usercount='$usercount', modes='$modes' WHERE channel = '$chan'";
		}
		
		if ($query){ $sql::query($query); }
		else { return; }
		
		$gw->sendraw("TOPIC $chan");
	}
	elseif ($n == 332){
		$table = $prefix."channel";
		$chan = $parv[3];
		$toTrim = "$parv[0] $parv[1] $parv[2] $parv[3] :";
		$topic = str_replace($toTrim,"",$u['parc']);
		if (!IsUnrealChan($chan)){ return; }
		$query = "UPDATE $table SET topic='$topic' WHERE channel = '$chan'";
		$sql::query($query);
	}
	elseif ($n == 333){
		$table = $prefix."channel";
		$chan = $parv[3];
		$nick = $parv[4];
		$time = $parv[5];
		
		if (!IsUnrealChan($chan)){ return; }
		$query = "UPDATE $table SET topicby='$nick', topicset='$time' WHERE channel = '$chan'";
		$sql::query($query);
	}
	elseif ($n == 212){
		$table = $prefix."cmdstat";
		$cmd = $parv[3];
		$count = $parv[4];
		if (!IsUnrealCmdStat($cmd)){
			
			$query = "INSERT INTO $table (command, count) VALUES ('$cmd', '$count')";
			
		}
		else {
			
			$query = "UPDATE $table count='$count' WHERE command='$cmd'";
		}
		$sql::query($query);
	}
	elseif ($n == 223 && $parv[3] === "G"){
		$table = $prefix."gstats";
		$mask = $parv[4];
		$secondsRemaining = $parv[5];
		$setSecondsAgo = $parv[6];
		$setby = $parv[7];
		$toTrim = "$parv[0] $parv[1] $parv[2] $parv[3] $parv[4] $parv[5] $parv[6] $parv[7] ";
		$reason = ltrim(str_replace($toTrim,"",$u['parc']),":");
		$query = "INSERT INTO $table (
			mask,
			setSecondsAgo,
			secondsRemaining,
			setby,
			reason
		) VALUES (
		
			'$mask',
			'$setSecondsAgo',
			'$secondsRemaining',
			'$setby',
			'$reason'
		)";
		
		$sql::query($query);
	}
	elseif ($n == 381) { 
		$gw->sendraw("STATS G");
		$gw->sendraw("STATS M");
	}
});
function IsUnrealCmdStat($cmd){
	global $cf,$sql;
	if (!isset($chan)) { return false; }
	$prefix = $cf['unrealtable'] ?? "unreal_";
	$query = "SELECT * FROM ".$prefix."cmdstat WHERE command = '$cmd'";
	$result = $sql::query($query);
	if (!isset($result) || !$result){ createCmdTable("cmdstat"); return false; }
	if (mysqli_num_rows($result) > 0){ $return = true; }
	else { $return = false; }
	mysqli_free_result($result);
	return $return;
}
	
function IsUnrealChan($chan){
	global $cf,$sql;
	if (!isset($chan)) { return false; }
	$prefix = $cf['unrealtable'] ?? "unreal_";
	$query = "SELECT * FROM ".$prefix."channel WHERE channel = '$chan'";
	$result = $sql::query($query);
	if (!isset($result) || !$result){ createChanTable("channel"); return false; }
	if (mysqli_num_rows($result) > 0){ $return = true; }
	else { $return = false; }
	mysqli_free_result($result);
	return $return;
}

function sqlGo(){
	global $cf,$sql;
	
	if (!checkSqlTableExists("stats")) { createStatsTable("stats"); }
	if (!checkSqlTableExists("channel")) { createChanTable("channel"); }
	if (!checkSqlTableExists("cmdstat")) { createCmdTable("cmdstat"); }
	if (!checkSqlTableExists("gstats")) { createGstatTable("gstats"); }
}
	
function checkSqlTableExists($table){
	//glow-balls
	global $sql,$cf,$gw;
	
	$prefix = $cf['unrealtable'] ?? "unreal_";
	$table = $prefix.$table;
	$query = "SELECT * FROM $table";
	$result = $sql::query($query);
	if (!$result){ return false; }
	else if (mysqli_num_rows($result) == 0) { return false; }
	
	else { return true; }
}

function createStatsTable($table){
	global $sql,$cf;
	if (!$table){ return; }
	$prefix = $cf['unrealtable'] ?? "unreal_";
	$table = $prefix."stats";
	$query = "CREATE TABLE $table (

		currentusers INT(6) NOT NULL,
		maxusers INT(6) NOT NULL,
		uptime INT(6) NOT NULL,
		channels INT(6) NOT NULL
	)";
	$sql::query($query);
	
	// set null values
	$query = "INSERT INTO $table (currentusers, maxusers, uptime, channels) VALUES ('0', '0', '0', '0')";
	$sql::query($query);
}

function createChanTable($table){
	global $sql,$cf;
	if (!$table){ return; }
	$prefix = $cf['unrealtable'] ?? "unreal_";
	$table = $prefix.$table;
	
	// may want to adjust some of these depending on your config.h changes
	$query = "CREATE TABLE $table (
		channelid INT(6) NOT NULL AUTO_INCREMENT,
		channel VARCHAR(33) NOT NULL,
		usercount INT(6) NOT NULL,
		topic VARCHAR(360),
		topicby VARCHAR(360),
		topicset INT(11),
		modes VARCHAR(100),
		PRIMARY KEY (channelid)
	)";
	$sql::query($query);
}

function createCmdTable($table){
	global $sql,$cf;
	if (!$table){ return; }
	$prefix = $cf['unrealtable'] ?? "unreal_";
	$table = $prefix.$table;
	
	$query = "CREATE TABLE $table (
		id INT(2) NOT NULL AUTO_INCREMENT,
		command VARCHAR(30) NOT NULL,
		count INT(18) NOT NULL,
		PRIMARY KEY (id)
	)";
	$sql::query($query);
}
function createGstatTable($table){
	global $sql,$cf;
	if (!$table){ return; }
	$prefix = $cf['unrealtable'] ?? "unreal_";
	$table = $prefix.$table;
	
	$query = "CREATE TABLE $table (
		id INT(2) NOT NULL AUTO_INCREMENT,
		mask VARCHAR(360) NOT NULL,
		setSecondsAgo VARCHAR(20) NOT NULL,
		secondsRemaining VARCHAR(20) NOT NULL,
		setby VARCHAR(360) NOT NULL,
		reason VARCHAR(360) NOT NULL,
		PRIMARY KEY (id)
	)";
	$sql::query($query);
}

?>
