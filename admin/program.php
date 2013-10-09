<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_POST['program']) {
	$program = clean_input($_POST['program']);

	if ($program['name'] == '' || $program['description'] == '') {
		$errors[] = "Program name / descriptions cannot be empty";
	} else {
		$lastWeight = $db->fetchCell("SELECT weight FROM Program WHERE category = ? ORDER BY weight DESC", array($program['category']));

		if (!$lastWeight) $lastWeight = 0;

		$newProgram = array(
			'name' => $program['name'],
			'description' => $program['description'],
			'category' => $program['category'],
			'weight' => ($lastWeight ? $lastWeight+1 : 0),
			'time_type' => $program['time_type'],
		);

		if (!$program['id']) {
			$newProgramId = $db->insert($newProgram, 'Program');

			if ($newProgramId) $success[] = '節目已新增';
		} else {
			$newProgramId = $db->update($newProgram, 'Program', 'id = ?', array($program['id']));

			if ($newProgramId) $success[] = '節目已更新';
		}
	}
} elseif ($_POST['sort'] && $_POST['type']) {
	$programSort = clean_input($_POST['sort']);
	$categoryId = clean_input($_POST['type']);

	for ($i = 0; $i < count($programSort); $i++) {
		echo $db->update(array('weight' => $i), 'Program', "id = ? AND category = ?", array($programSort[$i], $categoryId));
	}

	die('success');
} elseif ($_GET['delete']) {
	$deleteId = clean_input($_GET['delete']);

	$affected = $db->delete("Program", "id = ?", array($deleteId));

	if ($affected) {
		$db->delete("Programs_Channel", "program = ?", array($deleteId));
		$db->delete("Video", "program = ?", array($deleteId));
	}

	if ($affected) $success[] = "節目已移除";
} elseif ($_GET['id']) {
	$_GET['id'] = clean_input($_GET['id']);

	$editProgram = $db->fetchRow("SELECT * FROM Program WHERE id = ?", array($_GET['id']));
}

$categories = $db->fetchRows("SELECT id, name FROM Category ORDER BY weight ASC");

include $_SERVER['DOCUMENT_ROOT'] . '/admin/templates/header.template.php';
?>

<div class="row">
	<div class="large-12 large-centered columns">
		<div class="section-container tabs" data-section="tabs">
			<?php
				foreach ($categories as $category) {
					?>
						<section>
						    <p class="title" data-section-title><a href="#"><?php echo $category['name']; ?></a></p>
						    <div class="content" data-section-content>
						    	
								<div class="row">
									<div class="large-12 large-centered columns">
								      	<ul class="list-sortable" data-list="<?php echo $category['id']; ?>" id="sortable-<?php echo $category['id']; ?>">
								      		<?php
								      			$programsForCategory = $db->fetchRows("SELECT id, name, category FROM Program WHERE category = ? ORDER BY weight ASC", array($category['id']));

								      			foreach ($programsForCategory as $programData) {
								      				echo '<li class="ui-state-default" id="' . $programData['id'] . '">
								      						<div class="panel radius">
								      						<span class="sort-action"><img src="/img/sort-icon.png" /></span>'
					      									. $programData['name'] . 
					      									'<span class="list-action">
					      										<a href="videos.php?program=' . $programData['id'] . '"><img src="/img/edit-icon.png" /></a>
					      										<a href="program.php?id=' . $programData['id'] . '"><img src="/img/edit-icon.png" /></a>
					      										<a href="program.php?delete=' . $programData['id'] . '"><img src="/img/delete-icon.png"/></a>
					      									</span>
					      								</div></li>';
								      			}
								      		?>
										</ul>
									</div>
								</div>
						    </div>
						</section>
					<?php
				}
			?>
		</div>
	</div>
</div>

<div class="row">
	<div class="large-12 large-centered columns">
		<form action="/admin/program.php" method="POST">
		  	<fieldset>
		    	<legend><?php echo ($_GET['id'] ? '更新節目' : '新增節目'); ?></legend>

		    	<div class="row">
			    	<div class="large-12 columns">
				      	<select name="program[category]" class="medium">
				      		<?php
				      			foreach ($categories as $category) {
				      				$selected = '';

				      				if ($_GET['id']) {
				      					if ($category['id'] == $editProgram['category']) {
				      						$selected = 'selected';
				      					}
				      				}
				      				echo '<option value="' . $category['id'] . '" ' . $selected . '>' . $category['name'] . '</option>';
				      			}
				      		?>
				      	</select>
				    </div>
				</div>

				<?php 
		    		if ($_GET['id']) {
		    			echo '<input type="hidden" name="program[id]" value="' . $_GET['id'] . '">';
		    		}
		    	?>	

			    <div class="row">
			      <div class="large-12 columns">
			        <input type="text" name="program[name]" placeholder="節目名稱" value="<?php if ($_GET['id']) echo $editProgram['name']; ?>">
			      </div>
			    </div>
			    
			    <div class="row">
			      	<div class="large-12 columns">
			        	<textarea name="program[description]" placeholder="節目描述"><?php if ($_GET['id']) echo $editProgram['description']; ?></textarea>
			      	</div>
			    </div>
			    
			    <div class="row">
			    	<div class="large-12 columns">
				      	<select name="program[time_type]" class="medium">
				      		<?php
				      			foreach (ProgramTimeType::getProgramTimeType() as $index => $time_type) {
				      				echo '<option value="' . $index . '">' . $time_type . '</option>';
				      			}
				      		?>
				      	</select>
				    </div>
				</div>

				<?php
				if ($_GET['id']) {
					$programChannels = $db->fetchRows("SELECT channel FROM Programs_Channel WHERE program = ?", array($_GET['id']));

					if (count($programChannels)) {
						?>
						<div class="row">
				    		<div class="large-12 columns">
				    			<h4>Source:</h4>
				    			<ul>
						<?php

						foreach ($programChannels as $programChannel) {
							$channelName = $db->fetchCell("SELECT channel_name FROM Channel WHERE id = ?", array($programChannel['channel']));

							echo '<li>' . $channelName . '</li>';
						}

						echo '</ul></div></div>';
					}
				}
				?>


			    <div class="row">
			    	<div class="large-12 columns">
			    		<ul class="inline-list right">
						  	<li><input type="submit" class="small radius button" value="<?php echo ($_GET['id'] ? '更新節目' : '新增節目'); ?>"/></li>
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