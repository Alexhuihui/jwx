<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(dirname(__FILE__) .'/../third_party/Wechatcallbackapi.php');

class Wechatcallback extends WechatCallbackapi
{
    public $_CI;
    
    public function __construct()
    {
        $this->_CI = & get_instance();
        $this->_CI->config->load('config');
        $option = $this->_CI->config->item('wechat');
        
        parent::__construct($option['token']);
        
        $this->valid();
        $this->responseMsg();
    }
}