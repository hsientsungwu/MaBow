<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_POST['special']) {
	$special = clean_input($_POST['special']);

	$GoogleClient = new Google_Client();
	$GoogleClient->setDeveloperKey($MABOW_GOOGLEDEVELOPER_KEY);
	$YouTube = new Google_YoutubeService($GoogleClient);

	$uploadsListId = $special['listId'];
	$videos = getVideosFromPlaylist($uploadsListId, 3);

	if (count($videos) > 0) {
		foreach ($videos as $video) {
			if (!isVideoExisted($video['contentDetails']['videoId'])) {
				$newVideo = array(
					'video_id' => $video['contentDetails']['videoId'],
					'date' => $video['snippet']['publishedAt'],
					'name' => $video['snippet']['title'],
					'description' => $video['snippet']['description'],
					'program' => $special['program'],
					'channel' => '',
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
} elseif ($_POST['single']) {
	$single = clean_input($_POST['single']);

	if (!isVideoExisted($single['videoId'])) {
		$video = getVideo($single['videoId']);

		$newSingleVideo = array(
			'video_id' => $video['id'],
			'date' => $video['snippet']['publishedAt'],
			'name' => $video['snippet']['title'],
			'description' => $video['snippet']['description'],
			'program' => $single['program'],
			'channel' => '',
			'status' => Status::ACTIVE,
		);

		$affected = $db->insert($newSingleVideo, 'Video');

		if ($affected) {
			$success[] = "節目新增 - {$video['snippet']['title']}"; 
		} else {
			$error[] = "新增節目失敗";
		}
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

<div class="row">
	<div class="large-12 large-centered columns">
		<form action="/admin/special.php" method="POST">
		  	<fieldset>
		    	<legend>新增單一節目</legend>

				<div class="row">
			    	<div class="large-12 columns">
				      	<select name="single[program]" class="medium">
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
			        <input type="text" name="single[videoId]" placeholder="YouTube 節目ID" value="">
			      </div>
			    </div>
			    
			    <div class="row">
			    	<div class="large-12 columns">
			    		<ul class="inline-list right">
						  	<li><input type="submit" class="small radius button" value="新增節目"/></li>
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