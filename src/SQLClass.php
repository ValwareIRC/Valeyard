<?php

class SQL {
	
	function __construct($ip,$user,$pass,$db){
		global $ip,$user,$pass,$db;
	}
	function query($query){
		global $sqlip,$sqluser,$sqlpass,$sqldb,$gw,$cf;
		$conn = mysqli_connect($sqlip,$sqluser,$sqlpass,$sqldb);
		if (!$conn) { $gw->msg($cf['statschan'],"Could not connect to SQLdb (user database)",mysqli_connect_error()); return "ERROR"; }
		else {
			$result = mysqli_query($conn,$query);
			return $result;
		}
	}
}

?>
