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