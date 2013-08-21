<?
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_GET['id']) {
	$programs = $db->fetchRows("SELECT * FROM Program WHERE category = ? ORDER BY weight ASC", array($_GET['id']));

	$pageTitle = $db->fetchCell("SELECT name FROM Category WHERE id = ?", array($_GET['id']));
}
?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/header.template.php'); ?>

<div data-role="header">
	<a href="index.php" data-icon="back" data-transition="slide" data-direction="reverse">上一頁</a>
    <h1><?php echo $pageTitle; ?></h1>
</div>
<ul data-role="listview" data-inset="true">
    <?php
    foreach ($programs as $program) {
      echo '<li><a href="program.php?id=' . $program['id'] . '&category=' . $_GET['id']. '" data-transition="slide" data-inline="true">' . $program['name'] . '</a></li>';
    }
    ?>
</ul>
  
<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/footer.template.php'); ?>