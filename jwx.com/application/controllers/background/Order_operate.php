<?php
/**
 * Created by PhpStorm.
 * order: 29308
 * Date: 2017/10/21
 * Time: 20:53
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_operate extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Order_model');
        $this->load->model('User_model');
    }

    public function display($page_num = NULL)
    {
        $this->load->library('pagination');
        $perPage = 10;

        $total_rows = $this->db->count_all_results('tb_order');
        $config['base_url'] = site_url('background/order_operate/display');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $perPage;
        $config['first_link'] = '第一页';
        $config['prev_link'] = '上一页';
        $config['next_link'] = '下一页';
        $config['last_link'] = '最后一页';
        $config['use_page_numbers'] = TRUE;

        if($page_num == NULL || $page_num == floor($total_rows / 10) + 1 || $page_num == 0)
        {
            $config['num_links'] = 4;
        }
        else if($page_num == '2' || $page_num == floor($total_rows / 10))
        {
            $config['num_links'] = 3;
        }
        else
        {
            $config['num_links'] = 2;
        }

        $this->pagination->initialize($config);

        $data['links'] = $this->pagination->create_links();
        $offset = $this->uri->segment(4);

        $data['order'] = $this->Order_model->get_order($offset, $perPage);
        life();
        $this->load->view('background/order_display.html', $data);
    }

    //回调函数
    public function telnum_check($str)
    {
        $result = $this->Order_model->select_alltelnum();
        $total_rows = $this->db->count_all_results('tb_user');
        $flag = false;

        for($i = 0; $i < $total_rows; $i++)
        {
            if($result[$i]['telnum'] == $str)
            {
                $flag = true;
            }
        }

        if ($flag == false)
        {
            $this->form_validation->set_message('telnum_check', '{field}不存在.');
            return false;
        }
        else
        {
            return true;
        }
    }

    //ajax检查手机号码是否存在
    public function check_tel()
    {
        $telnum = $this->input->post('telnum');
        $result = $this->Order_model->select_alltelnum();
        $total_rows = $this->db->count_all_results('tb_user');
        $flag = false;

        for($i = 0; $i < $total_rows; $i++)
        {
            if($result[$i]['telnum'] == $telnum)
            {
                $flag = true;
            }
        }

        if($flag)
        {
            $result_array[0]['exist'] = 1;
        }
        else
        {
            $result_array[0]['exist'] = 0;
        }

        $result_json = json_encode($result_array);
        echo $result_json;
    }

    public function insert_order(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('telnum', '电话号码', 'trim|required|exact_length[11]');
        $flag = $this->form_validation->run();
        $time = strtotime($this->input->post('buytime'));

        $flag2 = $this->telnum_check($user_tel = trim($this->input->post('telnum')));
        if($flag2 == false)
        {
            success('background/order_skip/order_add', '该手机号不存在，请重新输入');
        }

        if($this->input->post('filter1') != -1)
        {
            $changetime1 = $time;
        }
        else
        {
            $changetime1 = 0;
        }
        if($this->input->post('filter2') != -1)
        {
            $changetime2 = $time;
        }
        else
        {
            $changetime2 = 0;
        }
        if($this->input->post('filter3') != -1)
        {
            $changetime3 = $time;
        }
        else
        {
            $changetime3 = 0;
        }
        if($this->input->post('filter4') != -1)
        {
            $changetime4 = $time;
        }
        else
        {
            $changetime4 = 0;
        }
        if($this->input->post('filter5') != -1)
        {
            $changetime5 = $time;
        }
        else
        {
            $changetime5 = 0;
        }
        if($this->input->post('filter6') != -1)
        {
            $changetime6 = $time;
        }
        else
        {
            $changetime6 = 0;
        }

        if($flag && $flag2)
        {
            $user_tel = trim($this->input->post('telnum'));
            $user_id = $this->User_model->find_tel($user_tel);
            $user_id = $user_id[0]['userid'];

            $data = array(
                'machineid'=> $this->input->post('machineid'),
                'userid'=> $user_id,
                'buytime' => $time,
                'filter1' => $this->input->post('filter1'),
                'changetime1' => $changetime1,
                'filter2' => $this->input->post('filter2'),
                'changetime2' => $changetime2,
                'filter3' => $this->input->post('filter3'),
                'changetime3' => $changetime3,
                'filter4' => $this->input->post('filter4'),
                'changetime4' => $changetime4,
                'filter5' => $this->input->post('filter5'),
                'changetime5' => $changetime5,
                'filter6' => $this->input->post('filter6'),
                'changetime6' => $changetime6
            );
            if($this->Order_model->insert_order($data))
            {
                success('background/order_operate/display', '添加成功');
            }
            else
            {
                error('添加失败');
            }
        }
        else
        {
            $this->load->view('background/order_add.html');
        }
    }

    public function edit_order(){
        $segment = $this->uri->segment(4);
        $orderid = $this->input->post('orderid');
        $data['buytime'] =  $this->Order_model->select_time($orderid);

        if($this->input->post('changetime1') != 0)
        {
            $changetime1 = strtotime($this->input->post('changetime1'));
        }
        else
        {
            $changetime1 = 0;
        }
        if($this->input->post('changetime2') != 0)
        {
            $changetime2 = strtotime($this->input->post('changetime2'));
        }
        else
        {
            $changetime2 = 0;
        }
        if($this->input->post('changetime3') != 0)
        {
            $changetime3 = strtotime($this->input->post('changetime3'));
        }
        else
        {
            $changetime3 = 0;
        }
        if($this->input->post('changetime4') != 0)
        {
            $changetime4 = strtotime($this->input->post('changetime4'));
        }
        else
        {
            $changetime4 = 0;
        }
        if($this->input->post('changetime5') != 0)
        {
            $changetime5 = strtotime($this->input->post('changetime5'));
        }
        else
        {
            $changetime5 = 0;
        }
        if($this->input->post('changetime6') != 0)
        {
            $changetime6 = strtotime($this->input->post('changetime6'));
        }
        else
        {
            $changetime6 = 0;
        }
        $data = array(
            'changetime1' => $changetime1,
            'changetime2' => $changetime2,
            'changetime3' => $changetime3,
            'changetime4' => $changetime4,
            'changetime5' => $changetime5,
            'changetime6' => $changetime6
        );
        if($this->Order_model->edit_order($data, $orderid))
        {
            success('background/order_operate/display/' . $segment, '修改成功');
        }
        else
        {
            error('修改失败');
        }
    }

    public function order_del(){
        $segment = $this->uri->segment(5);
        $orderid = $this->uri->segment(4);
        if($this->Order_model->order_del($orderid))
        {
            success_no('background/order_operate/display/' . $segment);
        }
        else
        {
            error('删除失败');
        }
    }
}