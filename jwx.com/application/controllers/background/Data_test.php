<?php
/**
 * Created by PhpStorm.
 * User: 29308
 * Date: 2017/10/21
 * Time: 19:56
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_test extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->load->model('Machine_model');
        $this->load->model('Filter_model');
        $this->load->helper('string');
    }

    public function index()
    {
        $this->user();
        $this->machine();
        $this->filter();
        $this->order();
    }

    public function user()
    {
        for($i = 1 ; $i <= 500 ; $i++)
        {
            $user_data = array(
                'createtime' => time(),
                'username' => random_string('alnum' , 12),
                'passwd' => random_string('alnum' , 15),
                'telnum' => random_string('nozero' , 11) + $i,
                'genderid' => 2,
                'address' => random_string('nozero' , 10),
                'remark' => random_string('alnum' , 50)
            );

            $this->User_model->insert_user($user_data);
        }
    }

    public function order()
    {
        for($i = 2 ; $i <= 501 ; $i++)
        {
            $order_data = array(
                'machineid' => $i,
                'userid' => $i,
                'buytime' => time(),
                'filter1' => $i,
                'changetime1' => time()
            );

            $this->Order_model->insert_order($order_data);
        }
    }

    public function machine()
    {
        for($i = 2 ; $i <= 501 ; $i++)
        {
            $machine_data = array(
                'machineid' => NULL,
                'machinename' => random_string('alnum' , 50)
            );

            $this->Machine_model->insert_machine($machine_data);
        }
    }

    public function filter()
    {
        for($i = 1; $i <= 600; $i++)
        {
            $filter_data = array(
                'filterid' => NULL,
                'filtername' => random_string('alnum' , 10),
                'life' => 180*24*60*60
            );

            $this->Filter_model->insert_filter($filter_data);
        }
    }
}