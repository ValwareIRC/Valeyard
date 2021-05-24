<?php

/*
	This module handles STS redirects
*/

hook::func("caps", function($u){
	global $cf,$gw,$server,$port;
	// grab de caps
	$caps = $u['caps'];
	// splittem de CAPs
	$cap = explode(" ",$caps);
	// loopem de CAPs
	for ($i = 0; isset($cap[$i]);){
		
		//splittem up de CAPaParamás, cha cha cha
		$token = explode("=",$cap[$i]);
		
		// not STS, not for us
		if ($token[0] !== "sts"){ $i++; continue; }
		
		// malform it ahahhahahaa jk
		$token = str_replace("sts=","",$cap[$i]);
		
		$ststok = explode(",",$token);
		
		//loopem de STS options
		for ($sts = 0; isset($ststok[$sts]);){
			
			$moreOptions = explode("=",$ststok[$sts]);
			
			if ($moreOptions[0] == "port"){
				if ($port === $moreOptions[1]){ return; }
				$gw->shout("STS: Redirecting to secure port ".$moreOptions[1]);
				$port = $moreOptions[1];
				$server = "ssl://".$cf['serverip'];
				
				//die("STS redirect failed probably because we have no cert (sorry about that), use port $port instead and set your server to $server");
				unset($socket);
				// for when we sort out stuff properly
				$gw = new Bot($server,$port,$cf['nick'],$cf['ident'],$cf['realname'],$cf['caps'],$cf['password']);
			}
			$sts++;
			continue;
		}
		$i++;
	}
});