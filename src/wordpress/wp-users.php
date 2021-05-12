<?php


function wpUserLookup($searchTerm,$searchRow,$resultRow){
	
	global $sql,$cf;
	
	$prefix = $cf['wp-prefix'] ?? "wp_";
	$table = $prefix."users";
	
	$query = "SELECT * FROM $table WHERE $searchRow='$searchTerm'";
	$result = $sql::query($query) ?? NULL;
	
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)){
			$return = $row[$resultRow];
		}
	}
	else { $return = NULL; }
	mysqli_free_result($result);
	if (!$return){ return; }
	return $return;
}


function ID2Login($id){
	
	if (!$id) { return; }
	
	$user_login = wpUserLookup($id,"ID","user_login") ?? NULL;
	
	if (!$user_login){ return; }
	return $user_login;
}

function Login2ID($user_login){
	
	if (!$user_login){ return; }
	
	$id = wpUserLookup($user_login,"user_login","ID") ?? NULL;
	
	if (!$id){ return; }
	return $id;
}

function ID2Email($id){
	
	if (!$id) { return; }
	
	$user_email = wpUserLookup($id,"ID","user_email") ?? NULL;
	
	if (!$user_email){ return; }
	return $user_email;
}

function Login2Email($user_login){
	
	if (!$user_login){ return; }
	
	$user_email = wpUserLookup($user_login,"user_login","user_email") ?? NULL;
	
	if (!$user_email){ return; }
	return $user_email;
}

function Email2Login($user_email){
	
	if (!$user_email){ return; }
	
	$user_login = wpUserLookup($user_email,"user_email","user_login") ?? NULL;
	
	if (!$user_login){ return; }
	return $user_login;
}

function Email2ID($user_email){
	
	if (!$user_email){ return; }
	
	$id = wpUserLookup($user_email,"user_email","ID") ?? NULL;
	
	if (!$id){ return; }
	return $id;
}

?>
