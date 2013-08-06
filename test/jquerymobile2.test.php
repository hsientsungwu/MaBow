<html>
	<head>
		<title>Test Jquery Mobile</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
	</head>
	<body>
		<div data-role="header">
			<a href="jquerymobile.test.php" data-icon="back" data-transition="slide" data-direction="reverse">上一頁</a>
		    <h1>媽寶 - 線上影音</h1>
		</div>
		<ul data-role="listview" data-inset="true">
		    <li><a href="video.html" data-transition="slide" data-inline="true">111</a></li>
		    <li><a href="video2.html" data-transition="slide" data-inline="true">222</a></li>
		    <li><a href="#">333</a></li>
		    <li><a href="#">444</a></li>
		</ul>
		<div style="display:none;">
			<iframe id="video" src="//www.youtube.com/embed/b0z8o-kRJ2k?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe>
		</div>
	</body>
</html>