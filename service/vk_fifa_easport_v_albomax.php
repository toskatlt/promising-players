 <?php
include "../config.php";

echo date('H').":".date('i');

if ((date('H') == '9') and (date('i') == '00')) {
	
	$connect_vk = mysqli_query($dbcnx, "SELECT * FROM `connect_vk` WHERE run='1'");
	$connect_vk_result = mysqli_fetch_assoc($connect_vk);

	$access_token = $connect_vk_result['token'];
	$groupId = $connect_vk_result['id_group']; // fifa_easport
	//$groupId = 58571212; // test

	$attachments = 'album-31685665_256991698,album-31685665_256991693,album-31685665_256991690,album-31685665_256991685';
	
	/// GK ///////////////////////////////////////////////////////////////////////////////////////
	echo ":::::::::::::: GK :::::::::::::: <br><br>";
	$gk = json_decode(file_get_contents("https://api.vk.com/method/photos.get?owner_id=-31685665&album_id=256991698&access_token={$access_token}&v=5.68"),true);
	$gk_count = $gk["response"]["count"];
	$gk_count_new = $gk_count - 1;

	$random_gk = rand(0, $gk_count_new);
	$id_photo_gk = $gk["response"]["items"][$random_gk]["id"];

	$gk1 = json_decode(file_get_contents("https://api.vk.com/method/photos.makeCover?owner_id=-31685665&album_id=256991698&photo_id={$id_photo_gk}&access_token={$access_token}&v=5.68"),true);
	echo $gk_count." - всего фотографий в альбоме<br>";
	echo $random_gk." - рандом фото<br><br>";
	echo $id_photo_gk." - id фотографии<br><br>";
	//var_dump($gk1);
	echo "<br><br>";
	echo "-------------------------------------------<br>";
	sleep(1);
	/// DEF ///////////////////////////////////////////////////////////////////////////////////////
	echo ":::::::::::::: DEF :::::::::::::: <br><br>";
	$def = json_decode(file_get_contents("https://api.vk.com/method/photos.get?owner_id=-31685665&album_id=256991693&access_token={$access_token}&v=5.68"),true);
	$def_count = $def["response"]["count"];
	$def_count_new = $def_count - 1;

	$random_def = rand(0, $def_count_new);
	$id_photo_def = $def["response"]["items"][$random_def]["id"];

	$def1 = json_decode(file_get_contents("https://api.vk.com/method/photos.makeCover?owner_id=-31685665&album_id=256991693&photo_id={$id_photo_def}&access_token={$access_token}&v=5.68"),true);
	echo $def_count." - всего фотографий в альбоме<br>";
	echo $random_def." - рандом фото<br><br>";
	echo $id_photo_def." - id фотографии<br><br>";
	//var_dump($def1);
	echo "<br><br>";
	echo "-------------------------------------------<br>";
	sleep(1);
	/// MED ///////////////////////////////////////////////////////////////////////////////////////
	echo ":::::::::::::: MED :::::::::::::: <br><br>";
	$med = json_decode(file_get_contents("https://api.vk.com/method/photos.get?owner_id=-31685665&album_id=256991690&access_token={$access_token}&v=5.68"),true);
	$med_count = $med["response"]["count"];
	$med_count_new = $med_count - 1;

	$random_med = rand(0, $med_count_new);
	$id_photo_med = $med["response"]["items"][$random_med]["id"];

	$med1 = json_decode(file_get_contents("https://api.vk.com/method/photos.makeCover?owner_id=-31685665&album_id=256991690&photo_id={$id_photo_med}&access_token={$access_token}&v=5.68"),true);
	echo $med_count." - всего фотографий в альбоме<br>";
	echo $random_med." - рандом фото<br><br>";
	echo $id_photo_med." - id фотографии<br><br>";
	//var_dump($med1);
	echo "<br><br>";
	echo "-------------------------------------------<br>";
	sleep(1);
	/// ATK ///////////////////////////////////////////////////////////////////////////////////////
	echo ":::::::::::::: ATK :::::::::::::: <br><br>";
	$atk = json_decode(file_get_contents("https://api.vk.com/method/photos.get?owner_id=-31685665&album_id=256991685&access_token={$access_token}&v=5.68"),true);
	$atk_count = $atk["response"]["count"];
	$atk_count_new = $atk_count - 1;

	$random_atk = rand(0, $atk_count_new);
	$id_photo_atk = $atk["response"]["items"][$random_atk]["id"];

	$atk1 = json_decode(file_get_contents("https://api.vk.com/method/photos.makeCover?owner_id=-31685665&album_id=256991685&photo_id={$id_photo_atk}&access_token={$access_token}&v=5.68"),true);
	echo $atk_count." - всего фотографий в альбоме<br>";
	echo $random_atk." - рандом фото<br><br>";
	echo $id_photo_atk." - id фотографии<br><br>";
	//var_dump($atk1);
	echo "<br><br>";
	echo "-------------------------------------------<br>";
	sleep(1);
	////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$sum = $gk_count + $def_count + $med_count + $atk_count;
	echo $sum." всего фотографий <br>";
	$gk_rest = substr($sum, -2); 

	function sklonen($attempts,$s1,$s2,$s3, $b = false){
		$m = $attempts % 10; $j = $attempts % 100;
		if($b) $attempts = '<b>'.$attempts.'</b>';
		if($m==0 || $m>=5 || ($j>=10 && $j<=20)) return $attempts.' '.$s3;
		if($m>=2 && $m<=4) return  $attempts.' '.$s2;
		return $attempts.' '.$s1;
	}

	$sklonen = sklonen($sum, '<br>запись', '<br>записи', '<br>записей');

	$messages = "&#9917; В наших альбомах ".$sklonen." о перспективных игроках!!!".PHP_EOL ;
	$message .=  strip_tags($messages).PHP_EOL ;
	$messages = " ".PHP_EOL;
	$message .= "✖ Вратари: ".$gk_count." ".PHP_EOL;
	$message .= "✖ Защитники: ".$def_count." ".PHP_EOL;
	$message .= "✖ Полузащитники: ".$med_count." ".PHP_EOL;
	$message .= "✖ Нападающие: ".$atk_count." ".PHP_EOL;
	$message .=  strip_tags($messages);
	$message .= "#FIFA19 &#128285; @club31685665 (FIFA 19 | Карьера | Перспективные футболисты)".PHP_EOL;

	$message = urlencode($message);

	if ((isset($gk_count)) and (isset($def_count)) and (isset($med_count)) and (isset($atk_count))) {
		$members = json_decode(file_get_contents("https://api.vk.com/method/wall.post?owner_id=-{$groupId}&message={$message}&attachments={$attachments}&access_token={$access_token}&v=5.68"),true);
	}
}