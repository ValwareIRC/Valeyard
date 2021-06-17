<?php

hook::func("notice", function($u){
	
	//glow-balls
	global $gw;
	
	// sender of the notice
	$nick = $u['nick'];
	
	// if not server, return early, we listenin' for server notices
	if (!IsServer($nick)) { return; }
	
	// splittem up
	$parv = explode(" ",$u['parc']);
	
	// the first to parvs
	$connectNotice = $parv[1]." ".$parv[2];
	
	// check if first two parvs are telling us there is a client connecting, if not return
	if ($connectNotice !== "Client connecting:"){ return; }

	// grab the IP from the notice
	$ip = trim($parv[5],"[]");
	
	// check if they match a dnsbl, if they do, store the result in $dnsbl
	// if not, let opers know that it was scanned and safe
	if (!($dnsbl = dnslookup($ip))) { return; }
	
	/* EXTERMINATE */
	
	// user to exterminate
	$user = $parv[3];
	
	// high-grade extermination
	$gw->gline($user,"24h","DNSBL hit: $dnsbl");
	
	return;
});
	
	
	
function dnslookup($ip){
	
	// some dnsbls to lookup fam
    $dnsbl_lookup = [
        "dnsbl.dronebl.org",
		"tor.dan.me.uk",
		"torexit.dan.me.uk",
		"xbl.spamhaus.org",
		"exitnodes.tor.dnsbl.sectoor.de",	
    ];

	// clear variable just in case
    $listed = NULL;

	// if the IP was not given because you're an idiot, stop processing
    if (!$ip) { return; }
	
	// get the first two segments of the IPv4	
	$because = explode(".",$ip);   // why you
	$you = $because[1]; 		  // gotta play
	$want = $because[2];		 // that song
	$to = $you.".".$want.".";	// so loud?
	
	// exempt local connections because sometimes they get a false positive
	if ($to == "192.168." || $to == "127.0.") { return NULL; }
	
	// you spin my IP right round, right round, to check the records baby, right round-round-round
	$reverse_ip = implode(".", array_reverse(explode(".", $ip)));
	
	// checkem
	foreach ($dnsbl_lookup as $host) {
		
		//if it was listed
		if (checkdnsrr($reverse_ip . "." . $host . ".", "A")) {
			
			//take note
			$listed = $host;
		}
	}

	// if it was safe, return NOTHING
    if (!$listed) {
        return NULL;
    }
	
	// else, you guessed it, return where it was listed
	else {
        return $listed;
    }
}

// high-grade close PHP tag
?>
