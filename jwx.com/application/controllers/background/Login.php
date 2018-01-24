<?php
/**
 * Created by PhpStorm.
 * User: 29308
 * Date: 2017/10/20
 * Time: 21:35
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index () {
        $this->load->view('background/login.html');
    }

    public function login () {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('telnum', '电话号码', 'required|exact_length[11]|trim');
        $this->form_validation->set_rules('passwd', '密码', 'required|trim');
        $flag = $this->form_validation->run();

        if($flag)
        {
            $_SESSION['tel'] =  $this->input->post('telnum');
            $data = array(
                'telnum' => $this->input->post('telnum'),
                'passwd'=> $this->input->post('passwd')
            );

            $row = $this->User_model->get();

            if($row[0]['telnum'] == $data['telnum'] && $row[0]['passwd'] == $data['passwd'])
            {
                success_no('background/menu');
            }
            else
            {
                error('登录失败，用户名密码不正确！');
            }
        }
        else
        {
            $this->load->view('background/login.html');
        }
    }
}