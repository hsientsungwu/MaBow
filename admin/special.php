<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_POST) {
	$special = clean_input($_POST['special']);

	$GoogleClient = new Google_Client();
	$GoogleClient->setDeveloperKey($MABOW_GOOGLEDEVELOPER_KEY);
	$YouTube = new Google_YoutubeService($GoogleClient);

	$uploadsListId = $special['listId'];
	$apiContent = 'snippet, contentDetails';
	$apiParams = array(
		'playlistId' => $uploadsListId,
        'maxResults' => 50
	);

	$level = 1;
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

	$new_video_count = 0;

	if (count($videos) > 0) {
		foreach ($videos as $video) {
			$isExisted = $db->fetchRow("SELECT id FROM Video WHERE video_id = ?", array($video['contentDetails']['videoId']));

			if (!$isExisted) {
				$newVideo = array(
					'video_id' => $video['contentDetails']['videoId'],
					'date' => $video['snippet']['publishedAt'],
					'name' => $video['snippet']['title'],
					'description' => $video['snippet']['description'],
					'program' => $special['program'],
					'channel' => $special['channel'],
					'status' => Status::ACTIVE,
				);

				$db->insert($newVideo, 'Video');

				$new_video_count++;
			}
		}
	}

	if ($new_video_count > 0) {
		$success[] = "新增了 {$new_video_count} 的節目";
	} else {
		$error[] = "沒有新增任何節目";
	}
}

$categories = $db->fetchRows("SELECT id, name FROM Category ORDER BY weight ASC");
$programs = $db->fetchRows("SELECT id, name FROM Program ORDER BY weight ASC");

include $_SERVER['DOCUMENT_ROOT'] . '/admin/templates/header.template.php';
?>

<div class="row">
	<div class="large-12 large-centered columns">
		<form action="/admin/special.php" method="POST">
		  	<fieldset>
		    	<legend>新增特殊節目名單</legend>

		    	<div class="row">
			    	<div class="large-12 columns">
				      	<select name="special[category]" class="medium">
				      		<?php
				      			foreach ($categories as $category) {
				      				echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
				      			}
				      		?>
				      	</select>
				    </div>
				</div>

				<div class="row">
			    	<div class="large-12 columns">
				      	<select name="special[program]" class="medium">
				      		<?php
				      			foreach ($programs as $program) {
				      				echo '<option value="' . $program['id'] . '">' . $program['name'] . '</option>';
				      			}
				      		?>
				      	</select>
				    </div>
				</div>

			    <div class="row">
			      <div class="large-12 columns">
			        <input type="text" name="special[listId]" placeholder="YouTube 名單ID" value="">
			      </div>
			    </div>
			    
			    <div class="row">
			    	<div class="large-12 columns">
			    		<ul class="inline-list right">
						  	<li><input type="submit" class="small radius button" value="新增名單"/></li>
							<li><input type="reset" class="small radius button" value="取消"/></li>
						</ul>
			    	</div>
			    </div>

		  	</fieldset>
		</form>
	</div>
</div>

<?php
$scripts = array();

include $_SERVER['DOCUMENT_ROOT'] . '/admin/templates/footer.template.php';
?>