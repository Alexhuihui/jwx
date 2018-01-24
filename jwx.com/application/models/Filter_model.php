<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Filter_model extends  CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add($filter_array)
    {
        return $this->db->insert('tb_filter' , $filter_array);
    }

    public function delete($filter_id)
    {
        return $this->db->where('filterid' , $filter_id)->delete('tb_filter');
    }

    public function update($filter_array , $filter_id)
    {
        return $this->db->update('tb_filter' , $filter_array , array('filterid' => $filter_id));
    }

    public function select()
    {
        $object_filter = $this->db->get('tb_filter')->result();

        return $object_filter;
    }

    public function select_by_id($filter_id)
    {
        $row = $this->db->get_where('tb_filter' , array('filterid' => $filter_id))->row_object();

        $row->life = $row->life / (24 * 60 * 60);

        return $row;
    }

    public function filter_is_exists($filter_id)
    {
        $result = $this->db->get_where('tb_filter' , array('filterid' => $filter_id))->result();

        if($result == NULL)
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function type_is_exists($filter_name)
    {
        $result = $this->db->get_where('tb_filter' , array('filtername' => $filter_name))->result();

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
        $total = $this->db->count_all_results('tb_filter');

        return $total;
    }

    public function select_per_num($filter_num)
    {
        if ($filter_num == NULL)
        {
            $result = $this->db->limit(10)->order_by('filterid' , 'DESC')->get('tb_filter')->result();
            return $result;
        }
        else
        {
            $result = $this->db->limit(10, ($filter_num * 10) - 10)->order_by('filterid' , 'DESC')->get('tb_filter')->result();
            return $result;
        }
    }

    public function get()
    {
        $query = $this->db->query("select * from tb_filter");
        $result = $query->result_array();
        return $result;
    }

    public function get_expect_filter($filter_name)
    {
        $result = $this->db->where('filtername !=' , $filter_name)->get('tb_filter')->result();

        return $result;
    }

    public function get_filter($offset, $perPage){
        $id = 1;
        $where = "filterid != $id";
        if($offset == "")
        {
            $query = $this->db->where($where)->limit($perPage)->get('tb_filter');
            $row = $query->result_array();
            return $row;
        }
        else
        {
            $query = $this->db->limit($perPage , ($offset - 1) * $perPage)->get('tb_filter');
            $row = $query->result_array();
            return $row;
        }
    }

    public function insert_filter($data){
        if($this->db->insert('tb_filter', $data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function check($filterid){
        $result = $this->db->where(array('filterid'=>$filterid))->get('tb_filter')->result_array();
        return $result;
    }

    public function edit_filter($data, $filterid){
        if($this->db->update('tb_filter', $data, array('filterid'=>$filterid)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function filter_del($filterid){
        if($this->db->delete('tb_filter', array('filterid'=>$filterid)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //根据ajax传过来的machinename查询filter的名字和剩余寿命
    public function select_filter($machinename_json)
    {
        $machineid_a = $this->db->select('machineid')->get_where('tb_machine' , array('machinename' => $machinename_json))->result_array();
        $result = $this->db->select('filter1,changetime1,filter2,changetime2,filter3,changetime3,filter4,changetime4,
            filter5,changetime5,filter6,changetime6')->get_where('tb_order', array('machineid' =>
            $machineid_a[0]['machineid'], 'userid' => $_SESSION['userid']))->result_array();
        return $result;
    }

    //根据filterid查询filtername
    public function select_name($filter_id)
    {
        $result = $this->db->select('filtername')->get_where('tb_filter', array('filterid' => $filter_id))->result_array();
        return $result[0]['filtername'];
    }

    //根据filtername查询life
    public function select_life($filter_name)
    {
        $result = $this->db->select('life')->get_where('tb_filter', array('filtername' => $filter_name))->result_array();
        return $result[0]['life'];
    }

    //查找根据滤芯id和上次更换时间滤芯寿命
    public function select_lefttime($life_time, $change_time)
    {
        $time1 = $change_time;
        $time2 = $time1  + $life_time;
        $time3 = $time2 - time();
        $time4 = $time3 / (24*60*60);
        $time5 = (int)$time4;
        return $time5;
    }

    //查找滤芯剩余天数所占的百分比
    public function select_percentage($life_time, $change_time)
    {
        $time1 = $change_time;
        $time2 = $time1  + $life_time;
        $time3 = $time2 - time();
        $time4 = $time3 / (24*60*60);
        $time5 = (int)$time4;
        $sum_day = $life_time/(24*60*60);
        $sum_day = (int)$sum_day;
        $percentage = $time5/$sum_day;
        $percentage = round($percentage,2);
        return $percentage;
    }

    //查询所有滤芯
    public function select_all()
    {
        $result = $this->db->get('tb_filter')->result_array();
        return $result;
    }

    public function update_is_exist($filter_name , $update_name)
    {
        $result = $this->db->where('filtername !=' , $filter_name)->get('tb_filter')->result_object();

        foreach($result as $v)
        {
            if ($v->filtername === $update_name)
            {
                return FALSE;
            }
        }

        return TRUE;
    }
}