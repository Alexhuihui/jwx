<?php
/**
 * Created by PhpStorm.
 * User: 29308
 * Date: 2017/10/21
 * Time: 15:14
 */
class User_model extends CI_Model
{
    public function get()
    {
        $query = $this->db->query("select * from tb_user");
        $result = $query->result_array();
        return $result;
    }

    public function get_user($offset, $perPage){
        $id = 1;
        $where = "userid != $id";
        if($offset == "" || $offset == 0)
        {
            $query = $this->db->where($where)->limit($perPage)->order_by('userid' , 'DESC')->get('tb_user');
            $row = $query->result_array();
            return $row;
        }
        else
        {
            $query = $this->db->where($where)->limit($perPage , ($offset - 1) * $perPage)->order_by('userid' , 'DESC')->get('tb_user');
            $row = $query->result_array();
            return $row;
        }
    }

    public function insert_user($data){
        if($this->db->insert('tb_user', $data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function check($userid){
        $result = $this->db->where(array('userid'=>$userid))->get('tb_user')->result_array();
        return $result;
    }

    public function edit_user($data, $userid){
        if($this->db->update('tb_user', $data, array('userid'=>$userid)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function user_del($userid){
        if($this->db->delete('tb_user', array('userid'=>$userid)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //根据手机号查找一个用户
    public function select_tel(){
        $query = $this->db->get_where('tb_user', array('telnum'=>$_SESSION['telnum']));
        $result = $query->result_array();
        return $result;
    }

    //根据手机号查找一个用户，用于增加订单
    public function find_tel($telnum){
        $query = $this->db->get_where('tb_user', array('telnum'=>$telnum));
        $result = $query->result_array();
        return $result;
    }

    //查找user表一共多少行
    public function select_row()
    {
        $total = $this->db->count_all_results('tb_user');

        return $total;
    }
}