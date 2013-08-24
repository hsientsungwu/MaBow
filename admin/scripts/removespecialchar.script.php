<?php

require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$testing = (isset($_GET['exec']) ? false : true);

// remove []
$videos = $db->fetchRows("SELECT * FROM Video WHERE name LIKE '%【】%'", array());

if (count($videos)) {
	foreach ($videos as $video) {
		print_r("{$video['name']}");

		$title = str_replace(array('【', '】'), '' , $video['name']);
		
		if (!$testing) {
			$affected = $db->update(array('name' => $title), 'Video', 'id = ?', array($video['id']));

			if ($affected) {
				print_r(" - UPDATED <br>");
			} else {
				print_r(" - FAILED <br>");
			}	
		} else {
			print_r("<br>");
		}
	}
}
