<?php 
session_start();

/* CHECKS PROTOCOL */
define('URL_PROTOCOL', isset($_SERVER['HTTPS']) ? 'https' : 'http');
define('DB_HOST','localhost');
define('DB_USER','jusmaxdy_justine');
define('DB_PASS','justine-1755');
define('DB_NAME','jusmaxdy_krauthammer_sitecms');

define('URL', URL_PROTOCOL.'://jus.maxdyna.com/krauthammer/');
define('ROOT', realpath(dirname(__FILE__) . '/') . '/');
define('LIBS','libraries/');
define('PLUGINS',ROOT.'plugins/');
define('ACTIVE_THEME','krauthammer');
define('PATH',__DIR__.'/../assets/');

