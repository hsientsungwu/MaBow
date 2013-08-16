<?
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_GET['id']) {
	$programs = $db->fetchRows("SELECT * FROM Program WHERE category = ? ORDER BY weight ASC", array($_GET['id']));

	$title = $db->fetchCell("SELECT name FROM Category WHERE id = ?", array($_GET['id']));
}

?>

<html>
  <head>
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
  </head>
  <body>
    <div data-role="header">
    	<a href="index.php" data-icon="back" data-transition="slide" data-direction="reverse">上一頁</a>
        <h1><?php echo $title; ?></h1>
    </div>
    <ul data-role="listview" data-inset="true">
        <?php
        foreach ($programs as $program) {
          echo '<li><a href="program.php?id=' . $program['id'] . '&category=' . $_GET['id']. '" data-transition="slide" data-inline="true">' . $program['name'] . '</a></li>';
        }
        ?>
    </ul>
  </body>
</html>