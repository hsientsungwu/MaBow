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
	$headers .= 'To: Steve Wu<hsientsungwu@gmail.com>' . "\r\n";
	$headers .= 'From: Mabow System Message<no-reply@latteblog.com>' . "\r\n";

	// Mail it
	mail($to, $subject, $message, $headers);
}