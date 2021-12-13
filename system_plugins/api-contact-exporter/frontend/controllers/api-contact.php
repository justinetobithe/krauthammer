<?php
class ApiContact extends Controller{
  function __construct(){
    Session::handleLogin();
    parent::__construct();
  }
  function index(){
    header_json(); print_r("API Contact"); exit();
  }
}