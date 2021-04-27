<?php

//NickServ tables integrati0n lmao

global $cf,$gw;
class NickServ {
	
	function __construct(){
		
		global $sql,$cf,$gw;
		$error = NULL;
		$tablesToCheck = array(
			0 => "",
			1 => "NickCore",
			2 => "NickAlias",
			3 => "user",
			4 => "SeenInfo");
		
		for ($i = 0; $tablesToCheck[$i]; $i++){
			$table = $cf['anopetable'].$tablesToCheck[$i];
			$query = "SELECT * FROM $table";
			$result = $sql::query($query);
			if (mysqli_num_rows($result) == 0) { $error .= "Couldn't find any data in table $table - please make sure you have anope configured correctly.\n"; }
		}
		if ($error) { $gw->hear($error); die($error); }
	}
	
	function IsReg($nick){
		global $cf,$sql;
	
		$p = $cf['anopetable'];
		
		$query = "SELECT * FROM ".$p."NickCore WHERE display = '$nick'";
		$result = $sql::query($query);
		if (mysqli_num_rows($result) > 0) { $ind = true; }
		else { $ind = false; }
		mysqli_free_result($result);
		
		return $ind;
	}
	function UserInfo($nick,$search){
	
		global $cf,$sql;
		
		$p = $cf['anopetable'];
		
		$query = "SELECT * FROM ".$p."user WHERE nick = '$nick'";
		$result = $sql::query($query);
		if (mysqli_num_rows($result) > 0) { 
			while($row = mysqli_fetch_assoc($result)){
				$ind = $row[$search];
			}
		}
		if (!$ind){ $ind = false; }
		mysqli_free_result($result);
		return $ind;
	}
	function AccountInfo($nick,$search){
		
		global $cf,$sql;
		
		$p = $cf['anopetable'];
		
		$query = "SELECT * from ".$p."NickCore WHERE display = '$nick'";
		$result = $sql::query($query);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)){
				$ind = $row[$search];
			}
		}
		if (!$ind){ $ind = false; }
		mysqli_free_result($result);
		return $ind;
	}
	function Seen($nick,$search){
		
		global $cf,$sql;
		
		$p = $cf['anopetable'];
		
		$query = "SELECT * FROM ".$p."SeenInfo WHERE nick = '$nick'";
		$result = $sql::query($query);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)){
				$ind = $row[$search];
			}
		}
		if (!$ind){ $ind = false; }
		mysqli_free_result($result);
		return $ind;
	}
	function IsAJoinEntry($nick,$chan){
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
		
		if (!$this->IsReg($nick)){ $error = $nick." is not a registered user."; }
		#elseif (!$this->IsAnopeChan($chan)){ $error = $chan." is not a registered channel."; }
		elseif ($this->IsAjoinEntry($nick,$chan)){ $error = $chan." is already in $nick's ajoin list."; }

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
		
		if (!$this->IsAnopeUser($nick)){ $error = $nick." is not a registered user."; }
		elseif (!$this->IsAnopeChan($chan)){ $error = $chan." is not a registered channel."; }
		elseif (!$this->IsAjoinEntry($nick,$chan)){ $error = $chan." is not in $nick's ajoin list."; }

		if ($error) { $gw->hear($error); return $error; }
		else {
			
			$query = "DELETE FROM ".$p. "AJoinEntry WHERE channel = '$chan' AND owner = '$nick'";
			$sql::query($query);
			return 1;
		}
	}
	function IsOnline($nick){
		if (!$this->UserInfo($nick,"nick")) { return false; }
		else { return true; }
	}
	function NickID($id){
		
		global $cf,$sql;
		
		$p = $cf['anopetable'];
		
		$error = NULL;
		$query = "SELECT * FROM $p.user WHERE id = '$id'";
		$result = $sql::query($query);
		if (mysqli_num_rows($result) > 0) { 
			while($row = mysqli_fetch_assoc($result)){
				$ind = $row[$search];
			}
		}
		if (!$ind){ $ind = false; }
		mysqli_free_result($result);
		return $ind;
	}
	function hostname($nick){ return $this->UserInfo($nick,"hostname"); }
	function ip($nick){ return $this->UserInfo($nick,"ip"); }
	function ident($nick){ return $this->UserInfo($nick,"ident"); }
	function gecos($nick){ return $this->UserInfo($nick,"realname"); }
	function hostmask($nick){
		$hostmask = "$nick!$this->ident($nick)@$this->hostname($nick)";
		return $hostmask;
	}
	function fullhost($nick){
		$fullhost = "$this->hostmask($nick)#$this->gecos($nick)";
	}
}


?>
		