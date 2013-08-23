<html>
	<head>
		<title><?php echo ($title ? $title : '媽寶 - 線上影音'); ?></title>
		<!-- basic stylesheets and scripts -->
		<meta name="viewport" content="width=device-width" />
		<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
		<link rel="icon" href="/img/favicon.ico" type="image/x-icon">
		<link rel="stylesheet" href="/css/normalize.css" />
		<link rel="stylesheet" href="/css/foundation.min.css" />
		<link rel="stylesheet" href="/css/admin.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" ></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<script src="/js/vendor/custom.modernizr.js"></script>
		<script src="/js/foundation.min.js"></script>
		<script src="/js/admin.js"></script>

		<?php
			if (count($headers)) {
				foreach ($headers as $header) {
					echo $header;
				}
			}
		?>
	</head>
	<body>
		<div class="row">
			<div class="large-12 large-centered columns">
				<div class="row">
					<div class="large-7 large-centered columns">
						<ul class="inline-list admin-nav">
							<li><a href="/admin/category.php">種類</a></li>
							<li><a href="/admin/program.php">節目</a></li>
							<li><a href="/admin/channel.php">YouTube 頻道</a></li>
							<li><a href="/admin/special.php">特別節目</a></li>
							<li><a href="/admin/index.php">統計</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<?php 
			if (count($errors) || count($success)) { ?>
				<div class="row notification-container">
					<div class="large-8 large-centered columns">
						<?php 
						if (count($errors)) {
							echo '<div data-alert class="alert-box radius alert">';
							echo implode('<br>', $errors);
						}

						if (count($success)) {
							echo '<div data-alert class="alert-box radius">';
							echo implode('<br>', $success);
						}
						
		  				?>	
		  					<a href="#" class="close">&times;</a>
		  				</div>
					</div>
				</div>

		<?php }