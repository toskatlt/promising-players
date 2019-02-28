<?php
/// СКАНИРОВАНИЕ КОМАНД СОЗДАННЫХ ПОЛЬЗОВАТЕЛЯМИ на SOFIFA // 13.07.2018


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

function select25command ($dbcnx_s) {
	$query = mysql_query("SELECT * FROM `sofifa_user_command18` WHERE scan='0' ORDER BY `id` DESC limit 50", $dbcnx_s);
	$n = mysql_num_rows($query);
	for ($i = 0; $i < $n; $i++) {
		$result[] = mysql_fetch_assoc($query);
	}
	return $result;
}

$select25command = select25command ($dbcnx_s);

foreach ($select25command as $s25) {
	
	echo $s25['squad_link'].'<br>';
	
	$sofifa = get_web_page('https://sofifa.com/squad/'.$s25['squad_link']);
	$sofifa_scan = $sofifa['content'];
	
	preg_match_all( '/player\/(.*?)" title/usi' , $sofifa_scan , $players );
	
	foreach ($players[1] as $pl) {
		mysql_query("INSERT INTO `sofifa_players_user_command18`(`player_link`, `squad_link`) VALUES ('".$pl."', '".$s25['squad_link']."')", $dbcnx_s);
	}
	
	mysql_query("UPDATE `sofifa_user_command18` SET `scan`='1' WHERE `squad_link`='".$s25['squad_link']."'", $dbcnx_s);
	
	echo "<br><hr><br>";	
}