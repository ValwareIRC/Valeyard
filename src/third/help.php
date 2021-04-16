<?php

// create the "HELP" command and hook.
// This module allows you to make your bot list its command when it gets a PM "help"
// so if you want to make a module, you can easily specify the help shit in the module its for

hook::func("privmsg", function($u){
	
	global $gw,$me;
	
	$nick = $u['nick'];
	$parv = explode(" ",$u['parc']);
	$cmd = $parv[0];
	$target = $u['dest'];
	
	// if it's not for us, return it
	if ($target !== $me) { return; }
	
	if ($cmd !== "help") { return; }
	
	if (!isset($parv[1])) {
		$gw->notice($nick,"Listing commands available to you.");
		hook::run("help", $u);
		$gw->notice($nick," ");
		$gw->notice($nick,"For more information on a command, type /msg ".$me." HELP <command>");
	}
	else hook::run("help", $u);
});

?>