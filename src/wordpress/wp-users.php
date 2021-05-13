<?php
/*
	This module allows you to fetch and put data to your wp_users table
	if you have a prefix that is not "wp_" please add the following line to
	your gateway.config.php
	
		'wp-prefix' => 'YourWordpressPrefix_",
		
	if you do not do this, WordPress plugin for Valeyard will not function correctly.
	
*/


// looks up some information about a wordpress user
// returns info or NULL
function wpUserLookup($searchTerm,$searchColumn,$resultColumn){
	
	global $sql,$cf;
	
	// if you didn't use the correct paramaters, you mor0n
	if (!$searchTerm || !$searchColumn || !$resultColumn) { return; }
	
	// find our prefix
	$prefix = $cf['wp-prefix'] ?? "wp_";
	
	// wordpress prefix and de user tab0l
	$table = $prefix."users";
	
	// define our query
	$query = "SELECT * FROM $table WHERE $searchColumn='$searchTerm'";
	$result = $sql::query($query) ?? NULL; // query it
	
	// if we got something!?
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)){
			
			// find the column
			$return = $row[$resultColumn];
		}
	}
	
	// if we got nothing?!
	else { $return = NULL; }
	
	// free it
	mysqli_free_result($result);
	
	// if no info, return NULL
	if (!$return){ return NULL; }
	
	// or return the info!
	return $return;
}

// Updates a user column by ID, checks the update happened and then
// returns true or false
function WPUpdateUser($user_id,$column,$new_data){
	
	global $cf,$sql;
	
	// if you didn't use the correct paramaters, you mor0n
	if (!WPIsUser($user_id)) { return; }
	if (!$column || !$new_data) { return; }
	
	$prefix = $cf['wp-prefix'] ?? "wp_";
	$table = $prefix."users";
	
	$query = "UPDATE $table SET $column='$new_data' WHERE ID='$user_id'";
	$sql::query($query);
	
	//check update w0rked
	
	$query = "SELECT * FROM $table WHERE ID='$user_id'";
	$result = $sql::query($query);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)){
			if ($row[$column] == $new_data){ $return = true; }
			else { $return = false; }
		}
	}
	mysqli_free_result($result);
	
	//returns true if the new data was uploaded
	//false if not
	
	
	return $return;
}
	

// uses a quick lookup to check existence of a user
// returns true or false
function WPIsUser($id){
	
	// if you didn't use the correct paramaters, you mor0n
	if (!$id){ return; }
	
	if (!WPID2Login($id)){ return false; }
	else { return true; }
}


// find user_login by ID
// returns user_login or NULL
function WPID2Login($id){
	
	// if you didn't use the correct paramaters, you mor0n
	if (!$id) { return; }
	
	$user_login = wpUserLookup($id,"ID","user_login") ?? NULL;
	
	if (!$user_login){ return; }
	return $user_login;
}

// find ID by user_login
// returns ID or NULL
function WPLogin2ID($user_login){
	
	// if you didn't use the correct paramaters, you mor0n
	if (!$user_login){ return; }
	
	$id = wpUserLookup($user_login,"user_login","ID") ?? NULL;
	
	if (!$id){ return; }
	return $id;
}

// find user_email by ID
// returns user email or NULL
function WPID2Email($id){
	
	// if you didn't use the correct paramaters, you mor0n
	if (!$id) { return; }
	
	$user_email = wpUserLookup($id,"ID","user_email") ?? NULL;
	
	if (!$user_email){ return; }
	return $user_email;
}

// find user_email by user_login
// returns user_email or NULL
function WPLogin2Email($user_login){
	
	// if you didn't use the correct paramaters, you mor0n
	if (!$user_login){ return; }
	
	$user_email = wpUserLookup($user_login,"user_login","user_email") ?? NULL;
	
	if (!$user_email){ return; }
	return $user_email;
}

// find user_login by user_email
// returns user_login or NULL
function WPEmail2Login($user_email){
	
	// if you didn't use the correct paramaters, you mor0n
	if (!$user_email){ return; }
	
	$user_login = wpUserLookup($user_email,"user_email","user_login") ?? NULL;
	
	if (!$user_login){ return; }
	return $user_login;
}

//find ID by user_email
// returns ID or NULL
function WPEmail2ID($user_email){
	
	// if you didn't use the correct paramaters, you mor0n
	if (!$user_email){ return; }
	
	$id = wpUserLookup($user_email,"user_email","ID") ?? NULL;
	
	if (!$id){ return; }
	return $id;
}

// Updates user_email
// returns true or false
function WPUpdateUserEmail($user_id,$new_email){
	
	// if you didn't use the correct paramaters, you mor0n
	if (!$user_id || !$new_email){ return; }
	
	if (!WPUpdateUser($user_id,"user_email",$new_email)){ return false; }
	
	return true;
}

// Updates user_login and user_nicename field
// returns true or false
function WPUpdateUserLogin($user_id,$new_login){
	
	// if you didn't use the correct paramaters, you mor0n
	if (!$user_id || !$new_login){ return; }
	
	// if $name does not == false after updating our user
	if (!($name = WPUpdateUser($user_id,"user_login",$new_login))){
		return false; //couldn't find 'em, return early
	}
	
	// wordpress user_nicename
	$makeItSmolCaseLol = strtolower($new_login);
	
	if (!($nicename = WPUpdateUser($user_id,"user_nicename",$makeItSmolCaseLol))){
		return false; //couldn't find 'em, return early
	}
	return true; // everything went okay
}

// Updates user_pass with an MD5 hash
// next time this user logs in, their password hash will be converted
// from MD5 to a true wordpress hash + salt
function WPUpdateUserPass($user_id,$new_password){
	
	// if you didn't use the correct paramaters, you mor0n
	if (!$user_id || !$new_password){ return; }
	
	if (!WPUpdateUser($user_id,"user_pass",MD5($new_password))){ return false; }
		
	return true; // everything went okay
}

?>
