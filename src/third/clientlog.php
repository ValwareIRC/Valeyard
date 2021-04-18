<?php

/*

	Module for keeping client-side logs of PRIVMSG JOIN and PART
	note, we are logging notices as privmsgs atm because it's late and I'm tired basically :>
	
	so, this is gonna need a new entry in your config.php
	you need to specify the FULL PATH of the directory you want to log to.
	if the directory you specified does not exist, it will be created.
	
	something like this:

	"log_dir" => '/path/to/directory/to/write/logs/to/",
	
*/

hook::func("privmsg", function($u){
	// this is a privmsg so we log it as such
	$action = "PRIVMSG";

	// variables for easy use
	$nick = $u['nick'];
	
	// if it's a server it doesn't have an ident or host fam
	// is "NULL" not NULL because it returns "NULL" not NULL getme, "NULL" != NULL
	if ($u['ident'] !== "NULL"){ $nick .= "!".$u['ident']; }
	if ($u['hostmask'] !== "NULL") { $nick .= "@".$u['hostmask']; }
	
	// de place where it was sent lol
	$target = $u['dest'];
	
	// if it is PM, record the log using their username. if not, record the log as channel.
	$loc = (strpos($target,"#") !== false) ? $target : $nick;
	
	clientlog($nick,$action,$loc,$u['parc']);
});

hook::func("join", function($u){
	// this is a join so we log it as such
	$action = "JOIN";
	
	// variables for easy use
	$nick = $u['nick'];
	$target = $u['dest'];
	
	clientlog($nick,$action,$target,"");
});

hook::func("part", function($u){
	// this is a part so we log it as such
	$action = "PART";
	
	// variables for easy use
	$nick = $u['nick'];
	$target = $u['dest'];
	
	clientlog($nick,$action,$target,"");
});

// logging function
function clientlog($user,$action,$loc,$string){

	global $cf,$gw;

	$date = date("Y-m-d");
	$timestamp = date("H:i:s");

	// check the config
	if (!$cf['log_dir']) { $gw->hear("CLIENTLOG Error: Cannot find config entry for log file directory."); return; }

	// if the log directory does not exist already, create it
	if (!is_dir($cf['log_dir'])) { mkdir($cf['log_dir'],0777,true); }
	
	// if server directory does not exist already, create it
	if (!is_dir($cf['log_dir']."/".$cf['serverip'])) { mkdir($cf['log_dir']."/".$cf['serverip'],0777,true); }
	
	$logfile = $cf['log_dir']."/".$cf['serverip']."/".$loc.".".$date.".log";
	
	$text_to_write = "[".$timestamp."] [".$action."] <".$user."> ".$string."\n";
	
	$file = fopen($logfile, "a+");
	fwrite($file,$text_to_write);
	fclose($file);
}
?>