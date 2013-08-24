<?
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_GET['id']) {
	$programs = $db->fetchRows("SELECT * FROM Program WHERE category = ? ORDER BY weight ASC", array($_GET['id']));

	$pageTitle = $db->fetchCell("SELECT name FROM Category WHERE id = ?", array($_GET['id']));
}
?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/header.template.php'); ?>

<div class="cl-bar-title">
    <a href="/index.php" class="cl-btn" data-transition="slide-out"><i class="icon-app-arrow-left"></i>Back</a>
    <h1 class="cl-title"><?php echo $pageTitle; ?></h1>
</div>
<div class="cl-content">
    <div class="cl-table">
        <?php
        foreach ($programs as $program) {
            $video_count = $db->fetchCell("SELECT COUNT(id) FROM Video WHERE program = ?", array($program['id']));

          echo '<div class="cl-table-cell">
                <a href="program.php?id=' . $program['id'] . '&category=' . $_GET['id']. '" >
                    <span class="label">' . $program['name'] . '</span>
                    <span class="count">' . $video_count . '</span><i class="icon icon-ios-arrow-right"></i>
                    </a></div>';
        }
        ?>
    </div>
</div>
        
  
<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/footer.template.php'); ?>