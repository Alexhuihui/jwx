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
        $this->_CI->load->model('article_model');
        $base  = $this->_CI->config->item('base_url');

        parent::__construct($option['token']);

        $data = $this->_CI->article_model->get_last();
        $this->responseMsg($data , $base);
    }
}