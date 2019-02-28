<?php
include $_SERVER["DOCUMENT_ROOT"]."/config.php";

$date = date("d.m.Y");
$date_time = date("Y-m-d H:i:s");
$timestamp = date('Y-m-d G:i:s');

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////   АВТОРИЗАЦИЯ - РЕГИСТРАЦИЯ   //////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function authorization ($dbcnx, $id) {	
	$query = mysqli_query($dbcnx, "SELECT `user`.*, CONCAT(SUBSTRING_INDEX(`user`.`fio`, ' ', 1), ' ', SUBSTRING((SUBSTRING_INDEX(`user`.`fio`, ' ', -2)),1,1), '.') AS `fio` FROM `user` WHERE `id` = '".intval($id)."' ");
	$userdata = mysqli_fetch_array($query);	
	return $userdata;
}

# Функция для генерации случайной строки
function generateCode ($length) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;  
    while (strlen($code) < $length) {
        $code .= $chars[mt_rand(0,$clen)];  
    }
    return $code;
}