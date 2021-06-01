<?php

/*

	Description:

	This module aims to fix a problem that occurs when using
	Anope's m_sql_authentication
	
	Problem:
	
	When a user registers with the chosen registration
	software, Anope does not pick it up and create an account
	for the user until they first identify with NickServ,
	only after which Anope Services will recognise them as
	a user.
	
	Solution:
	
	We do some checks on connecting users assuming you've
	compiled Anope in English.**
	
	First, we listen for the server to tell us that a user is
	connecting. If they SASL with an account, we let them pass.
	
	If they do not sasl we check:
	
	-If they are registered in Anope, Anope will ask them to
		identify, we let them pass.
	
	-If they are not registered in Anope, we check if they are
		registered in WordPress.
		
	-If they are not registered in WordPress either, it means
		they are a guest, we let them pass.
		
	-If they are registered in WordPress, it means they have
		not yet identified to NickServ for the first time,
		and NickServ does not recognise them as a user.
		This is the error.
		
		We disconnect the user and ask them to put in a password.
		
	
	**If you compiled in another language, feel free to request 
		one that works in your language.
	
	
	Requirements:
	
	UnrealIRCd 5.0.9 or later
	Bot to have an O-Line
	MySQL database properly configured
	Anope with m_sql_authentication properly configured

*/


hook::func("notice", function($u){
	
	global $gw;
	$gw->shout("Test 1");
	// if is not a server notice, return
	if (!IsServer($u['nick'])){ $gw->shout("true"); }
	
	$gw->shout("Test 2");
	// explodem
	$parv = explode(" ",$u['parc']);
	
	$gw->shout("Test 3");
	// Grab nick of connecting user
	$nick = $parv[3];
	
	$gw->shout("Test 4");
	// check the notice is a connection notice (requires o-line)
	if ($parv[0] !== "***"){ return; }
	$gw->shout("Test 4.5");
	if ($parv[1] !== "Client"){ $gw->shout("Test 4.5.5"); return; }
	if ($parv[2] !== "connecting:"){ $gw->shout("Test 4.5.6"); return; }

	$gw->shout("Test 5");
	// if they have an account, set it in $account var
	$account = ($parv[6] == "[account") ? substr_replace($parv[7], "", -1) : NULL;

	$gw->shout("Test 6");
	// if they have an account, let them pass
	if ($account){ return; }
	
	$gw->shout("Test 7");
	// if they are not a wordpress user, they are a guest, let them pass
	if (WPIsUser($nick)){ return; }
	
	$gw->shout("Test 8");
	$gw->notice($nick,"This username is registered. Please put in the password or choose another username.");
	return;
});
