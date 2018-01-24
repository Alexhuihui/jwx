<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form'));
        $this->load->model('article_model');
    }

    public function index()
    {
        $this->load->view('foreground/week_news.html');
    }

    public function preview($article_id)
    {
        if($article_id == NULL || $article_id == '')
        {
            echo '<script>';
            echo "alert('文章编号不能为空！');";
            echo 'top.location="' . site_url() . '/background/article/display";';
            echo '</script>';
        }

        else
        {
            $result = $this->article_model->article_is_exists($article_id);
            if( ! $result)
            {
                echo '<script>';
                echo "alert('该文章编号不存在！');";
                echo 'top.location="' . site_url() . '/background/article/display";';
                echo '</script>';
            }
            else
            {
                $current_id_row = $this->article_model->select_by_id($article_id);

                $current_id_row->updatetime = date('Y-m-d' , $current_id_row->updatetime);

                $this->load->view('background/preview.html' , array('result' => $current_id_row));
            }
        }
    }
    public function create()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('title' , '文章标题' , 'required|min_length[2]|max_length[10]|is_unique[tb_article.articlename]');
        $this->form_validation->set_rules('introduce' , '文章简介' , 'required|min_length[5]|max_length[20]');
        $this->form_validation->set_rules('content' , '文章内容' , 'required|min_length[5]|max_length[1000]');

        $upload_config = array(
            'upload_path' => './articleimages',
            'allowed_types' => 'gif|jpg|png|jpeg',
            'max_size' => 100,
            'max_width' => 1024,
            'max_height' => 768
        );

        $this->load->library('upload' , $upload_config);

        if( ! $this->form_validation->run())
        {
            $this->load->view('background/article_upload.html');
        }

        else if( ! $this->upload->do_upload('upload'))
        {
            $error =  array('error' => $this->upload->display_errors('<span style="color:red">' , '</span>'));
            $this->load->view('background/article_upload.html' , $error);
        }
        else
        {
            $temp_arr = explode(".", $this->upload->data('file_name'));
            $file_ext = array_pop($temp_arr);
            $file_ext = trim($file_ext);
            $file_ext = strtolower($file_ext);

            $filename = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;

            rename('./articleimages/' . $this->upload->data('file_name') , './articleimages/' . $filename);

            $thumb_config = array(
                'image_library' => 'gd2',
                'source_image' => './articleimages/' . $filename,
                'create_thumb' => TRUE,
                'maintain_ratio' => TRUE,
                'width' => 360,
                'height' => 200
            );

            $this->load->library('image_lib' , $thumb_config);

            if( ! $this->image_lib->resize())
            {
                echo $this->image_lib->display_errors();
            }

            else
            {
                $prefix = substr($filename , 0 , strpos($filename , '.'));
                $lastfix = substr($filename , strpos($filename , '.'));
                $thumb_url = base_url('articleimages/' . $prefix . '_thumb' . $lastfix);
                $uploadimg_path = './articleimages/' . $filename;

                $title = trim($this->input->post('title'));
                $introduce = trim($this->input->post('introduce'));
                $content = trim($this->input->post('content'));
                $createtime = time();
                $updatetime = time();

                $content = str_replace('temp_' , '' , $content);

                $article_array = array(
                    'articleid' => NULL,
                    'articlename' => $title,
                    'articleimg' => $thumb_url,
                    'articleintro' => $introduce,
                    'articlecontent' => $content,
                    'createtime' => $createtime,
                    'updatetime' => $updatetime
                );

                $this->article_model->add($article_array);

                $this->load->helper('content');
                $new_content_images = contentimg_array($content);
                contentimg_modify($new_content_images , $createtime);

                if(file_exists($uploadimg_path))
                {
                    unlink($uploadimg_path);
                    redirect(site_url('background/article/display'));
                }
                else
                {
                    redirect(site_url('background/article/display'));
                }
            }
        }
    }

    public function display($page_num = NULL)
    {
        $total_rows = $this->article_model->select_total();

        $this->load->library('pagination');
        $config['base_url'] = site_url('background/article/display');
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

        $result = $this->article_model->select_per_num($page_num);

        foreach($result as $v)
        {
            $v->createtime = date('Y-m-d' , $v->createtime);
            $v->updatetime = date('Y-m-d' , $v->updatetime);
        }

        $this->load->view('background/article_display.html' , array('article_array' => $result , 'links' => $this->pagination->create_links()));
    }

    public function delete($article_id , $segment)
    {
        $this->load->helper('content');
        if($article_id == NULL || $article_id == '')
        {
            echo '<script>';
            echo "alert('文章编号不能为空！');";
            echo 'top.location="' . site_url() . '/background/article/display";';
            echo '</script>';
        }

        else
        {
            $result = $this->article_model->article_is_exists($article_id);
            if( ! $result)
            {
                echo '<script>';
                echo "alert('该文章编号不存在！');";
                echo 'top.location="' . site_url() . '/background/article/display";';
                echo '</script>';
            }
            else
            {
                $current_id_row = $this->article_model->select_by_id($article_id);
                $file_path = './articleimages/' . substr($current_id_row->articleimg, strrpos($current_id_row->articleimg , '/') + 1);

                if(file_exists($file_path))
                {
                    unlink($file_path);
                    contentimg_delete($current_id_row->articlecontent);
                    $this->article_model->delete($article_id);
                    $article_total_num = $this->article_model->select_total();
                    if($segment == 0)
                    {
                        redirect(site_url('background/article/display'));
                    }
                    else if($article_total_num % 10 === 0)
                    {
                        $segment = $segment - 1;
                        redirect(site_url('background/article/display/' . $segment));
                    }
                    else
                    {
                        redirect(site_url('background/article/display/' . $segment));
                    }
                }
                else
                {
                    contentimg_delete($current_id_row->articlecontent);
                    $this->article_model->delete($article_id);
                    $article_total_num = $this->article_model->select_total();
                    if($segment == 0)
                    {
                        redirect(site_url('background/article/display'));
                    }
                    else if($article_total_num % 10 === 0)
                    {
                        $segment = $segment - 1;
                        redirect(site_url('background/article/display/' . $segment));
                    }
                    else
                    {
                        redirect(site_url('background/article/display/' . $segment));
                    }
                }
            }
        }
    }

    public function check($article_id)
    {
        if($article_id == NULL || $article_id == '')
        {
            echo '<script>';
            echo "alert('文章编号不能为空！');";
            echo 'top.location="' . site_url() . '/background/article/display";';
            echo '</script>';
        }

        else
        {
            $result = $this->article_model->article_is_exists($article_id);
            if( ! $result)
            {
                echo '<script>';
                echo "alert('该文章编号不存在！');";
                echo 'top.location="' . site_url() . '/background/article/display";';
                echo '</script>';
            }
            else
            {
                $current_id_row = $this->article_model->select_by_id($article_id);

                $current_id_row->updatetime = date('Y-m-d' , $current_id_row->updatetime);

                $this->load->view('background/article_index.html' , array('result' => $current_id_row));
            }
        }
    }

    public function update($article_id , $segment)
    {
        $this->load->helper('content');
        if($article_id == NULL || $article_id == '')
        {
            echo '<script>';
            echo "alert('文章编号不能为空！');";
            echo 'top.location="' . site_url() . '/background/article/display";';
            echo '</script>';
        }

        else
        {
            $result = $this->article_model->article_is_exists($article_id);
            if ( ! $result)
            {
                echo '<script>';
                echo "alert('该文章编号不存在！');";
                echo 'top.location="' . site_url() . '/background/article/display";';
                echo '</script>';
            }
            else
            {
                $this->load->library('form_validation');

                $target_articlename = $this->article_model->select_by_id($article_id)->articlename;

                $GLOBALS['target'] = $target_articlename;

                $this->form_validation->set_rules('updatetitle', '文章标题',
                    array(
                        'required',
                        'min_length[2]',
                        'max_length[10]',
                        array(
                            'update_exist' ,
                            function($value)
                            {
                                $result = $this->article_model->get_expect_article($GLOBALS['target']);

                                $flag = TRUE;
                                foreach ($result as $v)
                                {
                                    if($v->articlename === $value)
                                    {
                                        $flag = FALSE;
                                    }
                                }

                                return $flag;
                            }
                        )
                    )
                );
                $this->form_validation->set_rules('updateintro' , '文章简介' , 'required|min_length[5]|max_length[20]');
                $this->form_validation->set_rules('updatecontent' , '文章内容' , 'required|min_length[5]|max_length[1000]');

                $upload_config = array(
                    'upload_path' => './articleimages',
                    'allowed_types' => 'gif|jpg|png',
                    'max_size' => 100,
                    'max_width' => 1024,
                    'max_height' => 768
                );

                $this->load->library('upload' , $upload_config);

                if( ! $this->form_validation->run())
                {
                    $this->load->view('background/article_update.html' , array(
                        'row' => $this->article_model->select_by_id($article_id),
                        'segment' => $segment));
                }

                else if( ! $this->upload->do_upload('upload'))
                {
                    $updatetitle = trim($this->input->post('updatetitle'));
                    $updateintro = trim($this->input->post('updateintro'));
                    $updatecontent = trim($this->input->post('updatecontent'));
                    $updatetime = time();

                    $row = $this->article_model->select_by_id($article_id);

                    $before_update = contentimg_array($row->articlecontent);
                    $after_update = contentimg_array($updatecontent);

                    $updatecontent = str_replace('temp_' , '' , $updatecontent);

                    for($i = 0 ; $i < count($before_update) ; $i++)
                    {
                        $flag = FALSE;
                        for($j = 0 ; $j < count($after_update) ; $j++)
                        {
                            if(substr($after_update[$j], strrpos($after_update[$j] , '/') + 1) ==
                                substr($before_update[$i], strrpos($before_update[$i] , '/') + 1))
                            {
                                $flag = TRUE;
                            }
                        }

                        if($flag === FALSE)
                        {
                            unlink($before_update[$i]);
                        }
                    }

                    $article_array = array(
                        'articlename' => $updatetitle,
                        'articleintro' => $updateintro,
                        'articlecontent' => $updatecontent,
                        'updatetime' => $updatetime
                    );

                    $this->article_model->update($article_array , $article_id);

                    $new_content_images = contentimg_array($updatecontent);
                    contentimg_modify($new_content_images , $updatetime);

                    if($segment === '0')
                    {
                        redirect(site_url('background/article/display'));
                    }
                    else
                    {
                        redirect(site_url('background/article/display/' . $segment));
                    }
                }
                else
                {
                    $old_img_url = $this->article_model->select_by_id($article_id)->articleimg;
                    $old_img_path = './articleimages/' . substr($old_img_url, strrpos($old_img_url , '/') + 1);

                    $temp_arr = explode(".", $this->upload->data('file_name'));
                    $file_ext = array_pop($temp_arr);
                    $file_ext = trim($file_ext);
                    $file_ext = strtolower($file_ext);

                    $filename = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;

                    rename('./articleimages/' . $this->upload->data('file_name') , './articleimages/' . $filename);

                    $thumb_config = array(
                        'image_library' => 'gd2',
                        'source_image' => './articleimages/' . $filename,
                        'create_thumb' => TRUE,
                        'maintain_ratio' => TRUE,
                        'width' => 360,
                        'height' => 200
                    );

                    $this->load->library('image_lib' , $thumb_config);

                    $prefix = substr($filename , 0 , strpos($filename , '.'));
                    $lastfix = substr($filename , strpos($filename , '.'));
                    $thumb_url = base_url('articleimages/' . $prefix . '_thumb' . $lastfix);

                    $uploadimg_path = './articleimages/' . $filename;


                    if( ! $this->image_lib->resize())
                    {
                        echo $this->image_lib->display_errors();
                    }
                    else
                    {
                        $filename = $thumb_url;
                        $updatetitle = trim($this->input->post('updatetitle'));
                        $updateintro = trim($this->input->post('updateintro'));
                        $updatecontent = trim($this->input->post('updatecontent'));
                        $updatetime = time();

                        $row = $this->article_model->select_by_id($article_id);

                        $before_update = contentimg_array($row->articlecontent);
                        $after_update = contentimg_array($updatecontent);

                        $updatecontent = str_replace('temp_' , '' , $updatecontent);

                        for($i = 0 ; $i < count($before_update) ; $i++)
                        {
                            $flag = FALSE;
                            for($j = 0 ; $j < count($after_update) ; $j++)
                            {
                                if(substr($after_update[$j], strrpos($after_update[$j] , '/') + 1) ==
                                    substr($before_update[$i], strrpos($before_update[$i] , '/') + 1))
                                {
                                    $flag = TRUE;
                                }
                            }

                            if($flag === FALSE)
                            {
                                unlink($before_update[$i]);
                            }
                        }

                        $article_array = array(
                            'articlename' => $updatetitle,
                            'articleimg' => $filename,
                            'articleintro' => $updateintro,
                            'articlecontent' => $updatecontent,
                            'updatetime' => $updatetime
                        );


                        if(file_exists($old_img_path))
                        {
                            unlink($old_img_path);
                        }

                        if(file_exists($uploadimg_path))
                        {
                            unlink($uploadimg_path);
                        }

                        $this->article_model->update($article_array , $article_id);

                        $new_content_images = contentimg_array($updatecontent);
                        contentimg_modify($new_content_images , $updatetime);

                        if($segment === '0')
                        {
                            redirect(site_url('background/article/display'));
                        }
                        else
                        {
                            redirect(site_url('background/article/display/' . $segment));
                        }
                    }
                }
            }
        }
    }

    public function upload_unique()
    {
        $article_name = $this->input->post('title');

        $is_exist = $this->article_model->type_is_exists($article_name);

        $flag = json_encode(array('exist' => (int)$is_exist));

        echo $flag;
    }

    public function update_unique()
    {
        $current_value = $this->input->post('current_value');
        $update_value = $this->input->post('update_value');

        $result = $this->article_model->get_expect_article($current_value);

        $flag = TRUE;
        foreach ($result as $v)
        {
            if($v->articlename === $update_value)
            {
                $flag = FALSE;
            }
        }

        $json_array = json_encode(array('exist' => (int)$flag));

        echo $json_array;
    }
}