<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_GET['delete']) {
	$db->delete('Video', 'id = ?', array($_GET['delete']));
}

if ($_GET['program']) {
	$videos = $db->fetchRows("SELECT * FROM Video WHERE program = ? ORDER BY title_date DESC", array($_GET['program']));
}

include $_SERVER['DOCUMENT_ROOT'] . '/admin/templates/header.template.php';
?>

<div class="row">
	<div class="large-12 large-centered columns">	
		<table>
		  	<thead>
			    <tr>
			    	<th width="100">ID</th>
			      	<th width="500">影片名稱</th>
			      	<th width="300">影片日期</th>
			      	<th width="100"></th>
			    </tr>
		  	</thead>
		  	<tbody>
		  		<?php
		  			foreach ($videos as $data) {
		  				echo "<tr>";
		  				?>
		  				<?php
		  				echo "<td>" . $data['id'] . "</td>";
		  				echo "<td>" . $data['name'] . "</td>";
		  				echo "<td>" . $data['title_date'] . "</td>";
		  				echo "<td>
		  						<a href='videos.php?program=" . $_GET['program'] . "&delete=" . $data['id'] . "'>移除</a>
		  					</td>";
		  				echo "</tr>";
		  			}
		  		?>
		  	</tbody>
		</table>
	</div>
</div>
