<?php
/* HEADER */ include ($_SERVER["DOCUMENT_ROOT"]."/section/header.php");
/* MENU */  include ($_SERVER["DOCUMENT_ROOT"]."/section/menu.php");

include "../config.php";

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
	
	// i=127
	
	for ($i=0; $i<127; $i++) {
		
		$s = $i*60;
		
		$sofifa = get_web_page('https://sofifa.com/players?aeh=23&offset='.$s);
		$sofifa_text = $sofifa['content'];

		preg_match('@<table class="table table-hover persist-area">.*?<tbody>(.*?)</tbody>@usi', $sofifa_text, $all);
		preg_match_all( '@<tr>(.*?)</tr>@usi' , $all[1], $allPlayers );
		
		//var_dump($allPlayers);
		echo '<br>';
		echo count($allPlayers[1]).' - count(allPlayers[1])';
		echo '<br>';
		
		for ($u=0; $u<count($allPlayers[1]); $u++) {	

			preg_match( '@<a href="/player/(.*?)/(.*?)/" title="(.*?)">(.*?)</a>@usi' , $allPlayers[1][$u], $players );

			$id = $players[1];
			$link = $players[2];
			$name = $players[3];
			
			preg_match( '@<div class="col-digit col-oa"><span class="label p p.*?">(.*?)</span></div>@usi' , $allPlayers[1][$u], $skill );
			
			$skillT = $skill[1];
			
			preg_match( '@<div class="col-digit col-pt"><span class="label p p.*?">(.*?)</span></div>@usi' , $allPlayers[1][$u], $potencial );
			
			$potencialT = $potencial[1];


			$query = sprintf("INSERT INTO `all_players_23_age_sofifa_2019` (`num`, `link`, `name`, `skill`, `potencial`) VALUES ('".$id."','".$link."','".$name."','".$skillT."','".$potencialT."')");
			$result =  mysqli_query($dbcnx, $query);
			
			// echo $query.' <br><br>';

		}	
	}
?>