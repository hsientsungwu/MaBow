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
}

?>

<html>
  <head>
    <title><?php echo $videoInfo['name']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
  </head>
  <body>
    <div data-role="header">
        <a href="<?php echo $backurl; ?>" data-icon="back" data-transition="slide" data-direction="reverse">上一頁</a>
        <h1><?php echo $title; ?></h1>
    </div>
    <div>
      <iframe width="420" height="345"src="http://www.youtube.com/embed/<?php echo $videoId; ?>"></iframe>
    </div>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/footer.template.php'); ?>