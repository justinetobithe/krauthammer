<?php 
session_start();
/* CHECKS PROTOCOL */
define('URL_PROTOCOL', isset($_SERVER['HTTPS'] ? 'https' : 'http');
define('DB_HOST','');
define('DB_USER','');
define('DB_PASS','');
define('DB_NAME','');
define('URL', URL_PROTOCOL.'://hostname/');
define('ROOT', realpath(dirname(__FILE__) . '/') . '/');
define('LIBS','libraries/');
define('PLUGINS',ROOT.'plugins/');
define('ACTIVE_THEME','default');
define('PATH',__DIR__.'/../assets/');

