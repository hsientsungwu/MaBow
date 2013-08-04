<?php
mb_internal_encoding('utf-8');
header("Content-Type: text/html; charset=utf-8");

// configuration
require_once($_SERVER['DOCUMENT_ROOT'] . '/config/master_config.php');

// special functions

// common functions
require_once($_SERVER['DOCUMENT_ROOT'] . '/support/facebook.func.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/support/common.func.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/support/search.func.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/support/enum.php');

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