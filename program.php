<?
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_GET['id']) {
	$videos = $db->fetchRows("SELECT * FROM Video WHERE program = ? ORDER BY title_date DESC", array($_GET['id']));

	$pageTitle = $db->fetchCell("SELECT name FROM Program WHERE id = ?", array($_GET['id']));
}
?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/header.template.php'); ?>


<div class="cl-bar-title">
    <a href="category.php?id=<?php echo $_GET['category']; ?>" class="cl-btn" data-transition="slide-out"><i class="icon-app-arrow-left"></i>Back</a>
    <h1 class="cl-title"><?php echo $pageTitle; ?></h1>
</div>
<div class="cl-content">
    <div class="cl-table">
        <?php
        foreach ($videos as $video) {
            echo '<div class="cl-table-cell">
                    <a href="video.php?id=' . $video['id'] . '&category=' . $_GET['category'] . '" >
                        <span class="label">' . $video['name'] . '</span><i class="icon icon-ios-arrow-right"></i>
                    </a></div>';
        }
        ?>
    </div>
</div>
  
<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/footer.template.php'); ?>