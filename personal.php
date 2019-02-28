<?php
/* HEADER */ include ($_SERVER["DOCUMENT_ROOT"]."/section/header.php");
/* MENU */  include ($_SERVER["DOCUMENT_ROOT"]."/section/menu.php");

?>
<style>
@font-face {
	font-family: 'Dusha';
	src: url('/fonts/Dusha.ttf');
	url('/fonts/Dusha.ttf') format('ttf'),
	font-weight: normal;
	font-style: normal;
}
</style>
<?php

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

echo "<link type='text/css' rel='stylesheet' href='css/fut.css'/>";
require_once($_SERVER["DOCUMENT_ROOT"]."/function/function_vk.php");

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
	$userdata = authorization ($dbcnx, $_COOKIE['id']);
	if (isset($userdata)) {
		if ((isset($_GET['s4'])) and (!isset($_GET['t4'])) and (!isset($_GET['t']))) {
			$card = $_POST['card_own'];
			$liga_db = $_GET['liga'];
			$from_db = $_GET['from'];
			$team_db = $_GET['team'];
			$name_db = $_GET['name'];

			if ($card == 'gold') { copy('img/card/19/g.png', 'tmp/card.png'); }
			elseif ($card == 'gold_nr') { copy('img/card/19/gnr.png', 'tmp/card.png'); }
			elseif ($card == 'silver') { copy('img/card/19/s.png', 'tmp/card.png'); }
			elseif ($card == 'silver_nr') { copy('img/card/19/snr.png', 'tmp/card.png'); }
			elseif ($card == 'bronze') { copy('img/card/19/b.png', 'tmp/card.png'); }
			else { copy('img/card/19/bnr.png', 'tmp/card.png'); }
//// GD ////////////////////////////////////
			$im = ImageCreateTrueColor (640,360);
			$im1 = @imagecreatefromjpeg('/home/prom114768/promising-players.ru/docs/tmp/1.jpg'); // фон
			$im2 = @imagecreatefrompng('/home/prom114768/promising-players.ru/docs/img/card/19/down.png'); // эффекты и ссылки
			$im3 = @imagecreatefrompng('/home/prom114768/promising-players.ru/docs/tmp/card.png'); // карточка
			$im4 = @imagecreatefrompng('/home/prom114768/promising-players.ru/docs/tmp/nation.png'); // флаг
			$im5 = @imagecreatefrompng('/home/prom114768/promising-players.ru/docs/tmp/club.png'); // логотип клуба
			$im6 = @imagecreatefrompng('/home/prom114768/promising-players.ru/docs/tmp/picture.png'); // фото игрока
			$im7 = @imagecreatefrompng('/home/prom114768/promising-players.ru/docs/img/card/19/top.png'); // стрелка вверх

			//$font = imageloadfont('/home/web_www/public_html/fonts/futhead-sans-bold.ttf');
			$fontb = '/home/prom114768/promising-players.ru/docs/fonts/DINPro-Cond.otf'; // ТОНЬШЕ

			$font = '/home/prom114768/promising-players.ru/docs/fonts/DINPro-CondBold.otf'; // ТОЛЩЕ
			$font_name = '/home/prom114768/promising-players.ru/docs/fonts/DINPro-CondMedium.otf'; // ФАМИЛИЯ

			imagealphablending($im, true);
			imagealphablending($im, 1);
			imagealphablending($im1, 1);
			imagealphablending($im2, 1);
			imagealphablending($im3, 1);
			imagealphablending($im4, 1);
			imagealphablending($im5, 1);
			imagealphablending($im6, 1);
			imagealphablending($im7, 1);

			$sofifa_ruscolor = imagecolorallocate($im, 43, 36, 21);
			$sofifa_ruscolorw = imagecolorallocate($im, 127, 225, 29);

			imagecopy($im, $im1, 0, 0, 0, 0, 640, 360); // ОСНОВА
			imagecopy($im, $im2, 0, 0, 0, 0, 640, 360); // ПОДЛОЖКА НИЖНЯЯ
			imagecopy($im, $im3, 23, 34, 0, 0, 202, 307); // КАРТОЧКА
			ImageCopyResampled($im, $im4, 60, 133, 0, 0, 28, 16, 104, 62); // НАЦИЯ
			ImageCopyResampled($im, $im5, 60, 158, 0, 0, 29, 29, 140, 140); // КЛУБ

			$imsize = getimagesize('/home/prom114768/promising-players.ru/docs/tmp/picture.png');
			$width_img = $imsize[0]; //ширина
			$height_img = $imsize[1]; // высота

			ImageCopyResampled($im, $im6, 84, 67, 0, 0, 131, 131, $width_img, $height_img); // ФОТО
			imagecopy($im, $im7, 0, 0, 0, 0, 640, 360); // ПОДЛОЖКА ВЕРХНЯЯ

			//imagecopy($im, $im8, 23, 45, 0, 0, 200, 300);
			//imagestring($im, 54, 90, 196, $_GET["a1"], $sofifa_ruscolor);

			$center = round(126); //центр изображения
			$box = imagettfbbox(16, 0, $fontb, $_GET["pn"]); // ФАМИЛИЯ
			$position = $center-round(($box[2]-$box[0])/2); //позиция начала текста

			//$center2 = round(100); //центр изображения
			$rost = "+".$_GET["rost"];

			//$box2 = imagettfbbox(14, 0, $font, $rost); // РОСТ СКИЛА
			//$position2 = $center2-round(($box2[2]-$box2[0])/2); //позиция начала текста

			$center_position = round(73); // ЦЕНТР РАЗМЕЩЕНИЯ ПОЗИЦИИ
			$box_position = imagettfbbox(13, 0, $font, $_GET["pp"]); // ПОЗИЦИЯ РАСЧЕТ
			$position3 = $center_position-round(($box_position[2]-$box_position[0])/2); // НАЧАЛО ТЕКСТА ПОЗИЦИИ

			imagettftext($im, 16, 0, $position, 221, $sofifa_ruscolor, $fontb, $_GET["pn"]);

			$center4 = round(99); // ЦЕНТР РАЗМЕЩЕНИЯ РОСТ
			$box4 = imagettfbbox(14, 0, $font, $rost); // РОСТ СКИЛА
			$position4 = $center4-round(($box4[2]-$box4[0])/2); //позиция начала текста

			imagettftext($im, 14, 0, $position4, 75, $sofifa_ruscolor, $font, $rost); // РОСТ РАЗМЕЩЕНИЕ
			imagettftext($im, 15, 0, $position3, 120, $sofifa_ruscolor, $font, $_GET["pp"]); // ПОЗИЦИЯ РАЗМЕЩЕНИЕ
			imagettftext($im, 28, 0, 58, 99, $sofifa_ruscolor, $font, $_GET["pr"]); // РЕЙТИНГ РАЗМЕЩЕНИЕ
			$position = trim($_GET["pp"]);
			if ($position != 'GK') {
				$s1 = " PAC";
				$s2 = " SHO";
				$s3 = " PAS";
				$s4 = " DRI";
				$s5 = " DEF";
				$s6 = " PHY";
			} else {
				$s1 = " DIV";
				$s2 = " HAN";
				$s3 = " KIC";
				$s4 = " REF";
				$s5 = " SPE";
				$s6 = " POS";
			}

			imagettftext($im, 15, 0, 61, 248, $sofifa_ruscolor, $fontb, $_GET["s1"]);
			imagettftext($im, 15, 0, 62, 271, $sofifa_ruscolor, $fontb, $_GET["s2"]);
			imagettftext($im, 15, 0, 61, 292, $sofifa_ruscolor, $fontb, $_GET["s3"]);
			imagettftext($im, 15, 0, 146, 249, $sofifa_ruscolor, $fontb, $_GET["s4"]);
			imagettftext($im, 15, 0, 146, 270, $sofifa_ruscolor, $fontb, $_GET["s5"]);
			imagettftext($im, 15, 0, 146, 292, $sofifa_ruscolor, $fontb, $_GET["s6"]);

			imagettftext($im, 15, 0, 78, 249, $sofifa_ruscolor, $fontb, $s1);
			imagettftext($im, 15, 0, 79, 270, $sofifa_ruscolor, $fontb, $s2);
			imagettftext($im, 15, 0, 78, 292, $sofifa_ruscolor, $fontb, $s3);
			imagettftext($im, 15, 0, 162, 249, $sofifa_ruscolor, $fontb, $s4);
			imagettftext($im, 15, 0, 162, 270, $sofifa_ruscolor, $fontb, $s5);
			imagettftext($im, 15, 0, 162, 292, $sofifa_ruscolor, $fontb, $s6);

			$filename = md5(microtime());

			//header("Content-type: image/png;");
			imagepng($im, '/home/prom114768/promising-players.ru/docs/tmp/public/'.$filename.'.png');
			imagedestroy($im);

//// VK ////////////////////////////////////////////////////////////////////////
			$connect_vk = mysqli_query($dbcnx, "SELECT * FROM `connect_vk` WHERE run='1'");
			$connect_vk_result = mysqli_fetch_assoc($connect_vk);
			$access_token = $connect_vk_result['token'];
//////////// #############################  fifa_easport  ########################################
			$message_out = $_POST['message'];
			$album = $_GET['album'];
			$groupId = 31685665; // fifa_easport
			$med = json_decode(file_get_contents("https://api.vk.com/method/photos.getWallUploadServer?group_id={$groupId}&v=5.68&access_token={$access_token}"),true);
			$link = $med['response']['upload_url'];

			$img_src = '/home/prom114768/promising-players.ru/docs/tmp/public/'.$filename.'.png';

			$file = new CURLFile(realpath($img_src));
			$ch = curl_init($link);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, array('photo' => $file));
			$response  = curl_exec($ch);
			curl_close($ch);
			
			$response1 = json_decode($response);
			$server = $response1 -> server;
			$photo = $response1 -> photo;
			$hash = $response1 -> hash;

			$saveWallPhoto = "https://api.vk.com/method/photos.saveWallPhoto?access_token=".$access_token."&server=".$server."&hash=".$hash."&photo=".$photo."&group_id={$groupId}&v=5.68";
			$data3 = file_get_contents($saveWallPhoto);
			$data3 = json_decode($data3, true);
			$attachments = "photo247298_".$data3['response'][0]['id'];
			$message_out = urlencode($message_out);
//////////// #############################  fifa_easport - конец  ########################################
			mysqli_query($dbcnx, "INSERT INTO `vk_fifa_easport_promising_players`(`name`, `skill`, `position`, `from`, `club`, `liga`, `filename`, `attachments`, `text`, `publicated`, `album`) VALUES ('".$name_db."', '".$_GET["pr"]."', '".$position."', '".$from_db."', '".$team_db."', '".$liga_db."', '".$filename."', '".$attachments."', '".$message_out."', '0', '".$album."')");
		}
		if (isset($_GET['t']) and ($_GET['t'] == 'news')) 	{
			
			$card = $_POST['card_own'];
			$liga_db = $_GET['liga'];
			$from_db = $_GET['from'];
			$team_db = $_GET['team'];
			$name_db = $_GET['name'];
			
			if ($card == 'gold') { copy('img/card/19/g.png', 'tmp/card.png'); }
			elseif ($card == 'gold_nr') { copy('img/card/19/gnr.png', 'tmp/card.png'); }
			elseif ($card == 'silver') { copy('img/card/19/s.png', 'tmp/card.png'); }
			elseif ($card == 'silver_nr') { copy('img/card/19/snr.png', 'tmp/card.png'); }
			elseif ($card == 'bronze') { copy('img/card/19/b.png', 'tmp/card.png'); }
			elseif ($card == 'bronze_nr') { copy('img/card/19/bnr.png', 'tmp/card.png'); }
			elseif ($card == 'otw') { copy('img/card/19/otw.png', 'tmp/card.png'); }
			
			
//// GD ////////////////////////////////////
			$im = ImageCreateTrueColor (640,360);
			$im1 = @imagecreatefromjpeg('/var/www/html/www/tmp/1.jpg'); // фон
			$im2 = @imagecreatefrompng('/var/www/html/www/img/card/19/down.png'); // эффекты и ссылки
			$im3 = @imagecreatefrompng('/var/www/html/www/tmp/card.png'); // карточка
			$im4 = @imagecreatefrompng('/var/www/html/www/tmp/nation.png'); // флаг
			$im5 = @imagecreatefrompng('/var/www/html/www/tmp/club.png'); // логотип клуба
			$im6 = @imagecreatefrompng('/var/www/html/www/tmp/picture.png'); // фото игрока
			$im7 = @imagecreatefrompng('/var/www/html/www/img/card/19/top_news.png'); // стрелка вверх
			
			if ($card == 'otw') { $sofifa_ruscolor = imagecolorallocate($im, 255, 1, 192); } // розовый 
			else { $sofifa_ruscolor = imagecolorallocate($im, 43, 36, 21); } // черный

			//$font = imageloadfont('/home/web_www/public_html/fonts/futhead-sans-bold.ttf');
			$fontb = '/var/www/html/www/fonts/DINPro-Cond.otf'; // ТОНЬШЕ

			$font = '/var/www/html/www/fonts/DINPro-CondBold.otf'; // ТОЛЩЕ
			$font_name = '/var/www/html/www/fonts/DINPro-CondMedium.otf'; // ФАМИЛИЯ

			imagealphablending($im, true);
			imagealphablending($im, 1);
			imagealphablending($im1, 1);
			imagealphablending($im2, 1);
			imagealphablending($im3, 1);
			imagealphablending($im4, 1);
			imagealphablending($im5, 1);
			imagealphablending($im6, 1);
			imagealphablending($im7, 1);

			imagecopy($im, $im1, 0, 0, 0, 0, 640, 360); // ОСНОВА
			imagecopy($im, $im2, 0, 0, 0, 0, 640, 360); // ПОДЛОЖКА НИЖНЯЯ
			imagecopy($im, $im3, 23, 34, 0, 0, 202, 307); // КАРТОЧКА
			ImageCopyResampled($im, $im4, 60, 133, 0, 0, 28, 16, 104, 62); // НАЦИЯ
			ImageCopyResampled($im, $im5, 60, 158, 0, 0, 29, 29, 140, 140); // КЛУБ

			$imsize = getimagesize('/var/www/html/www/tmp/picture.png');
			$width_img = $imsize[0]; //ширина
			$height_img = $imsize[1]; // высота

			ImageCopyResampled($im, $im6, 84, 67, 0, 0, 131, 131, $width_img, $height_img); // ФОТО
			imagecopy($im, $im7, 0, 0, 0, 0, 640, 360); // ПОДЛОЖКА ВЕРХНЯЯ

			$center = round(126); //центр изображения
			$box = imagettfbbox(16, 0, $fontb, $_GET["pn"]); // ФАМИЛИЯ
			$position = $center-round(($box[2]-$box[0])/2); //позиция начала текста

			$center_position = round(73); // ЦЕНТР РАЗМЕЩЕНИЯ ПОЗИЦИИ
			$box_position = imagettfbbox(13, 0, $font, $_GET["pp"]); // ПОЗИЦИЯ РАСЧЕТ
			$position3 = $center_position-round(($box_position[2]-$box_position[0])/2); // НАЧАЛО ТЕКСТА ПОЗИЦИИ

			imagettftext($im, 16, 0, $position, 221, $sofifa_ruscolor, $fontb, $_GET["pn"]);
			imagettftext($im, 15, 0, $position3, 120, $sofifa_ruscolor, $font, $_GET["pp"]); // ПОЗИЦИЯ РАЗМЕЩЕНИЕ
			imagettftext($im, 28, 0, 58, 99, $sofifa_ruscolor, $font, $_GET["pr"]); // РЕЙТИНГ РАЗМЕЩЕНИЕ
			$position = trim($_GET["pp"]);
			if ($position != 'GK') {
				$s1 = " PAC";
				$s2 = " SHO";
				$s3 = " PAS";
				$s4 = " DRI";
				$s5 = " DEF";
				$s6 = " PHY";
			} else {
				$s1 = " DIV";
				$s2 = " HAN";
				$s3 = " KIC";
				$s4 = " REF";
				$s5 = " SPE";
				$s6 = " POS";
			}

			imagettftext($im, 15, 0, 61, 248, $sofifa_ruscolor, $fontb, $_GET["s1"]);
			imagettftext($im, 15, 0, 62, 271, $sofifa_ruscolor, $fontb, $_GET["s2"]);
			imagettftext($im, 15, 0, 61, 292, $sofifa_ruscolor, $fontb, $_GET["s3"]);
			imagettftext($im, 15, 0, 146, 249, $sofifa_ruscolor, $fontb, $_GET["s4"]);
			imagettftext($im, 15, 0, 146, 270, $sofifa_ruscolor, $fontb, $_GET["s5"]);
			imagettftext($im, 15, 0, 146, 292, $sofifa_ruscolor, $fontb, $_GET["s6"]);

			imagettftext($im, 15, 0, 78, 249, $sofifa_ruscolor, $fontb, $s1);
			imagettftext($im, 15, 0, 79, 270, $sofifa_ruscolor, $fontb, $s2);
			imagettftext($im, 15, 0, 78, 292, $sofifa_ruscolor, $fontb, $s3);
			imagettftext($im, 15, 0, 162, 249, $sofifa_ruscolor, $fontb, $s4);
			imagettftext($im, 15, 0, 162, 270, $sofifa_ruscolor, $fontb, $s5);
			imagettftext($im, 15, 0, 162, 292, $sofifa_ruscolor, $fontb, $s6);

			$filename = md5(microtime());

			//header("Content-type: image/png;");
			imagepng($im, '/var/www/html/www/tmp/public/'.$filename.'.png');
			imagedestroy($im);

//// VK ////////////////////////////////////////////////////////////////////////
			$connect_vk = mysqli_query("SELECT * FROM `connect_vk` WHERE run='1'", $dbcnx_s);
			$connect_vk_result = mysqlifetch_assoc($connect_vk);
			$access_token = $connect_vk_result['token'];
//////////// #############################  fifa_easport  ########################################
			$message_out = $_POST['message'];
			$album = 259304713; // FIFA19 | Новости футбола
			$groupId = 31685665; // fifa_easport
			$med = json_decode(file_get_contents("https://api.vk.com/method/photos.getWallUploadServer?group_id={$groupId}&v=5.68&access_token={$access_token}"),true);
			$link = $med['response']['upload_url'];
			$img_src = '/var/www/html/www/tmp/public/'.$filename.'.png';

			$file = new CURLFile(realpath($img_src));
			$ch = curl_init($link);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, array('photo' => $file));
			$response  = curl_exec($ch);
			curl_close($ch);

			$response1 = json_decode($response);
			$server = $response1 -> server;
			$photo = $response1 -> photo;
			$hash = $response1 -> hash;

			$saveWallPhoto = "https://api.vk.com/method/photos.saveWallPhoto?access_token=".$access_token."&server=".$server."&hash=".$hash."&photo=".$photo."&group_id={$groupId}&v=5.68";
			$data3 = file_get_contents($saveWallPhoto);
			$data3 = json_decode($data3, true);
			$attachments = "photo247298_".$data3['response'][0]['id'];
			$message_out = urlencode($message_out);
