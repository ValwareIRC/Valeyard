<?php

/*

	SAY

	This module provides command SAY

	Permission found on WordPress using usermeta wp_user_level
	
	Syntax: SAY #channel <string>
	
	Not a fantasy command
	
	By Valware
	
*/


hook::func("privmsg", function($u){
	
	global $gw,$me;
	
	// split up their params
	$parv = explode(" ",$u['parc']);
	
	// if it was not a privmsg for us, return
	if ($u['dest'] !== $me){ return; }
	
	// if the command is not "SAY", it's not for us, return;
	if (($cmd = $parv[0]) !== "say"){ return; }
	
	// grab nick and transform it to wordpress *_user_level
	$nick = $u['nick'];
	
	// grab anope account if any, if none return
	if (!($account = account($nick))){ return; }
	
	// if account doesn't exist in wordpress, deny
	if (!($id = WPLogin2ID($account))){ return; }
	
	// grab their level
	$level = WPUserMetaUserLevel($id);
	
	// if they have no permissi0ns
	if ($level < 1){ return; }
	
	
	/* they got permission for this module, let's go */
	
	// check if Valeyard is even on that channel
	$chan = $parv[1];
	if (!IsOn($me,$chan)){
		
		// tell em and return;
		$gw->notice($nick,"I am not on that channel ($chan).");
		return;
	}
	
	// say what they want
	
	$string = str_replace($parv[0]." ".$parv[1]." ","",$u['parc']);	
	
	$gw->msg($chan,$string);
	return;

});
	
	
