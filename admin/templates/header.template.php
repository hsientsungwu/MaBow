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
		<script src="/js/vendor/custom.modernizr.js"></script>
		<script src="/js/foundation.min.js"></script>

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
							<li><a href="#">節目</a></li>
							<li><a href="#">YouTube 頻道</a></li>
							<li><a href="/admin/index.php">統計</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>