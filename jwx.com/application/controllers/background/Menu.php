<?php
/**
 * Created by PhpStorm.
 * User: 29308
 * Date: 2017/10/21
 * Time: 15:20
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index (){
        life();
        $this->load->view('background/menu.html');
    }
}