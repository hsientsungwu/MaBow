<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_POST['category']) {
	$category = $_POST['category'];

	if ($category['name'] == '' || $category['description'] == '') {
		$errors[] = "Category name / descriptions cannot be empty";
	} else {
		$newCategory = array(
			'name' => $category['name'],
			'description' => $category['description']
		);

		$newCategoryId = $db->insert($newCategory, 'Category');

		if ($newCategoryId) $success[] = 'New category added';
	}
}

$categories = $db->fetchRows("SELECT id, name, description FROM Category");

include $_SERVER['DOCUMENT_ROOT'] . '/admin/templates/header.template.php';
?>
<div class="row">
	<div class="large-12 large-centered columns">
		<div class="section-container tabs" data-section="tabs">
			<section>
			    <p class="title" data-section-title><a href="#">Data</a></p>
			    <div class="content" data-section-content>
			      	<div class="row">
						<div class="large-12 large-centered columns">	
							<table>
							  	<thead>
								    <tr>
								    	<th width="100">ID</th>
								      	<th width="300">Name</th>
								      	<th width="450">Description</th>
								      	<th width="100">Action</th>
								    </tr>
							  	</thead>
							  	<tbody>
							  		<?php
							  			foreach ($categories as $data) {
							  				echo "<tr>";
							  				echo "<td>" . $data['id'] . "</td>";
							  				echo "<td>" . $data['name'] . "</td>";
							  				echo "<td>" . $data['description'] . "</td>";
							  				echo "<td>Edit | Delete</td>";
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
			    <p class="title" data-section-title><a href="#">Sort</a></p>
			    <div class="content" data-section-content>
			      	<p>Content of section 2.</p>
			    </div>
			</section>
		</div>
	</div>
</div>

<div class="row">
	<div class="large-12 large-centered columns">
		<form action="/admin/category.php" method="POST">
		  	<fieldset>
		    	<legend>Add Category</legend>

			    <div class="row">
			      <div class="large-12 columns">
			        <input type="text" name="category[name]" placeholder="Category Name">
			      </div>
			    </div>
			    
			    <div class="row">
			      	<div class="large-12 columns">
			        	<textarea name="category[description]" placeholder="Category Description"></textarea>
			      	</div>
			    </div>

			    <div class="row">
			    	<div class="large-12 columns">
			    		<ul class="inline-list right">
						  	<li><input type="submit" class="small radius button" value="Add"/></li>
							<li><input type="reset" class="small radius button" value="Cancel"/></li>
						</ul>
			    	</div>
			    </div>

		  	</fieldset>
		</form>
	</div>
</div>
<script>
	$(document).foundation('section');
</script>
<?php
$scripts = array();

include $_SERVER['DOCUMENT_ROOT'] . '/admin/templates/footer.template.php';
?>