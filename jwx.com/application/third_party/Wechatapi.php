<?php

class Wechatapi
{
	public $token;
	public $appid;
	public $appsecret;
	public $debug;
	public $encodingaeskey;

	public function __construct($option)
	{
		$this->token = $option['token'];
		$this->appid = $option['appid'];
		$this->appsecret = $option['appsecret'];
		$this->debug = $option['debug'];
		$this->encodingaeskey = $option['encodingaeskey'];
	}

	public function get_accesstoken()
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->appid . '&secret=' . $this->appsecret ;
		$accesstoken_of_json = file_get_contents($url);
        
        $accesstoken = substr($accesstoken_of_json , 17 , -20);
        
      	return $accesstoken;
	}
    
   	public function delete_menu($access_token)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=' . $access_token;
		        
		$result = file_get_contents($url);
		        
		echo $result;
	}
    
    public function create_menu($json_array , $access_token)
   	{
       	$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $access_token;
        
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_POST, 1);  
		curl_setopt($ch, CURLOPT_URL, $url);  
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_array);  
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
		                'Content-Type: application/json; charset=utf-8',  
		                'Content-Length: ' . strlen($json_array)) 
		                       );   
		curl_exec($ch);  
		$return_content = ob_get_contents();
		echo $return_content;
		curl_close($ch);
	}
}
