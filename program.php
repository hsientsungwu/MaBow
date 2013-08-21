<?
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_GET['id']) {
	$videos = $db->fetchRows("SELECT * FROM Video WHERE program = ? ORDER BY date DESC", array($_GET['id']));

	$pageTitle = $db->fetchCell("SELECT name FROM Program WHERE id = ?", array($_GET['id']));
}
?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/header.template.php'); ?>

<div data-role="header">
    <a href="category.php?id=<?php echo $_GET['category']; ?>" data-icon="back" data-transition="slide" data-direction="reverse">上一頁</a>
    <h1><?php echo $pageTitle; ?></h1>
</div>
<ul data-role="listview" data-inset="true">
    <?php
    foreach ($videos as $video) {
      echo '<li><a href="video.php?id=' . $video['id'] . '&category=' . $_GET['category'] . '" data-transition="slide" data-inline="true">' . $video['name'] . '</a></li>';
    }
    ?>
</ul>
  
<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/footer.template.php'); ?>