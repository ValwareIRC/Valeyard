<?php

// command !stats

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
	global $gw;
	sqlGo();
	// stats to grab, obviously, you idiot!
	$gw->sendraw("LUSERS");
	$gw->sendraw("STATS u");
	$gw->sendraw("LIST");
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
	
		
});
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
	global $sql;
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



?>