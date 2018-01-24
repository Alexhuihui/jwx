<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Machine extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('machine_model');
    }

    public function index()
    {

    }

    public function create()
    {
        $this->load->library('form_validation');

        $machinename = trim($this->input->post('machinename'));

        $this->form_validation->set_rules('machinename' , '净水器名' , 'required|min_length[2]|max_length[20]|is_unique[tb_machine.machinename]');

        if( ! $this->form_validation->run())
        {
            $this->load->view('background/machine_upload.html');
        }
        else
        {
            $machine_array = array(
                'machineid' => NULL,
                'machinename' => $machinename
            );

            $this->machine_model->add($machine_array);
            redirect(site_url('background/machine/display'));
        }
    }

    public function display($page_num = NULL)
    {
        $total_rows = $this->machine_model->select_total();

        $this->load->library('pagination');
        $config['base_url'] = site_url('background/machine/display');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;
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

        $result = $this->machine_model->select_per_num($page_num);

        $this->load->view('background/machine_display.html' , array('machine_array' => $result , 'links' => $this->pagination->create_links()));
    }

    public function delete($machine_id , $segment)
    {
        if($machine_id == NULL || $machine_id == '')
        {
            echo '<script>';
            echo "alert('净水器编号不能为空！');";
            echo 'top.location="' . site_url() . '/background/machine/display";';
            echo '</script>';
        }

        else
        {
            $id_is_exist = $this->machine_model->machine_is_exists($machine_id);

            if( ! $id_is_exist)
            {
                echo '<script>';
                echo "alert('该净水器不存在！');";
                echo 'top.location="' . site_url() . '/background/machine/display";';
                echo '</script>';
            }
            else
            {
                $this->machine_model->delete($machine_id);

                $machine_total_num = $this->machine_model->select_total();

                if($segment === '0')
                {
                    redirect(site_url('background/machine/display'));
                }
                else if($machine_total_num % 10 === 0)
                {
                    $segment = $segment - 1;
                    redirect(site_url('background/machine/display/' . $segment));
                }
                else
                {
                    redirect(site_url('background/machine/display/' . $segment));
                }
            }
        }
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
        else
        {
            $result = $this->machine_model->machine_is_exists($machine_id);

            if ( ! $result)
            {
                echo '<script>';
                echo "alert('该净水器不存在！');";
                echo 'top.location="' . site_url() . '/background/machine/display";';
                echo '</script>';
            }

            else
            {
                $this->load->library('form_validation');

                $target_machinename = $this->machine_model->select_by_id($machine_id)->machinename;

                $GLOBALS['target'] = $target_machinename;

                $this->form_validation->set_rules('updatename', '净水器名',
                    array(
                        'required',
                        'min_length[2]',
                        'max_length[20]',
                        array(
                            'update_exist' ,
                            function($value)
                            {
                                $result = $this->machine_model->get_expect_machine($GLOBALS['target']);

                                $flag = TRUE;
                                foreach ($result as $v)
                                {
                                    if($v->machinename === $value)
                                    {
                                        $flag = FALSE;
                                    }
                                }

                                return $flag;
                            }
                        )
                    )
                );

                if( ! $this->form_validation->run())
                {
                    $this->load->view('background/machine_update.html' , array('row' => $this->machine_model->select_by_id($machine_id) , 'segment' => $segment));
                }
                else
                {
                    $machinename = trim($this->input->post('updatename'));

                    $machine_array = array(
                        'machinename' => $machinename
                    );

                    $this->machine_model->update($machine_array , $machine_id);
                    if($segment === '0')
                    {
                        redirect(site_url('background/machine/display'));
                    }
                    else
                    {
                        redirect(site_url('background/machine/display/' . $segment));
                    }
                }
            }
        }
    }

    public function upload_unique()
    {
        $machine_name = $this->input->post('machinename');

        $is_exist = $this->machine_model->type_is_exists($machine_name);

        $flag = json_encode(array('exist' => (int)$is_exist));

        echo $flag;
    }

    public function update_unique()
    {
        $current_value = $this->input->post('current_value');
        $update_value = $this->input->post('update_value');

        $result = $this->machine_model->get_expect_machine($current_value);

        $flag = TRUE;
        foreach ($result as $v)
        {
            if($v->machinename === $update_value)
            {
                $flag = FALSE;
            }
        }

        $json_result = json_encode(array('exist' =>(int)$flag));

        echo $json_result;
    }
}