//////////// #############################  fifa_easport - конец  ########################################
			mysqli_query("INSERT INTO `vk_fifa_easport_news`(`name`, `skill`, `position`, `filename`, `attachments`, `text`, `publicated`, `album`) VALUES ('".$name_db."', '".$_GET["pr"]."', '".$position."', '".$filename."', '".$attachments."', '".$message_out."', '0', '".$album."')", $dbcnx_s);
			
		}
		if ((isset($_GET['s4'])) and (isset($_GET['t4']))) {

			$card = $_POST['card_own'];
			$liga_db = $_GET['liga'];
			$from_db = $_GET['from'];
			$team_db = $_GET['team'];
			$name_db = $_GET['name'];

			if     ($card == 'gold') { copy('img/card/g.png', 'tmp/card.png'); }
			elseif ($card == 'gold_nr') { copy('img/card/gnr.png', 'tmp/card.png'); }
			elseif ($card == 'silver') { copy('img/card/s.png', 'tmp/card.png'); }
			elseif ($card == 'silver_nr') { copy('img/card/snr.png', 'tmp/card.png'); }
			elseif ($card == 'bronze') { copy('img/card/b.png', 'tmp/card.png'); }
			else   { copy('img/card/bnr.png', 'tmp/card.png'); }

			$im = ImageCreateTrueColor (600,600);
			$im1 = @imagecreatefromjpeg('/home/web_www/public_html/tmp/evolution.jpg'); // фон
			$im3 = @imagecreatefrompng('/home/web_www/public_html/tmp/card.png'); // карточка
			$im4 = @imagecreatefrompng('/home/web_www/public_html/tmp/nation.png'); // флаг
			$im5 = @imagecreatefrompng('/home/web_www/public_html/tmp/club.png'); // логотип клуба
			$im6 = @imagecreatefrompng('/home/web_www/public_html/tmp/picture.png'); // фото игрока
			//$im7 = @imagecreatefrompng('/home/web_www/public_html/img/card/top.png'); // стрелка вверх
			$im8 = @imagecreatefrompng('/home/web_www/public_html/tmp/nationOld.png'); // флаг
			$im9 = @imagecreatefrompng('/home/web_www/public_html/tmp/clubOld.png'); // логотип клуба
			$im10 = @imagecreatefrompng('/home/web_www/public_html/tmp/pictureOld.png'); // фото игрока
			$im12 = @imagecreatefrompng('/home/web_www/public_html/img/card/top_evo.png'); // стрелка вверх

			$fontb = '/home/web_www/public_html/fonts/DINPro-CondLight.otf'; // ТОНЬШЕ
			$font = '/home/web_www/public_html/fonts/DINPro-Medium.otf'; // ТОЛЩЕ
			$font_name = '/home/web_www/public_html/fonts/DINPro-CondMedium.otf'; // ФАМИЛИЯ
			$font_bold = '/home/web_www/public_html/fonts/DINPro-CondBlack.otf'; // ФАМИЛИЯ

			imagealphablending($im, true);
			imagealphablending($im, 1);
			imagealphablending($im1, 1);
			imagealphablending($im3, 1);
			imagealphablending($im4, 1);
			imagealphablending($im5, 1);
			imagealphablending($im6, 1);
			imagealphablending($im8, 1);
			imagealphablending($im9, 1);
			imagealphablending($im10, 1);
			imagealphablending($im12, 1);

			$sofifa_ruscolor = imagecolorallocate($im, 63, 63, 52);
			$sofifa_ruscolorw = imagecolorallocate($im, 223, 243, 250);
	//////////////////////////	новое
			imagecopy($im, $im1, 0, 0, 0, 0, 600, 600);
			imagecopy($im, $im3, 275, 146, 0, 0, 202, 310);
			ImageCopyResampled($im, $im4, 309, 271, 0, 0, 39, 25, 71, 45);
			ImageCopyResampled($im, $im5, 309, 227, 0, 0, 40, 40, 256, 256);

			$imsize = getimagesize('/home/web_www/public_html/tmp/picture.png');
			$width_img = $imsize[0]; //ширина
			$height_img = $imsize[1]; // высота

			ImageCopyResampled($im, $im6, 350, 182, 0, 0, 120, 120, $width_img, $height_img);
	//////////////////////////	новое
	////////////////////////// старое
			ImageCopyResampled($im, $im8, 148, 278, 0, 0, 30, 19, 71, 45);
			ImageCopyResampled($im, $im9, 148, 246, 0, 0, 30, 30, 256, 256);

			$imsize_old = getimagesize('/home/web_www/public_html/tmp/pictureOld.png');
			$width_img_old = $imsize_old[0]; //ширина
			$height_img_old = $imsize_old[1]; // высота

			if ($width_img_old < $height_img_old) {
				$height_proc = 85/($height_img_old/100)*0.01;
				$w = $width_img_old*$height_proc;
				$h = $height_img_old;
			}
			elseif ($width_img_old > $height_img_old) {
				$height_proc = 85/($height_img_old/100)*0.01;
				$w = $width_img_old*$height_proc;
				$h = 85;
			}
			elseif ($width_img_old = $height_img_old) {
				$w = 85;
				$h = 85;
			}
			ImageCopyResampled($im, $im10, 186, 217, 0, 0, $w, $h, $width_img_old, $height_img_old);
			imagecopy($im, $im12, 0, 0, 0, 0, 600, 600);
	//////////////////////////
			$center = round(376); //центр изображения
			$box = imagettfbbox(16, 0, $font_name, $_GET["pn"]); // ФАМИЛИЯ
			$position = $center-round(($box[2]-$box[0])/2); //позиция начала текста

			$center_old = round(210); //центр изображения
			$box_old = imagettfbbox(13, 0, $font_name, $_GET["pn"]); // ФАМИЛИЯ
			$position_old = $center_old-round(($box_old[2]-$box_old[0])/2); //позиция начала текста

			imagettftext($im, 16, 0, $position, 323, $sofifa_ruscolor, $font_name, $_GET["pn"]);
			imagettftext($im, 13, 0, $position_old, 319, $sofifa_ruscolorw, $font_name, $_GET["pn"]);

			$center3 = round(329); //центр изображения
			$box3 = imagettfbbox(12, 0, $font, $_GET["pp"]); // ПОЗИЦИЯ
			$position3 = $center3-round(($box3[2]-$box3[0])/2); //позиция начала текста

			imagettftext($im, 12, 0, $position3, 223, $sofifa_ruscolor, $font, $_GET["pp"]);
			imagettftext($im, 22, 0, 314, 207, $sofifa_ruscolor, $font, $_GET["pr"]); // РЕЙТИНГ

			$center4 = round(166); //центр изображения
			$box4 = imagettfbbox(12, 0, $font, $_GET["ppo"]); // ПОЗИЦИЯ
			$position4 = $center4-round(($box4[2]-$box4[0])/2); //позиция начала текста

			imagettftext($im, 10, 0, $position4, 242, $sofifa_ruscolor, $font, $_GET["ppo"]);
			imagettftext($im, 18, 0, 149, 229, $sofifa_ruscolor, $font, $_GET["pro"]); // РЕЙТИНГ

			$version = "FIFA".$_GET['fifaold'];

			imagettftext($im, 12, 0, 190, 405, $sofifa_ruscolorw, $font_bold, $version); // ВЕРСИЯ ФИФЫ

			$potencial = "POTENTIAL: ".$_GET['pt']."+";

			$center5 = round(334); //центр изображения
			$box5 = imagettfbbox(14, 0, $font, $_GET["pt"]); // ПОЗИЦИЯ
			$position5 = $center5-round(($box5[2]-$box5[0])/2); //позиция начала текста

			imagettftext($im, 14, 0, $position5, 423, $sofifa_ruscolor, $font_bold, $potencial); // ВЕРСИЯ ФИФЫ

			if ($_GET["pp"] != 'GK'){
				$s1 = " PAC";
				$s2 = " SHO";
				$s3 = " PAS";
				$s4 = " DRI";
				$s5 = " DEF";
				$s6 = " PHY";
			}
			else {
				$s1 = " DIV";
				$s2 = " HAN";
				$s3 = " KIC";
				$s4 = " REF";
				$s5 = " SPE";
				$s6 = " POS";
			}

			imagettftext($im, 14, 0, 306, 348, $sofifa_ruscolor, $font, $_GET["s1"]);
			imagettftext($im, 14, 0, 306, 373, $sofifa_ruscolor, $font, $_GET["s2"]);
			imagettftext($im, 14, 0, 306, 396, $sofifa_ruscolor, $font, $_GET["s3"]);
			imagettftext($im, 14, 0, 390, 348, $sofifa_ruscolor, $font, $_GET["s4"]);
			imagettftext($im, 14, 0, 390, 373, $sofifa_ruscolor, $font, $_GET["s5"]);
			imagettftext($im, 14, 0, 390, 396, $sofifa_ruscolor, $font, $_GET["s6"]);

			imagettftext($im, 14, 0, 327, 348, $sofifa_ruscolor, $font, $s1);
			imagettftext($im, 14, 0, 327, 373, $sofifa_ruscolor, $font, $s2);
			imagettftext($im, 14, 0, 327, 396, $sofifa_ruscolor, $font, $s3);
			imagettftext($im, 14, 0, 411, 348, $sofifa_ruscolor, $font, $s4);
			imagettftext($im, 14, 0, 411, 373, $sofifa_ruscolor, $font, $s5);
			imagettftext($im, 14, 0, 411, 396, $sofifa_ruscolor, $font, $s6);

			if (($_GET['fifaold'] < 15) and ($_GET["pp"] != 'GK')) { $s6 = " HEA"; }

////////////////////////////////////////
			imagettftext($im, 11, 0, 151, 341, $sofifa_ruscolorw, $font, $_GET["t1"]);
			imagettftext($im, 11, 0, 151, 360, $sofifa_ruscolorw, $font, $_GET["t2"]);
			imagettftext($im, 11, 0, 151, 379, $sofifa_ruscolorw, $font, $_GET["t3"]);

			imagettftext($im, 11, 0, 222, 341, $sofifa_ruscolorw, $font, $_GET["t4"]);
			imagettftext($im, 11, 0, 222, 360, $sofifa_ruscolorw, $font, $_GET["t5"]);
			imagettftext($im, 11, 0, 222, 379, $sofifa_ruscolorw, $font, $_GET["t6"]);

			imagettftext($im, 11, 0, 165, 341, $sofifa_ruscolorw, $font, $s1);
			imagettftext($im, 11, 0, 165, 360, $sofifa_ruscolorw, $font, $s2);
			imagettftext($im, 11, 0, 165, 379, $sofifa_ruscolorw, $font, $s3);

			imagettftext($im, 11, 0, 236, 341, $sofifa_ruscolorw, $font, $s4);
			imagettftext($im, 11, 0, 236, 360, $sofifa_ruscolorw, $font, $s5);
			imagettftext($im, 11, 0, 236, 379, $sofifa_ruscolorw, $font, $s6);

			$filename = md5(microtime());
			imagepng($im, '/home/web_www/public_html/tmp/public/'.$filename.'.png');
			imagedestroy($im);

	//////////////   VK   ///////////////////////////
			$message_out = $_POST['message'];
			$groupId = 31685665;
			
			$connect_vk = mysqli_query("SELECT * FROM `connect_vk` WHERE run='1'", $dbcnx_s);
			$connect_vk_result = mysqlifetch_assoc($connect_vk);
			$access_token = $connect_vk_result['token'];

			$med = json_decode(file_get_contents("https://api.vk.com/method/photos.getWallUploadServer?group_id={$groupId}&v=5.62&access_token={$access_token}"),true);

			$link = $med['response']['upload_url'];
			$img_src = '/home/web_www/public_html/tmp/public/'.$filename.'.png';

			$post_params = array(
				'file1' => '@'.$img_src
			);

			$ch = curl_init($link);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
			$response = curl_exec( $ch );
			curl_close( $ch );

			$response1 = json_decode($response);
			$server=$response1->server;
			$photo=$response1->photo;
			$hash=$response1->hash;

			$link2= "https://api.vk.com/method/photos.saveWallPhoto?access_token=".$access_token."&server=".$server."&hash=".$hash."&photo=".$photo."&group_id={$groupId}&v=5.62";
			$data3 = file_get_contents($link2);
			$data3 = json_decode($data3, true);

			$attachments = "photo247298_".$data3['response'][0]['id'];
			$message_out = urlencode($message_out);

			$insert = "INSERT INTO `vk_fifa_easport_evolution`(`filename`, `attachments`,  `text`, `publicated`, `album`) VALUES ('".$filename."', '".$attachments."', '".$message_out."', '0', '236375294')";
			mysqli_query($insert,$dbcnx_s);

			//ob_clean();
		}
		if (isset($_POST['enter']))	{
				if ((!empty($_FILES["file_news"]["name"])) and (!empty($_POST['curl_news']))) {
					
					if($_FILES["file_news"]["size"] > 1024*3*1024) {
						echo ("Размер файла превышает три мегабайта");
						exit;
					}
				   // Проверяем загружен ли файл
					if(is_uploaded_file($_FILES["file_news"]["tmp_name"])) {
						move_uploaded_file($_FILES["file_news"]["tmp_name"], "tmp/1.jpg");
					} else {
						echo("Ошибка загрузки файла");
					}
					
					// FUTHEAD
					$fudhead = get_web_page('https://www.futhead.com/19/players/'.$_POST['curl_news']);
					$fudhead_text = $fudhead['content'];
					// КАРТОЧКА
					preg_match( '/<div class="futhead-group player-info".*?<div class="player-cards">(.*?)<\/div>\s*<\/div>/sui' , $fudhead_text, $card );
					preg_match( '@img/19/players/(\d+).png@sui' , $card[1], $player_id);

					// SOFIFA
					$sofifa_eng = get_web_page('https://sofifa.com/player/'.$player_id[1]);
					$sofifa_rus = get_web_page('https://sofifa.com/player/'.$player_id[1].'?hl=ru-RU&units=mks');
					
					////// АНГЛИЙСКАЯ ВЕРСИЯ САЙТА //////

					preg_match( '/<div class="meta">(.*?)\s\<a/sui' , $sofifa_rus['content'] , $fio ); // ФАМИЛИЯ ИМЯ ИГРОКА

					if (stripos($fio[1], "'")) {
						$fio[1] = str_replace("'", "", $fio[1]);
					}

					preg_match( '/Overall Rating&nbsp;<span class="label p p\d+">(.*?)</siu' , $sofifa_eng['content'] , $skill_reiting ); // РЕЙТИНГ
					preg_match( '/span>\s??Age\s(\d+)/sui' , $sofifa_eng['content'] , $age ); // ВОЗРАСТ
					
					$skl = $skill_reiting[1];

					preg_match_all( '/var point.*?=\s(\d+);/' , $sofifa_rus['content'], $calculator );
					preg_match( '/<span class="pos pos\d+">(.*?)</usi' , $sofifa_eng['content'], $pos_t ); // ПОЗИЦИЯ

					// РАЗБИРАЕМ КАРТОЧКУ FUDHEAD НА СКИЛЫ
					preg_match_all( '/playercard-attr.?">(\d+)\s<span/' , $card[1] , $playercard_attr );
					$calculator10 = "playercard-attr1\">".$calculator[1][0]." <span";
					$calculator11 = "playercard-attr2\">".$calculator[1][1]." <span";
					$calculator12 = "playercard-attr3\">".$calculator[1][2]." <span";
					$calculator13 = "playercard-attr4\">".$calculator[1][3]." <span";
					$calculator14 = "playercard-attr5\">".$calculator[1][4]." <span";
					$calculator15 = "playercard-attr6\">".$calculator[1][5]." <span";
					$position_new = "class='playercard-position'>".$pos_t[1]."</div>";
					$skill_reiting_new = "class='playercard-rating'>".$skill_reiting[1]."</div>";
					$basic = "<div class='playercard-chem not-draggable'><img class='chem-icon' src='https://www.futhead.com/static/img/chemistry/bas.png'><span id='chem' class='chem-name input-toggle'>BASIC</span><span id='ruler' class='hidee'></span></div>";

					//preg_match('/playercard-attr4">(\d+)\s<span/', $card[1], $card4);
					$card0 = preg_replace('/playercard-attr1">(\d+)\s<span/', $calculator10, $card[1]);
					$card1 = preg_replace('/playercard-attr2">(\d+)\s<span/', $calculator11, $card0);
					$card2 = preg_replace('/playercard-attr3">(\d+)\s<span/', $calculator12, $card1);
					$card3 = preg_replace('/playercard-attr4">(\d+)\s<span/', $calculator13, $card2);
					$card4 = preg_replace('/playercard-attr5">(\d+)\s<span/', $calculator14, $card3);
					$card5 = preg_replace('/playercard-attr6">(\d+)\s<span/', $calculator15, $card4);
					$card6 = preg_replace('/class="playercard-position">(.*?)<\/div>/', $position_new, $card5);
					$card7 = preg_replace('/class="playercard-rating">(.*?)<\/div>/', $skill_reiting_new, $card6);
					$card8 = preg_replace('/<div class="playercard-workrates playercard-thin">.*?<\/div>/', $basic, $card7);

					preg_match('/playercard\s+fut19 card-large\s*(.*?)\stext/usi', $card8, $card_nif);

					if (($card_nif[1] == 'nif  gold') or ($card_nif[1] == 'transfer gold') or ($card_nif[1] == 'transfer  gold')){
						$card_own = 'gold';
					} elseif (($card_nif[1] == 'nif  non-rare gold') or ($card_nif[1] == 'transfer gold non-rare') or ($card_nif[1] == 'transfer  non-rare gold')) {
						$card_own = 'gold_nr';
					} elseif (($card_nif[1] == 'nif  silver') or ($card_nif[1] == 'transfer  silver')) {
						$card_own = 'silver';
					} elseif (($card_nif[1] == 'nif  non-rare silver') or ($card_nif[1] == 'transfer silver non-rare')) {
						$card_own = 'silver_nr';
					} elseif (($card_nif[1] == 'nif  bronze') or ($card_nif[1] == 'transfer bronze')) {
						$card_own = 'bronze';
					} else { $card_own = 'bronze_nr'; }

					preg_match('@<div class="playercard-nation">.*?src="https://futhead.cursecdn.com/(.*?)"@usi', $card[1], $card_country); //+
					copy("https://futhead.cursecdn.com/".$card_country[1], '/var/www/html/www/tmp/nation.png'); // сохранение флага
					preg_match('@<div class="playercard-club">.*?src="https://futhead.cursecdn.com/(.*?)"@usi', $card[1], $card_club); //+
					copy("https://futhead.cursecdn.com/".$card_club[1], '/var/www/html/www/tmp/club.png'); // сохранение клуба
					preg_match('@<div class="playercard-picture">.*?src="https://futhead.cursecdn.com/(.*?)"@usi', $card[1], $card_picture); //+
					copy("https://futhead.cursecdn.com/".$card_picture[1], '/var/www/html/www/tmp/picture.png'); // сохранение фотографии игрока
					preg_match('/<div class="playercard-name">(.*?)<\/div>/usi', $card[1], $card_name); //+
					$pn = $card_name[1]; $pn = trim($pn);
					preg_match('@playercard-rating.*?>\s*(.*?)\s*<@usi', $card8, $card_rating); //+
					$pr = $card_rating[1];
					preg_match('@playercard-position.*?>\s*(.*?)\s*<@usi', $card8, $card_position); //+

						$s1 = $calculator[1][0];
						$s2 = $calculator[1][1];
						$s3 = $calculator[1][2];
						$s4 = $calculator[1][3];
						$s5 = $calculator[1][4];
						$s6 = $calculator[1][5];

					$left='346';
					$randval = rand();

					echo '<br><br><br><br>';
					echo "<div class='fut_fon'><img src='/tmp/1.jpg?n=".$randval."'/></div>";
					echo "<div class='fut_down'><img src='/img/card/19/down.png'/></div>";
					echo "<div class='fut_top'><img src='/img/card/19/top_news.png'/></div>";

					preg_match( '/href="\/players\?na=\d*"\srel="nofollow"\stitle="(.*?)"/usi' , $sofifa_eng['content'] , $from ); //+
					preg_match_all( '/a href=\"\/team\/.*?\">([^<]+)/sui' , $sofifa_rus['content'] , $team ); //+
					preg_match( '/<label>Контракт<\/label>.*?(\d*)</sui' , $sofifa_rus['content'] , $contract ); //+
					preg_match( '/<div class="meta">(.*?)Возр/usi' , $sofifa_rus['content'] , $position_temporary ); //+
					preg_match_all( '/<span class="pos pos\d+">(.*?)</usi' , $position_temporary[1], $pos ); //+
					preg_match( '/<div class="meta">(.*?)Age/usi' , $sofifa_eng['content'] , $position_temporary_eng ); //+
					preg_match_all( '/<span class="pos pos\d+">(.*?)</usi' , $position_temporary_eng[1], $pos_eng ); //+
					
					$pp = $pos_eng[1][0];

					$command = ["Portugal", "Russia", "Mexico", "Egypt", "Venezuela", "Austria", "Australia", "Argentina", "Norway", "Uruguay"];

						if( in_array($team[1][0], $command) ) {
							$teamp = str_replace(" ","",$team[1][1]);
							$team_new = $team[1][1];
						} else {
							$team_new = $team[1][0];
							$teamp = str_replace(" ","",$team[1][0]);
						}

					//$teamp = str_replace(" ","",$team[1][0]);
					$legp = trim($leg[1]);
					$legp = mb_convert_case($legp, MB_CASE_LOWER, "UTF-8");
					$rf = trim($realface[1]);
					$rf = mb_convert_case($rf, MB_CASE_LOWER, "UTF-8");
					$bt = trim($bodytype[1]);
					//$bt = mb_convert_case($bt, MB_CASE_LOWER, "UTF-8");

					$liga = mysqli_query("SELECT `ligapub`, `real`, `club`  FROM `fut_liga19` WHERE `club`='".$team_new."' ",$dbcnx_s);
					echo "<div class='fut_text'>";
						
					echo "<div>";
					if ($skl < '65') {
						echo "<b>до 64 - БРОНЗА </b>";
					} elseif ($skl > '74') {
						echo "<b>больше 75 - ЗОЛОТО </b>";
					} else {
						echo "<b>от 65 до 74 - СЕРЕБРО </b>";
					}
					echo '<br><br>';
				///////////////////////////////////////////////////////////////////
					
					$news_text = $_POST['news_text'];
					
					$lft = "&#9917; НОВОСТИ ФУТБОЛА &#9917;";
						$message .= $lft." ".PHP_EOL ;
					echo $lft."<br>";
						$message .= " ".PHP_EOL ;
					echo "<br>";
					echo $news_text;
					$news_text = str_replace('<br>', '', $news_text);
						$message .= $news_text." ".PHP_EOL ;
					echo "<br>";
						$message .= " ".PHP_EOL ;
					echo "<br>";
						$message .= "#transferrumors #football_news #football #news #fifa19 #fifa".PHP_EOL ;
					echo "#transferrumors #football_news #football #news #fifa19 #fifa<br><br>";
					echo "</div>";
					
					$name_db = $fio[1];
					
					echo "<form name='curl' method='POST' action='personal.php?t=news&pn=".$pn."&pr=".$skl."&pp=".$pp."&s1=".$s1."&s2=".$s2."&s3=".$s3."&s4=".$s4."&s5=".$s5."&s6=".$s6."&from=".$from_db."&team=".$team_db."&name=".$name_db."' enctype='multipart/form-data'>";
					echo "<input name='message' type='hidden' size='2' value='".$message."'>";
					echo "Карточка ";

						$playerCard = array("gold" => ("Gold"), "gold_nr" => ("Gold non-rare"),"silver" => ("Silver"), "silver_nr" => ("Silver non-rare"), "bronze" => ("Bronze"), "bronze_nr" => ("Bronze non-rare"), "otw" => ("OTW"));

						echo " <select size='1' name='card_own'>";
						foreach ($playerCard as $key=>$row) {
							if ($card_own == $key) {
								echo "<option value='".$key."' selected>".$row."</option>";
							} else {
								echo "<option value='".$key."'>".$row."</option>";
							}
						}
						echo "</select> ";
					echo " <input type=\"submit\" value=\"Отправить\"/>";
					echo "</form>";
					echo "</div>";
						
					
				} else {
					echo "<center><b>Не добавлен файл, или не указана ссылка!</b></center><br>";
				}
			}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////    АНАЛИЗ КЛУБА    ///////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			elseif (isset($_POST['enter2'])) {

				// SOFIFA
				$sofifa = get_web_page('https://sofifa.com/team/'.$_POST['curl2'].'?hl=ru-RU');
				$sofifa_rus = $sofifa['content'];

				preg_match( '/<div class="info">\s*<h1>(.*?)\s\(ID/sui' , $sofifa_rus , $com_name );
				preg_match( '@href="/teams\?na=\d+" rel="nofollow" title="(.*?)">@sui' , $sofifa_rus , $championship );
					$championship_new = mb_substr($championship[1], 0, strlen($championship[1],'UTF-8')-1);

				preg_match( '/бюджет<\/label>\s*(.*?)\s*<\/li>/sui' , $sofifa_rus , $budget );
				$budget_new = str_replace(',','.',$budget[1]);
				preg_match( '/team\saverage\sage<\/label>\s*(.*?)\s*<\/li>/sui' , $sofifa_rus , $average_age );
				preg_match( '/противника<\/label>.*?<a\shref="\/team.*?">(.*?)<\/a>/siu' , $sofifa_rus , $enemy );

				preg_match( '/Общ\.&nbsp;<span class="label p\d+">(\d+)</usi' , $sofifa_rus, $rSq);
				preg_match( '/(\d+)/usi' , $rSq[0], $rSq2);

				$reitSquad = $rSq[1]; // Общий средний рейтинг команды
				preg_match_all( '/<tr class="(starting|sub|res)">(.*?)<\/tr>/siu' , $sofifa_rus , $player );
				echo "<br><br><br><div id='card_body'>";
				echo "<div class='card'>";

				echo "Анализ клуба ".$com_name[1]." от Прядкина Сергея #analizator19@fifa_easport<br><br>
				Добрый день друзья. Сегодня мы проведем анализ состава клуба ".$com_name[1]." из чемпионата ".$championship_new."и.<br>
				Посмотрим и разберем текущий состав, поищем слабые зоны, определимся с основным составом и запасными, и поищем кем можно усилить слабые стороны.";
				echo '<br><br>';

				echo "Трансферный бюджет: ".$budget_new."<br>";
				echo "Стандартная схема: x-x-x<br>";
				echo "Средний возраст команды: ".$average_age[1]."<br>";
				echo "Команда противника: ".$enemy[1]."<br><br>";

				for ($i=0;$i<count($player[0]);$i++){
					preg_match( '/class=".*?"\stitle="(.*?)"/usi', $player[0][$i], $country_pl);
					preg_match( '/href="\/player\/.*?"\stitle=".*?">(.*?)<\/a>/sui', $player[0][$i], $name_pl);
					preg_match_all( '/<span\sclass="label p.*?">(.*?)<\/span>/siu', $player[0][$i], $skill_pl);
					//preg_match( '/data-title="Потенциал">\s*<span\sclass="p.*?">(.*?)<\/span>/', $player[0][$i], $potencial_pl);
					preg_match( '/<div class="col-digit col-ae">(.*?)<\/div>/sui', $player[0][$i], $age_pl);
					preg_match_all( '/pn=.*?"><span\sclass="pos.*?">(.*?)<\/span>/', $player[0][$i], $position_pl);
					preg_match( '/<span\sclass="label p.*?">(.*?)<\/span>/siu', $skill_pl[0][0], $skill_pl1);
					preg_match( '/<span\sclass="label p.*?">(.*?)<\/span>/siu', $skill_pl[0][1], $skill_pl2);

					$array_pl[$i] = array("name_pl" => $name_pl[1],"skill_pl" => $skill_pl1[1], "potencial_pl" => $skill_pl2[1],"age_pl" => $age_pl[1], "country_pl" => $country_pl[1], "position_pl" => $position_pl[1],  "varende_pl" => $varende_pl[1]);
					//var_dump($arenda_pl);
				}

				$position = array('ВРТ','ЛЗ','ЛФЗ','ПЗ','ЛФЗ','ЦЗ','ЛП','ПП','ЦОП','ЦП','ЦАП','ЛФА','ПФА','ЦФД','ФРВ');
				foreach ($position as $row)	{
					$m=0;
					for ($i=0;$i<count($array_pl);$i++){
						if($array_pl[$i]['position_pl'][0] == $row){
							$m++;
						}
					}
					if ($m > 0) {
						$positiontypes = [
						"Атака" => ["ФРВ","ЛФА","ПФА","ПФД","ЛФД","ЦФД"],
						"Полузащита" => ["ЛП","ПП","ЦП","ЦАП","ЦОП"],
						"Защита" => ["ЦЗ","ПЗ","ЛЗ","ЛФЗ","ПФЗ"],
						"Вратари" => ["ГК", "ВРТ"]
						];
						$curpostype = "";
						foreach($positiontypes as $postype=>$postypes) {
							if( in_array($row, $postypes) ) {
									$curpostype = $postype;
									break;
							}
						}
						echo "&#9917; ".$postype." [".$row."]:<br>";
						for ($i=0;$i<count($array_pl);$i++){
							if($array_pl[$i]['position_pl'][0] == $row){

								$age = $array_pl[$i]['age_pl'];
								if ($age < 34){
									if (($age < 30) and ($age > 27)) {
										$potencial = $array_pl[$i]['potencial_pl'];
										$plus = '+';
									}
									elseif (($age < 28) and ($age > 23)) {
										$potencial = $array_pl[$i]['potencial_pl']+2;
										$plus = '+';
									}
									else {
										$potencial = $array_pl[$i]['potencial_pl']+3;
										$plus = '+';
									}
								}
								else { $plus = ''; $potencial = $array_pl[$i]['potencial_pl']; }

								// если потенциал игрока ниже 73
								if ($potencial > $reitSquad-4){
									echo "&#10133; ";
								}
								else { echo "&#10006; "; }

								echo $array_pl[$i]['name_pl']." [".$array_pl[$i]['skill_pl']."][".$potencial."".$plus."] ";

								$age_sklonen = age_sklonen ($age);
								echo $age." ".$age_sklonen;

								echo " | ".$array_pl[$i]['country_pl']." | ";
									for ($z=0;$z<count($array_pl[$i]['position_pl']);$z++) {
										echo $array_pl[$i]['position_pl'][$z]." ";
									}
								echo " - ";
								if ($array_pl[$i]['skill_pl'] > $reitSquad-1) {
									echo "Основной";
								}
								elseif ($array_pl[$i]['skill_pl'] < $reitSquad-5) {
									echo "Резерв";
								}
								else { echo "Запасной"; }

								if (($array_pl[$i]['potencial_pl'] > $reitSquad+5) and ($array_pl[$i]['potencial_pl'] < 88)) {
									echo ", Надежда клуба";
								}
								elseif ($array_pl[$i]['potencial_pl'] > 87) {
									echo ", Надежда клуба, Один из лучших в Мире";
								}
								elseif ($array_pl[$i]['potencial_pl'] < $reitSquad-4) {
									echo ", Сомнительная перспектива";
								}
								if (isset($array_pl[$i]['varende_pl'])){
									echo " [арендован у ]";
								}
								echo "<br>";
							}
						}

						echo "<br>";
						echo "&#128203; Анализ:";
						echo "<br><br>";
						echo "&#128206; Совет:";
						echo "<br><br>";
					}
				}
