<?php
/*

	WARNING: This module is in beta. Use at your own risk.
	
	This module lets you use SQL as a client-backend for Valeyard.
	It have a $me2 global so you can do things like:
	
	$me2->nick(); 		// will return your nick
	$me2->account(); 	// will return your account

*/


/* classes for de client variabels for easy-scriptin' */

class clientself {
	function nick(){
		global $sql;
		$query = "SELECT meta_value FROM valeyard_client_userinfo WHERE type = 'me' AND meta_key = 'nick'";
		if (!mysqli_num_rows($result = $sql::query($query))){ return false; }
		$row = mysqli_fetch_assoc($result);
		return $row['meta_value'];
	}
	function account(){
		global $sql;
		$query = "SELECT meta_value FROM valeyard_client_userinfo WHERE type = 'me' AND meta_key = 'account'";
		if (!mysqli_num_rows($result = $sql::query($query))){ return false; }
		$row = mysqli_fetch_assoc($result);
		return $row['meta_value'];
	}
	function hostmask(){
		global $sql;
		$query = "SELECT meta_value FROM valeyard_client_userinfo WHERE type = 'me' AND meta_key = 'hostmask'";
		if (!mysqli_num_rows($result = $sql::query($query))){ return false; }
		$row = mysqli_fetch_assoc($result);
		return $row['meta_value'];
	}
	function usermode(){
		global $sql;
		$query = "SELECT meta_value FROM valeyard_client_userinfo WHERE type = 'me' AND meta_key = 'usermode'";
		if (!mysqli_num_rows($result = $sql::query($query))){ return false; }
		$row = mysqli_fetch_assoc($result);
		return $row['meta_value'];
	}
	function ident(){
		global $sql;
		$query = "SELECT meta_value FROM valeyard_client_userinfo WHERE type = 'me' AND meta_key = 'ident'";
		if (!mysqli_num_rows($result = $sql::query($query))){ return false; }
		$row = mysqli_fetch_assoc($result);
		return $row['meta_value'];
	}
}
$me2 = new clientself();


hook::func("preconnect", function($u){
	global $sql,$gw;
	$query = "CREATE TABLE IF NOT EXISTS valeyard_client_serverinfo (
			type varchar(500) NOT NULL,
			meta_key varchar(500) NOT NULL,
			meta_value varchar(500) NOT NULL
		)";
		$sql::query($query);
	$query = "CREATE TABLE IF NOT EXISTS valeyard_client_userinfo (
			type varchar(500) NOT NULL,
			meta_key varchar(500) NOT NULL,
			meta_value varchar(500) NOT NULL
		)";
		$sql::query($query);
		
	$query = "CREATE TABLE IF NOT EXISTS valeyard_client_channelinfo (
			type varchar(500) NOT NULL,
			meta_key varchar(500) NOT NULL,
			meta_value varchar(500) NOT NULL
		)";
		$sql::query($query);
		
	$query = "TRUNCATE TABLE valeyard_client_serverinfo";
	$sql::query($query);
	
	$query = "TRUNCATE TABLE valeyard_client_userinfo";
	$sql::query($query);
	$query = "TRUNCATE TABLE valeyard_client_channelinfo";
	$sql::query($query);
});

