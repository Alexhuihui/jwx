<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add($article_array)
    {
        return $this->db->insert('tb_article' , $article_array);
    }

    public function delete($article_id)
    {
        return $this->db->where('articleid' , $article_id)->delete('tb_article');
    }

    public function update($article_array , $article_id)
    {
        return $this->db->update('tb_article' , $article_array , array('articleid' => $article_id));
    }

    public function select()
    {
        $object_article = $this->db->get('tb_article')->result();

        return $object_article;
    }

    public function select_by_id($article_id)
    {
        $row = $this->db->get_where('tb_article' , array('articleid' => $article_id))->row_object();

        return $row;
    }

    public function article_is_exists($article_id)
    {
        $result = $this->db->get_where('tb_article' , array('articleid' => $article_id))->result();

        if($result == NULL)
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function get_expect_article($article_name)
    {
        $result = $this->db->where('articlename !=' , $article_name)->get('tb_article')->result();

        return $result;
    }

    public function type_is_exists($article_name)
    {
        $result = $this->db->get_where('tb_article' , array('articlename' => $article_name))->result();

        if($result == NULL)
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function select_total()
    {
        $total = $this->db->count_all_results('tb_article');

        return $total;
    }

    public function select_per_num($article_num)
    {
        if ($article_num == NULL)
        {
            $result = $this->db->limit(10)->order_by('articleid' , 'DESC')->get('tb_article')->result();
            return $result;
        }
        else
        {
            $result = $this->db->limit(10, ($article_num * 10) - 10)->order_by('articleid' , 'DESC')->get('tb_article')->result();
            return $result;
        }
    }

    public function get_last()
    {
        if($this->select_total() == 0)
        {
            return FALSE;
        }
        else
        {
            return $this->db->get('tb_article')->last_row();
        }
    }

    public function get_time_content()
    {
        return $this->db->select('updatetime , articlecontent')->get('tb_article')->result_array();
    }

    public function get_more($num , $last_id = NULL)
    {
        if($last_id == NULL)
        {
            return $this->db->limit($num)->order_by('articleid' , 'DESC')->select('articleid , articlename , articleimg , updatetime')->get('tb_article')
                ->result_array();
        }
        else
        {
            if( ! $this->article_is_exists($last_id))
            {
                return NULL;
            }
            else
            {
                return $this->db->limit($num)->order_by('articleid' , 'DESC')->where('articleid < ' , $last_id)
                    ->select('articleid , articlename , articleimg , updatetime')->get('tb_article')->result_array();
            }
        }
    }
}