<?php

require 'config.php';

foreach(glob(LIBS.'*.php') as $class){
    require_once ROOT.$class;
}
 require_once ROOT.'assets/include/tcpdf/tcpdf.php';
 
Permission::initialize();
$url= new Url();
$url->initialize();