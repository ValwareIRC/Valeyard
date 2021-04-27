<?php

//			~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// This module provides identifiers that allow you to script with quick access to anope's tables
// Must have Anope using db_sql_live
// must add in new line in configuration for your anope table prefix.
// for example if your anope tables look like this anope_SomeTable then your config should look like:
//
// 'anopetable' => 'anope_',
//			~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// if we do not have your anope table with this module installed, it is not needed lol
global $gw,$cf;
if (isset($cf['anopetable'])) { $gw->shout("ERROR: No anope configuration was listed in the config. Please fix!"); die; }


// Is this nick registered with NickServ
// use:
//		if (IsAnopeUser($nick)) { $gw->msg($nick,"Welcome as fuck!"); }
// or
//		if (!IsAnopeUser($nick)) { $gw->msg($nick,"Why aren't you even regisTurd?!!?"); }
//
function IsAnopeUser($nick){
	
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT * FROM " .$p. "NickCore WHERE display = '".$nick."'";
	$result = $sql::query($query);
	if (mysqli_num_rows($result) > 0) { $ind = true; }
	else { $ind = false; }
	mysqli_free_result($result);
	
	return $ind;
}

function NSInfo($nick,$search){
	
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT * FROM " .$p. "user WHERE nick = '".$nick."'";
	$result = $sql::query($query);
	if (mysqli_num_rows($result) > 0) { 
		while($row = mysqli_fetch_assoc($result)){
			$ind = $row[$search];
		}
	}
	if (!isset($ind)){ $ind = false; }
	mysqli_free_result($result);
	return $ind;
}

function IsAjoinEntry($nick,$chan){
	
	global $cf,$sql,$gw;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT * FROM ".$p. "AJoinEntry WHERE channel = '$chan' AND owner = '$nick'";
	$result = $sql::query($query);
	if (mysqli_num_rows($result) > 0) {
		$return = true;
	}
	else { $return = false; }
	mysqli_free_result($result);
	return $return;
}

function AddAjoinEntry($nick,$chan){
	
	global $cf,$sql,$gw;
	
	$p = $cf['anopetable'];
	
	$error = NULL;
	
	if (!IsAnopeUser($nick)){ $error = $nick." is not a registered user."; }
	elseif (!IsAnopeChan($chan)){ $error = $chan." is not a registered channel."; }
	elseif (IsAjoinEntry($nick,$chan)){ $error = $chan." is already in $nick's ajoin list."; }

	if ($error) { $gw->hear($error); return $error; }
	else {
		
		$query = "INSERT INTO ".$p. "AJoinEntry (channel, owner) VALUES ('$chan', '$nick')";
		$sql::query($query);
		return 1;
	}
}
function DelAjoinEntry($nick,$chan){
	
	global $cf,$sql,$gw;
	
	$p = $cf['anopetable'];
	
	$error = NULL;
	
	if (!IsAnopeUser($nick)){ $error = $nick." is not a registered user."; }
	elseif (!IsAnopeChan($chan)){ $error = $chan." is not a registered channel."; }
	elseif (!IsAjoinEntry($nick,$chan)){ $error = $chan." is not in $nick's ajoin list."; }

	if ($error) { $gw->hear($error); return $error; }
	else {
		
		$query = "DELETE FROM ".$p. "AJoinEntry WHERE channel = '$chan' AND owner = '$nick'";
		$sql::query($query);
		return 1;
	}
}
function IsAnopeChan($chan){
	
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT * FROM " .$p. "ChannelInfo WHERE name = '".$chan."'";
	$result = $sql::query($query);
	if (mysqli_num_rows($result) > 0) { $ind = true; }
	else { $ind = false; }
	mysqli_free_result($result);
	
	return $ind;
}
function IsChan($chan){
	
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT * FROM " .$p. "chan WHERE channel = '".$chan."'";
	$result = $sql::query($query);
	if (mysqli_num_rows($result) > 0) { $ind = true; }
	else { $ind = false; }
	mysqli_free_result($result);
	
	return $ind;
}


//check if a user is online
//use:
//		if (IsOnline($nick)) { $gw->shout("This nick is online"; }

function IsOnline($nick){
	
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT * FROM ".$p. "user WHERE nick = '".$nick."'";
	$result = $sql::query($query);
	if (mysqli_num_rows($result) > 0) { $ind = true; }
	else { $ind = false; }
	mysqli_free_result($result);
	
	return $ind;
}
// check by ID of nick
function ID2Nick($id){
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT * FROM ".$p. "user WHERE nickid = '".$id."'";
	$result = $sql::query($query);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$display = $row['display'];
	}
	else { $display = false; }
	mysqli_free_result($result);
	return $display;
}

