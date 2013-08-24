<?php

require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$videos = $db->fetchRows("SELECT * FROM Video");

foreach ($videos as $video) {
	$date = getDateFromVideoTitle($video['name']);

	if (isset($date['updated'])) {
		$newDate = array('title_date' => $date['updated']);
		print_r("{$video['name']} - {$date['updated']}<br>");
	} else {
		$newDate = array('title_date' => $video['date']);
		print_r("{$video['name']} - {$video['date']}<br>");
	}
	
	$db->update($newDate, 'Video', 'id = ?', array($video['id']));
}