<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('article_model');
    }

    public function index()
    {

    }

    public function check($article_id)
    {
        if($article_id == NULL || $article_id == '')
        {
            echo '<script>';
            echo "alert('文章编号不能为空！');";
            echo 'top.location="' . site_url() . '/background/article";';
            echo '</script>';
        }
        else
        {
            $current_id_row = $this->article_model->select_by_id($article_id);

            $current_id_row->updatetime = date('Y-m-d' , $current_id_row->updatetime);

            $this->load->view('foreground/article_index.html' , array('result' => $current_id_row));
        }
    }

    public function test()
    {
        $data = $this->article_model->get_last();

        var_dump($data);
    }

    public function load_more()
    {
        $json_article_num = $this->input->post('id');

        if($json_article_num == '-1')
        {
            $article_array = $this->article_model->get_more(10);
        }
        else
        {
            $article_array = $this->article_model->get_more(5 , $json_article_num);
        }

        for($i = 0 ; $i < count($article_array) ; $i++)
        {
            $article_array[$i]['updatetime'] = date('Y-m-d' , $article_array[$i]['updatetime']);
        }

        $json_article = json_encode($article_array);
        echo $json_article;
    }
}