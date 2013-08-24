<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_POST['action'] == 'channel') {
	$channelPostData = clean_input($_POST['channel']);

	if ($channelPostData['id']) {
		$channelId = $channelPostData['id'];

		$editedChannelData = array(
			'isTC' => ($channelPostData['isTC'] ? '1' : '0')
		);

		$db->update($editedChannelData, 'Channel', 'id = ?', array($channelPostData['id']));
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
		    		'status' => ($channelPostData['status'] == 'on' ? Status::ACTIVE : Status::INACTIVE),
		    		'isTC' => ($channelPostData['isTC'] ? '1' : '0')
		    	);

		    	$affected = $db->insert($newChannel, "Channel");

		    	if ($affected) {
		    		$channelId = $affected;
		    		$success[] = "頻道已新增";
		    	}
		    } else {
		    	$errors[] = "無法找到 YouTube 頻道";
		    }
		} catch (Google_ServiceException $e) {
			$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
		} catch (Google_Exception $e) {
			$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
		}
	}

	if ($channelId) {
		$affected = $db->delete("Programs_Channel", "channel = ?", array($channelId));

		if (count($channelPostData['programs'])) {
			foreach ($channelPostData['programs'] as $programId) {
				$newProgramsChannel = array(
					'channel' => $channelId,
					'program' => $programId,
				);

				$affected = $db->insert($newProgramsChannel, 'Programs_Channel');
			}
		}
	}
} elseif ($_POST['action'] == 'status') {
	$_POST = clean_input($_POST);

	if ($_POST['channel'] && $_POST['status']) {
		$affected = $db->update(array('status' => $_POST['status']), 'Channel', 'id = ?', array($_POST['channel']));

		if ($affected) die('success');
	}

	die('failure');

} elseif ($_GET['delete']) {
	$_GET['delete'] = clean_input($_GET['delete']);

	$affected = $db->delete("Channel", "id = ?", array($_GET['delete']));

	if ($affected) {
		$db->delete("Programs_Channel", "channel = ?", array($_GET['delete']));
	}

	if ($affected) $success[] = "頻道已移除";
} elseif ($_GET['id']) {
	$editChannel = $db->fetchRow("SELECT * FROM Channel WHERE id = ?", array($_GET['id']));
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
			      	<th width="325">頻道名稱</th>
			      	<th width="325">節目數量</th>
			      	<th width="100">中文</th>
			      	<th width="100"></th>
			    </tr>
		  	</thead>
		  	<tbody>
		  		<?php
		  			foreach ($channels as $data) {
		  				$programCount = $db->fetchCell("SELECT COUNT(id) FROM Programs_Channel WHERE channel = ? GROUP BY channel", array($data['id']));

		  				echo "<tr>";
		  				?>
		  				<td>
		  					<div class="status-switch switch tiny round" data-channelid="<?php echo $data['id']; ?>">
							  	<input id="channel-switch-<?php echo $data['id']; ?>" class="status" name="channel-switch-<?php echo $data['id']; ?>" data-status="<?php echo Status::INACTIVE; ?>" type="radio" <?php if ($data['status'] == Status::INACTIVE) echo 'checked'; ?>>
							  	<label for="channel-switch-<?php echo $data['id']; ?>" onclick="">Off</label>

							  	<input id="channel-switch-<?php echo $data['id']; ?>o" class="status" name="channel-switch-<?php echo $data['id']; ?>" data-status="<?php echo Status::ACTIVE; ?>"type="radio" <?php if ($data['status'] == Status::ACTIVE) echo 'checked'; ?>>
							  	<label for="channel-switch-<?php echo $data['id']; ?>o" onclick="">On</label>

	  							<span></span>
							</div>
						</td>
		  				<?php
		  				echo "<td>" . $data['id'] . "</td>";
		  				echo "<td>" . $data['channel_name'] . "</td>";
		  				echo "<td>" . ($programCount ? $programCount : '0') . "</td>";
		  				echo "<td>" . ($data['isTC'] ? '繁體中文' : '簡體中文') . "</td>";
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
		    		echo '<input type="hidden" name="channel[id]" value="' . $_GET['id'] . '">';
		    	}
		    	?>
		    	
		    	<input type="hidden" name="action" value="channel" />

		    	<div class="row">
				      <div class="large-12 columns">
		    	<?php 
		    	if ($_GET['id']) {
		    		echo '<h3>' . $editChannel['channel_name'] . '</h3>';
		    	} else {
		    		echo '<input type="text" name="channel[channel_id]" placeholder="頻道ID">';
		    	}
				?>  
					</div>
			    </div>

			    <div class="row">
				    <div class="large-12 columns">
				    	<label for="checkbox1"><input type="checkbox" name="channel[isTC]" id="checkbox1" <?php if ($_GET['id'] && $editChannel['isTC'] == 1) echo 'checked'; ?>><span class="custom checkbox"></span>繁體中文</label>
				     </div>
			    </div>

			    <div class="row">
				    <div class="large-12 columns">
				    	<button class="small button add-program">新增節目</button>
				     </div>
			    </div>

			    <?php
			    if ($_GET['id']) {
			    	$programsChannel = $db->fetchRows("SELECT program FROM Programs_Channel WHERE channel = ?", array($editChannel['id']));

			    	if (count($programsChannel)) {
			    		foreach ($programsChannel as $program_channel) {
			    			?>
			    				<div class="row programSelect-existed">
				    				<div class="large-12 columns">
			    			<?php
			    				$programInfo = $db->fetchRow("SELECT id, name FROM Program WHERE id = ?", array($program_channel['program']));
			    				echo '<input type="hidden" name="channel[programs][]" value="' . $programInfo['id'] . '">';
			    				echo '<h4>' . $programInfo['name'] . '</h4><button class="tiny button remove-program">移除節目</button></div></div>';
			    		}
			    	}
			    }


			    ?>

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