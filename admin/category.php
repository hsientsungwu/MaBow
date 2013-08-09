<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_POST['category']) {
	$category = clean_input($_POST['category']);

	if ($category['name'] == '' || $category['description'] == '') {
		$errors[] = "種類名稱/敘述不能空白！";
	} else {
		$lastWeight = $db->fetchCell("SELECT weight FROM Category ORDER BY weight DESC");

		$newCategory = array(
			'name' => $category['name'],
			'description' => $category['description']
		);

		if (isset($category['id']) && is_numeric($category['id'])) {
			$affected = $db->update($newCategory, 'Category', 'id = ?', array($category['id']));

			if ($affected) $success[] = '種類已更新';
		} else {
			$newCategory['weight'] = $lastWeight+1;
			$affected = $db->insert($newCategory, 'Category');

			if ($affected) $success[] = '種類已新增';
		}

		
	}
} elseif ($_POST['sort']) {
	$categorySort = clean_input($_POST['sort']);

	for ($i = 0; $i < count($categorySort); $i++) {
		$db->update(array('weight' => $i), 'Category', "id = ?", array($categorySort[$i]));
	}

	die('success');
} elseif ($_GET['id']) {
	$_GET['id'] = clean_input($_GET['id']);

	$editCategory = $db->fetchRow("SELECT * FROM Category WHERE id = ?", array($_GET['id']));
} elseif ($_GET['delete']) {
	$_GET['delte'] = clean_input($_GET['delete']);

	$affected = $db->delete("Category", "id = ?", array($_GET['delete']));

	if ($affected) $success[] = "種類已移除";
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
							  				echo "<td>
							  						<a href='category.php?id=" . $data['id'] . "'>修改</a> | 
							  						<a href='category.php?delete=" . $data['id'] . "'>移除</a>
							  					</td>";
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
					      				echo '<li class="ui-state-default" id="' . $data['id'] . '">
					      						<div class="panel radius">
					      						<span class="sort-action"><img src="/img/sort-icon.png" /></span>'
					      						. $data['name'] . 
					      						'<span class="list-action"><img src="/img/edit-icon.png" /><img src="/img/delete-icon.png"/></span>
					      					</div></li>';
					      			}
					      		?>
							</ul>
						</div>
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
		    	<legend><?php echo ($_GET['id'] ? '修改種類' : '新增種類'); ?></legend>
		    	<?php 
		    		if ($_GET['id']) {
		    			echo '<input type="hidden" name="category[id]" value="' . $_GET['id'] . '">';
		    		}
		    	?>	
			    <div class="row">
			      <div class="large-12 columns">
			        <input type="text" name="category[name]" placeholder="種類名稱" value="<?php echo $editCategory['name']; ?> ">
			      </div>
			    </div>
			    
			    <div class="row">
			      	<div class="large-12 columns">
			        	<textarea name="category[description]" placeholder="種類描述"><?php echo $editCategory['description']; ?></textarea>
			      	</div>
			    </div>

			    <div class="row">
			    	<div class="large-12 columns">
			    		<ul class="inline-list right">
						  	<li><input type="submit" class="small radius button" value="<?php echo ($_GET['id'] ? '修改種類' : '新增種類'); ?>"/></li>
							<li><a href="/admin/category.php" class="small radius button">取消</a></li>
						</ul>
			    	</div>
			    </div>

		  	</fieldset>
		</form>
	</div>
</div>
<script>

	$(document).ready( function(){ 
		$(document).foundation('section');
	});

</script>
<?php
$scripts = array();

include $_SERVER['DOCUMENT_ROOT'] . '/admin/templates/footer.template.php';
?>