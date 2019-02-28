<?php
include $_SERVER["DOCUMENT_ROOT"]."/config.php";

$date = date("d.m.Y");
$date_time = date("Y-m-d H:i:s");
$timestamp = date('Y-m-d G:i:s');


// КОЛИЧЕСТВО НЕ ОПУБЛИКОВАННЫХ ПЕРСПЕКТИВНЫХ ИГРОКОВ
function count_publicated ($dbcnx) {
	$query = mysqli_query($dbcnx, "SELECT COUNT(*) FROM `vk_fifa_easport_promising_players` WHERE `publicated`='0'");
	$result = mysqli_fetch_assoc($query);
	return $result['COUNT(*)'];
}

function no_publicated ($dbcnx) {
	$query = mysqli_query($dbcnx, "SELECT name, club, filename, skill, position FROM `vk_fifa_easport_promising_players` WHERE `publicated` = 0");
	$n = mysqli_num_rows($query);
	for ($i = 0; $i < $n; $i++) {
		$result[] = mysqli_fetch_assoc($query);
	}
	return $result;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////   FIFA   /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// СКЛОНЕНИЕ 'ГОД'
function age_sklonen ($age) {	
	$ageArray0 = ["22","23","24","32","33","34","42","43","44","52"];
	$ageArray1 = ["21","31","51"];		
	if	(in_array($age, $ageArray0)) {
		$age_sklonen = "года";
	} elseif (in_array($age, $ageArray1)) {
		$age_sklonen = "год";
	} else {
		$age_sklonen = "лет";
	}	
	return $age_sklonen;
}

// КОЛИЧЕСТВО НЕ ОПУБЛИКОВАННЫХ ЭВОЛЮЦИЙ
function count_publicated_evolution ($dbcnx) {
	$query = mysqli_query($dbcnx, "SELECT COUNT(*) FROM `vk_fifa_easport_evolution` WHERE `publicated`='0'");
	$result = mysqli_fetch_assoc($query);
	return $result['COUNT(*)'];
}