hook::func("numeric", function($u){
	
	global $sql,$gw,$me;
	$p = $u['parc'];
	$parv = explode(" ", $p);
	$n = $u['numeric'];
	if ($n == 001){
		$query = "INSERT INTO valeyard_client_userinfo (type, meta_key, meta_value) VALUES ('me', 'nick','$me')";
		$sql::query($query);
	}
	elseif ($n == 004){
		$parv = explode(" ",$p);
		$servername = $parv[3];
		$version = $parv[4];
		$usermodes = $parv[5];
		$chanmodes = $parv[6];
		$extcmodes = $parv[7];
		$query = "INSERT INTO valeyard_client_serverinfo (type, meta_key, meta_value) VALUES ('MYINFO', 'servername','$servername')";
		$sql::query($query);
		$query = "INSERT INTO valeyard_client_serverinfo (type, meta_key, meta_value) VALUES ('MYINFO', 'version','$version')";
		$sql::query($query);
		$query = "INSERT INTO valeyard_client_serverinfo (type, meta_key, meta_value) VALUES ('MYINFO', 'usermodes','$usermodes')";
		$sql::query($query);
		$query = "INSERT INTO valeyard_client_serverinfo (type, meta_key, meta_value) VALUES ('MYINFO', 'chanmodes','$chanmodes')";
		$sql::query($query);
		$query = "INSERT INTO valeyard_client_serverinfo (type, meta_key, meta_value) VALUES ('MYINFO', 'extcmodes','$extcmodes')";
		$sql::query($query);
	}
	elseif ($n == 005){ 
		for ($i = 1; isset($parv[$i]); $i++){
			$tok = explode("=",$parv[$i]);
			if (!isset($tok[1])){ continue; }
			$query = "INSERT INTO valeyard_client_serverinfo (type, meta_key, meta_value) VALUES ('ISUPPORT', '".$tok[0]."','".$tok[1]."')";
			$sql::query($query);
		}
	}
	elseif ($n == 396){
		$query = "SELECT * FROM valeyard_client_userinfo WHERE type = 'me' AND meta_key = 'hostmask'";
		if (!mysqli_num_rows($result = $sql::query($query))){
			$query = "INSERT INTO valeyard_client_userinfo (type, meta_key, meta_value) VALUES ('me','hostmask','".$parv[3]."')";
			$sql::query($query);
		}
		else {
			$query = "UPDATE valeyard_client_userinfo SET meta_value = '".$parv[3]."' WHERE meta_key = 'hostmask'";
			$sql::query($query);
		}
			
	}
	elseif ($n == 900){
		$query = "SELECT * FROM valeyard_client_userinfo WHERE type = 'me' AND meta_key = 'account'";
		if (!mysqli_num_rows($result = $sql::query($query))){
			$query = "INSERT INTO valeyard_client_userinfo (type, meta_key, meta_value) VALUES ('me','account','".$parv[4]."')";
			$sql::query($query);
		}
		else {
			$query = "UPDATE valeyard_client_userinfo SET meta_value = '".$parv[4]."' WHERE meta_key = 'account'";
			$sql::query($query);
		}
		$query = "SELECT * FROM valeyard_client_userinfo WHERE type = 'me' AND meta_key = 'ident'";
		$ident = get_string_between($parv[3],"!","@");
		if (!mysqli_num_rows($result = $sql::query($query))){
			$query = "INSERT INTO valeyard_client_userinfo (type, meta_key, meta_value) VALUES ('me','ident','$ident')";
			$sql::query($query);
		}
		else {
			$query = "UPDATE valeyard_client_userinfo SET meta_value = '$ident' WHERE meta_key = 'ident'";
			$sql::query($query);
		}
	}
	
});

hook::func("mode", function($u){
	
	global $gw,$sql,$me;
	
	$parv = $u['parc'];
	
	$o = ($parv[0] == "+") ? "+" : "-";
	$mode = ltrim($parv,"+-");
	
	
	/* FOR USERMODES */
	if ($u['dest'] == $me){
		
		if ($o == "+"){
			
			$query = "SELECT * FROM valeyard_client_userinfo WHERE type = 'me' AND meta_key = 'usermode'";
			if (!mysqli_num_rows($result = $sql::query($query))){
				$query = "INSERT INTO valeyard_client_userinfo (type, meta_key, meta_value) VALUES ('me','usermode','$mode')";
				$sql::query($query);
			}
			else {
				$row = mysqli_fetch_assoc($result);
				$query = "UPDATE valeyard_client_userinfo SET meta_value = '".$row['meta_value'].$mode."' WHERE meta_key = 'usermode'";
				$sql::query($query);
			}
		}
		elseif ($o == "-"){
			$query = "SELECT * FROM valeyard_client_userinfo WHERE type = 'me' AND meta_key = 'usermode'";
			if (!mysqli_num_rows($result = $sql::query($query))){
				return;
			}
			else {
				$row = mysqli_fetch_assoc($result);
				$query = "UPDATE valeyard_client_userinfo SET meta_value = '".str_replace($mode,"",$row['meta_value'])."' WHERE meta_key = 'usermode'";
				$sql::query($query);
			}
		}
		// something weird fuckin happend
		else { return; }
	}

	
});
