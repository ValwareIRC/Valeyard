<?php

/*

 To use: $yourbot = new Bot($server,$port,$nick,$ident,$host,$gecos,$caps)
 Example:
	
	$snotRag = new Bot("127.0.0.1","6667","Username","ident","lmao.pornhub.com","I am a bot lmao","sasl away-notify");
	
	
 Big thanks to OmerAti and Jobe for helping to get secure connections working
*/

class Bot {
	
	// Welcome to Build-A-Bot, may we take your order
	function __construct($server,$port,$nick,$ident,$gecos,$caps,$password) {
		global $me;
		$me = $nick;
		hook::run("preconnect", array(
			"nick" => $nick,
			"server" => $server,
			"port" => $port,
			"ident" => $ident,
			"gecos" => $gecos,
			"caps" => $caps,
			"password" => $password)
		);
		// INITIALISING CONNECT SEQUENCE lmao
		$this->connect($server,$port,$nick,$ident,$gecos,$caps,$password);
	
	}
	private function connect($server,$port,$nick,$ident,$gecos,$caps,$password) {
		
		// Declare de globals;
		global $socket,$sasl;
		
		// Anything we wanna initialise before we connect
		
		/* pre connect shit */
		
		// we are disabling verification for now until built upon more :>
		// create ssl context
		$context = stream_context_create(['ssl' => [
			'verify_peer' => false,
			'ciphers' => 'ECDHE-ECDSA-AES256-GCM-SHA384'
		]]);

		//opening socket YO
		$socket = stream_socket_client($server.':'.$port, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);
		
		
		// Anything after we open the connection
		
		// who the fuck are ya?!
		$this->send_client_credentials($nick,$ident,$gecos);
		$this->send_cap_req($caps,$nick,$password);
		
			
		
	}
	private function send_sasl_auth($nick,$password){
		$this->sendraw("AUTHENTICATE PLAIN");
	}
	private function send_client_credentials($nick,$ident,$gecos){
		
		// Send our username!
		$this->sendraw("NICK ".$nick);
		$this->sendraw("USER ".$ident." 0 0 :".$gecos);
		
	}
	private function send_cap_req($caps,$nick,$password){
		if ($caps !== NULL) {
			
			$this->sendraw("CAP REQ :".$caps);
			$cap = explode(" ",$caps);
			for ($s = count($cap), $i = 0; $i < $s;){
				if (strtolower($cap[$i]) == 'sasl') {
					$this->sendraw("AUTHENTICATE PLAIN");
					$sasl = true;

				}
				$i++;
			}
			//if (!isset($sasl)) { $this->sendraw('CAP END'); }
		}
	}
	function sasl($nick,$password) {
		$this->sendraw("AUTHENTICATE ".base64_encode(chr(0).$nick.chr(0).$password));
	}
	function sendraw($string){
		// Declare de globals;
		global $socket;
		
		fputs($socket, ircstrip($string)."\n");
		
	}
	
	// Main funcs imo tbh famalam lmoa
	function quit($msg){ $this->sendraw("QUIT :".$msg); }
	function msg($dest,$string){ $this->sendraw("PRIVMSG ".$dest." :".$string); }
	function act($dest,$string){ $this->msg($dest,"ACTION ".$string.""); }
	function notice($dest,$string){ $this->sendraw("NOTICE ".$dest." :".$string); }
	function globalnotice($string){ $this->sendraw("NOTICE $* ".$string); }
	function gline($nick,$time,$reason){ $this->sendraw("GLINE ".$nick." ".$time." ".$reason); }
	function kline($nick,$time,$reason){ $this->sendraw("KLINE ".$nick." ".$time." ".$reason); }
	function kill($nick,$reason){ $this->sendraw("KILL ".$nick." ".$reason); }
	function join($chan){ $this->sendraw("JOIN ".$chan); }
	function part($chan){ $this->sendraw("PART ".$chan); }
	function samode($chan,$mode){ $this->sendraw("SAMODE ".$chan." ".$mode); }
	function sajoin($nick,$chan){ $this->sendraw("SAJOIN ".$nick." ".$chan); }
	function sapart($nick,$chan){ $this->sendraw("SAPART ".$nick." ".$chan); }
	function mode($dest,$mode){ $this->sendraw("MODE ".$dest." ".$mode); }
	function csmode($chan,$mode){ $this->cs("MODE ".$chan." ".$mode); }
	function selfmode($mode){ global $me; $this->mode($me,$mode); }
	function ns($string){ $this->msg("NickServ ",$string); }
	function cs($string){ $this->msg("ChanServ ",$string); }
	function os($string){ $this->msg("OperServ ",$string); }
	function bs($string){ $this->msg("BotServ ",$string); }
	function hs($string){ $this->msg("HostServ ",$string); }
	function ss($string){ $this->msg("StatServ ",$string); }
	function ms($string){ $this->msg("MemoServ ",$string); }
	
	
	// For showing information on your screen famalam
	function shout($string){
		global $me;
		echo "[".$me."][-->] ".$string."\n";
	}
	function hear($string){
		global $me;
		echo "[".$me."][<--] ".$string."\n";
	}
}
