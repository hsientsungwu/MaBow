<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
?>

<html>
  <head>
    <title>媽寶 - 線上影音</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
  </head>
  <body>
    <div data-role="header">
        <h1>媽寶 - 線上影音</h1>
    </div>
    <ul data-role="listview" data-inset="true">
        <?php
        $categories = $db->fetchRows("SELECT id, name FROM Category ORDER BY weight ASC");

        foreach ($categories as $category) {
          echo '<li><a href="category.php?id=' . $category['id'] . '" data-transition="slide" data-inline="true">' . $category['name'] . '</a></li>';
        }
        ?>
    </ul>
  </body>
</html>