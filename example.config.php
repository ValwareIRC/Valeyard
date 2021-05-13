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
	'nick' => 'test',

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
	'sasl' => 'yes',

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

	/* Table name where the users can be found */
	'sql_users' => 'users',

	/* Table name where the users metadata can be found */
	'sql_usermeta'=> 'usermeta',

	/* Table where the users capabilities can be found */
	'sql_clicap' => 'capabilities',
	
	/* OTHER settings */
	
	/* Anope SQL table prefix */
	//'anopetable' => 'anope_',
	
	/* WordPress SQL table prefix */
	//'wp-prefix' => 'wp_',
	
	/*	UnrealIRCd SQL table prefix
		Uncomment this line if you have SQL set up correctly and want SQL stats from
		your UnrealIRCd server.
		
		Check out src/unrealircd for more details
	*/
	//'unrealtable' => 'unreal_',
	
	
	/* modes for GateWay to set on connect */
	'modes_on_connect' => '+iwx',
	
	/* modes for GateWay to set on oper */	
	'modes_on_oper' => '+iwx',

	/* Minimum amount of days a person must be registered before using a third party client */
	/* Comment out this line if you want there to be no minumum (disable the requirement altogether) */
	'thirdparty_min_reg_days' => '30',


	/* The server which allows third-party connections */
	'thirdparty_server' => 'third.pornhub.com',
	
	
	/* Minimum allowed age of chatters */
	'min_age' => '18',
	
	/* Maximum age of chatters */
	//'max_age' => '110', /* Commented out because we don't want max age but there just in case :> */
	
	
	// Listem mechanism - How does GateWay ask for the channels to join? 'server' or 'chanserv'  or 'sql'
	'listmech' => 'sql',

	/* Important chans yo. These are the chans that GateWay will try to join before all the others. */
	
	'statschan' => '#gatewaystats', /* The channel that GateWay outputs its activity to */
	'serviceschan' => '#services', /* The channel that Services outputs its activity to */
	'staffchan' => '#staff', /* The staff room */
	'helpchan' => '#help', /* The Help room */
	

	/* Not imperative */
	/* Landing room for everyone */
	'landingchan' => '#lounge',


	/* Force join users to their location rooms when they connect */
	'fjoin_loc_chans' => 'yes',

	/* Force join users to their age-respective rooms when they connect */
	'fjoin_age_chans' => 'no',


	
	/* Set mode on staff when they connect */
	'staffmode_connect' => '+qh-R',

	/* Force join rooms for staff, separated by a "," */
	'staffchan_connect' => '#staff,#help',
	


	/* If a user tries to connect to chat who is banned according to Profile Grid */
	'userban_connect_msg' => 'Your account is disabled. For more information, please email appeals@pornhub.com',

]


?>
