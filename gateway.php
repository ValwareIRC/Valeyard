<?php
include "src/module.php";
include "gateway.config.php";
//include "src/UKChatterbox/modules.php";

global $cf,$sql,$gw,$sql,$sqlip,$sqluser,$sqlpass,$sqldb;

// Server config
$server = $cf['serverip'];
$port = $cf['port'];
$me = $cf['nick'];
$myident = $cf['ident'];
$myhost = $cf['host'];
$mygecos = $cf['realname'];
$mypass = $cf['password'];

$caps = $cf['caps'];


// SQL config
$sqlip = $cf['sqlip'];
$sqluser = $cf['sqluser'];
$sqlpass = $cf['sqlpass'];
$sqldb = $cf['sqldb'];

start:

$gw = new Bot($server,$port,$me,$myident,$mygecos,$caps,$mypass);
$sql = new SQL($sqlip,$sqluser,$sqlpass,$sqldb);

while (1) {
	while ($input = fgets($socket, 300)) {
		echo $input;
		flush();
		
		$strippem = ircstrip(str_replace('\n','',str_replace('\r','',$input)));
		$splittem = explode(' ',$strippem);
		
		// If the server pings us
		if ($splittem[0] == 'PING') { 
		
			// Ping it back
			$gw->sendraw("PONG ".$splittem[1]);
			
		}
		elseif ($splittem[0] == 'ERROR') {
			hook::run("error", array("errstring" => $input));
			
			if (strpos($input,'Throttled') !== false) {
				$gw->hear("Uh-oh, we've been throttled! Waiting 40 seconds and starting again.");
				sleep(40);
				$gw->shout("Reconnecting...");
				goto start;
			}
			elseif (strpos($input,'Timeout') !== false) {
				$gw->hear("Hmmmm. It seems there was a problem. Please check config.conf that 'nick', 'ident' and 'realname' are correct");
				die();
			}
			elseif (strpos($input,'brb lmoa') !== false) {
				$gw->hear("Looks like we've been asked to restart! Lets go! Pewpewpew!");
				goto start;
			}
			else {
				$gw->hear("Unknown exit issue! Restarting");
				goto start;
			}
		}
		elseif ($splittem[0] != 'PING') {

			// Split our variables up into easy-to-use syntax imo tbh uno init anorl lmao

			if (IsServer($splittem[0]) == 'true') { $nick = ltrim($splittem[0],':'); }
			elseif (IsServer($splittem[0]) == 'false') {
				$nick = get_string_between($splittem[0],':', '!');
				$ident = get_string_between($splittem[0],'!', '@');
				$hostmask = get_string_between($splittem[0].' ','@', ' ');

			}
			if (isset($splittem[2])) { $sp = $splittem[0].' '.$splittem[1].' '.$splittem[2]; }
			if (isset($splittem[3])) { 
				$parc = ircstrip(ltrim(str_replace($sp,'',$strippem),' :'));
				$parv = explode(' ',$parc);

				$cmd = (!empty($splittem[3])) ? strtolower($parv[0]) : NULL;
				$str = ($cmd !== 'NULL') ? str_replace($cmd,'',$parc) : NULL;

			}
			if (isset($splittem[2])) { $dest = ltrim($splittem[2],':'); }

			$action = $splittem[1] ?? NULL;
			$fullstr = $strippem;
			
			
			/*	Important variable description
			**
			**	$nick represents nick of event trigger
			**	$ident represents ident of nick
			**	$hostmask represents hostmask of nick
			** 
			**	$action represents the type of action (PRIVMSG, JOIN, PART, QUIT, etc)
			** 	$dest represents the place of the event trigger - for example, if ($dest == $me) <- Private message.
			**								or, if ($dest == '#staff') <- staff room
			** 
			**	$parc is the entire parameter string ($cmd + $str)
			** 	$parv is an array of params in $parc
			** 	$cmd is the first param in the input string ($parv[0]) (must be lowercase)
			** 	$str is the string that follows after the $cmd ($parv[1], $parv[2] etc)
			** 
			**	$fullstr is the full RAW string from start to finish. (rarely used)
			**	
			**	To call an error, set $error = "Your error";
			**	An $error will abort the script and will be logged.
			**	Don't abort the script unless absolutely necessary!
			**	
			**	To call a warning, it's the same. $warning = "Your warning";
			**	A $warning will be outputted in your 'statchan' and the console and logged.
			**
			*/
			if ($fullstr == 'AUTHENTICATE +') {
				hook::run("auth", NULL);
				$gw->hear('The server wants us to send our login credentials.');
				$gw->sasl($me,$mypass);
				$gw->shout('Sent our login credentials. Fingers crossed!');
			}
			elseif ($action == "001"){
				hook::run("connect",array(
					"nick" => $nick)
				);
			}
			elseif ($action == "PRIVMSG"){ 
				hook::run("privmsg",array(
					"nick" => $nick,
					"ident" => $ident ?? 'NULL',
					"hostmask" => $hostmask,
					"dest" => $dest,
					"parc" => $parc)
				);
			}
			elseif ($action == "NOTICE"){ 
				hook::run("privmsg",array(
					"nick" => $nick,
					"hostmask" => $hostmask ?? 'NULL',
					"ident" => $ident ?? 'NULL',
					"dest" => $dest,
					"parc" => $parc)
				);
			}
			elseif ($action == "JOIN"){
				hook::run("join",array(
					"nick" => $nick,
					"ident" => $ident,
					"hostmask" => $hostmask,
					"dest" => $dest)
				);
			}
			elseif ($action == "PART") {
				hook::run("part",array(
					"nick" => $nick,
					"ident" => $ident,
					"hostmask" => $hostmask,
					"dest" => $dest)
				);
			}
			elseif ($action == "QUIT") {
				hook::run("quit",array(
					"nick" => $nick,
					"ident" => $ident,
					"hostmask" => $hostmask,
					"reason" => $parc)
				);
			}
		}
		
		// variable cleanup.
		$nick = NULL; $ident = NULL;
		$hostmask = NULL; $parv = NULL;
		$parc = NULL; $dest = NULL;
		$cmd = NULL; $str = NULL;
		$fullstr = NULL; $action = NULL;
		
	}
}
?>