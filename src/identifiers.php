<?php

// If user is online
function IsOnline($nick){
	global $sql;
	$query = "SELECT LOWER(nick) AS lnick FROM anope_user WHERE lnick = '".strtolower($nick)."'";
	$result = $sql::query($query);
	if (mysqli_num_rows($result) > 0) { return true; }
	else { return false; }	
}
// If user is logged in
function IsLoggedin($nick){
	global $sql;
	$query = "SELECT LOWER(nick) AS lnick, LOWER(account) AS laccount FROM anope_user WHERE nick = '".$nick."' AND account = '".$nick."'";
	$result = $sql::query($query);
	if ($result == NULL) { return false; }
	if (mysqli_num_rows($result) > 0) {
		return true; 
	}
	else { return false; }
}
function CsInfo($chan,$option){
	global $sql;
	$query = "SELECT * FROM anope_chan WHERE channel = '".$chan."'";
	$result = $sql::query($query);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$s = $row[$option];
		}
		mysqli_free_result($result);
		return $s;
	}
	else return NULL;
}
function IsChan($chan){
	if (CsInfo($chan,'chanid') !== NULL) { return true; }
	else { return false; }
}