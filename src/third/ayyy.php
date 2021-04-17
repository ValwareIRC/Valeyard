<?php

// commits toaster bath lol

hook::func("privmsg", function($u){

    global $gw,$me;

    $nick = $u['nick'];
    $target = $u['dest'];
    $parv = explode(" ",$u['parc']);
    $cmd = $parv[0];

    // return if PM lol
    if ($nick === $me) { return; }

    // return if cmd not !ayyy
    if ($cmd !== "!ayyy") { return; }

    $gw->kill($nick,"toaster-bath");
    return;

});

hook::func("help", function($u){

    global $gw,$me;
	$nick = $u['nick'];
	$parv = explode(" ",$u['parc']);
	
	if (!isset($parv[1])) {
		$gw->notice($nick,"!ayyy         makes you a dead lol");
	}
	elseif ($parv[1] == "!ayyy") {
		$gw->notice($nick,"Syntax: !ayyy");
	}
    return;

});

?>