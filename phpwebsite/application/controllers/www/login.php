<?php

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('www/user_model', 'user');
    }

    public function index() {
        $error = "";
        $data['error'] = $error;
        if (!empty($_POST['user'])) {
            $data['login_name'] = $_POST['user']["login_name"];
            $data['password'] = $_POST['user']["password"];
            $data['orgcode'] = $_POST['user']["orgcode"];
            $data['login_sys'] = $_POST['user']["login_sys"];
            //验证登录
            $data['error'] = $this->user->CheckLogin($_POST['user']["login_sys"], $_POST['user']['login_name'], $_POST['user']['password'], "ShopEx");
        }
        $this->load->view('www/login/index', $data);
    }

    public function logout() {
        $this->load->library('session');
        $this->session->sess_destroy();
        $this->index();
    }

}

?>
