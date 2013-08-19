<?php
// $content = array('source' => '', 'message' => '');
function logThis($content, $type = LogType::ERROR) {
	global $db;

	$newLog = array(
		'type' => $type,
		'content' => json_encode($content),
	);

	$affected = $db->insert($newLog, 'Log');

	$emailContent['subject'] = LogType::getLogTypeText($type) . " from {$content['source']}";

	$emailContent['body'] = print_array_recursive($content['message']);

	send_email($emailContent);

	return ($affected) ? true : false;
}

function print_array_recursive($data) {
	$content = '';

	if (is_array($data)) {
		$content .= "<ul>";
		foreach ($data as $index => $row) {
			$content .= "<b>{$index}</b>" . print_array_recursive($row);
		}
		$content .= "</ul>";
	} else {
		$content = "<li>" . $data . "</li>";
	}

	return $content;
}