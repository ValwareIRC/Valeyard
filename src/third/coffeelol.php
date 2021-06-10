<?php

// This is an example module lol. coffee anyone?

hook::func("privmsg", function($u){
	
	global $gw,$sql,$me;

    // convenience identifiers
    $nick = $u['nick'];
    $parv = explode(" ",$u['parc']);
    $cmd = $parv[0];
    $target = $u['dest'];
	
	//get msgid because we are going to be replying to it directly
	$tags = explode(";",$u['mtags']);
	for ($i = 0; $tags[$i];){
		$tag = explode("=",$tags[$i]);
		if ($tag[0] != "msgid") { $i++; }
		else {
			$msgid = $tag[1]; break;
		}
	}
    // if it's a PM, ignore it
    if ($target == $me) { return; }

    // if the user does not says "!coffee" ignore it and return safely
    if ($cmd !== "!coffee") { return; }
    $targ = (isset($parv[1])) ? $parv[1] : $nick;


    // send the coffee using @+draft/reply=$msgid lmao
    $gw->msgreply($msgid,$target,"Here you go! *makes ".$targ." a coffee!...*");
    return;
});


// the help hook which is from a /third module
hook::func("help", function($u) {
	global $gw,$me;
	$nick = $u['nick'];
	$parv = explode(" ",$u['parc']);
	
	if (!isset($parv[1])) {
		$gw->notice($nick,"!coffee         makes you a coffee.");
	}
	elseif ($parv[1] == "!coffee") {
		$gw->notice($nick,"Syntax: !coffee");
	}
    return;
});