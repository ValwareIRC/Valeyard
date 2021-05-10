<?php

hook::func("privmsg", function($u){
	global $gw,$sql;
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
	// glow-balls
	global $gw;
	sqlGo();
	// stats to grab, you idiot
	$gw->sendraw("LUSERS");
	$gw->sendraw("STATS u");

});
hook::func("numeric", function($u){
	// glow-balls
	global $gw,$cf,$sql;
	$prefix = $cf['unrealtable'] ?? "unreal_";
	$table = $prefix."stats";
	// our numeric = $n
	$n = $u['numeric'];
	
	if ($n == 266){
		$parv = explode(" ",$u['parc']);
		$query = "UPDATE $table SET currentusers='".$parv[3]."', maxusers='".$parv[4]."'";
		$sql::query($query);
	}
	elseif ($n == 242){
		$parv = explode(" ",$u['parc']);
		$query = "UPDATE $table SET uptime='".$parv[5]."'";
		$sql::query($query);
	}
	elseif ($n == 254){
		$parv = explode(" ",$u['parc']);
		$query = "UPDATE $table SET channels='".$parv[3]."'";
		$sql::query($query);
	}
});
		

function sqlGo(){
	global $cf,$sql;
	$prefix = $cf['unrealtable'] ?? "unreal_";
	$table = $prefix."stats";
	if (!checkSqlTableExists($table)) { createSqlTable($table); }
}
	
function checkSqlTableExists(){
	//glow-balls
	global $sql,$cf,$gw;
	
	$prefix = $cf['unrealtable'] ?? "unreal_";
	$table = $prefix."stats";
	$query = "SELECT * FROM $table";
	$result = $sql::query($query);
	if (!$result){ return false; }
	else if (mysqli_num_rows($result) == 0) { return false; }
	
	else { return true; }
}

function createSqlTable($table){
	global $sql;
	if (!$table){ return; }
	
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
function putIntoSql($column,$value){
	global $cf,$sql;
	$prefix = $cf['unrealtable'] ?? "unreal_";
	$table = $prefix."stats";
	$query = "UPDATE $table SET ".$column."='".$value."'";
	$sql::query($query);
}
function unrealquery($column){
	global $cf,$sql;
	$prefix = $cf['unrealtable'] ?? "unreal_";
	$table = $prefix."stats";
	$query = "SELECT * FROM $table";
	$result = $sql::query($query);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$return = $row[$column];
		mysqli_free_result($result);
	}
	if ($return) { return $return; }
	else { return NULL; }
}

?>