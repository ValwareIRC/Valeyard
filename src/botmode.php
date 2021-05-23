<?php
/*
	This module provides setting BOT mode on the bot when it connects
	If you want to use it, gently uncomment the //"botmode" => true,
	line in gateway.conf.php
*/

hook::func("numeric", function($u){
	global $cf,$gw,$me;
	
	if (!isset($cf['botmode']) || $cf['botmode'] !== true) { return; }
	echo $u['parc'];
	if ($u['numeric'] == 005){
			
		$ISUPPORT = explode(" ",$u['parc']);
		
		for ($i = 1; $ISUPPORT[$i]; $i++){
			if (strpos($ISUPPORT[$i],"BOT=") !== false) {
				$token = explode("=",$ISUPPORT[$i]);
				
				$gw->mode($me,$token[1]);
			}
		}
	}
	return;
});

