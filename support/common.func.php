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
	$subject = $content['subject'];
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
	$headers .= 'To: Steve Wu<' . $ADMIN_EMAIL . '>' . "\r\n";
	$headers .= 'From: Mabow System Message<' . $SYSTEM_EMAIL . '>' . "\r\n";
var_dump($headers);
	// Mail it
	mail($to, $subject, $message, $headers);
}