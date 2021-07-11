<?php

/*---------------------------------------------------------------------------
	GEOISP module by modmenager													
																			
	Looks up a users ISP														
---------------------------------------------------------------------------*/

function ISP($ip){
    
	if (!$ip){ return NULL; }
 
    $ip_data = json_decode(file_get_contents("https://ip-api.io/json/$ip"));
 
    if($ip_data && $ip_data->ip != NULL){
       	$result = $ip_data->organisation;
    }
    return $result;
}

