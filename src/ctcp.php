<?php

// Feel free to change these values, add more, whatever
global $ctcp;
$ctcp = [

	// the version lol
	"version" => "IRCSQLGW 0.1-beta",
	
	//ping is 1 for true or 0 for false, or choose your own (use "quoted string")
	"ping" => 1,
	
	// our timestamp hauehauehae
	"time" => date("D M j G:i:s Y"),
	
];


// this function returns NULL if the compared string is not a CTCP,
// and returns the full string of the CTCP stripped of chr(1) if it is a CTCP

function IsCTCP($string){
	
	// grab the first and last chars
	$first = $string[0];
	$last = substr($string,-1);
	
	// if they are wrapped in chr(1) which means it's a CTCP
	if ($first == chr(1) && $last == chr(1)) {
		
		// tidy it up and return it to the caller
		return trim($string,chr(1));
	}
	// if it was not a CTCP, lettem kn0
	else { return NULL; }
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
	global $ctcp;
	// return early if it's not a CTCP
	if (!IsCTCP($u['parc'])) { return; }
	else { $params = IsCTCP($u['parc']); }
	// grab their nick and where they sent it
	$nick = $u['nick'];
	$target = $u['dest'];

	// figure out if it's sent as a channel CTCP or directly
	$whereToSendTo = ($target[0] == "#") ? $target : $nick;
	
	// splittem up
	$parv = explode(" ",$params);
	
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

		
		
	
	