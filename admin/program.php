<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_POST['program']) {
	$program = $_POST['program'];

	if ($program['name'] == '' || $program['description'] == '') {
		$errors[] = "Program name / descriptions cannot be empty";
	} else {
		$lastWeight = $db->fetchCell("SELECT weight FROM Program ORDER BY weight DESC");

		$newProgram = array(
			'name' => $program['name'],
			'description' => $program['description'],
			'category' => $program['category'],
			'weight' => $lastWeight+1,
			'time_type' => $program['time_type'],
		);

		$newProgramId = $db->insert($newProgram, 'Program');

		if ($newCategoryId) $success[] = 'New program added';
	}
}


$categories = $db->fetchRows("SELECT id, name FROM Category ORDER BY weight ASC");

$headers[] = '<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>';

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
								      	<ul class="program-sortable" id="sortable">
								      		<?php
								      			$programsForCategory = $db->fetchRows("SELECT id, name FROM Program WHERE category = ?", array($category['id']));

								      			foreach ($programsForCategory as $programData) {
								      				echo '<li class="ui-state-default" data-category="' . $programData['id'] . '">
								      						<div class="panel radius">
								      						<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>' 
								      						. $programData['name'] . 
								      					'</div></li>';
								      			}
								      		?>
										</ul>
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
		    	<legend>新增節目</legend>

		    	<div class="row">
			    	<div class="large-12 columns">
				      	<select name="program[category]" class="medium">
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
			        <input type="text" name="program[name]" placeholder="節目名稱">
			      </div>
			    </div>
			    
			    <div class="row">
			      	<div class="large-12 columns">
			        	<textarea name="program[description]" placeholder="節目描述"></textarea>
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
<script>
	$(document).foundation('section');

	$(function() {
	    $( ".program-sortable" ).sortable();
	    $( ".program-sortable" ).disableSelection();
  	});
</script>
<?php
$scripts = array();

include $_SERVER['DOCUMENT_ROOT'] . '/admin/templates/footer.template.php';
?>