///////  ВАРЕНДЕ   //////////////////////////////////////////////////////////////////////////
					preg_match('/(:?В аренде).*?<tbody>(.*?)<\/tbody>/siu' , $sofifa_rus , $player_arenda_all );
					preg_match_all('/<tr>(.*?)<\/tr>/siu' , $player_arenda_all[2] , $player_arenda );

					for ($i=0;$i<count($player_arenda[0]);$i++){

					preg_match( '/class=".*?"\stitle="(.*?)"/usi', $player_arenda[0][$i], $country_pl);
					preg_match( '/href="\/player\/.*?"\stitle=".*?">(.*?)<\/a>/isu', $player_arenda[0][$i], $name_pl);
					preg_match_all( '/<span\sclass="label p.*?">(.*?)<\/span>/siu', $player_arenda[0][$i], $skill_pl);
					preg_match( '/<div class="col-digit col-ae">(.*?)<\/div>/sui', $player_arenda[0][$i], $age_pl);
					preg_match_all( '/pn=.*?"><span\sclass="pos.*?">(.*?)<\/span>/isu', $player_arenda[0][$i], $position_pl);
					preg_match( '/<span\sclass="label p.*?">(.*?)<\/span>/siu', $skill_pl[0][0], $skill_pl1);
					preg_match( '/<span\sclass="label p.*?">(.*?)<\/span>/siu', $skill_pl[0][1], $skill_pl2);
					preg_match_all( '@href="/team/\d+/.*?/">(.*?)</a@usi', $player_arenda[0][$i], $arenda_pl);

					$array_pl_arr[$i] = array("name_pl" => $name_pl[1], "skill_pl" => $skill_pl1[1], "potencial_pl" => $skill_pl2[1], "age_pl" => $age_pl[1], "country_pl" => $country_pl[1], "position_pl" => $position_pl[1], "arenda_pl" => $arenda_pl[1]);

					//var_dump($array_pl[$i]);
				}
				$m=0;
				for ($i=0;$i<count($array_pl_arr);$i++){
					if(isset($array_pl_arr[$i])){
						$m++;
					}
				}
				if ($m > 0) {
					echo "&#9917; В АРЕНДЕ:<br>";
					for ($i=0;$i<count($array_pl_arr);$i++){

						$age = $array_pl_arr[$i]['age_pl'];
							echo "+ ".$array_pl_arr[$i]['name_pl']." [".$array_pl_arr[$i]['skill_pl']."][".$array_pl_arr[$i]['potencial_pl']."] ";

							$age_sklonen = age_sklonen ($age);
							echo $age." ".$age_sklonen;

							echo " | ".$array_pl_arr[$i]['country_pl']." | ";
								for ($z=0;$z<count($array_pl_arr[$i]['position_pl']);$z++) {
									echo $array_pl_arr[$i]['position_pl'][$z]." ";
								}
							echo " - [арендован ".$array_pl_arr[$i]['arenda_pl'][0]."]";
							echo "<br>";

					}
					echo "<br>";
					echo "&#128203; Анализ:";
					echo "<br><br>";
					echo "&#128206; Совет:";
					echo "<br><br>";
					echo "<br><br>";
					echo "</div></div>";
					echo "<br><br>";
					echo "<br><br>";
					echo "<br><br>";
				}
			}
