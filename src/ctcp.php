<?php

// Feel free to change these values, add more, whatever
global $ctcp;
$ctcp = [

	// the version lol
	"version" => "Valeyard v1.0",
	
	//ping is 1 for true or 0 for false, or choose your own (use "quoted string")
	"ping" => 1,
	
	// our timestamp hauehauehae
	"time" => date("D M j G:i:s Y"),
	
];


// this function returns NULL if the compared string is not a CTCP,
// and returns the full string of the CTCP stripped of chr(1) if it is a CTCP

function IsCTCP($string){
	
	// if we have no params, 
	if (!$string) { return; }
	
	// grab the first and last chars
	$first = $string[0];
	$last = substr($string,-1);
	
	$parv = explode(" ",$string);
	
	$action = chr(1)."ACTION";
	
	if ($parv[0] == $action) { return false; }
	
	// if they are wrapped in chr(1) which means it's a CTCP
	if ($first == chr(1) && $last == chr(1)) {
		
		// tidy it up and return it to the caller
		return trim($string,chr(1));
	}
	// if it was not a CTCP, lettem kn0
	else { return false; }
}

// Syntax example, CTCP($nick,"VERSION");
function CTCP($nick,$string){
	
	//glow-balls
	global $gw;
	
	//send a CTCP request
	$gw->privmsg($nick,chr(1).$string.chr(1));
}

// Syntax example, CTCPReply($nick,"VERSION yo mama lmao");
function CTCPReply($nick,$string){
	
	//glow-balls
	global $gw;
	
	//send a CTCP reply
	$gw->notice($nick,chr(1).$string.chr(1));
}

hook::func("privmsg", function($u){
	global $ctcp,$me;
	
	// return early if it's not a CTCP
	if (!($params = IsCTCP($u['parc']))) { return; }
	
	//forward it to the ctcp h00k
	hook::run("ctcp",array(
					"nick" => $u['nick'],
					"ident" => $u['ident'],
					"hostmask" => $u['hostmask'],
					"dest" => $u['dest'],
					"params" => $params,
					"mtags" => $u['mtags'])
				);
				
});

hook::func("ctcp", function($u){
	global $ctcp,$me;
	// grab their nick and where they sent it
	$nick = $u['nick'];
	$target = $u['dest'];

	// figure out if it's sent as a channel CTCP or directly
	$whereToSendTo = ($target != $me) ? $target : $nick;
	
	// splittem up
	$parv = explode(" ",$u['params']);
	
	// if our CTCP is PING
	if ($parv[0] == "PING"){
		
		// if the second parv is not a number, return cos that's just fuckin weird
		if (!is_numeric($parv[1])){ return; }
	
		if ($ctcp['ping'] == 0) { return; }
		
		else if ($ctcp['ping'] == 1) { CTCPReply($whereToSendTo,$params); }
		else if (isset($ctcp['ping'])){ CTCPReply($whereToSendTo,$ctcp['ping']); }
	}
	else if ($parv[0] == "VERSION"){
		
		// if we no have a version in the thing upstairs, return
		if (!$ctcp['version']) { return; }
		
		// we do, so send it
		else { CTCPReply($whereToSendTo,"VERSION ".$ctcp['version']); }
		
	}
	else if ($parv[0] == "TIME"){
		
		// if we no have a time in the thing upstairs, return
		if (!$ctcp['time']) { return; }
		
		// we do so send it
		else { CTCPReply($whereToSendTo,"TIME ".$ctcp['time']); }
		
	}
	
	else { // or, if it's maybe some other CTCP, which will check if you have a reply for it upstairs
		
		if (!$ctcp[$parv[0]]) { return; }
		else { CTCPReply($whereToSendTo,$parv[0]." ".$ctcp[$parv[0]]); }
	}
});

?>

/* PeGaSuS is awesome */
