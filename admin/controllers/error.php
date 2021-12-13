<?php


class Error extends Controller{
	function __construct(){
		parent::__construct();
	}

	function index($header_status_code = '404'){
		Session::handleLogin();
		cms_header($header_status_code);

    $this->view->render('header');
    if ($header_status_code == '401') {
			$this->view->render('error/401');
    }else{
			$this->view->render('error/index');
    }
		$this->view->render('footer');
	}
    
}
