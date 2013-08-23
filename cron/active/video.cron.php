<?php
$beginTime = time();

if ($_SERVER['DOCUMENT_ROOT'] == "") $_SERVER['DOCUMENT_ROOT'] = '/home/hwu1986/public_html/htwu/mabow/htdocs';

$debug = ($_GET['debug'] ? true : false);
$testing = ($_GET['testing'] ? true : false);

require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$channels = $db->fetchRows("SELECT id, channel_name, isTC FROM Channel WHERE status = ? ORDER BY id ASC", array(Status::ACTIVE));

$cron_reports = array();
$video_stored_count = 0;

if (count($channels)) {
	foreach ($channels as $channel) {

		if ($channel['isTC'] == '0') {
			$titleTranslation = true;
		} else {
			$titleTranslation = false;
		}

		print_r("Running <b>{$channel['channel_name']}</b>");

		if ($debug) print_r("<br>");

		$uploadsListId = $db->fetchCell("SELECT upload_list_id FROM Channel WHERE id = ?", array($channel_id));

		$videos = getVideosFromPlaylist($uploadsListId, 3);
		
		$programs = getProgramsForChannel($channel['id']);

		foreach ($videos as $video) {
			if ($debug) print_r("{$video['snippet']['title']} - ");

			foreach ($programs as $program) {
				
				if (!isset($cron_reports[$program['name']])) {
					$cron_reports[$program['name']] = 0;
				}

				if ($titleTranslation) {
					$video_title = translateIntoTraditionalChinese($video['snippet']['title']);
				} else {
					$video_title = $video['snippet']['title'];
				}

				if (strstr($video_title, $program['name'])) {
					if (!isVideoExistedWithDateValidation($video['contentDetails']['videoId'], $program['name'], $video_title)) {
						$newVideo = array(
							'video_id' => $video['contentDetails']['videoId'],
							'date' => $video['snippet']['publishedAt'],
							'name' => renameVideoTitle($video_title, $program['name']),
							'description' => ($titleTranslation ? translateIntoTraditionalChinese($video['snippet']['description']) : $video['snippet']['description']),
							'program' => $program['id'],
							'channel' => $channel['id'],
							'status' => Status::ACTIVE,
						);

						if ($debug) print_r("{$newVideo['name']} - stored");

						if (!$testing) $db->insert($newVideo, 'Video');

						$video_stored_count++;
						$cron_reports[$program['name']]++;
					}
				}
			}

			if ($debug) print_r("<br>");
		}

		if (!$debug) print_r(" ... DONE<br>");
	}
}

logThis(array('source' => 'YouTube Hourly Cron - ' . $video_stored_count . ' videos', 'message' => $cron_reports), LogType::CRON);

$endTime = time();

$timeUsed = $endTime - $beginTime;

print_r("Total time spent: {$timeUsed}s <br>");
print_r($cron_reports);