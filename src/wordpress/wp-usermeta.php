<?php

//lookup user metadata
function WPUserMeta($user_id,$meta_key){
	
	global $sql,$cf;
	
	// if you didn't use the correct paramaters, you mor0n
	if (!$user_id || !$meta_key) { return; }
	
	// find our prefix
	$prefix = $cf['wp-prefix'] ?? "wp_";
	
	// wordpress prefix and de user tab0l
	$table = $prefix."usermeta";
	
	// define our query
	$query = "SELECT * FROM $table WHERE meta_key='$meta_key' AND user_id='$user_id'";
	$result = $sql::query($query) ?? NULL; // query it
	
	// if we got something!?
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)){
			
			// find the column
			$return = $row['meta_value'];
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

function WPUserMetaNickname($user_id){
	
	// if you didn't use the correct paramaters, you mor0n
	if (!$user_id){ return; }
	
	if (!($return = WPUserMeta($user_id,"nickname"))){ return NULL; }
	else { return $return; }
}

function WPUserMetaFirstName($user_id){
	
	// if you didn't use the correct paramaters, you mor0n
	if (!$user_id){ return; }
	
	if (!($return = WPUserMeta($user_id,"first_name"))){ return NULL; }
	else { return $return; }
}

function WPUserMetaLastName($user_id){
	
	// if you didn't use the correct paramaters, you mor0n
	if (!$user_id){ return; }
	
	if (!($return = WPUserMeta($user_id,"last_name"))){ return NULL; }
	else { return $return; }
}

function WPUserMetaDescription($user_id){
	
	// if you didn't use the correct paramaters, you mor0n
	if (!$user_id){ return; }
	
	if (!($return = WPUserMeta($user_id,"description"))){ return NULL; }
	else { return $return; }
}

function WPUserMetaUserLevel($user_id){
	
	// if you didn't use the correct paramaters, you mor0n
	if (!$user_id){ return; }
	
	if (!($return = WPUserMeta($user_id,"wp_user_level"))){ return NULL; }
	else { return $return; }
}

function WPUserMetaCapabilities($user_id){
	
	$capabilities = NULL;
	// if you didn't use the correct paramaters, you mor0n
	if (!$user_id){ return; }
	
	$string = explode(":",WPUserMeta($user_id,"wp_capabilities"));
	
	foreach ($string as $eval){
		if (strpos($eval,chr(34)) !== false){
			$capabilities .= get_string_between($eval,chr(34),chr(34))." ";
		}
	}
	if (!$capabilities){ return; }
	
	$capabilities = rtrim($capabilities," ");
	
	return $capabilities;
}

?>

