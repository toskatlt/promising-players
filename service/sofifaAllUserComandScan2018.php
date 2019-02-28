<?php
/// СКАНИРОВАНИЕ СПИСКА КОМАНД СОЗДАННЫХ ПОЛЬЗОВАТЕЛЯМИ на SOFIFA // 13.07.2018


include $_SERVER["DOCUMENT_ROOT"]."/config.php";

function get_web_page ($url) {

	$options = array(
		CURLOPT_RETURNTRANSFER => true,     // return web page
		CURLOPT_HEADER         => false,    // don't return headers
		CURLOPT_FOLLOWLOCATION => true,     // follow redirects
		CURLOPT_ENCODING       => "",       // handle all encodings
		CURLOPT_USERAGENT      => "spider", // who am i
		CURLOPT_AUTOREFERER    => true,     // set referer on redirect
		CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
		CURLOPT_TIMEOUT        => 120,      // timeout on response
		CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
		CURLOPT_SSL_VERIFYPEER => false 
	);

	$ch      = curl_init( $url );
	curl_setopt_array( $ch, $options );
	$content = curl_exec( $ch );
	$err     = curl_errno( $ch );
	$errmsg  = curl_error( $ch );
	$header  = curl_getinfo( $ch );
	curl_close( $ch );

	$header['errno']   = $err;
	$header['errmsg']  = $errmsg;
	$header['content'] = $content;
	return $header;
}

function countUserCom ($dbcnx_s) {
	$query = mysql_query("SELECT FLOOR(count(*)/80) as count FROM `sofifa_user_command18`", $dbcnx_s);
	$result = mysql_fetch_array($query);	
	return $result;
}

$countUserCom = countUserCom ($dbcnx_s);



$l=0;
$y=1;
//$s=2301;
//$t = $countUserCom[0]
$t = 0;
$s = 15;
for ($i = $t; $i<$s; $i++) {
	
	$q = $i*80;
	
	$sofifa = get_web_page('https://sofifa.com/squads?offset='.$q);
	$sofifa_scan = $sofifa['content'];
	preg_match_all( '/div class="col-title text-clip rtl">(.*?)<figure/usi' , $sofifa_scan , $command );
	
	foreach ($command[1] as $comm) {
		
		preg_match( '/team\/(.*?)"/usi' , $comm , $squad_id );
		preg_match( '/squad\/(.*?)"/usi' , $comm , $squad_link );
		preg_match( '/"nofollow">(.*?)</usi' , $comm , $squad_name );
		
		
		
		//echo $y." [".$command_id[1]."] ".$command_name[1]."<br><br>";
		$squad_name[1] = str_replace("'", "", $squad_name[1]);
		mysql_query("INSERT INTO `sofifa_user_command18`(`squad_id`, `squad_link`, `squad_name`) VALUES ('".$squad_id[1]."', '".$squad_link[1]."', '".$squad_name[1]."')", $dbcnx_s);
	}
	
	$l=$l+80;
}

echo $l."<br>";


/// SELECT count(*), squad_name FROM `sofifa_user_command18` group by squad_name order by count(*) desc limit 100