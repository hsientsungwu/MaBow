<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_GET['id']) {
	$videoInfo = $db->fetchRow("SELECT * FROM Video WHERE id = ?", array($_GET['id']));

  $videoId = $videoInfo['video_id'];
  $program = $videoInfo['program'];
  $category = $_GET['category'];
  $backurl = 'program.php?id=' . $program . '&category=' . $category;

  $newView = array(
    'view' => $videoInfo['view']+1
  );

  $db->update($newView, 'Video', 'id = ?', array($videoInfo['id']));

  $pageTitle = $videoInfo['name'];
}
?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/header.template.php'); ?>
<style>
.video-container {
    position: relative;
    padding-bottom: 56.25%;
    padding-top: 30px; height: 0; overflow: hidden;
}
 
.video-container iframe,
.video-container object,
.video-container embed {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
</style>
<div class="cl-device-body">
    <div class="cl-page">
        <div class="cl-bar-title">
            <a href="<?php echo $backurl; ?>" class="cl-btn" data-transition="slide-out">Back</a>
            <h1 class="cl-title"><?php echo $pageTitle; ?></h1>
        </div>
        <div class="cl-content">
            <div class="cl-article">
                <p class="video-container"><iframe src="http://www.youtube.com/embed/<?php echo $videoId; ?>"></iframe></p>
            </div>
        </div>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/footer.template.php'); ?>