///////////////////////////////////////////////////////////////////////////////////////
/////// 3. КАРТОЧКИ ИГРОКОВ ///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
			elseif (isset($_POST['enter3'])) {
				if ((!empty($_FILES["filename"]["name"])) and (!empty($_POST['curl3-1']))) {

					if($_FILES["filename"]["size"] > 1024*3*1024) {
						echo ("Размер файла превышает три мегабайта");
						exit;
					}
				   // Проверяем загружен ли файл
					if(is_uploaded_file($_FILES["filename"]["tmp_name"])) {
						move_uploaded_file($_FILES["filename"]["tmp_name"], "tmp/1.jpg");
					} else {
						echo("Ошибка загрузки файла");
					}

					// FUTHEAD
					$fudhead = get_web_page('https://www.futhead.com/19/players/'.$_POST['curl3-1']);
					$fudhead_text = $fudhead['content'];
					// КАРТОЧКА
					preg_match( '/<div class="futhead-group player-info".*?<div class="player-cards">(.*?)<\/div>\s*<\/div>/sui' , $fudhead_text, $card );
					preg_match( '@img/19/players/(\d+).png@sui' , $card[1], $player_id);

					// SOFIFA
					$sofifa_eng = get_web_page('https://sofifa.com/player/'.$player_id[1]);
					$sofifa_rus = get_web_page('https://sofifa.com/player/'.$player_id[1].'?hl=ru-RU&units=mks');

					////// АНГЛИЙСКАЯ ВЕРСИЯ САЙТА //////

					preg_match( '/<div class="meta">(.*?)\s\<a/sui' , $sofifa_rus['content'] , $fio ); // ФАМИЛИЯ ИМЯ ИГРОКА

					if (stripos($fio[1], "'")) {
						$fio[1] = str_replace("'", "", $fio[1]);
					}

					preg_match( '/Overall Rating&nbsp;<span class="label p p\d+">(.*?)</siu' , $sofifa_eng['content'] , $skill_reiting ); // РЕЙТИНГ
					preg_match( '/Potential&nbsp;<span class="label p p\d+">(.*?)</siu' , $sofifa_eng['content'] , $skill_potential ); // ПОТЕНЦИАЛ
					preg_match( '/span>\s??Age\s(\d+)/sui' , $sofifa_eng['content'] , $age ); // ВОЗРАСТ

					// РАСЧЕТ ПОТЕНЦИАЛА
					$skl = $skill_reiting[1];
					$pt = $skill_potential[1];

					if ($pt > 85) {
						if ($age[1] < 18) {
							$pt = $pt+3;
						} else {
							$pt = $pt+2;
						}
					} else {
						if (($age[1] < 20) and ($pt > 82)) { $pt = $pt+2; }
						elseif (($age[1] < 19) and ($pt < 82)) { $pt = $pt+3; }
						else { $pt = $pt+2; }
					}
					$rost = $pt - $skl;

					preg_match_all( '/var point.*?=\s(\d+);/' , $sofifa_rus['content'], $calculator );
					//preg_match( '/<div class="meta">(.*?)Age/usi' , $sofifa_eng['content'] , $position_t ); //+
					preg_match( '/<span class="pos pos\d+">(.*?)</usi' , $sofifa_eng['content'], $pos_t ); // ПОЗИЦИЯ

					// РАЗБИРАЕМ КАРТОЧКУ FUDHEAD НА СКИЛЫ
					preg_match_all( '/playercard-attr.?">(\d+)\s<span/' , $card[1] , $playercard_attr );
					$calculator10 = "playercard-attr1\">".$calculator[1][0]." <span";
					$calculator11 = "playercard-attr2\">".$calculator[1][1]." <span";
					$calculator12 = "playercard-attr3\">".$calculator[1][2]." <span";
					$calculator13 = "playercard-attr4\">".$calculator[1][3]." <span";
					$calculator14 = "playercard-attr5\">".$calculator[1][4]." <span";
					$calculator15 = "playercard-attr6\">".$calculator[1][5]." <span";
					$position_new = "class='playercard-position'>".$pos_t[1]."</div>";
					$skill_reiting_new = "class='playercard-rating'>".$skill_reiting[1]."</div>";
					$basic = "<div class='playercard-chem not-draggable'><img class='chem-icon' src='https://www.futhead.com/static/img/chemistry/bas.png'><span id='chem' class='chem-name input-toggle'>BASIC</span><span id='ruler' class='hidee'></span></div>";

					//preg_match('/playercard-attr4">(\d+)\s<span/', $card[1], $card4);
					$card0 = preg_replace('/playercard-attr1">(\d+)\s<span/', $calculator10, $card[1]);
					$card1 = preg_replace('/playercard-attr2">(\d+)\s<span/', $calculator11, $card0);
					$card2 = preg_replace('/playercard-attr3">(\d+)\s<span/', $calculator12, $card1);
					$card3 = preg_replace('/playercard-attr4">(\d+)\s<span/', $calculator13, $card2);
					$card4 = preg_replace('/playercard-attr5">(\d+)\s<span/', $calculator14, $card3);
					$card5 = preg_replace('/playercard-attr6">(\d+)\s<span/', $calculator15, $card4);
					$card6 = preg_replace('/class="playercard-position">(.*?)<\/div>/', $position_new, $card5);
					$card7 = preg_replace('/class="playercard-rating">(.*?)<\/div>/', $skill_reiting_new, $card6);
					$card8 = preg_replace('/<div class="playercard-workrates playercard-thin">.*?<\/div>/', $basic, $card7);

					preg_match('/playercard\s+fut19 card-large\s*(.*?)\stext/usi', $card8, $card_nif);

					if (($card_nif[1] == 'nif  gold') or ($card_nif[1] == 'transfer gold') or ($card_nif[1] == 'transfer  gold')){
						$card_own = 'gold';
					} elseif (($card_nif[1] == 'nif  non-rare gold') or ($card_nif[1] == 'transfer gold non-rare') or ($card_nif[1] == 'transfer  non-rare gold')) {
						$card_own = 'gold_nr';
					} elseif (($card_nif[1] == 'nif  silver') or ($card_nif[1] == 'transfer  silver')) {
						$card_own = 'silver';
					} elseif (($card_nif[1] == 'nif  non-rare silver') or ($card_nif[1] == 'transfer silver non-rare')) {
						$card_own = 'silver_nr';
					} elseif (($card_nif[1] == 'nif  bronze') or ($card_nif[1] == 'transfer bronze')) {
						$card_own = 'bronze';
					} else { $card_own = 'bronze_nr'; }

					preg_match('@<div class="playercard-nation">.*?src="https://futhead.cursecdn.com/(.*?)"@usi', $card[1], $card_country); //+
					copy("https://futhead.cursecdn.com/".$card_country[1], 'tmp/nation.png'); // сохранение флага
					preg_match('@<div class="playercard-club">.*?src="https://futhead.cursecdn.com/(.*?)"@usi', $card[1], $card_club); //+
					copy("https://futhead.cursecdn.com/".$card_club[1], 'tmp/club.png'); // сохранение клуба
					preg_match('@<div class="playercard-picture">.*?src="https://futhead.cursecdn.com/(.*?)"@usi', $card[1], $card_picture); //+
					copy("https://futhead.cursecdn.com/".$card_picture[1], 'tmp/picture.png'); // сохранение фотографии игрока
					preg_match('/<div class="playercard-name">(.*?)<\/div>/usi', $card[1], $card_name); //+
					$pn = $card_name[1]; $pn = trim($pn);
					preg_match('@playercard-rating.*?>\s*(.*?)\s*<@usi', $card8, $card_rating); //+
					$pr = $card_rating[1];
					preg_match('@playercard-position.*?>\s*(.*?)\s*<@usi', $card8, $card_position); //+

						$s1 = $calculator[1][0];
						$s2 = $calculator[1][1];
						$s3 = $calculator[1][2];
						$s4 = $calculator[1][3];
						$s5 = $calculator[1][4];
						$s6 = $calculator[1][5];

					$left='346';

					function make_seed() {
						list($usec, $sec) = explode(' ', microtime());
						return (float) $sec + ((float) $usec * 100000);
					}
					srand(make_seed());
					$randval = rand();

					echo '<br><br><br><br>';
					echo "<div class='fut_fon'><img src='/tmp/1.jpg?n=".$randval."'/></div>";
					echo "<div class='fut_down'><img src='/img/card/19/down.png'/></div>";
					//echo "<div class='fut_card' style='line-height: 1.6;'>".$card8."</div></div></div>";
					echo "<div class='fut_top'><img src='/img/card/19/top.png'/></div>";
					echo "<div class='fut_rost'>+".$rost."</div>";
					//var_dump($card_nif);

					preg_match( '/href="\/players\?na=\d*"\srel="nofollow"\stitle="(.*?)"/usi' , $sofifa_eng['content'] , $from ); //+
					preg_match_all( '/a href=\"\/team\/.*?\">([^<]+)/sui' , $sofifa_rus['content'] , $team ); //+
					preg_match( '/<label>Контракт<\/label>.*?(\d*)</sui' , $sofifa_rus['content'] , $contract ); //+
					preg_match( '/<div class="meta">(.*?)Возр/usi' , $sofifa_rus['content'] , $position_temporary ); //+
					preg_match_all( '/<span class="pos pos\d+">(.*?)</usi' , $position_temporary[1], $pos ); //+
					preg_match( '/<div class="meta">(.*?)Age/usi' , $sofifa_eng['content'] , $position_temporary_eng ); //+
					preg_match_all( '/<span class="pos pos\d+">(.*?)</usi' , $position_temporary_eng[1], $pos_eng ); //+

					preg_match( '/Возр.*?\(([^\)]+)/sui' , $sofifa_rus['content'] , $date ); //+
					preg_match( '/Возр.*?\)\s([^cm]+)/sui' , $sofifa_rus['content'] , $growth ); //+
					preg_match( '/Возр.*?cm\s([^kg]+)/sui' , $sofifa_rus['content'] , $weight ); //+
					// <label>Ведущая нога</label> Левая </li>
					preg_match( '/Ведущая нога<\/label>(.*?)<\/li>/sui' , $sofifa_rus['content'] , $leg ); //+
					// <label>Work rate</label><span>Средний / Средний</span>
					preg_match( '/Эффективность<\/label>.*?<span>([^<]+)/sui' , $sofifa_rus['content'] , $workrate ); //+
					// <label>Телосложение</label><span>Нормастеник</span>
					preg_match( '/ожение<\/label>.*?<span>([^<]+)/sui' , $sofifa_rus['content'] , $bodytype ); //+
					// <label>Real face</label><span>Нет</span>
					preg_match( '/Face<\/label>.*?<span>([^<]+)/sui' , $sofifa_rus['content'] , $realface ); //+

					preg_match( '/Weak Foot<\/label>(\d+)/sui' , $sofifa_eng['content'] , $weakfoot ); //+
					preg_match( '/Skill Moves<\/label>(\d+)/sui' , $sofifa_eng['content'] , $skillmoves ); //+

					preg_match( '@Таланты</h5>.*?<ul class="pl">(.*?)</ul@siu' , $sofifa_rus['content'], $talent ); //+
					preg_match_all( '/<li><span.*?>([а-я_ -]*)<\/span>/sui' , $talent[1] , $talent2 ); //+
					preg_match_all( '/class="label">#([а-я_ -.]*)</siu' , $sofifa_rus['content'] , $specialization ); //+
					//preg_match_all( '/<li>([а-я_ -]*)<\/li>/ui' , $specialization[0][0] , $specialization2 ); //+
					preg_match( '/Value.*?<span>€[0-9.]+(M|K)</sui' , $sofifa_eng['content'] , $value_m); //+
					if ($value_m[1] == "M"){
						preg_match( '/Value.*?<span>€([^M]+)/sui' , $sofifa_eng['content'] , $value );

						if (preg_match("/[\.]/", $value[1])) $value_pub = $value[1]."00.000€";
						else { $value_pub = $value[1].".000.000€"; }
					} else {
						preg_match('/.*?(\d+)K<\/span>/s', $sofifa_eng['content'] , $value);
						$value_pub = $value[1].".000€";
					}
					preg_match( '/Wage.*?<span>€([^K]+)/s' , $sofifa_eng['content'] , $wage ); //+

					//// ОТСТУПНЫЕ ПО КОНТРАКТУ
					preg_match( '/clause.*?<span>€[0-9.]+(M|K)</sui' , $sofifa_eng['content'] , $value_m); //+
					if (strlen($value_m[1]) > 0) {
						if ($value_m[1] == "M") {
							preg_match( '/clause.*?<span>€([^M]+)/sui' , $value_m[0] , $valueClause );

							if (preg_match("/[\.]/", $valueClause[1])) $ReleaseClause = $valueClause[1]."00.000€";
							else { $ReleaseClause = $valueClause[1].".000.000€"; }
						} else {
							preg_match('/.*?(\d+)K</sui', $value_m[0] , $valueClause);
							$ReleaseClause = $valueClause[1].".000€";
						}
					} else { $ReleaseClause = "не прописана"; }

					// НАПАДЕНИЕ
					preg_match_all( '/(?:Нападение<\/h5>)(?:.*?)<ul class="pl">(.*?)<\/ul>/si' , $sofifa_rus['content'] , $attacking); //+
					preg_match_all( '/">(.*?)<\/span>\s<span class=".*?">(.*?)</siu' , $attacking[1][0] , $attacking2 );
					// НАВЫК
					preg_match_all( '/(?:Навык<\/h5>)(?:.*?)<ul class="pl">(.*?)<\/ul>/si' , $sofifa_rus['content'] , $skill); //+
					preg_match_all( '/">(.*?)<\/span>\s<span class=".*?">(.*?)</siu' , $skill[1][0] , $skill2 );
					// ПЕРЕМЕЩЕНИЕ
					preg_match_all( '/(?:Перемещение<\/h5>)(?:.*?)<ul class="pl">(.*?)<\/ul>/si' , $sofifa_rus['content'] , $movement); //+
					preg_match_all( '/">(.*?)<\/span>\s<span class=".*?">(.*?)</siu' , $movement[1][0] , $movement2 );
					// МОЩНОСТЬ
					preg_match_all( '/(?:Мощность<\/h5>)(?:.*?)<ul class="pl">(.*?)<\/ul>/si' , $sofifa_rus['content'] , $power); //+
					preg_match_all( '/">(.*?)<\/span>\s<span class=".*?">(.*?)</siu' , $power[1][0] , $power2 );
					// НАСТРОЙ
					preg_match_all( '/(?:Настрой<\/h5>)(?:.*?)<ul class="pl">(.*?)<\/ul>/si' , $sofifa_rus['content'] , $mentality); //+
					preg_match_all( '/">(.*?)<\/span>\s<span class=".*?">(.*?)</siu' , $mentality[1][0] , $mentality2 );
					// ЗАЩИТА
					preg_match_all( '/(?:Защита<\/h5>)(?:.*?)<ul class="pl">(.*?)<\/ul>/si' , $sofifa_rus['content'] , $defending); //+
					preg_match_all( '/">(.*?)<\/span>\s<span class=".*?">(.*?)</siu' , $defending[1][0] , $defending2 );
					// ВРАТАРИ
					preg_match_all( '/(?:Вратари<\/h5>)(?:.*?)<ul class="pl">(.*?)<\/ul>/si' , $sofifa_rus['content'] , $goalkeeping); //+
					preg_match_all( '/">(.*?)<\/span>\s(.*?)</siu' , $goalkeeping[1][0] , $goalkeeping2 );

					$command = ["Portugal", "Russia", "Mexico", "Egypt", "Venezuela", "Austria", "Australia", "Argentina", "Norway", "Uruguay"];

						if( in_array($team[1][0], $command) ) {
							$teamp = str_replace(" ","",$team[1][1]);
							$team_new = $team[1][1];
						} else {
							$team_new = $team[1][0];
							$teamp = str_replace(" ","",$team[1][0]);
						}

					//$teamp = str_replace(" ","",$team[1][0]);
					$legp = trim($leg[1]);
					$legp = mb_convert_case($legp, MB_CASE_LOWER, "UTF-8");
					$rf = trim($realface[1]);
					$rf = mb_convert_case($rf, MB_CASE_LOWER, "UTF-8");
					$bt = trim($bodytype[1]);
					//$bt = mb_convert_case($bt, MB_CASE_LOWER, "UTF-8");

					$liga = mysqli_query($dbcnx, "SELECT `ligapub`, `real`, `club`  FROM `fut_liga19` WHERE `club`='".$team_new."' ");
						//var_dump($liga);
					echo "<div class='fut_text'>";

				//	echo $from[2]." - from[2]<br>";
				//	echo $teamp." - teamp<br>";
				//	echo $team_new." - team_new<br>";

					while ($row = mysqli_fetch_assoc($liga)) {
						if ($from[1] == 'United States') { $fromis = 'USA'; }
						elseif ($from[1] == 'Korea Republic') { $fromis = 'Korea'; }
						elseif ($from[1] == 'Korea DPR') { $fromis = 'DPRKorea'; }
						elseif ($from[1] == 'Czech Republic') { $fromis = 'Czech'; }
						elseif ($from[1] == 'DR Congo') { $fromis = 'DRCongo'; }
						elseif ($from[1] == 'Saudi Arabia') { $fromis = 'SaudiArabia'; }
						elseif ($from[1] == 'Ivory Coast') { $fromis = 'IvoryCoast'; }
						elseif ($from[1] == 'Dominican Republic') { $fromis = 'Dominican'; }
						elseif ($from[1] == 'New Zealand') { $fromis = 'NewZealand'; }
						elseif ($from[1] == 'Burkina Faso') { $fromis = 'BurkinaFaso'; }
						elseif ($from[1] == 'FYR Macedonia') { $fromis = 'Macedonia'; }
						elseif ($from[1] == 'Republic of Ireland') { $fromis = 'Ireland'; }
						else { $fromis = $from[1]; }
						if (strlen($row['real']) > 0) {
							$liga_from_team = "#".$row['ligapub']." | #".$fromis." | #".$row['real'];
							$liga_db = $row['ligapub'];
							$from_db = $from[1];
							$team_db = $row['real'];
						} else {
							$liga_from_team ="#".$row['ligapub']." | #".$fromis." | #".$teamp;

							$liga_db = $row['ligapub'];
							$from_db = $fromis;
							$team_db = $teamp;
						}
					}
					echo "<div>";

					$name_db = $fio[1];
					$selectPlayerName = mysqli_query($dbcnx, "SELECT * FROM `vk_fifa_easport_promising_players` WHERE `name`='".$name_db."'");
					if (!empty(mysqli_fetch_assoc($selectPlayerName))) {
						echo "<b>!!! Игрок ".$name_db." уже есть в БД!!!</b><br><br>";
					}

						if ($skl < '65') {
							echo "<b>до 64 - БРОНЗА </b>";
						} elseif ($skl > '74') {
							echo "<b>больше 75 - ЗОЛОТО </b>";
						} else {
							echo "<b>от 65 до 74 - СЕРЕБРО </b>";
						}
					echo '<br><br>';

					$lft = "&#9917; ".$liga_from_team." &#9917;";
						$message .= $lft." ".PHP_EOL ;
					echo $lft."<br>";
						$message .= " ".PHP_EOL ;
					echo "<br>";
						$message .= "✖ Имя: ".$fio[1]." ".PHP_EOL ;
					echo "✖ Имя: ".$fio[1]." <br>";
						$message .= "✖ Скилл: ".$skl." ".PHP_EOL ;
					echo "✖ Скилл: ".$skl." <br>";
					//echo "✖ PR: ".$pr." <br>";
						$message .= "‼ Потенциал: ".$pt." [ +".$rost." ]".PHP_EOL ;
					echo "‼ Потенциал: ".$pt." [ +".$rost." ] <br>";

					$positiontypes = [
					"Attackers" => ["ФРВ","ЛФА","ПФА","ПФД","ЛФД","ЦФД"],
					"Midfielders" => ["ЛП","ПП","ЦП","ЦАП","ЦОП"],
					"Defenders" => ["ЦЗ","ПЗ","ЛЗ","ЛФЗ","ПФЗ"],
					"Goalkeepers" => ["ГК", "ВРТ"]
					];

					$curpostype = "";
					foreach($positiontypes as $postype=>$postypes) {
						if( in_array($pos[1][0], $postypes) ) {
							$curpostype = "#".$postype;
							break;
						}
					}
					$pp = $pos_eng[1][0];
					$position_role = "✖ Позиция: ".implode(", ",$pos[1])." | ".$curpostype;
						$message .= $position_role.PHP_EOL ;
					echo $position_role." <br>";
				/////  НАЗНАЧЕНИЕ АЛЬБОМОВ ДЛЯ ФОТОГРАФИЙ В ВК
					if ($postype == 'Attackers') { $album = 256991685; }
					elseif ($postype == 'Midfielders') { $album = 256991690; }
					elseif ($postype == 'Defenders') { $album = 256991693; }
					else { $album = 256991698; }
				///////////////////////////////////////////////////////////////////

						$message .= "✖ Цена/зарплата: ".$value_pub." / ".$wage[1].".000€".PHP_EOL ;
					echo "✖ Цена/зарплата: ".$value_pub." / ".$wage[1].".000€ <br>";

					if (!empty($contract[1])) {
						$message .= "✖ Контракт до: ".$contract[1]."г".PHP_EOL ;
					echo "✖ Контракт до: ".$contract[1]."г<br>";
					}

					preg_match( '@Loaned From</label>.*?>(.*?)<@sui' , $sofifa_eng['content'] , $loan); //+
					if (!empty($loan[1])) {
							$message .= "✖ Арендован у клуба: ".$loan[1]."".PHP_EOL ;
						echo "✖ Арендован у клуба: ".$loan[1]."<br>";
					}

						$message .= "✖ Сумма отступных: ".$ReleaseClause."".PHP_EOL ;
					echo "✖ Сумма отступных: ".$ReleaseClause."<br>";
						$message .= "✖ Возраст: ".$age[1]." ( ".$date[1]." )".PHP_EOL ;
					echo "✖ Возраст: ".$age[1]." ( ".$date[1]." ) <br>";
						$message .= "✖ Рост/вес: ".$growth[1]."см / ".$weight[1]."кг".PHP_EOL ;
					echo "✖ Рост/вес: ".$growth[1]."см / ".$weight[1]."кг <br>";
						$message .= "✖ Ведущая нога: ".$legp.PHP_EOL ;
					echo "✖ Ведущая нога: ".$legp." <br>";

					if ($weakfoot[1] == '1') {	$weaklegstars = "★"; }
					elseif ($weakfoot[1] == '2') {	$weaklegstars = "★★"; }
					elseif ($weakfoot[1] == '3') {	$weaklegstars = "★★★"; }
					elseif ($weakfoot[1] == '4') {	$weaklegstars = "★★★★"; }
					elseif ($weakfoot[1] == '5') {	$weaklegstars = "★★★★★"; }
						$message .= "✖ Слабая нога: ".$weaklegstars.PHP_EOL ;
					echo "✖ Слабая нога: ".$weaklegstars." <br>";

					if ($skillmoves[1] == '1') {	$weaklegstars = "★"; }
					elseif ($skillmoves[1] == '2') {	$weaklegstars = "★★"; }
					elseif ($skillmoves[1] == '3') {	$weaklegstars = "★★★"; }
					elseif ($skillmoves[1] == '4') {	$weaklegstars = "★★★★"; }
					elseif ($skillmoves[1] == '5') {	$weaklegstars = "★★★★★"; }
						$message .= "✖ Особые приемы: ".$weaklegstars.PHP_EOL ;
					echo "✖ Особые приемы: ".$weaklegstars." <br>";

						$message .= "✖ Воркрейты: ".$workrate[1].PHP_EOL ;
					echo "✖ Воркрейты: ".$workrate[1]." <br>";
						$message .= "✖ Телосложение: ".$bt.PHP_EOL ;
					echo "✖ Телосложение: ".$bt." <br>";
						$message .= "✖ Реальное лицо: ".$rf.PHP_EOL ;
					echo "✖ Реальное лицо: ".$rf." <br>";
						if (count($talent2[1]) > 0) { $talentConclusion = implode(", ", $talent2[1]); } else { $talentConclusion = "-"; }
						$message .= "✖ Таланты: ".$talentConclusion.PHP_EOL ;
					echo "✖ Таланты: ".$talentConclusion." <br>";
						if (count($specialization[1]) > 0) { $specializationConclusion = implode(", ", $specialization[1]); } else { $specializationConclusion = "-"; }
						$message .= "✖ Специализация: ".$specializationConclusion.PHP_EOL ;
					echo "✖ Специализация: ".$specializationConclusion." <br>";

					mysqli_query($dbcnx, "TRUNCATE TABLE  `skill`");

					for ($i=0;$i<5;$i++){
						$log = "INSERT INTO `skill`(`id`, `skill`, `name`) VALUES ('','".$attacking2[1][$i]."','".$attacking2[2][$i]."')";
						mysqli_query($dbcnx, $log);
					}
					for ($i=0;$i<5;$i++){
						$log = "INSERT INTO `skill`(`id`, `skill`, `name`) VALUES ('','".$skill2[1][$i]."','".$skill2[2][$i]."')";
						mysqli_query($dbcnx, $log);
					}
					for ($i=0;$i<5;$i++){
						$log = "INSERT INTO `skill`(`id`, `skill`, `name`) VALUES ('','".$movement2[1][$i]."','".$movement2[2][$i]."')";
						mysqli_query($dbcnx, $log);
					}
					for ($i=0;$i<5;$i++){
						$log = "INSERT INTO `skill`(`id`, `skill`, `name`) VALUES ('','".$power2[1][$i]."','".$power2[2][$i]."')";
						mysqli_query($dbcnx, $log);
					}
					for ($i=0;$i<5;$i++){
						$log = "INSERT INTO `skill`(`id`, `skill`, `name`) VALUES ('','".$mentality2[1][$i]."','".$mentality2[2][$i]."')";
						mysqli_query($dbcnx, $log);
					}
					for ($i=0;$i<3;$i++){
						$log = "INSERT INTO `skill`(`id`, `skill`, `name`) VALUES ('','".$defending2[1][$i]."','".$defending2[2][$i]."')";
						mysqli_query($dbcnx, $log);
					}
					for ($i=0;$i<5;$i++){
						$log = "INSERT INTO `skill`(`id`, `skill`, `name`) VALUES ('','".$goalkeeping2[1][$i]."','".$goalkeeping2[2][$i]."')";
						mysqli_query($dbcnx, $log);
					}

					$top5 = mysqli_query($dbcnx, "SELECT `skill`, `name` FROM `skill` WHERE `skill` IS NOT NULL ORDER BY `skill` DESC LIMIT 5");
						$message .= " ".PHP_EOL ;
					echo "<br>";
						$message .= "&#9889; ТОП5 навыков:".PHP_EOL ;
					echo "&#9889; ТОП5 навыков: <br>";
					while ($row = mysqli_fetch_assoc($top5)) {
						$message .= "✖ ".$row['skill']." - ".$row['name'].PHP_EOL ;
						echo "✖ ".$row['skill']." - ".$row['name']." <br>";
					}
						$message .= " ".PHP_EOL ;
					echo "<br>";
						$message .= "#promising_players #fifa19 #fifa #перспективные_футболисты".PHP_EOL ;
					echo "#promising_players #fifa19 #fifa #перспективные_футболисты <br><br>";
					echo "</div>";

					echo "<form name='curl' method='POST' action='personal.php?pn=".$pn."&pr=".$skl."&pp=".$pp."&s1=".$s1."&s2=".$s2."&s3=".$s3."&s4=".$s4."&s5=".$s5."&s6=".$s6."&rost=".$rost."&album=".$album."&liga=".$liga_db."&from=".$from_db."&team=".$team_db."&name=".$name_db."' enctype='multipart/form-data'>";
					echo "<input name='message' type='hidden' size='2' value='".$message."'>";
					echo "Карточка ";

						$playerCard = array("gold" => ("Gold"), "gold_nr" => ("Gold non-rare"),"silver" => ("Silver"), "silver_nr" => ("Silver non-rare"), "bronze" => ("Bronze"), "bronze_nr" => ("Bronze non-rare"));

						echo " <select size='1' name='card_own'>";
						foreach ($playerCard as $key=>$row) {
							if ($card_own == $key) {
								echo "<option value='".$key."' selected>".$row."</option>";
							} else {
								echo "<option value='".$key."'>".$row."</option>";
							}
						}
						echo "</select> ";
					echo " <input type=\"submit\" value=\"Отправить\"/>";
					echo "</form>";
					echo "</div>";
				} else {
					echo "<center><b>Не добавлен файл, или не указана ссылка!</b></center><br>";
				}
			}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////// EVOLUTION  ///////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			elseif (isset($_POST['enter4'])) {

				function make_seed()
				{
					list($usec, $sec) = explode(' ', microtime());
					return (float) $sec + ((float) $usec * 100000);
				}
				srand(make_seed());
				$randval = rand();

				$proxy_ip = '192.168.93.29';
				$proxy_port = '3129';

				$fud_old = $_POST['curl4-1'];
				$fudhead = $_POST['curl4-2'];
				//$sofifa = $_POST['curl4-3'];

				// OLD FUTHEAD 10/players/909/cristiano-ronaldo/
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://www.futhead.com/'.$fud_old);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, '1');
				curl_setopt($ch, CURLOPT_TIMEOUT_MS, 10500);
				$fudhead_old = curl_exec($ch);
				curl_close($ch);

				preg_match( '@(\d+)/@usi' , $fudhead_old , $number_fifa_old );
				$number_fifa_old[1]; // номер версии FIFA OLD

				// FUTHEAD17
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://www.futhead.com/17/players/'.$fudhead);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, '1');
				curl_setopt($ch, CURLOPT_TIMEOUT_MS, 10500);
				$fudhead_text = curl_exec($ch);
				curl_close($ch);

				preg_match( '/<div\sclass="col-lg-3 col-sm-6 col-xs-12 padding-t-b-12".*?<div class="player-cards">(.*?)<\/div>\s*<\/div>/sui' , $fudhead_text, $card );

				preg_match( '@img/17/players/(\d+).png@sui' , $card[1], $player_id );
				$sofifa = $player_id[1];

				// SOFIFA17
				$ch3 = curl_init();
				curl_setopt($ch3, CURLOPT_URL, 'http://sofifa.com/player/'.$sofifa);
				curl_setopt($ch3, CURLOPT_HEADER, 0);
				curl_setopt($ch3, CURLOPT_RETURNTRANSFER, '1');
				curl_setopt($ch3, CURLOPT_PROXYPORT, $proxy_port);
				curl_setopt($ch3, CURLOPT_PROXYTYPE, 'HTTP');
				curl_setopt($ch3, CURLOPT_PROXY, $proxy_ip);
			//	curl_setopt($ch, CURLOPT_PROXYUSERPWD, $loginpassw);
				curl_setopt($ch3, CURLOPT_TIMEOUT_MS, 10500);
				$sofifa_eng = curl_exec($ch3);
				curl_close($ch3);

				preg_match( '/<div class="info">\s<h1>(.*?)\s\(/' , $sofifa_eng , $fio ); // имя игрока
				if (stripos($fio[1], "'")) {
					$fio[1] = str_replace("'", "", $fio[1]);
				}

				// ТЕКУЩАЯ ПОЗИЦИЯ ИГРОКА SOFIFA17
				preg_match( '/<div class="meta">(.*?)Age/usi' , $sofifa_eng , $position_t );
				preg_match( '/<span class="pos pos\d+">(.*?)</usi' , $position_t[1], $pos_t );
				// ТЕКУЩИЙ РЕЙТИНГ ИГРОКА SOFIFA17
				preg_match( '/Overall rating <span class="label p\d+">(.*?)<\/span>/siu' , $sofifa_eng , $skill_reiting );

				// КАРТОЧКА FUTHEAD17
				preg_match( '/<div\sclass="col-lg-3 col-sm-6 col-xs-12 padding-t-b-12".*?<div class="player-cards">(.*?)<\/div>\s*<\/div>/sui' , $fudhead_text, $card );
				// СТАРАЯ КАРТОЧКА FUTHEAD
				preg_match( '/<div\sclass="col-lg-3 col-sm-6 col-xs-12 padding-t-b-12".*?<div class="player-cards">(.*?)<\/div>\s*<\/div>/sui' , $fudhead_old, $cardOld );

				// ТЕКУЩИЕ СКИЛЫ ИГРОКА SOFIFA17
				preg_match_all( '/var point.*?=\s(\d+);/' , $sofifa_eng, $calculator );
				// РАЗБИРАЕМ КАРТОЧКУ FUDHEAD17 НА СКИЛЫ
				preg_match_all( '/playercard-attr.?">(\d+)\s<span/' , $card[1] , $playercard_attr );
				$calculator10 = "playercard-attr1\">".$calculator[1][0]." <span";
				$calculator11 = "playercard-attr2\">".$calculator[1][1]." <span";
				$calculator12 = "playercard-attr3\">".$calculator[1][2]." <span";
				$calculator13 = "playercard-attr4\">".$calculator[1][3]." <span";
				$calculator14 = "playercard-attr5\">".$calculator[1][4]." <span";
				$calculator15 = "playercard-attr6\">".$calculator[1][5]." <span";
				$position_new = "class='playercard-position'>".$pos_t[1]."</div>";
				$skill_reiting_new = "class='playercard-rating'>".$skill_reiting[1]."</div>";
				$basic = "<div class='playercard-chem not-draggable'><img class='chem-icon' src='http://www.futhead.com/static/img/chemistry/bas.png'><span id='chem' class='chem-name input-toggle'>BASIC</span><span id='ruler' class='hidee'></span></div>";

				//preg_match('/playercard-attr4">(\d+)\s<span/', $card[1], $card4);
				$card0 = preg_replace('/playercard-attr1">(\d+)\s<span/', $calculator10, $card[1]);
				$card1 = preg_replace('/playercard-attr2">(\d+)\s<span/', $calculator11, $card0);
				$card2 = preg_replace('/playercard-attr3">(\d+)\s<span/', $calculator12, $card1);
				$card3 = preg_replace('/playercard-attr4">(\d+)\s<span/', $calculator13, $card2);
				$card4 = preg_replace('/playercard-attr5">(\d+)\s<span/', $calculator14, $card3);
				$card5 = preg_replace('/playercard-attr6">(\d+)\s<span/', $calculator15, $card4);
				$card6 = preg_replace('/class="playercard-position">(.*?)<\/div>/', $position_new, $card5);
				$card7 = preg_replace('/class="playercard-rating">(.*?)<\/div>/', $skill_reiting_new, $card6);
				$card8 = preg_replace('/<div class="playercard-workrates playercard-thin">.*?<\/div>/', $basic, $card7);

				preg_match( '/Potential\s<span class="label\sp.*?">(\d+)</usi' , $sofifa_eng , $skill_reiting_sf ); //+
				preg_match( '/Age\s(\d+)/sui' , $sofifa_eng , $age ); //+

				// РАСЧЕТ ПОТЕНЦИАЛА
				$skl = $skill_reiting[1];
				$pt = $skill_reiting_sf[1];
				if ($pt > 85) {
					if ($age[1] < 18) {
						$pt = $pt+4;
					}
					else {
						$pt = $pt+2;
					}
				}
				else {
					if (($age[1] < 20) and ($pt > 82)) {
						$pt = $pt+4;
					}
					elseif (($age[1] < 19) and ($pt < 82)) {
						$pt = $pt+5;
					}
					else {
						$pt = $pt+3;
					}
				}

				preg_match('/playercard\s+fut17 card-large\s(.*?)\stext/usi', $card8, $card_nif);

				//var_dump($card_nif);

				if (($card_nif[1] == 'nif  gold') or ($card_nif[1] == 'transfer gold') or ($card_nif[1] == 'transfer  gold')){
					$card_own = 'gold';
				}
				elseif (($card_nif[1] == 'nif  non-rare gold') or ($card_nif[1] == 'transfer gold non-rare') or ($card_nif[1] == 'transfer  non-rare gold')) {
					$card_own = 'gold_nr';
				}
				elseif (($card_nif[1] == 'nif  silver') or ($card_nif[1] == 'transfer  silver')) {
					$card_own = 'silver';
				}
				elseif (($card_nif[1] == 'nif  non-rare silver') or ($card_nif[1] == 'transfer silver non-rare')) {
					$card_own = 'silver_nr';
				}
				elseif (($card_nif[1] == 'nif  bronze') or ($card_nif[1] == 'transfer bronze')) {
					$card_own = 'bronze';
				}
				else { $card_own = 'bronze_nr';
				}
				preg_match('/<div class="playercard-nation"><img src="(.*?)"/usi', $card8, $card_country);
				copy($card_country[1], 'tmp/nation.png');
				preg_match('/<div class="playercard-club"><img src="(.*?)"/usi', $card8, $card_club);
				copy($card_club[1], 'tmp/club.png');
				preg_match('/<div class="playercard-picture"><img src="(.*?)"/usi', $card8, $card_picture);
				copy($card_picture[1], 'tmp/picture.png');
				preg_match('/<div class="playercard-name">(.*?)<\/div>/usi', $card8, $card_name);
				preg_match('@playercard-position.*?>(.*?)<@usi', $card8, $card_position);

				// CARD OLD FUDHEAD
				preg_match('/<div class="playercard-nation"><img src="(.*?)"/usi', $cardOld[1], $card_country_old);
				$card_country_old_new = preg_replace('/img\/(\d+)\//sui', 'img/17/', $card_country_old[1]);
				copy($card_country_old_new, 'tmp/nationOld.png');

				preg_match('/<div class="playercard-club"><img src="(.*?)"/usi', $cardOld[1], $card_club_old);
				$card_club_old_new = preg_replace('/img\/(\d+)\//sui', 'img/17/', $card_club_old[1]);
				copy($card_club_old_new, 'tmp/clubOld.png');

				preg_match('/<div class="playercard-picture"><img src="(.*?)"/usi', $cardOld[1], $card_picture_old);
				copy($card_picture_old[1], 'tmp/pictureOld.png');
				preg_match('@playercard-position.*?>(.*?)<@usi', $cardOld[1], $card_position_old);
				preg_match('@<div class="playercard-rating">(.*?)</div>@usi', $cardOld[1], $skill_reiting_old);

				preg_match( '/playercard-attr1">(\d+)\s<span/usi' , $cardOld[1] , $playercard_attr1 );
				preg_match( '/playercard-attr2">(\d+)\s<span/usi' , $cardOld[1] , $playercard_attr2 );
				preg_match( '/playercard-attr3">(\d+)\s<span/usi' , $cardOld[1] , $playercard_attr3 );
				preg_match( '/playercard-attr4">(\d+)\s<span/usi' , $cardOld[1] , $playercard_attr4 );
				preg_match( '/playercard-attr5">(\d+)\s<span/usi' , $cardOld[1] , $playercard_attr5 );
				preg_match( '/playercard-attr6">(\d+)\s<span/usi' , $cardOld[1] , $playercard_attr6 );

					$s1 = $calculator[1][0];
					$s2 = $calculator[1][1];
					$s3 = $calculator[1][2];
					$s4 = $calculator[1][3];
					$s5 = $calculator[1][4];
					$s6 = $calculator[1][5];

					$t1 = $playercard_attr1[1];
					$t2 = $playercard_attr2[1];
					$t3 = $playercard_attr3[1];
					$t4 = $playercard_attr4[1];
					$t5 = $playercard_attr5[1];
					$t6 = $playercard_attr6[1];

				echo "<br><br><br><br><center>";
				echo "<b>Старая карточка FIFA".$number_fifa_old[1]."</b><br>";
				echo "Позиция игрока ".$card_position_old[1]."<br>";
				echo "Бывший рейтинг игрока ".$skill_reiting_old[1]."<br>";
				echo "PAC ".$t1."<br>";
				echo "SHO ".$t2."<br>";
				echo "PAS ".$t3."<br>";
				echo "DRI ".$t4."<br>";
				echo "DEF ".$t5."<br>";
				echo "PHI ".$t6."<br>";
				echo "<img src='tmp/nationOld.png?n=".$randval."'' /><br>";
				echo "<img src='tmp/clubOld.png?n=".$randval."'' style='width:60px'/><br>";
				echo "<img src='tmp/pictureOld.png?n=".$randval."'' /><br>";
				echo "";
				echo "<br><br><b>Новая карточка FIFA17</b><br>";
				echo "Имя на карте ".$card_name[1]."<br>";
				echo "Позиция игрока ".$card_position[1]."<br>";
				echo "Текущий рейтинг игрока ".$skill_reiting[1]."<br>";
				echo "Потенциал ".$pt."<br>";
				echo "PAC ".$s1."<br>";
				echo "SHO ".$s2."<br>";
				echo "PAS ".$s3."<br>";
				echo "DRI ".$s4."<br>";
				echo "DEF ".$s5."<br>";
				echo "PHI ".$s6."<br>";
				echo "<img src='tmp/nation.png?n=".$randval."'' /><br>";
				echo "<img src='tmp/club.png?n=".$randval."'' style='width:60px'/><br>";
				echo "<img src='tmp/picture.png?n=".$randval."'' /><br>";
				echo "<br>";

				$message .= "#evolution —  ".$fio[1]." &#10084; ".$skill_reiting_old[1]." рубрике и игроку ".PHP_EOL ;
				$message .= " ".PHP_EOL ;
				$message .= "Рубрика, в которой мы будем показывать эволюцию игрока, который неплохо развивается, с его первой FIFA до 17.".PHP_EOL ;
				$message .= " ".PHP_EOL ;
				$message .= "#fifa17@fifa_easport #fifa17".PHP_EOL ;

				echo "<form name='curl' method='POST' action='personal.php?pn=".$card_name[1]."&pr=".$skill_reiting[1]."&pp=".$card_position[1]."&s1=".$s1."&s2=".$s2."&s3=".$s3."&s4=".$s4."&s5=".$s5."&s6=".$s6."&t1=".$t1."&t2=".$t2."&t3=".$t3."&t4=".$t4."&t5=".$t5."&t6=".$t6."&fifaold=".$number_fifa_old[1]."&ppo=".$card_position_old[1]."&pro=".$skill_reiting_old[1]."&pt=".$pt."' enctype='multipart/form-data'>";
				echo "<input name='message' type='hidden' size='2' value='".$message."'>";

				$playerCard = array("gold" => ("Gold"), "gold_nr" => ("Gold non-rare"),"silver" => ("Silver"), "silver_nr" => ("Silver non-rare"), "bronze" => ("Bronze"), "bronze_nr" => ("Bronze non-rare"));

						echo "<select size='1' name='card_own'>";
						foreach ($playerCard as $key=>$row) {
							if ($card_own == $key) {
								echo "<option value='".$key."' selected>".$row."</option>";
							} else {
								echo "<option value='".$key."'>".$row."</option>";
							}
						}
						echo "</select>";
				echo "<input type=\"submit\" value=\"Отправить\"/>";
				echo "</form></center>";

			}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////// + and -  /////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			elseif (isset($_POST['enter5'])) {
				function make_seed()
				{
					list($usec, $sec) = explode(' ', microtime());
					return (float) $sec + ((float) $usec * 100000);
				}
				srand(make_seed());
				$randval = rand();

				$proxy_ip = '192.168.93.29';
				$proxy_port = '3129';

				$fudhead = $_POST['curl5-1'];

				// FUTHEAD18WC
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://www.futhead.com/18/players/'.$fudhead);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, '1');
				curl_setopt($ch, CURLOPT_TIMEOUT_MS, 10500);
				$fudhead_text = curl_exec($ch);
				curl_close($ch);

				preg_match( '/<div class="player-cards">(.*?)<div class="col-xs-6 player-info-price">/usi' , $fudhead_text, $playercard);

				preg_match( '/<div class="playercard-rating">\s(.*?)\s</usi' , $playercard[1], $rating);
				preg_match( '/<div class="playercard-name">\s(.*?)\s</usi' , $playercard[1], $name);
				preg_match( '/<div class="playercard-position">\s(.*?)\s</usi' , $playercard[1], $position);

				preg_match( '/18\/nations\/(.*?).png/usi' , $playercard[1], $nations_img);
				preg_match( '/18\/clubs\/(.*?).png/usi' , $playercard[1], $clubs_img);
				preg_match( '/18\/players\/(.*?).png/usi' , $playercard[1], $players_img);

				preg_match_all( '/"chembot-value">(.*?)</usi' , $playercard[1], $value);
				preg_match_all( '/"playercard-thin">(.*?)</usi' , $playercard[1], $thin);

				preg_match( '/playercard-attr1">.*?"chembot-value">(.*?)</usi' , $playercard[1] , $value1 );
				preg_match( '/playercard-attr2">.*?"chembot-value">(.*?)</usi' , $playercard[1] , $value2 );
				preg_match( '/playercard-attr3">.*?"chembot-value">(.*?)</usi' , $playercard[1] , $value3 );
				preg_match( '/playercard-attr4">.*?"chembot-value">(.*?)</usi' , $playercard[1] , $value4 );
				preg_match( '/playercard-attr5">.*?"chembot-value">(.*?)</usi' , $playercard[1] , $value5 );
				preg_match( '/playercard-attr6">.*?"chembot-value">(.*?)</usi' , $playercard[1] , $value6 );

				preg_match( '/playercard-attr1">.*?"playercard-thin">(.*?)</usi' , $playercard[1] , $thin1 );
				preg_match( '/playercard-attr2">.*?"playercard-thin">(.*?)</usi' , $playercard[1] , $thin2 );
				preg_match( '/playercard-attr3">.*?"playercard-thin">(.*?)</usi' , $playercard[1] , $thin3 );
				preg_match( '/playercard-attr4">.*?"playercard-thin">(.*?)</usi' , $playercard[1] , $thin4 );
				preg_match( '/playercard-attr5">.*?"playercard-thin">(.*?)</usi' , $playercard[1] , $thin5 );
				preg_match( '/playercard-attr6">.*?"playercard-thin">(.*?)</usi' , $playercard[1] , $thin6 );

				echo "<center>";
					echo "<b style='color: #e6d2a6;'>".$rating[1]."</b><br>";
					echo "<b style='color: #e6d2a6;font-family:Dusha;'>".$name[1]."</b><br>";
					echo "<b style='color: #e6d2a6;'>".$position[1]."</b><br>";
					echo "<img src='https://futhead.cursecdn.com/static/img/18/nations/".$nations_img[1].".png'>";
					echo "<img src='https://futhead.cursecdn.com/static/img/18/clubs/".$clubs_img[1].".png'>";
					echo "<img src='https://futhead.cursecdn.com/static/img/18/players/".$players_img[1].".png'>";

					echo "<br>";
					echo "<b style='color: #275490;'>".$thin1[1]." ".$value1[1]."</b><br>";
					echo "<b style='color: #275490;'>".$thin2[1]." ".$value2[1]."</b><br>";
					echo "<b style='color: #275490;'>".$thin3[1]." ".$value3[1]."</b><br>";
					echo "<b style='color: #275490;'>".$thin4[1]." ".$value4[1]."</b><br>";
					echo "<b style='color: #275490;'>".$thin5[1]." ".$value5[1]."</b><br>";
					echo "<b style='color: #275490;'>".$thin6[1]." ".$value6[1]."</b><br>";

				echo "</center>";

			}
