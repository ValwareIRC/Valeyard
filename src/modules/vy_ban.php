<?php

/*

	BAN

	This module provides command BAN

	Permission found on WordPress using usermeta wp_user_level
	
	Syntax: BAN #channel <nick> <duration-in-minutes> <reason>
	
	Will ban nick, hostmask and account name if applicable
	
	Not a fantasy command
	
	By Valware
	
*/


hook::func("privmsg", function($u){
	
	global $gw,$me;
	
	// split up their params
	$parv = explode(" ",$u['parc']);
	
	// not enough params
	if (!isset($parv[4])){ return; }
	
	// if it was not a privmsg for us, return
	if ($u['dest'] !== $me){ return; }
	
	// if the command is not "BAN", it's not for us, return;
	if (($cmd = $parv[0]) !== "ban"){ return; }
	
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
	
	$userToBan = $parv[2];
	if (!IsOn($userToBan,$chan)){
		
		$gw->notice($nick,"$userToBan is not on that channel.");
		return;
	}
	
	$timer = $parv[3];
	
	// timer check
	if (!is_numeric($timer)){
	
		// tell em and return;
		$gw->notice($nick,"Ban time must be a number.");
		return;
	}
	
	// ban what they want
	
	$reason = str_replace($parv[0]." ".$parv[1]." ".$parv[2]." ".$parv[3]." ","",$u['parc']);	
	
	$gw->mode($chan,"+bbb ~t:" . $timer . ":" . $userToBan . "!*@* ~t:".$timer.":*!*@".hostname($userToBan)." ~t:".$timer.":~a:".account($userToBan));
	$gw->kick($userToBan,$chan,$reason." [".$nick."]");
	return;

});
	
	
