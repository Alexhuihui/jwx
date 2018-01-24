<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(dirname(__FILE__) .'/../third_party/Wechatapi.php');

class Wecontrol extends Wechatapi
{
    public $_CI;
    
    public function __construct()
    {
        $this->_CI = & get_instance();
        $this->_CI->config->load('config');
        $option = $this->_CI->config->item('wechat');
        
        parent::__construct($option);
    }

    public function sub_get_accesstoken()
    {
        $this->_CI->load->driver('cache' , array('adapter' => 'apc' , 'backup' => 'file'));

        if( ! $this->_CI->cache->get('access_token'))
        {
            $this->cache->save('access_token' , $this->get_accesstoken() , 6900);

            return $this->_CI->cache->get('access_token');
        }
        else
        {
            return $this->_CI->cache->get('access_token');
        }
    }
    public function add_menu($json_array)
    {
        $this->create_menu($json_array , $this->sub_get_accesstoken());
    }
    
    public function drop_menu()
    {
        $this->delete_menu($this->sub_get_accesstoken());
    }
	
	public function test()
	{
		echo $this->get_accesstoken();
	}
}