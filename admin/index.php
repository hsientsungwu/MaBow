<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$total_videos_count = $db->fetchCell("SELECT COUNT(id) FROM Video");
$last_cron_job = $db->fetchCell("SELECT date FROM Log WHERE type = ? ORDER BY id DESC", array(LogType::CRON));
$programs_count = $db->fetchCell("SELECT COUNT(id) FROM Program");
$channels_count = $db->fetchCell("SELECT COUNT(id) FROM Channel");
$total_views_count = $db->fetchCell("SELECT SUM(view) FROM Video");

include $_SERVER['DOCUMENT_ROOT'] . '/admin/templates/header.template.php';
?>
<div class="row">
	<div class="large-12 large-centered columns">
		<div class="row">
			<div class="large-8 large-centered columns">	
				<table class="stat-table">
				  	<thead>
					    <tr>
					    	<th width="200">統計名稱</th>
					      	<th width="500">統計數字</th>
					    </tr>
				  	</thead>
				  	<tbody>
				  		<tr>
					      	<td>CRON 工作</td>
					      	<td><?php echo $last_cron_job; ?></td>
					    </tr>
					    <tr>
					      	<td>影片總數</td>
					      	<td><?php echo ($total_videos_count ? $total_videos_count : '0'); ?> 影片</td>
					    </tr>					    
					    <tr>
					      	<td>節目數量</td>
					      	<td><?php echo $programs_count; ?> 節目</td>
					    </tr>
					    <tr>
					      	<td>頻道數量</td>
					      	<td><?php echo $channels_count; ?> </td>
					    </tr>
					    <tr>
					      	<td>總觀看量</td>
					      	<td><?php echo $total_views_count; ?> 次</td>
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