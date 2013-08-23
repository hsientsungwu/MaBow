<?php

function renameVideoTitle($video_title, $program_title) {
	preg_match("/\d{4}[-.]?\d{2}[-.]?\d{2}/", $video_title, $match);

	$date = getDateFromVideoTitle($video_title);

	if (isset($date['updated']) && count($match)) {
		$title = str_replace($match[0], '', $video_title);

		$match[0] = str_replace('.', '-', $match[0]);

		$title = str_replace($program_title, '', $title);
		$title = $program_title . ' ' . $date['updated'] . ' ' . trim($title);

		return $title;
	}

	return $video_title;
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

function isVideoExisted($video_id) {
	global $db;
	$isExisted = $db->fetchRow("SELECT id FROM Video WHERE video_id = ?", array($video_id));

	return ($isExisted ? true : false);
}

function isVideoExistedWithDateValidation($video_id, $program, $video) {
	global $db;

	$date = getDateFromVideoTitle($video);

	$isExisted = $db->fetchRow("SELECT * FROM Video WHERE video_id = ?", array($video_id));

	if (!$isExisted && isset($date['updated'])) {
		$isExisted = $db->fetchRow("SELECT * FROM `Video` WHERE name LIKE '%" . $program . " " . $date['updated'] . "%'");
	}

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

function getVideosFromPlaylist($listId = '', $level = 1) {
	global $db, $MABOW_GOOGLEDEVELOPER_KEY;

	$videos = array();

	if ($listId != '') {
		$GoogleClient = new Google_Client();
		$GoogleClient->setDeveloperKey($MABOW_GOOGLEDEVELOPER_KEY);
		$YouTube = new Google_YoutubeService($GoogleClient);

		$apiContent = 'snippet, contentDetails';
		$apiParams = array(
			'playlistId' => $listId,
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
			var_dump($exceptionError);
			die;
		} catch (Google_Exception $e) {
			$exceptionError = sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
			logThis(array('source' => 'YouTube API', 'message' => $exceptionError), LogType::ERROR);
			var_dump($exceptionError);
			die;
		}
	}
		
	return $videos;
}

function getVideo($video_id) {
	global $MABOW_GOOGLEDEVELOPER_KEY;

	$GoogleClient = new Google_Client();
	$GoogleClient->setDeveloperKey($MABOW_GOOGLEDEVELOPER_KEY);
	$YouTube = new Google_YoutubeService($GoogleClient);

	$apiContent = 'snippet, contentDetails';
	$apiParams = array(
		'id' => $video_id,
        'maxResults' => 50
	);

	$video = array();

	try {
	    $videoResponse = $YouTube->videos->listVideos($apiContent, $apiParams);

	    if (count($videoResponse['items'])) {
	    	$video = $videoResponse['items'][0];
	    }
	} catch (Google_ServiceException $e) {
		$exceptionError = sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
		logThis(array('source' => 'YouTube API', 'message' => $exceptionError), LogType::ERROR);
		var_dump($exceptionError);
		die;
	} catch (Google_Exception $e) {
		$exceptionError = sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
		logThis(array('source' => 'YouTube API', 'message' => $exceptionError), LogType::ERROR);
		var_dump($exceptionError);
		die;
	}

	return $video;
}