//check nick ID

function Nick2ID($nick){
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT * FROM ".$p. "user WHERE nick = '".$nick."'";
	$result = $sql::query($query);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$id = $row['nickid'];
	}
	else { $id = false; }
	mysqli_free_result($result);
	return $id;
}

// check by ID of channel
function ID2Chan($id){
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT * FROM ".$p. "chan WHERE chanid = '".$id."'";
	$result = $sql::query($query);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$display = $row['channel'];
	}
	else { $display = false; }
	mysqli_free_result($result);
	return $display;
}


//check channel ID
function Chan2ID($chan){
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT * FROM ".$p. "chan WHERE channel = '".$chan."'";
	$result = $sql::query($query);

	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)){
			$id = $row['chanid'];
		}
	}
	else { $id = false; }
	mysqli_free_result($result);
	return $id;
}
			
	

//check if a user is on a channel
function IsOn($nick,$chan){
	
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT * FROM ".$p. "ison WHERE chanid = '".Chan2ID($chan)."'";
	$result = $sql::query($query);
	
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)){
			if ($row['nickid'] == Nick2ID($nick)) { $ison = true; }
		}
		if (!$ison) { $ison = false; }
	}
	else { $ison = false; }
	
	mysqli_free_result($result);
	
	return $ison;
}

function hostname($nick){
	
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT vhost FROM ".$p. "user WHERE nick = '".$nick."'";
	$result = $sql::query($query);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$vhost = $row['vhost'];
	}
	else { $vhost = false; }
	mysqli_free_result($result);
	return $vhost;
}

function ident($nick){
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT ident FROM ".$p. "user WHERE nick = '".$nick."'";
	$result = $sql::query($query);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$ident = $row['ident'];
	}
	else { $ident = false; }
	mysqli_free_result($result);
	return $ident;
}
function gecos($nick){
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT realname FROM ".$p. "user WHERE nick = '".$nick."'";
	$result = $sql::query($query);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$gecos = $row['realname'];
	}
	else { $gecos = false; }
	mysqli_free_result($result);
	return $gecos;
}
function account($nick){
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT account FROM ".$p. "user WHERE nick = '".$nick."'";
	$result = $sql::query($query);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$account = $row['account'];
	}
	else { $account = false; }
	mysqli_free_result($result);
	return $account;
}
function isaway($nick){
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT away FROM ".$p. "user WHERE nick = '".$nick."'";
	$result = $sql::query($query);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		if ($row['away'] == "Y"){ $isaway = true; }
	}
	else { $isaway = false; }
	mysqli_free_result($result);
	return $isaway;
}
function awaymsg($nick){
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT awaymsg FROM ".$p. "user WHERE nick = '".$nick."'";
	$result = $sql::query($query);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$awaymsg = $row['awaymsg'];
	}
	else { $awaymsg = false; }
	mysqli_free_result($result);
	return $awaymsg;
}
function userip($nick){
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT ip FROM ".$p. "user WHERE nick = '".$nick."'";
	$result = $sql::query($query);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$ip = $row['ip'];
	}
	else { $ip = false; }
	mysqli_free_result($result);
	return $ip;
}
function usermode($nick){
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT modes FROM ".$p. "user WHERE nick = '".$nick."'";
	$result = $sql::query($query);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$usermode = $row['modes'];
	}
	else { $usermode = false; }
	mysqli_free_result($result);
	return $usermode;
}
function UUID($nick){
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT uuid FROM ".$p. "user WHERE nick = '".$nick."'";
	$result = $sql::query($query);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$UUID = $row['uuid'];
	}
	else { $UUID = false; }
	mysqli_free_result($result);
	return $UUID;
}
function ctcpversion($nick){
	global $cf,$sql;
	
	$p = $cf['anopetable'];
	
	$query = "SELECT version FROM ".$p. "user WHERE nick = '".$nick."'";
	$result = $sql::query($query);
	
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$version = $row['version'];
	}
	else { $version = false; }
	mysqli_free_result($result);
	return $version;
}

function hostmask($nick){
	return $nick."!".ident($nick)."@".hostname($nick);
}
function fullhost($nick){
	return $nick."!".ident($nick)."@".hostname($nick)."#".gecos($nick);
}


?>

