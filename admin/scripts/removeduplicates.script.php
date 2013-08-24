<?php

require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$testing = (isset($_GET['exec']) ? false : true);

$programs = $db->fetchRows("SELECT * FROM Program WHERE id IN (15, 11)");

foreach ($programs as $program) {
	if (in_array($program['id'], array('27', '28', '31'))) continue;

	$videosForProgram = $db->fetchRows("SELECT * FROM Video WHERE program = ?", array($program['id']));

	if (count($videosForProgram)) {
		foreach ($videosForProgram as $video) {
			if ($video['type'] == VideoType::ARCHIVED) continue;

			foreach ($videosForProgram as $video2) {
				if ($video['id'] == $video2['id']) continue;
				if ($video2['type'] == VideoType::ARCHIVED) continue;

				if ($video['title_date'] == $video2['title_date']) {
					print_r("{$video['id']} : {$video['name']}<br>{$video2['id']} - {$video2['name']} - duplicates<br>********************<br>");
					
					if (!$testing) {
						$db->delete("Video", 'id = ?', array($video2['id']));
						print_r("duplicates deleted ---- <br>");
					}
				}
			}
		}
	}
}