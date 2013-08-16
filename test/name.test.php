<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$name = '国光帮帮忙 20130815 这些男人居然有正妹爱！！';
var_dump($name);

echo mb_detect_encoding($name);

//============UTF-8 的 gb2312 轉 UTF-8 的 BIG5 :
$T = iconv("BIG5","UTF-8",iconv("gb2312","BIG5",iconv("UTF-8","gb2312", $name)));

var_dump($T);
 
die;


$name = '國光幫幫忙 2013-08-15 這些男人居然有正妹愛？！';

preg_match("/\d{4}[-.]\d{2}[-.]\d{2}/", $name, $match);

print_r($match);

$name = '國光幫幫忙 20130815 這些男人居然有正妹愛？！';

preg_match("/\d{4}[-.]?\d{2}[-.]?\d{2}/", $name, $match2);

print_r($match2);

$name = '國光幫幫忙 2013.08.15 這些男人居然有正妹愛？！';

preg_match("/\d{4}[-.]\d{2}[-.]\d{2}/", $name, $match3);

print_r($match3);
