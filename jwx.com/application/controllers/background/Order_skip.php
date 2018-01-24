<?php
/**
 * Created by PhpStorm.
 * order: 29308
 * Date: 2017/10/23
 * Time: 12:46
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_skip extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Order_model');
        $this->load->model('Machine_model');
        $this->load->model('Filter_model');
        $this->load->model('User_model');
    }

    public function order_edit()
    {
        $orderid = $this->uri->segment(4);
        $data['user'] = $this->Order_model->check($orderid);
        for($i = 1; $i <= 6; $i++)
        {
            $filter_name = "name";
            $filter_id = "filter";
            $filter = $filter_id."$i";
            $name = $filter_name."$i";
            if($data['user'][0][$filter] != -1)
            {
                $data['filter'][0][$name] = $this->Filter_model->select_name($data['user'][0][$filter]);
            }
        }
        life();
        $data['segment'] = $this->uri->segment(5);
        $this->load->view('background/order_edit.html',$data);
    }

    public function order_add()
    {
        $data['machine'] = $this->Machine_model->select_all();
        $data['filter'] = $this->Filter_model->select_all();
        life();
        $this->load->view('background/order_add.html', $data);
    }

    public function order_details()
    {
        $orderid = $this->uri->segment(4);
        $data['details'] = $this->Order_model->check($orderid);
        $data['user_message'] = $this->User_model->check($data['details'][0]['userid']);

        for($i = 1; $i <= 6; $i++)
        {
            $filter_name = "name";
            $filter_id = "filter";
            $filter = $filter_id."$i";
            $name = $filter_name."$i";
            if($data['details'][0][$filter] != -1)
            {
                $data['filter'][0][$name] = $this->Filter_model->select_name($data['details'][0][$filter]);
            }
        }

        for($i = 1; $i <= 6; $i++)
        {
            $filter_name = "name";
            $filter_id = "filter";
            $filter_life = "life";
            $filter = $filter_id."$i";
            $name = $filter_name."$i";
            $life = $filter_life."$i";
            if($data['details'][0][$filter] != -1)
            {
                $data['filter'][0][$life] = $this->Filter_model->select_life($data['filter'][0][$name]);
            }
        }

        for($i = 1; $i <= 6; $i++)
        {
            $filter_name = "name";
            $filter_id = "filter";
            $filter_life = "life";
            $filter_lefttime = "lefttime";
            $filter_changetime = "changetime";
            $changetime = $filter_changetime."$i";
            $lefttime = $filter_lefttime."$i";
            $filter = $filter_id."$i";
            $name = $filter_name."$i";
            $life = $filter_life."$i";
            if($data['details'][0][$changetime] != 0)
            {
                $data['filter'][0][$lefttime] = $this->Filter_model->select_lefttime($data['filter'][0][$life], $data['details'][0][$changetime]);
            }
        }

        life();
        $this->load->view('background/order_details.html',$data);
    }
}