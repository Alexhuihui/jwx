<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article_skip extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('article_model');
    }

    public function index()
    {

    }

    public function edit()
    {
        $this->load->view('background/article_upload.html');
    }

    public function update($article_id , $segment)
    {
        if($article_id == NULL || $article_id == '')
        {
            echo '<script>';
            echo "alert('文章编号不能为空！');";
            echo 'top.location="' . site_url() . '/background/article/display";';
            echo '</script>';
        }
        else if( ! $this->article_model->article_is_exists($article_id))
        {
            echo '<script>';
            echo "alert('该文章不存在！');";
            echo 'top.location="' . site_url() . '/background/article/display";';
            echo '</script>';
        }
        else
        {
            $update_data = array(
                        'row' => $this->article_model->select_by_id($article_id),
                        'segment' => $segment
                    );
            $this->load->view('background/article_update.html' , $update_data);
//            $update_article_result = $this->article_model->select_by_id($article_id);
//
//            $filename = substr($update_article_result->articleimg, strrpos($update_article_result->articleimg , '/') + 1);
//            $prefix = substr($filename , 0 , strpos($filename , '.'));
//            $lastfix = substr($filename , strpos($filename , '.'));
//
//            $thumb_path = './articleimages/' . $prefix . '_thumb' . $lastfix;
//
//            if(file_exists($thumb_path))
//            {
//                $thumb_url = base_url('articleimages/' . $prefix . '_thumb' . $lastfix);
//
//                $update_data = array(
//                    'row' => $this->article_model->select_by_id($article_id),
//                    'thumb_url' => $thumb_url
//                );
//
//                $this->load->view('background/article_update.html' , $update_data);
//            }
//            else
//            {
//                $thumb_config = array(
//                    'image_library' => 'gd2',
//                    'source_image' => './articleimages/' . $filename,
//                    'create_thumb' => TRUE,
//                    'maintain_ratio' => TRUE,
//                    'width' => 150,
//                    'height' => 75
//                );
//                $this->load->library('image_lib' , $thumb_config);
//
//                if( ! $this->image_lib->resize())
//                {
//                    echo $this->image_lib->display_errors();
//                }
//                else
//                {
//                    $thumb_url = base_url('articleimages/' . $prefix . '_thumb' . $lastfix);
//                    $update_data = array(
//                        'row' => $this->article_model->select_by_id($article_id),
//                        'thumb_url' => $thumb_url
//                    );
//                    $this->load->view('background/article_update.html' , $update_data);
//                }
//            }
        }
    }
}