<?php
if ($_SERVER['DOCUMENT_ROOT'] == "") $_SERVER['DOCUMENT_ROOT'] = '/home/hwu1986/public_html/latteblog/tools/mabow/htdoc/';

require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$channels = $db->fetchRows("SELECT id, channel_name FROM Channel ORDER BY id ASC");
$sc_channels = array(9, 6, 5);

if (count($channels)) {
	foreach ($channels as $channel) {

		if (in_array($channel['id'], $sc_channels)) {
			$titleTranslation = true;
		} else {
			$titleTranslation = false;
		}

		print_r("<h3>{$channel['channel_name']}</h3>");

		$videos = getVideosForChannel($channel['id']);
		$programs = getProgramsForChannel($channel['id']);

		foreach ($videos as $video) {
			foreach ($programs as $program) {
				if ($titleTranslation) {
					$video['snippet']['title'] = translateIntoTraditionalChinese($video['snippet']['title']);
				}

				print_r("{$video['snippet']['title']} - {$video['contentDetails']['videoId']} - ");

				$date = getDateFromVideoTitle($video['snippet']['title']);

				if ($date) print_r("{$date} - ");

				if (strstr($video['snippet']['title'], $program['name'])) {
					if (!isVideoStored($video['contentDetails']['videoId'])) {
						$newVideo = array(
							'video_id' => $video['contentDetails']['videoId'],
							'date' => $video['snippet']['publishedAt'],
							'name' => $video['snippet']['title'],
							'description' => $video['snippet']['description'],
							'program' => $program['id'],
							'channel' => $channel['id'],
							'status' => Status::ACTIVE,
						);

						$db->insert($newVideo, 'Video');

						print_r("stored in database ");
					}
				}

				print_r("<br>");
			}
		}
	}
}

function translateIntoTraditionalChinese($str) {
	$translated_string = iconv("BIG5","UTF-8",iconv("gb2312","BIG5",iconv("UTF-8","gb2312", $str)));

	return $translated_string;
}

function getDateFromVideoTitle($video_title) {
	$match = array();

	preg_match("/\d{4}[-.]?\d{2}[-.]?\d{2}/", $video_title, $match);

	if (count($match)) {
		$match[0] = str_replace('.', '-', $match[0]);

		return date('Y-m-d', strtotime($match[0]));
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

function getVideosForChannel($channel_id) {
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

	try {
	    $playlistItemsResponse = $YouTube->playlistItems->listPlaylistItems($apiContent, $apiParams);
	} catch (Google_ServiceException $e) {
		$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
	} catch (Google_Exception $e) {
		$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
	}

	if (count($playlistItemsResponse['items'])) {
		return $playlistItemsResponse['items'];
	}

	return array();
}