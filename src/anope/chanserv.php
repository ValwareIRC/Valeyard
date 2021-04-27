<?php

//ChanServ tables integrati0n lmao
class ChanServ {
	
	function __construct(){
		
		global $sql,$cf,$gw;
		$error = NULL;
		$tablesToCheck = array(
			0 => "",
			1 => "chan",
			2 => "ChannelAccess",
			3 => "ChannelInfo",
			4 => "ison",
			5 => "ModeLock");
		
		for ($i = 1; $tablesToCheck[$i]; $i++){
			$table = $cf['anopetable'].$tablesToCheck[$i];
			$query = "SELECT * FROM $table";
			$result = $sql::query($query);
			if (mysqli_num_rows($result) == 0) { $error .= "Couldn't find any data in table $table - please make sure you have anope configured correctly.\n"; }
		}
		if ($error) { $gw->hear($error); die($error); }
	}
	
	function IsReg($chan){
		global $cf,$sql;
	
		$p = $cf['anopetable'];
		
		$query = "SELECT * FROM ".$p."ChannelInfo WHERE name = '$chan'";
		$result = $sql::query($query);
		if (mysqli_num_rows($result) > 0) { $ind = true; }
		else { $ind = false; }
		mysqli_free_result($result);
		
		return $ind;
	}
	function ChanInfo($chan,$search){
	
		global $cf,$sql;
		
		$p = $cf['anopetable'];
		
		$query = "SELECT * FROM ".$p."chan WHERE channel = '$chan'";
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
	function ChanSettings($chan,$search){
		
		global $cf,$sql;
		
		$p = $cf['anopetable'];
		
		$query = "SELECT * FROM ".$p."ChannelInfo WHERE name = '$chan'";
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
	function ModeLock($chan,$search){
		
		global $cf,$sql;
		
		$p = $cf['anopetable'];
		
		$query = "SELECT * FROM ".$p."ModeLock WHERE name = '$chan'";
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
	function ChanID($chan){
		
		global $cf,$sql;
		
		$p = $cf['anopetable'];
		
		$error = NULL;
		$query = "SELECT * FROM $p.chan WHERE chanid = '$id'";
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
}