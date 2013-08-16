<?php

function errorHandler($number, $string, $file, $line, $context) {
	$skip = array(E_NOTICE, E_STRICT);
	if(in_array($number, $skip))
		return true;
	$types = array(
		E_USER_WARNING => 'Warning',
		E_WARNING => 'Warning',
		E_NOTICE => 'Notice',
		E_USER_ERROR => 'Error',
		E_USER_WARNING => 'Warning',
		E_USER_NOTICE => 'Notice',
		E_STRICT => 'Strict',
		E_RECOVERABLE_ERROR => 'Recoverable Error',
		E_DEPRECATED => 'Deprecated',
		E_USER_DEPRECATED => 'Deprecated'
	);
	
	$syslogMessage = "Type: " . $types[ $number ] . " Message: " . $string;

	// don't log context
	send_data(LOG_ERR, $syslogMessage);
	
	$message = 	"Type: " . $types[ $number ] . 
				"\nMessage: " . $string . 
				"\nHost:" . $_SERVER['HTTP_HOST'] . 
				"\nFile: " . $file . 
				"\nLine: " . $line;
	
	$a = print_r(debug_backtrace(), true);
	$b = preg_replace('/([^0-9]|^)[0-9]{9,17}([^0-9]|$)/', '$1OMIT$2', $a);
	$b = preg_replace('/([^0-9]|^)[0-9]{3,4}([^0-9]|$)/', '$1OMIT$2', $b);	
	$message .= "\nStack: " . $b;

	// clean credit card, bank account, cvv, expiration numbers
	$a = print_r($context, true);
	$b = preg_replace('/([^0-9]|^)[0-9]{9,17}([^0-9]|$)/', '$1OMIT$2', $a);
	$b = preg_replace('/([^0-9]|^)[0-9]{3,4}([^0-9]|$)/', '$1OMIT$2', $b);	
	$message .= "\nContext: " . $b;
	
	var_dump($message);
	
	// don't die if error type is in the following array
	$skip = array(E_USER_NOTICE, E_RECOVERABLE_ERROR, E_DEPRECATED, E_STRICT, E_USER_DEPRECATED, E_WARNING);
	
	if (in_array($number, $skip)) return true;
	
	die('An error occurred. The administrator has been notified.');
}

function exceptionHandler($e) {
	$trace = $e->getTrace();

	$message = "Server: ".$_SERVER['SERVER_NAME']."\n";
	$message .= "Uncaught ".get_class($e)." thrown\n";
	$message .= "Message: ".$e->getMessage()."\n\n";
 	$message .= "File: " . $e->getFile() . " on line: " . $e->getLine()."\n\n";
	$message .= ($trace[0]['class'] != '') ? $trace[0]['class']."->" : '';
	$message .= $trace[0]['function']."()";

	$a = $e->getTraceAsString();
	$b = preg_replace('/([^0-9]|^)[0-9]{9,17}([^0-9]|$)/', '$1OMIT$2', $a);
	$b = preg_replace('/([^0-9]|^)[0-9]{3,4}([^0-9]|$)/', '$1OMIT$2', $b);	
	$message .= "Trace:\n".$b;

	var_dump($message);
}