<?php require $_SERVER['DOCUMENT_ROOT'] . '/config.php'; ?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/header.template.php'); ?>

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

<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/footer.template.php'); ?>