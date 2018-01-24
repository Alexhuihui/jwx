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
    
    public function add_menu($json_array)
    {
        $this->create_menu($json_array);
    }
    
    public function drop_menu()
    {
        $this->delete_menu();
    }
	
	public function test()
	{
		echo $this->get_accesstoken();
	}
}