<?php

function getPossibleDuplicateCounts() {
	global $db;

	$count = 0;
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
						$count++;
					}
				}
			}
		}
	}

	return $count;
}

function getPossibleWeirdCharacterVideoCount() {
	global $db;

	$videos = $db->fetchRows("SELECT * FROM Video WHERE name LIKE '%【】%'", array());

	return count($video);
}