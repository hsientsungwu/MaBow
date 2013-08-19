<?php
mb_internal_encoding('utf-8');
header("Content-Type: text/html; charset=utf-8");

// configuration
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/master_config.php');

// special functions

// common functions
require_once $_SERVER['DOCUMENT_ROOT'] . '/support/Google/Google_Client.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/support/Google/contrib/Google_YouTubeService.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/support/enum.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/support/common.func.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/support/error.handler.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/support/log.func.php';

function __autoload($className) {
	$configRoot = $_SERVER['DOCUMENT_ROOT'] . '/config/';
	$classRoot = $_SERVER['DOCUMENT_ROOT'] . '/class/';
	$supportRoot = $_SERVER['DOCUMENT_ROOT'] . '/support/';

	// classes
	$class['dbMysqli']    	 = $classRoot . 'dbMysqli.class.php';
	$class['Facebook']       = $supportRoot . 'Facebook/facebook.php';

    if (file_exists($class[$className])) {
          require_once $class[$className]; 
          return true; 
    } 
      
    return false; 
}

// initialize mysql settings
$mysql = new MysqlSetting();

// initialize mysqli class
$db = new dbMysqli();
$db->open($mysql->database, $mysql->username, $mysql->password, $mysql->host);
$db->execute("SET CHARACTER SET utf8");

// initalize facebook class
$fbSetting = new FacebookSetting();

$fb = new Facebook(
	array(
  		'appId'  => $fbSetting->appId,
  		'secret' => $fbSetting->appSecret,
	)
);

// set default time zone
date_default_timezone_set('America/New_York');

// set error/exception handlers
set_error_handler('errorHandler');
set_exception_handler('exceptionHandler');