<?php

//example module

//we are hooking into privmsgs
hook::func("privmsg", function($u){
	
	//set our glow-balls
	global $gw;
	$ns = new NickServ();
	//get the nick that triggered this hook
	$nick = $u['nick'];
	
	//where the message was sent
	$target = $u['dest'];
	
	//split their message up so you can access individual words using $parv
	$parv = explode(" ",$u['parc']);
	
	//set the first word to be $cmd
	$cmd = $parv[0];
	
	// if our command is not "!kiss", it is not for us, return early
	
	if ($cmd != "!kiss") { return; }
	
	// set the user we are kissing
	// if the user was specified, kiss them. if not, kiss nick.
	$user = $parv[1] ?? $nick;
	$gw->act($target,"kisses $user");
});

?>

