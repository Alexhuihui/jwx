<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Machine_model extends  CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add($machine_array)
    {
        return $this->db->insert('tb_machine' , $machine_array);
    }

    public function delete($machine_id)
    {
        return $this->db->where('machineid' , $machine_id)->delete('tb_machine');
    }

    public function update($machine_array , $machine_id)
    {
        return $this->db->update('tb_machine' , $machine_array , array('machineid' => $machine_id));
    }

    public function select()
    {
        $object_machine = $this->db->get('tb_machine')->result();

        return $object_machine;
    }

    public function select_by_id($machine_id)
    {
        $row = $this->db->get_where('tb_machine' , array('machineid' => $machine_id))->row_object();

        return $row;
    }

    public function machine_is_exists($machine_id)
    {
        $result = $this->db->get_where('tb_machine' , array('machineid' => $machine_id))->result();

        if($result == NULL)
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function type_is_exists($machine_name)
    {
        $result = $this->db->get_where('tb_machine' , array('machinename' => $machine_name))->result();

        if($result == NULL)
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function get_expect_machine($machine_name)
    {
        $result = $this->db->where('machinename !=' , $machine_name)->get('tb_machine')->result();

        return $result;
    }

    public function select_total()
    {
        $total = $this->db->count_all_results('tb_machine');

        return $total;
    }

    public function select_per_num($machine_num)
    {
        if ($machine_num == NULL)
        {
            $result = $this->db->limit(10)->order_by('machineid' , 'DESC')->get('tb_machine')->result();
            return $result;
        }
        else
        {
            $result = $this->db->limit(10, ($machine_num * 10) - 10)->order_by('machineid' , 'DESC')->get('tb_machine')->result();
            return $result;
        }
    }

    public function insert_machine($data){
        if($this->db->insert('tb_machine', $data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //查询所有净水器
    public function select_all()
    {
        $result = $this->db->get('tb_machine')->result_array();
        return $result;
    }

    public function update_is_exist($machine_name , $update_name)
    {
        $result = $this->db->where('machinename !=' , $machine_name)->get('tb_machine')->result_object();

        foreach($result as $v)
        {
            if ($v->machinename === $update_name)
            {
                return FALSE;
            }
        }

        return TRUE;
    }
}