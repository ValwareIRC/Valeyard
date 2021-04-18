<?php

/*
    This module attempts to identify with NickServ with it connects.
    If it hears that it is not registered, it will register it.
*/

hook::func("connect", function($u){

    global $cf,$gw,$me;

    if (!isset($cf['password'])) { return; }

    $pass = $cf['password'];

    $gw->ns("IDENTIFY ".$me." ".$pass);

});

hook::func("privmsg", function($u){

    global $cf,$gw,$me;

    $nick = $u['nick'];
    $parc = $u['parc'];
    if (!isset($cf['password'])) { return; }
    if (!isset($cf['email'])) { return; }

    $pass = $cf['password'];
    $email = $cf['email'];
    if ($nick !== "NickServ") { return; }
    if (strpos($parc,"isn't registered") === false && strpos($parc,$me) == false) { return; }

    $gw->ns("REGISTER ".$pass." ".$email);


});
?>
