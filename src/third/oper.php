<?php

// oper on connect yo

hook::func("connect", $function($u){

    globals $gw,$cf;

    //check the config
    $opernick = $cf['opernick'];
    $operpass = $cf['operpass'];

    if (!$opernick || !$operpass) { 
        $gw->shout("Couldn't find oper credentials");
        return;
    }
    $gw->sendraw("OPER ".$opernick." "$operpass);
    return;

});
?>