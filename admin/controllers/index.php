<?php

class Index extends Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        Session::handleLogin();
        $this->view->render('header');
        $this->view->render('index/index');
        $this->view->render('footer');
    }

    function dashboard() {
        $this->view->render('index/dashboard');
    }

    function logout() {
        if (Session::hasSet('fingerprint')) {
            Session::destroy(array('fingerprint', 'user'));

            Session::set('user_logout', "true");

            redirect(URL . 'login');
        } else {
            redirect(URL . 'login');
        }
    }

}
