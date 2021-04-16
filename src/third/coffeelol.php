<?php

// This is an example module lol. coffee anyone?

hook::func("privmsg", function($u){

    // convenience identifiers
    $nick = $u['nick'];
    $parv = explode(" "$u['parc']);
    $cmd = $parv[0];
    $target = $u['dest'];

    // if it's a PM, ignore it
    if ($target !== $me) { return; }

    // if the user does not says "!coffee" ignore it and return safely
    if ($cmd !== "!coffee") { return; }
    
    // send the coffee lmao
    $gw->msg($target,"Here you go! *makes ".$nick." a coffee!*")
    return;
});


// the help hook which is from a /third module
hook::func("help", function($u) {
	global $gw,$me;
	$nick = $u['nick'];
	$parv = explode(" ",$u['parc']);
	
	if (!isset($parv[1])) {
		$gw->notice($nick,"!coffee         makes you a coffee lol");
	}
	elseif ($parv[1] == "op") {
		$gw->notice($nick,"Syntax: !coffee");
	}
    return;
});