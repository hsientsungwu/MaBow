<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_POST['action'] == 'channel') {
	$channelPostData = clean_input($_POST['channel']);

	if ($channelPostData['id']) {
		$channelId = $channelPostData['id'];
	} else {
		$GoogleClient = new Google_Client();
		$GoogleClient->setDeveloperKey($MABOW_GOOGLEDEVELOPER_KEY);
		$YouTube = new Google_YoutubeService($GoogleClient);

		try {
			$apiContent = 'snippet, contentDetails';
			$apiParams = array(
				'id' => $channelPostData['channel_id']
			);

		    $channelInfo = $YouTube->channels->listChannels($apiContent, $apiParams);
		    $channelInfo = $channelInfo['items'][0];

		    if (isset($channelInfo['id']) && isset($channelInfo['snippet'])) {
		    	$newChannel = array(
		    		'channel_id' => $channelInfo['id'],
		    		'channel_name' => $channelInfo['snippet']['title'],
		    		'upload_list_id' => $channelInfo['contentDetails']['relatedPlaylists']['uploads'],
		    		'status' => ($channelPostData['status'] == 'on' ? Status::ACTIVE : Status::INACTIVE)
		    	);

		    	$affected = $db->insert($newChannel, "Channel");

		    	if ($affected) {
		    		$channelId = $affected;
		    		$success[] = "頻道已新增";
		    	}
		    }
		} catch (Google_ServiceException $e) {
			$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
		} catch (Google_Exception $e) {
			$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
		}
	}

	if ($channelId) {
		$affected = $db->delete("Programs_Channel", "channel = ?", array($channelId));

		foreach ($channelPostData['programs'] as $programId) {
			$newProgramsChannel = array(
				'channel' => $channelId,
				'program' => $programId,
			);

			$affected = $db->insert($newProgramsChannel, 'Programs_Channel');
		}
	}
} elseif ($_POST['action'] == 'status') {
	$_POST = clean_input($_POST);

	if ($_POST['channel'] && $_POST['status']) {
		$affected = $db->update(array('status' => $_POST['status']), 'Channel', 'id = ?', array($_POST['channel']));

		if ($affected) die('success');
	}

	die('failure');

} elseif ($_GET['id']) {
	$channel = $db->fetchRow("SELECT * FROM Channel WHERE id = ?", array($_GET['id']));
}

$channels = $db->fetchRows("SELECT * FROM Channel ORDER BY id DESC");

include $_SERVER['DOCUMENT_ROOT'] . '/admin/templates/header.template.php';
?>

<div class="row">
	<div class="large-12 large-centered columns">	
		<table>
		  	<thead>
			    <tr>
			    	<th width="75">狀態</th>
			    	<th width="75">ID</th>
			      	<th width="350">頻道名稱</th>
			      	<th width="350">節目數量</th>
			      	<th width="150"></th>
			    </tr>
		  	</thead>
		  	<tbody>
		  		<?php
		  			foreach ($channels as $data) {
		  				echo "<tr>";
		  				?>
		  				<td>
		  					<div class="status-switch switch tiny round" data-channelid="<?php echo $data['id']; ?>">
							  	<input id="z" class="status" name="channel[status]" data-status="<?php echo Status::INACTIVE; ?>" type="radio" <?php if ($data['status'] == Status::INACTIVE) echo 'checked'; ?>>
							  	<label for="z" onclick="">Off</label>

							  	<input id="z1" class="status" name="channel[status]" data-status="<?php echo Status::ACTIVE; ?>"type="radio" <?php if ($data['status'] == Status::ACTIVE) echo 'checked'; ?>>
							  	<label for="z1" onclick="">On</label>

	  							<span></span>
							</div>
						</td>
		  				<?php
		  				echo "<td>" . $data['id'] . "</td>";
		  				echo "<td>" . $data['channel_name'] . "</td>";
		  				echo "<td>0</td>";
		  				echo "<td>
		  						<a href='channel.php?id=" . $data['id'] . "'>修改</a> | 
		  						<a href='channel.php?delete=" . $data['id'] . "'>移除</a>
		  					</td>";
		  				echo "</tr>";
		  			}
		  		?>
		  	</tbody>
		</table>
	</div>
</div>

<div class="row channel-form-container">
	<div class="large-12 large-centered columns">
		<form action="/admin/channel.php" method="POST">
		  	<fieldset>
		    	<legend><?php echo ($_GET['id'] ? '修改頻道' : '新增頻道'); ?></legend>

		    	<?php
		    	if ($_GET['id']) {
		    		echo '<input type="hidden" name="channel[id]" value=' . $_GET['id'] . "/>";
		    	}
		    	?>
		    	
		    	<input type="hidden" name="action" value="channel" />

		    	<div class="row">
				      <div class="large-12 columns">
		    	<?php 
		    	if ($_GET['id']) {
		    		echo '<h3>' . $channel['channel_name'] . '</h3>';
		    	} else {
		    		echo '<input type="text" name="channel[channel_id]" placeholder="頻道ID">';
		    	}
				?>  
					</div>
			    </div>

			    <div class="row">
				    <div class="large-12 columns">
				    	<button class="small button add-program">新增節目</button>
				     </div>
			    </div>

			    <div class="programSelect-Container">
			    	<div class="row programSelect-Template">
				    	<div class="large-12 columns">
					      	<select name="template" class="medium">
					      		<option value="">Please select one</option>
					      		<?php
					      			$programs = $db->fetchRows("SELECT * FROM Program ORDER BY id");

					      			foreach ($programs as $index => $program) {
					      				echo '<option value="' . $program['id'] . '">' . $program['name'] . '</option>';
					      			}
					      		?>
					      	</select>
					      	<button class="tiny button remove-program">移除節目</button>
					    </div>
					</div>
			    </div>   

			    <div class="row">
			    	<div class="large-12 columns">
			    		<ul class="inline-list right">
						  	<li><input type="submit" class="small radius button" value="<?php echo ($_GET['id'] ? '修改頻道' : '新增頻道'); ?>"/></li>
							<li><a href="/admin/channel.php" class="small radius button">取消</a></li>
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