<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filter_skip extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form'));
        $this->load->model('filter_model');
    }

    public function index()
    {

    }

    public function edit()
    {
        $this->load->view('background/filter_upload.html');
    }

    public function update($filter_id , $segment)
    {
        if($filter_id == NULL || $filter_id == '')
        {
            echo '<script>';
            echo "alert('该滤芯编号不存在！');";
            echo 'top.location="' . site_url() . '/background/filter/display";';
            echo '</script>';
        }
        else if( ! $this->filter_model->filter_is_exists($filter_id))
        {
            echo '<script>';
            echo "alert('该滤芯不存在！');";
            echo 'top.location="' . site_url() . '/background/filter/display";';
            echo '</script>';
        }
        else
        {
            $this->load->view('background/filter_update.html' , array('row' => $this->filter_model->select_by_id($filter_id) , 'segment' => $segment));
        }
    }
}