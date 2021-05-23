<?php


/*	
 *             Example Configuration Settings
 *
 *		Yeah I wouldn't really advise changing any
 * 		of this unless you know what you're doing lmao
 */

global $cf;

$cf = [
	/* Output raw information to the console, you probably
		want to have this on if you first run a bot init ;) */
	'debugmode' => 'on',

	/* IRC  Bot details */

	/* Bot Username */
	'nick' => 'Valeyard',

	/* The part of the vhost that says ident@hostmask */
	'ident' => 'bot', 

	/* The GECOS (realname) of the bot */
	'realname' => 'Dis mah bot',
	
	/* The host */
	'host' => 'example.pornhub.com', /*lmoa*/

	/* NickServ password */
	'password' => 'SupahNickServPassWad123',
	
	/* Email used for NickServ */
	'email' => 'bigfaps@pornhub.com',
	
	/* Oper shit. Will be partially useless if not oper. */
	'opernick' => 'Oppah',
	'operpass' => 'GangnamStyle123lmao',

	/* Server details */
	'serverip' => '127.0.0.1', /* Will probably work for you if you are running IRCd on the same server */
	'port' => '6667', /* We don't have SSL yet lmao shuddup. We got sasl though. */

	'network' => 'NetworkName',

	/* takes 'yes' or 'no'. 	When 'yes', attempts SASL. When 'no', attempts to identify with NickServ upon connect */
	'caps' => 'message-tags sasl',
	
	/* Uncomment the following line for your bot to set the bot mode on itself when it can */
	//'botmode' => true,

	/* Uncomment the following line if the server uses a password */
	//'serverpassword' => 'CrazyServerPassword123:D', /* Left commented out because don't have one yet lmoa */

	
	/* SQL Config for the user database */

	/* IP of the SQL server */
	'sqlip' => '127.0.0.1', /* would work if your SQL server is on same server as us lol */ 
	
	/* Username to access SQL server */
	'sqluser' => 'sql_username_lol',

	/* Password for the SQL user */
	'sqlpass' => 'sql_password_lol_123',

	/* Name of the database within the SQL server */
	'sqldb' => 'sql_db_name',


	/* Important chans yo. These are the chans that GateWay will try to join before all the others. */
	
	'statschan' => '#gatewaystats', /* The channel that GateWay outputs its activity to */
	'serviceschan' => '#services', /* The channel that Services outputs its activity to */
	'staffchan' => '#staff', /* The staff room */
	'helpchan' => '#help', /* The Help room */
	

	
]


?>
