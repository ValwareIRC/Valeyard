<?php



hook::func("privmsg", function($u){
	
	global $gw,$me,$sendCaps;

    // convenience identifiers
    $nick = $u['nick'];
    $parv = explode(" ",$u['parc']);
    $cmd = $parv[0];
    $target = $u['dest'];
	
	//get message tag
	$tags = explode(";",$u['mtags']);
	for ($i = 0; isset($tags[$i]);){
		$tag = explode("=",$tags[$i]);
		if ($tag[0] != "msgid") { $i++; }
		else {
			$msgid = $tag[1]; break;
		}
	}
    // if it's a PM, ignore it
    if ($target == $me) { return; }

    // if the user does not says "!coffee" ignore it and return safely
    if ($cmd !== "!caps") { return; }
    $targ = (isset($parv[1])) ? $parv[1] : $nick;
   

    $gw->act($target,"Using caps: $sendCaps");
    return;
});
