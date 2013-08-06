<?php

include $_SERVER['DOCUMENT_ROOT'] . '/admin/templates/header.template.php';
?>
<div class="row">
	<div class="large-12 large-centered columns">
		<div class="row">
			<div class="large-12 large-centered columns">	
				<table>
				  	<thead>
					    <tr>
					    	<th width="100">ID</th>
					      	<th width="200">Name</th>
					      	<th width="300">Description</th>
					      	<th width="100">Action</th>
					    </tr>
				  	</thead>
				  	<tbody>
					    <tr>
					      	<td>Content Goes Here</td>
					      	<td>Content Goes Here</td>
					      	<td>This is longer content Donec id elit non mi porta gravida at eget metus.</td>
					      	<td>Edit | Delete</td>
					    </tr>
					    <tr>
					      	<td>Content Goes Here</td>
					      	<td>Content Goes Here</td>
					      	<td>This is longer Content Goes Here Donec id elit non mi porta gravida at eget metus.</td>
					      	<td>Edit | Delete</td>
					    </tr>
					    <tr>
					      	<td>Content Goes Here</td>
					      	<td>Content Goes Here</td>
					      	<td>This is longer Content Goes Here Donec id elit non mi porta gravida at eget metus.</td>
					      	<td>Edit | Delete</td>
					    </tr>
				  	</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="large-12 large-centered columns">
		<form>
		  	<fieldset>
		    	<legend>Add Category</legend>

			    <div class="row">
			      <div class="large-12 columns">
			        <input type="text" placeholder="Category Name">
			      </div>
			    </div>
			    
			    <div class="row">
			      	<div class="large-12 columns">
			        	<textarea placeholder="Category Description"></textarea>
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

<?php
$scripts = array(
  '<script src="/js/admin.js" ></script>',
);

include $_SERVER['DOCUMENT_ROOT'] . '/admin/templates/footer.template.php';
?>