////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
			else {
				?>
				<div class='card_body personal_flex' id='card_body'>
					<div class='card'>
						<?php

						///////////   УРОВЕНЬ ДОСТУПА   ///////////////////////////////////////
						if ($userdata['access'] == 10) {
							echo "<br><center><b>VK GROUP</b></center><br>";
							echo "<form name='curl' method='POST' action='personal.php' enctype='multipart/form-data'>";
						// echo "<br><br><input name='curl' type='text' size='20'> <input type='submit' name='enter' value='GO'>";
							echo "<br><br><input name='curl2' type='text' size='20'> <input type='submit' name='enter2' value='АНАЛИЗ'>";
							
							echo "<br><br>";
							echo "<br><center><b>КАРТОЧКИ ПЕРСПЕКТИВНЫХ ФУТБОЛИСТОВ</b></center><br>";
							
							echo "<br><input name='curl3-1' type='text' size='20' placeholder='fudhead'>";
						//	echo "<input name='curl3-2' type='text' size='20' placeholder='sofifa'>";
							echo "<input type='file' name='filename'> <input type='submit' name='enter3' value='FH | SF'>";
						//	echo "<br><br><br>";
						//	echo "<input name='curl4-1' type='text' size='20' placeholder='fudhead old'> <input name='curl4-2' type='text' size='20' placeholder='futhead 17'>";
						//	echo "<input name='curl4-3' type='text' size='20' placeholder='sofifa'>";
						//	echo "<input type='submit' name='enter4' value='evolution'>";
							//echo "<br><br><br>";
							//echo "<input name='curl5-1' type='text' size='20' placeholder='fudhead'>";
							//echo "<input type='submit' name='enter5' value=' FIFA18WC '>";
							echo "</form>";
							$count_publicated = count_publicated ($dbcnx);
							echo "<br><b>Не опубликованных карточек: ".$count_publicated."</b><br>";

							echo "<br>";
							$no_publicated = no_publicated ($dbcnx);
							if (!empty($no_publicated)) {
								foreach ($no_publicated as $np) {
									//echo "<a href='tmp/public/".$np['filename'].".png'>".$np['name']." ".$np['skill']." ".$np['position']." [ ".$np['club']." ]</a><br>";
									echo "<a href='tmp/public/".$np['filename'].".png' class='highslide' onclick='return hs.expand(this)'>".$np['name']." ".$np['skill']." ".$np['position']." [ ".$np['club']." ]</a><br>";
								}
							}
							echo "<br>";
							
							/*
								$count_publicated_evolution = count_publicated_evolution ($dbcnx);
								echo "<br> Не опубликованных эволюций: ".$count_publicated_evolution." <br>";
							*/
							
							echo "<br><center><b>КАРТОЧКИ ПЕРЕХОДОВ ФУТБОЛИСТОВ</b></center><br>";
							
							echo "<form name='news' method='POST' action='personal.php' enctype='multipart/form-data'>";
							echo "<input name='curl_news' type='text' size='20' placeholder='fudhead'>";
							echo "<input type='file' name='file_news'> <input type='submit' name='enter' value='NEWS'>";
							echo "<br><br>";
							echo "<textarea class='textarea' name='news_text' WRAP='virtual' COLS='100' ROWS='10' placeholder='новость' style='resize: none;'></textarea>"; 
							echo "</form>";
							echo "<br><br>";
							
						}
						echo "<br><br>";
				echo "</div></div>";
			}
	}
	echo "<br><br>";
}

/* FOOTER */ include ($_SERVER["DOCUMENT_ROOT"]."/section/footer.php");