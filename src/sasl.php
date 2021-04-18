<?php

/*
	This module provide pr0vide sasl
*/

hook::func("caps", function($u){
	$caps = $u['caps'];
	if ($caps == NULL) { return; }
	sendcaps($caps);
});

hook::func("auth", function(){
	
	global $gw,$me,$cf;
	
	$gw->sendraw("AUTHENTICATE ".base64_encode(chr(0).$me.chr(0).$cf['password']));
	
});

function sendcaps($caps){
	global $gw;
	$cap = explode(" ",$caps);
	for ($s = count($cap), $i = 0; $i < $s;){
		$gw->sendraw("CAP REQ :".$cap[$i]);
		if (strtolower($cap[$i]) == 'sasl') {
			$gw->sendraw("AUTHENTICATE PLAIN");
		}
		$i++;
	}
	$gw->sendraw('CAP END');
}
