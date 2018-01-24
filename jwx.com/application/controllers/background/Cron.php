<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('article_model');
    }

    public function index()
    {
        $file_of_attach = scandir('./kindeditor/attached/image' , 1);

        var_dump($file_of_attach);
    }

    public function test()
    {
        echo ini_get('base.url');
    }

    public function clean_content_images()
    {
        $current_date = date('Y-m-d', time());
        $date_of_dir = date('Ymd' , time());

        $current_article = $this->article_model->get_time_content();

        for ($i = 0; $i < count($current_article); $i++)
        {
            $current_article[$i]['updatetime'] = date('Y-m-d' , $current_article[$i]['updatetime']);
        }

        $string_content = '';

        foreach($current_article as $v)
        {
            if($v['updatetime'] === $current_date)
            {
                $string_content = $string_content . $v['articlecontent'];
            }
        }

        $this->load->helper('content');

        $content_images_array = contentimg_array($string_content);

        $file_of_attach = scandir('./kindeditor/attached/image/' . $date_of_dir , 1);

        for($i = 0 ; $i < count($file_of_attach) - 2 ; $i++)
        {
            if(array_search($file_of_attach[$i] , $content_images_array) === FALSE)
            {

            }
        }
    }
}