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

<div data-role="header">
    <a href="<?php echo $backurl; ?>" data-icon="back" data-transition="slide" data-direction="reverse">上一頁</a>
    <h1><?php echo $pageTitle; ?></h1>
</div>
<div>
    <iframe width="420" height="345"src="http://www.youtube.com/embed/<?php echo $videoId; ?>"></iframe>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/footer.template.php'); ?>