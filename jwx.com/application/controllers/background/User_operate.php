<?php
/**
 * Created by PhpStorm.
 * User: 29308
 * Date: 2017/10/21
 * Time: 15:28
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class User_operate extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function display($page_num = NULL)
    {
        $this->load->library('pagination');
        $perPage = 10;

        $total_rows = $this->db->count_all_results('tb_user');
        $total_rows = $total_rows - 1;
        $config['base_url'] = site_url('background/user_operate/display');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $perPage;
        $config['first_link'] = '第一页';
        $config['prev_link'] = '上一页';
        $config['next_link'] = '下一页';
        $config['last_link'] = '最后一页';
        $config['use_page_numbers'] = TRUE;

        if($page_num == NULL || $page_num == floor($total_rows / 10) + 1 || $page_num == $total_rows / 10)
        {
            $config['num_links'] = 4;
        }
        else if($page_num == '2' || $page_num == floor($total_rows / 10) || (int)$page_num === $total_rows / 10 - 1)
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

        $data['user'] = $this->User_model->get_user($offset, $perPage);
        life();
        $this->load->view('background/user_display.html', $data);
    }

    public function insert_user(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('telnum', '电话号码', 'trim|required|exact_length[11]|is_unique[tb_user.telnum]');
        $this->form_validation->set_rules('passwd', '密码', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('username', '用户名', 'trim|required');
        $this->form_validation->set_rules('address', '地址', 'trim|required');
        $this->form_validation->set_rules('remark', '备注', 'trim|max_length[150]');
        $this->form_validation->set_message('is_unique', '{field}已经存在.');
        $flag = $this->form_validation->run();

        if($flag)
        {
            $time = time();
            $data = array(
                'username' => $this->input->post('username'),
                'passwd'=> $this->input->post('passwd'),
                'telnum'=> $this->input->post('telnum'),
                'address'=> $this->input->post('address'),
                'remark'=> $this->input->post('remark'),
                'createtime'=> $time,
                'genderid'=>$this->input->post('genderid')
            );

            if($this->User_model->insert_user($data))
            {
                success('background/user_operate/display', '添加成功');
            }
            else
            {
                error('添加失败');
            }
        }
        else
        {
            $this->load->view('background/user_login.html');
        }
    }

    public function edit_user(){
        $segment = $this->uri->segment(4);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('telnum', '电话号码', 'trim|required|exact_length[11]');
        $flag = $this->form_validation->run();

        if($flag)
        {
            $userid = $this->input->post('userid');
            $data = array(
                'username' => $this->input->post('username'),
                'passwd'=> $this->input->post('passwd'),
                'telnum'=> $this->input->post('telnum'),
                'genderid'=> $this->input->post('genderid'),
                'address'=> $this->input->post('address'),
                'remark'=> $this->input->post('remark'),
                'userid'=>$this->input->post('userid')
            );

            if($this->User_model->edit_user($data, $userid))
            {
                success('background/user_operate/display/' . $segment, '修改成功');
            }
            else
            {
                error('修改失败');
            }
        }
        else
        {
            $this->load->view('background/user_edit.html');
        }
    }

    public function user_del(){
        $segment = $this->uri->segment(5);
        $userid = $this->uri->segment(4);
        if($this->User_model->user_del($userid))
        {
            $user_total_num = $this->db->count_all_results('tb_user') - 1;

            if($segment === '0')
            {
                redirect(site_url('background/user_operate/display'));
            }
            else if($user_total_num % 10 === 0)
            {
                $segment = $segment - 1;
                redirect(site_url('background/user_operate/display/' . $segment));
            }
            else
            {
                redirect(site_url('background/user_operate/display/' . $segment));
            }
        }
        else
        {
            error('删除失败');
        }
    }
}