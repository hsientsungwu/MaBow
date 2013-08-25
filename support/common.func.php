<?php

function clean_input($var) {

	if (is_array($var)) {
		foreach ($var as $index => $string) {
			$var[$index] = clean_input($string);
		}
	} else {
		$var = trim($var);
		$var = htmlspecialchars($var);
	}
		
	return $var;
}

// $content = array('subject' => '', 'body' => '');
function send_email($content) {	
	global $ADMIN_EMAIL, $SYSTEM_EMAIL;
	// subject
	$subject = '[Mabow] ' . $content['subject'];
	$body = $content['body'];

	// message
	$message = "
	<html>
		<head>
	  		<title>{$subject}</title>
		</head>
		<body>
	  		<p>{$body}</p>
		</body>
	</html>
	";

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= 'To: HTWU Webmaster<' . $ADMIN_EMAIL . '>' . "\r\n";
	$headers .= 'From: HTWU No-Reply Message<' . $SYSTEM_EMAIL . '>' . "\r\n";

	// Mail it
	$result = mail($to, $subject, $message, $headers);
}

function translateIntoTraditionalChinese($str) {
	$translated_string = iconv("BIG5","UTF-8",iconv("gb2312","BIG5",iconv("UTF-8","gb2312", $str)));

	return $translated_string;
}