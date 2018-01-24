<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Machine_skip extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form'));
        $this->load->model('machine_model');
    }

    public function index()
    {

    }

    public function edit()
    {
        $this->load->view('background/machine_upload.html');
    }

    public function update($machine_id , $segment)
    {
        if($machine_id == NULL || $machine_id == '')
        {
            echo '<script>';
            echo "alert('净水器编号不能为空！');";
            echo 'top.location="' . site_url() . '/background/machine/display";';
            echo '</script>';
        }

        else if( ! $this->machine_model->machine_is_exists($machine_id))
        {
            echo '<script>';
            echo "alert('该净水器不存在！');";
            echo 'top.location="' . site_url() . '/background/machine/display";';
            echo '</script>';
        }
        else
        {
            $this->load->view('background/machine_update.html' , array('row' => $this->machine_model->select_by_id($machine_id) , 'segment' => $segment));
        }
    }
}