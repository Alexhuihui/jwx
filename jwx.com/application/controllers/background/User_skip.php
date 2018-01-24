<?php
/**
 * Created by PhpStorm.
 * User: 29308
 * Date: 2017/10/21
 * Time: 19:24
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class User_skip extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function user_edit(){
        life();
        $userid = $this->uri->segment(4);
        $data['user'] = $this->User_model->check($userid);
        $data['segment'] = $this->uri->segment(5);
        $this->load->view('background/user_edit.html',$data);
    }

    public function user_login(){
        life();
        $this->load->view('background/user_login.html');
    }
}