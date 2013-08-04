
<?php

require($_SERVER['DOCUMENT_ROOT'] . '/config/master_config.php');
// Call set_include_path() as needed to point to your client library.
require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_YouTubeService.php';

  /* Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
  Google APIs Console <http://code.google.com/apis/console#access>
  Please ensure that you have enabled the YouTube Data API for your project. */
  $DEVELOPER_KEY = $googleDeveloperKey;

  $client = new Google_Client();
  $client->setDeveloperKey($DEVELOPER_KEY);

  $youtube = new Google_YoutubeService($client);

  try {
   
      $uploadsListId = 'UU1b8xc89zjg7S-VJiKfidPQ';

      $playlistItemsResponse = $youtube->playlistItems->listPlaylistItems('snippet, contentDetails', array(
        'playlistId' => $uploadsListId,
        'maxResults' => 50
      ));

  } catch (Google_ServiceException $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
      htmlspecialchars($e->getMessage()));
  }

echo '<pre>' . print_r($playlistItemsResponse) . '</pre>';
?>