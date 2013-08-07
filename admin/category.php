<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_POST['category']) {
	$category = $_POST['category'];

	if ($category['name'] == '' || $category['description'] == '') {
		$errors[] = "Category name / descriptions cannot be empty";
	} else {
		$lastWeight = $db->fetchCell("SELECT weight FROM Category ORDER BY weight DESC");

		$newCategory = array(
			'name' => $category['name'],
			'description' => $category['description'],
			'weight' => $lastWeight+1,
		);

		$newCategoryId = $db->insert($newCategory, 'Category');

		if ($newCategoryId) $success[] = 'New category added';
	}
}

$categories = $db->fetchRows("SELECT id, name, description FROM Category ORDER BY weight ASC");

$headers[] = '<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>';

include $_SERVER['DOCUMENT_ROOT'] . '/admin/templates/header.template.php';
?>
<div class="row">
	<div class="large-12 large-centered columns">
		<div class="section-container tabs" data-section="tabs">
			<section>
			    <p class="title" data-section-title><a href="#">種類細節</a></p>
			    <div class="content" data-section-content>
			      	<div class="row">
						<div class="large-12 large-centered columns">	
							<table>
							  	<thead>
								    <tr>
								    	<th width="100">ID</th>
								      	<th width="300">名稱</th>
								      	<th width="450">描述</th>
								      	<th width="100">功能列</th>
								    </tr>
							  	</thead>
							  	<tbody>
							  		<?php
							  			foreach ($categories as $data) {
							  				echo "<tr>";
							  				echo "<td>" . $data['id'] . "</td>";
							  				echo "<td>" . $data['name'] . "</td>";
							  				echo "<td>" . $data['description'] . "</td>";
							  				echo "<td>修改 | 移除</td>";
							  				echo "</tr>";
							  			}
							  		?>
							  	</tbody>
							</table>
						</div>
					</div>
			    </div>
			</section>
			<section>
			    <p class="title" data-section-title><a href="#">種類順序</a></p>
			    <div class="content" data-section-content>
			    	
					<div class="row">
						<div class="large-12 large-centered columns">
					      	<ul class="category-sortable" id="sortable">
					      		<?php
					      			foreach ($categories as $data) {
					      				echo '<li class="ui-state-default" data-category="' . $data['id'] . '">
					      						<div class="panel radius">
					      						<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>' 
					      						. $data['name'] . 
					      					'</div></li>';
					      			}
					      		?>
							</ul>
						</div>
			    </div>
			</section>
		</div>
	</div>
</div>

<div class="row">
	<div class="large-12 large-centered columns">
		<form action="/admin/category.php" method="POST">
		  	<fieldset>
		    	<legend>新增種類</legend>

			    <div class="row">
			      <div class="large-12 columns">
			        <input type="text" name="category[name]" placeholder="種類名稱">
			      </div>
			    </div>
			    
			    <div class="row">
			      	<div class="large-12 columns">
			        	<textarea name="category[description]" placeholder="種類描述"></textarea>
			      	</div>
			    </div>

			    <div class="row">
			    	<div class="large-12 columns">
			    		<ul class="inline-list right">
						  	<li><input type="submit" class="small radius button" value="新增種類"/></li>
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
	    $( "#sortable" ).sortable();
	    $( "#sortable" ).disableSelection();
  	});
</script>
<?php
$scripts = array();

include $_SERVER['DOCUMENT_ROOT'] . '/admin/templates/footer.template.php';
?>