<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

// initialize YouTube client
$GoogleClient = new Google_Client();
$GoogleClient->setDeveloperKey($MABOW_GOOGLEDEVELOPER_KEY);
$YouTube = new Google_YoutubeService($GoogleClient);

/*
YouTube_Channels = array('id', 'channel_id', 'channel_name', 'upload_list_id', 'status');
YouTube_Programs = array('id', 'name', 'updated_time_type');
YouTube_Channel_Program = array('id', 'program', 'channel', 'status');
YouTube_Videos = array('id', 'video_id', 'title', 'description', 'program', 'channel', 'uploaded_time', 'status');

Users = array('id', 'fname', 'lname', 'name', 'facebook_id', 'registered_time', 'lastlogin_time');
Users_Lists = array('id', 'name', 'type', 'added_time', 'lastmodified_time');
Users_List_Sort = array('id' ,'list_id', 'user', 'entity', 'entityType', 'weight');

*/
try {

  $uploadsListId = 'UU1b8xc89zjg7S-VJiKfidPQ';
  $apiContent = 'snippet, contentDetails';
  $apiParams = array(
    'playlistId' => $uploadsListId,
    'maxResults' => 50
  );

    $playlistItemsResponse = $YouTube->playlistItems->listPlaylistItems($apiContent, $apiParams);

} catch (Google_ServiceException $e) {
  $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
} catch (Google_Exception $e) {
  $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
}

$title = 'MaBow - Video List';

include $_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/header.template.php';


if (count($playlistItemsResponse['items'])) {
    foreach ($playlistItemsResponse['items'] as $video) {
    ?>
        <div class="row">
            <div class="large-12 large-centered columns">
                <div class="panel">
                    <h4><?php echo $video['snippet']['title']; ?></h4>
                    <div class="flex-video">
                      <iframe width="560" height="315" src="//www.youtube.com/embed/<?php echo $video['snippet']['resourceId']['videoId']; ?>" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
}


$scripts = array(
  '<script src="/js/admin.js" ></script>',
);

include $_SERVER['DOCUMENT_ROOT'] . '/frontend/templates/footer.template.php';
?>