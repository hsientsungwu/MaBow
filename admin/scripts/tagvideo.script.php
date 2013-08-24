<?php

require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$videos = $db->fetchRows("SELECT * FROM Video WHERE program NOT IN (27, 28, 31)");

foreach ($videos as $video) {	
	$date = getDateFromVideoTitle($video['name']);

	if (!isset($date['updated'])) {
		print_r("{$video['name']} - {$video['type']}<br>");
		//$db->update(array('type' => VideoType::ARCHIVED), 'Video', 'id = ?', array($video['id']));
	}

}