<?php
/**
 * Created by PhpStorm.
 * User: 29308
 * Date: 2017/10/28
 * Time: 18:21
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->load->model('Filter_model');
    }

    public function index()
    {
        $data['usermessage'] = $this->User_model->check($_SESSION['userid']);
        $data['ordermessage'] = $this->Order_model->check($_SESSION['userid']);
        $data['machine_name'] = $this->Order_model->select_name();
        $this->load->view('foreground/user_message.html', $data);
    }

    public function transmit()
    {
        $machinename_json = $this->input->post('machine');

        $filter_array = $this->Filter_model->select_filter($machinename_json);
        for ($i = 1; $i <= 6; $i++)
        {
            $filter = "filter";
            $filter = $filter."$i";
            if($filter_array[0][$filter] != -1)
            {
                $filter_array[0][$filter] = $this->Filter_model->select_name($filter_array[0][$filter]);
            }
            else
            {
                $filter_array[0][$filter] = null;
            }
        }

        for ($i = 1; $i <= 6; $i++)
        {
            $filter = "filter";
            $filter = $filter."$i";
            $lefttime = "changetime";
            $lefttime = $lefttime."$i";
            $lefttime_percentage = 'lefttime_percentage';
            $lefttime_percentage = $lefttime_percentage."$i";
            if($filter_array[0][$lefttime] != 0)
            {
                $life = array();
                $life[$i] = $this->Filter_model->select_life($filter_array[0][$filter]);

                $filter_array[0][$lefttime_percentage] = $this->Filter_model->select_percentage($life[$i], $filter_array[0][$lefttime]);
            }
            else
            {
                $filter_array[0][$lefttime_percentage] = 0;
            }
        }

        for ($i = 1; $i <= 6; $i++)
        {
            $filter = "filter";
            $filter = $filter."$i";
            $lefttime = "changetime";
            $lefttime = $lefttime."$i";
            if($filter_array[0][$lefttime] != 0)
            {
                $life = array();
                $life[$i] = $this->Filter_model->select_life($filter_array[0][$filter]);

                $filter_array[0][$lefttime] = $this->Filter_model->select_lefttime($life[$i], $filter_array[0][$lefttime]);
            }
            else
            {
                $filter_array[0][$lefttime] = -1;
            }
        }

        $filter_json = json_encode($filter_array);
        echo $filter_json;
    }
}