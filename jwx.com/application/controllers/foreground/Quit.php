<?php
/**
 * Created by PhpStorm.
 * User: 29308
 * Date: 2017/10/28
 * Time: 18:40
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Quit extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        session_start();
    }

    public function index(){
        session_destroy();
        redirect('foreground/login/index');
    }
}