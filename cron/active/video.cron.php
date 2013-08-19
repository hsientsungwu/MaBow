<?php
if ($_SERVER['DOCUMENT_ROOT'] == "") $_SERVER['DOCUMENT_ROOT'] = '/home/hwu1986/public_html/latteblog/tools/mabow/htdocs';

require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$channels = $db->fetchRows("SELECT id, channel_name, isTC FROM Channel WHERE status = ? ORDER BY id ASC", array(Status::ACTIVE));

$cron_reports = array();

if (count($channels)) {
	foreach ($channels as $channel) {

		if ($channel['isTC'] == false) {
			$titleTranslation = true;
		} else {
			$titleTranslation = false;
		}

		print_r("<h3>{$channel['channel_name']}</h3>");

		$videos = getVideosForChannel($channel['id'], 3);
		$programs = getProgramsForChannel($channel['id']);

		foreach ($videos as $video) {
			print_r("{$video['snippet']['title']} - ");

			foreach ($programs as $program) {
				
				if (!isset($cron_reports[$program['name']])) {
					$cron_reports[$program['name']] = 0;
				}

				if ($titleTranslation) {
					$video_title = translateIntoTraditionalChinese($video['snippet']['title']);
					$video_description = translateIntoTraditionalChinese($video['snippet']['description']);
				} else {
					$video_title = $video['snippet']['title'];
					$video_description = $video['snippet']['description'];
				}

				if (strstr($video_title, $program['name'])) {
					if (!isVideoStored($video['contentDetails']['videoId'])) {
						$newVideo = array(
							'video_id' => $video['contentDetails']['videoId'],
							'date' => $video['snippet']['publishedAt'],
							'name' => renameVideoTitle($video_title, $program['name']),
							'description' => $video_description,
							'program' => $program['id'],
							'channel' => $channel['id'],
							'status' => Status::ACTIVE,
						);

						print_r("{$newVideo['name']} - stored");
						$db->insert($newVideo, 'Video');

						$cron_reports[$program['name']]++;
					}
				}
			}
			print_r("<br>");
		}
	}
}

logThis(array('source' => 'YouTube Hourly Cron', 'message' => $cron_reports), LogType::CRON);

print_r($cron_reports);

function renameVideoTitle($video_title, $program_title) {
	$match = array();

	preg_match("/\d{4}[-.]?\d{2}[-.]?\d{2}/", $video_title, $match);

	if (count($match)) {
		$title = str_replace($match[0], '', $video_title);

		$match[0] = str_replace('.', '-', $match[0]);
		$date = date('Y-m-d', strtotime($match[0]));

		$title = str_replace($program_title, '', $title);

		$title = $program_title . ' ' . $date . ' ' . trim($title);

		return $title;
	}

	return $video_title;
}

function translateIntoTraditionalChinese($str) {
	$translated_string = iconv("BIG5","UTF-8",iconv("gb2312","BIG5",iconv("UTF-8","gb2312", $str)));

	return $translated_string;
}

function getDateFromVideoTitle($video_title) {
	$match = array();

	preg_match("/\d{4}[-.]?\d{2}[-.]?\d{2}/", $video_title, $match);

	if (count($match)) {
		$original = $match[0];

		$match[0] = str_replace('.', '-', $match[0]);

		return array('updated' => date('Y-m-d', strtotime($match[0])), 'original' => $original);
	}

	return false;
}

function isVideoStored($video_id) {
	global $db;

	$isExisted = $db->fetchRow("SELECT * FROM Video WHERE video_id = ?", array($video_id));

	return ($isExisted ? true : false);
}

function getProgramsForChannel($channel_id) {
	global $db;

	$programData = array();

	$programsForChannel = $db->fetchRows("SELECT program FROM Programs_Channel WHERE channel = ?", array($channel_id));

	foreach ($programsForChannel as $program) {
		$programData[] = $db->fetchRow("SELECT id, name FROM Program WHERE id = ?", array($program['program']));
	}

	return $programData;
}

function getVideosForChannel($channel_id, $level = 1) {
	global $db, $MABOW_GOOGLEDEVELOPER_KEY;

	$GoogleClient = new Google_Client();
	$GoogleClient->setDeveloperKey($MABOW_GOOGLEDEVELOPER_KEY);
	$YouTube = new Google_YoutubeService($GoogleClient);

	$uploadsListId = $db->fetchCell("SELECT upload_list_id FROM Channel WHERE id = ?", array($channel_id));
	$apiContent = 'snippet, contentDetails';
	$apiParams = array(
		'playlistId' => $uploadsListId,
        'maxResults' => 50
	);

	$currentLevel = 1;
	$videos = array();

	try {
	    $playlistItemsResponse = $YouTube->playlistItems->listPlaylistItems($apiContent, $apiParams);

	    $videos = $playlistItemsResponse['items'];

	    while ($level > $currentLevel && $playlistItemsResponse['nextPageToken'] != '') {
	    	$apiParams['pageToken'] = $playlistItemsResponse['nextPageToken'];

	    	$playlistItemsResponse = $YouTube->playlistItems->listPlaylistItems($apiContent, $apiParams);

	    	$videos = array_merge($videos, $playlistItemsResponse['items']);

	    	$currentLevel++;
	    }
	} catch (Google_ServiceException $e) {
		$exceptionError = sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
		logThis(array('source' => 'YouTube API', 'message' => $exceptionError), LogType::ERROR);
	} catch (Google_Exception $e) {
		$exceptionError = sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
		logThis(array('source' => 'YouTube API', 'message' => $exceptionError), LogType::ERROR);
	}

	return $videos;
}