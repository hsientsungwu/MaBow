<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

include $_SERVER['DOCUMENT_ROOT'] . '/admin/templates/header.template.php';
?>
<div class="row">
	<div class="large-12 large-centered columns">
		<div class="row">
			<div class="large-8 large-centered columns">	
				<table>
				  	<thead>
					    <tr>
					    	<th width="100">ID</th>
					      	<th width="150">Name</th>
					      	<th width="200">Description</th>
					    </tr>
				  	</thead>
				  	<tbody>
					    <tr>
					      	<td>Content Goes Here</td>
					      	<td>This is longer content Donec id elit non mi porta gravida at eget metus.</td>
					      	<td>Content Goes Here</td>
					    </tr>
					    <tr>
					      	<td>Content Goes Here</td>
					      	<td>This is longer Content Goes Here Donec id elit non mi porta gravida at eget metus.</td>
					      	<td>Content Goes Here</td>
					    </tr>
					    <tr>
					      	<td>Content Goes Here</td>
					      	<td>This is longer Content Goes Here Donec id elit non mi porta gravida at eget metus.</td>
					      	<td>Content Goes Here</td>
					    </tr>
				  	</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php
$scripts = array(
  '<script src="/js/admin.js" ></script>',
);

include $_SERVER['DOCUMENT_ROOT'] . '/admin/templates/footer.template.php';
?>