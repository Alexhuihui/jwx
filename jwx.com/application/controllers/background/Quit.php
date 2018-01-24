<?php
/**
 * Created by PhpStorm.
 * User: 29308
 * Date: 2017/10/21
 * Time: 20:25
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Quit extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        session_start();
    }

    public function index(){
        session_destroy();
        redirect('background/login/index');
    }
}