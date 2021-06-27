<?php

// oper on mode +r yo (in case you can't oper until identified (even with sasl) because of missing +r
// make sure your oper credentials 'opernick' and 'operpass' are set in gateway.config.php
hook::func("mode", function($u){
	
    global $gw,$cf,$me;

	$target = $u['dest'];
	if ($target != $me){ return; }
	$parv = explode(" ",$u['parc']);
	if ($parv[0][0] == "+" && strpos($parv[0],"r" !== false){ return; }
	//check the config
	$opernick = $cf['opernick'];
	$operpass = $cf['operpass'];

	// if we had no oper credentials, return
	if (!$opernick || !$operpass) {

		// print in the terminal
        $gw->shout("Couldn't find oper credentials");
        return;
    }
	
	// all good, so we send the oper credentials.
    $gw->sendraw("OPER ".$opernick." ".$operpass);
    return;

});
?>
