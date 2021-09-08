<?php

/*
	Example module to show integration between Anope and Wordpress and printed on IRC
	
	This module does several things:
		- Checks if the requester is logged into a NickServ account.
		- if so, finds the email they registered to NickServ with
		- Looks for the email in your wordpress database
		- if one is found, store their user ID
		- looks up the user registration date in the _user table using ID
		- looks up the user capabilities in the _usermeta table using ID
		- prints it to the user who requested their own information
		
*/

//we are hooking into privmsgs
hook::func("privmsg", function($u){
	
	//set our glow-balls
	// GateWay, our $me identifier lol, and NickServ
	global $gw,$me,$ns;

	//get the nick that triggered this hook
	$nick = $u['nick'];
	
	//where the message was sent
	$target = $u['dest'];
	
	// if the target was me, reply directly to sender, if not, to channel
	if ($target == $me) { $sendreply = $nick; }
	else { $sendreply = $target; }
	
	//split their message up so you can access individual words using $parv
	$parv = explode(" ",$u['parc']);
	
	//set the first word to be $cmd
	$cmd = $parv[0];
	
	// if our command is not "!account", it is not for us, return early
	if ($cmd != "!account") { return; }
	
	// check if the user asking for the account information is logged into nickserv
	// if not, return
	if (!($NickServAccount = account($nick))) { return; }
	
	// tell them
	$gw->msg($sendreply,"NickServ Account found: $NickServAccount");
	
	// found them, so store the email
	$email = $ns->AccountInfo($NickServAccount,"email");
	
	// search wordpress for the email
	if (!($user_id = WPEmail2ID($email))){ return ; }
	
	// if we found one
	if (!($user_reg = wpUserLookup($user_id,"ID","user_registered"))){ return; }
	
	// let em know
	$gw->msg($sendreply,"Registered on wordpress: $user_reg");
	
	// Wordpress user capabilities
	$caps = WPUserMetaCapabilities($user_id);
	$gw->msg($sendreply,"WordPress capabilities: $caps");
	
});

?>

