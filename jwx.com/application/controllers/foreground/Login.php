<?php
/**
 * Created by PhpStorm.
 * User: 29308
 * Date: 2017/9/19
 * Time: 23:26
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index () {
        $this->load->view('foreground/login.html');
    }

    public function login () {
        $result = $this->User_model->get();
        $telnum = trim($this->input->post('telnum'));
        $data = array(
            'telnum' => trim($this->input->post('telnum')),
            'passwd'=> trim($this->input->post('passwd'))
        );
        $flag = true;
        foreach ($result as $row => $v)
        {
            if($v['telnum'] == $data['telnum'] && $v['passwd'] == $data['passwd'] && $flag)
            {
                $_SESSION['telnum'] =  $telnum;
                $_SESSION['userid'] =  $v['userid'];
                $flag = false;
                success_no('foreground/home', '登录成功');
            }
        }
        if($flag)
        {
            error('登录失败');
        }
    }
}