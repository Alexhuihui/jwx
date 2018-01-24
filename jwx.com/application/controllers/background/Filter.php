<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filter extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('filter_model');
    }

    public function index()
    {

    }

    public function create()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('filtertype' , '滤芯名' , 'required|min_length[2]|max_length[20]|is_unique[tb_filter.filtername]');
        $this->form_validation->set_rules('filterlife' , '滤芯寿命' , 'required|greater_than[0]|less_than[99999]');

        if( ! $this->form_validation->run())
        {
            $this->load->view('background/filter_upload.html');
        }
        else
        {
            $filtertype = trim($this->input->post('filtertype'));
            $filterlife = trim($this->input->post('filterlife'));

            $filter_array = array(
                'filterid' => NULL,
                'filtername' => $filtertype,
                'life' => $filterlife * 24 * 60 * 60
            );

            $this->filter_model->add($filter_array);
            redirect(site_url('background/filter/display'));
        }
    }

    public function display($page_num = NULL)
    {
        $total_rows = $this->filter_model->select_total();

        $this->load->library('pagination');
        $config['base_url'] = site_url('background/filter/display');
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

        $result = $this->filter_model->select_per_num($page_num);

        foreach($result as $v)
        {
            $v->life = $v->life / (24 * 60 * 60);
        }

        $this->load->view('background/filter_display.html' , array('filter_array' => $result , 'links' => $this->pagination->create_links()));
    }

    public function delete($filter_id , $segment)
    {
        if($filter_id == NULL || $filter_id == '')
        {
            echo '<script>';
            echo "alert('滤芯编号不能为空！');";
            echo 'top.location="' . site_url() . '/background/filter/display";';
            echo '</script>';
        }

        else
        {
            $id_is_exist = $this->filter_model->filter_is_exists($filter_id);

            if( ! $id_is_exist)
            {
                echo '<script>';
                echo "alert('该滤芯编号不存在！');";
                echo 'top.location="' . site_url() . '/background/filter/display";';
                echo '</script>';
            }
            else
            {
                $this->filter_model->delete($filter_id);

                $filter_total_num = $this->filter_model->select_total();

                if($segment === '0')
                {
                    redirect(site_url('background/filter/display'));
                }
                else if($filter_total_num % 10 === 0)
                {
                    $segment = $segment - 1;
                    redirect(site_url('background/filter/display/' . $segment));
                }
                else
                {
                    redirect(site_url('background/filter/display/' . $segment));
                }
            }
        }
    }

    public function update($filter_id , $segment)
    {
        if($filter_id == NULL || $filter_id == '')
        {
            echo '<script>';
            echo "alert('滤芯编号不能为空！');";
            echo 'top.location="' . site_url() . '/background/filter/display";';
            echo '</script>';
        }
        else
        {
            $result = $this->filter_model->filter_is_exists($filter_id);
            if ( ! $result)
            {
                echo '<script>';
                echo "alert('该滤芯不存在！');";
                echo 'top.location="' . site_url() . '/background/filter/display";';
                echo '</script>';
            }
            else
            {
                $filtertype = trim($this->input->post('updatetype'));
                $filterlife = trim($this->input->post('updatelife'));

                $this->load->library('form_validation');

                $target_filtername = $this->filter_model->select_by_id($filter_id)->filtername;

                $GLOBALS['target'] = $target_filtername;

                $this->form_validation->set_rules('updatetype', '滤芯名',
                    array(
                        'required',
                        'min_length[2]',
                        'max_length[20]',
                        array(
                            'update_exist' ,
                            function($value)
                            {
                                $result = $this->filter_model->get_expect_filter($GLOBALS['target']);

                                $flag = TRUE;
                                foreach ($result as $v)
                                {
                                    if($v->filtername === $value)
                                    {
                                        $flag = FALSE;
                                    }
                                }

                                return $flag;
                            }
                        )
                    )
                );

                $this->form_validation->set_rules('updatelife', '滤芯寿命', 'required|greater_than[0]|less_than[99999]');

                if( ! $this->form_validation->run())
                {
                    $this->load->view('background/filter_update.html' , array('row' => $this->filter_model->select_by_id($filter_id) , 'segment' => $segment));
                }
                else
                {
                    $filter_array = array(
                        'filtername' => $filtertype,
                        'life' => $filterlife * 24 * 60 * 60
                    );

                    $this->filter_model->update($filter_array , $filter_id);

                    if($segment === '0')
                    {
                        redirect(site_url('background/filter/display'));
                    }
                    else
                    {
                        redirect(site_url('background/filter/display/' . $segment));
                    }
                }
            }
        }
    }

    public function upload_unique()
    {
        $filter_name = $this->input->post('filtertype');

        $is_exist = $this->filter_model->type_is_exists($filter_name);

        $flag = json_encode(array('exist' => (int)$is_exist));

        echo $flag;
    }

    public function update_unique()
    {
        $current_value = $this->input->post('current_value');
        $update_value = $this->input->post('update_value');

        $result = $this->filter_model->get_expect_filter($current_value);

        $flag = TRUE;
        foreach ($result as $v)
        {
            if($v->filtername === $update_value)
            {
                $flag = FALSE;
            }
        }

        $json_result = json_encode(array('exist' => (int)$flag));

        echo $json_result;
    }

    public function test($value)
    {
        $result = $this->filter_model->get_expect_filter($value);

        $flag = TRUE;
        foreach ($result as $v)
        {
            if($v->filtername === $value)
            {
                $flag = FALSE;
            }
        }

        var_dump($flag);
    }
}