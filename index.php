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

if (count($playlistItemsResponse['items'])) {
    foreach ($playlistItemsResponse['items'] as $video) {
        var_dump($video['snippet']['title']);
    }
}
  