<?php

/*---------------------------------------------------------------------------
	GEOIP module by Valware													
																			
	To use functionality of this, check the bottom three functions.			
	To enable setting the IP location in an swhois (this hook func below),	
	add in this line to your gateway.config.php:							
																			
	'geoip_swhois' => 'on',													
																			
	You can still use the GEOIP functions elsewhere if you don't want the 	
	swhois feature.															
---------------------------------------------------------------------------*/



hook::func("notice", function($u){
	
	//glow-balls
	global $gw,$cf;
	if (!($opt = $cf['geoip_swhois'])){ return; }
	if ($opt != 'on'){ return; }
	// sender of the notice
	$nick = $u['nick'];
	
	// if not server, return early, we listenin' for server notices
	if (!IsServer($nick)) { return; }
	
	// splittem up
	$parv = explode(" ",$u['parc']);
	
	// the first two parvs
	$connectNotice = $parv[1]." ".$parv[2];
	
	// check if first two parvs are telling us there is a client connecting, if not return
	if ($connectNotice !== "Client connecting:"){ return; }
	
	$user = $parv[3];

	// grab the IP from the notice
	$ip = trim($parv[5],"[]");
	
	// if we found GEOIP data
	if (!($geoip = GEOIP($ip))){ return; }
	
	// specify which information we are using (city, country)
	$city = ($geoip['city']) ? $geoip['city'].", " : NULL;
	$country = ($geoip['country']) ? $geoip['country'] : NULL;
	
	// if we found our chosen data amongst the GEOIP information, put it in $location
	if (!($location = $city.$country)){ return; }
	
	/*----------------------------------------------------------------------
		this next line sets an swhois line on the user with their location	
		using my module third/chgswhois - downloadable to unrealircd from	
		https://modules.unrealircd.org										
		or, you can run this from your unrealircd/ directory via SSH:		
		./unrealircd module install third/chgswhois							
		OR you can just change this line to do something else like CHGNAME	
		OR something else entirely, up to you :)							
	-----------------------------------------------------------------------*/
	
	$gw->sendraw("CHGSWHOIS $user is connecting from $location");
	
});
	
	
// get geoip information and return into arrayed variable
function GEOIP($ip){
    
	if (!$ip){ return NULL; }
 
    $ip_data = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=".$ip));
    $result  = ['country'=>'', 'city'=>''];
 
    if($ip_data && $ip_data['geoplugin_countryName'] != NULL){
        $result['country'] = $ip_data['geoplugin_countryName'];
        $result['city'] = $ip_data['geoplugin_city'];
    }
    return $result;
}

// shortcut to just get geoip country
function GEOIPCountry($ip){
	$geoip = NULL;
	$geoip = GEOIP($ip);
	if (!$geoip){ return NULL; }
	$country = $geoip['country'];
	return $country;
}

// shortcut to just get geoip city
function GEOIPCity($ip){
	$geoip = NULL;
	$geoip = GEOIP($ip);
	if (!$geoip){ NULL; }
	$city = $geoip['city'];
	return $city;
}
?>