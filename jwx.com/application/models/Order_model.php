<?php
/**
 * Created by PhpStorm.
 * order: 29308
 * Date: 2017/10/21
 * Time: 20:55
 */
class Order_model extends CI_Model
{
    public function get()
    {
        $query = $this->db->query("select * from tb_order");
        $result = $query->result_array();
        return $result;
    }

    public function get_order($offset, $perPage){
        if($offset == "" || $offset == 0)
        {
            $query = $this->db->limit($perPage)->order_by('orderid' , 'DESC')->select('*')->from('tb_order')
                ->join('tb_user', 'tb_user.userid = tb_order.userid', 'left')->join('tb_machine', 'tb_machine.machineid = tb_order.machineid', 'left')->get();
            $row = $query->result_array();
            return $row;
        }
        else
        {
            $query = $this->db->limit($perPage , ($offset - 1) * $perPage)->order_by('orderid' , 'DESC')->select('*')->from('tb_order')
                ->join('tb_user', 'tb_user.userid = tb_order.userid', 'left')->join('tb_machine', 'tb_machine.machineid = tb_order.machineid', 'left')->get();
            $row = $query->result_array();
            return $row;
        }
    }

    public function insert_order($data){
        if($this->db->insert('tb_order', $data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function edit_order($data, $orderid){
        if($this->db->update('tb_order', $data, array('orderid'=>$orderid)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function order_del($orderid){
        if($this->db->delete('tb_order', array('orderid'=>$orderid)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //根据session存储的手机号查找一个用户
    public function select_tel(){
        $query = $this->db->get_where('tb_order', array('telnum'=>$_SESSION['telnum']));
        $result = $query->result_array();
        return $result;
    }

    //根据session值查询order表的某个订单的所有信息
    public function check($orderid){
        $result = $this->db->where(array('orderid'=>$orderid))->get('tb_order')->result_array();
        return $result;
    }

    //根据用户id号查询他所购买的所有净水机名字
    public function select_name()
    {
        $result = $this->db->select('machineid')->get_where('tb_order', array('userid' => $_SESSION['userid']))->result_array();
        $length =  count($result,COUNT_NORMAL)-1;

        for($i = 0; $i <= $length; $i++)
        {
            $res = $this->db->select('machinename')->get_where('tb_machine', array('machineid' => $result[$i]['machineid']))->result_array();
            $result[$i]['machineid'] = $res[0]['machinename'];
        }
        return $result;
    }

    //查询所有用户的手机号
    public function select_alltelnum()
    {
        $query = $this->db->select('telnum')->get('tb_user');
        $result = $query->result_array();
        return $result;
    }

    //查询订单创建时间
    public function select_time($orderid)
    {
        $query = $this->db->select('buytime')->get_where('tb_order', array('orderid'=>$orderid));
        $result = $query->result_array();
        return $result;
    }
}