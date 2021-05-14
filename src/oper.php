<?php

// oper on connect yo
// make sure your oper credentials 'opernick' and 'operpass' are set in gateway.config.php
hook::func("connect", function($u){

    global $gw,$cf;

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
