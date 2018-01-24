<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	
	public function index()
	{
		$this->load->library('Wechatcallback');
	}
	
	public function create_menu()
	{
        $this->config->load('config');
        $base = $this->config->item('base_url');

		$json_array = '{
			"button":
			[
				{
					"name":"服务站",
					"sub_button":
					[
					    {
							"type":"view",
							"name":"资讯中心",
							"url":"' . $base . '/index.php/background/article"
						},
						{
							"type":"click",
							"name":"最新活动",
							"key":"C1001_LASTED_ACTIVITY"
						},
						{
							"type":"view",
							"name":"门店查询",
							"url":"' . $base .'/index.php/welcome/show_locate"
						}
					]
				},
				{
					"name":"产品中心",
					"sub_button":
					[
						{
							"type":"view",
							"name":"中央净水",
							"url":"' . $base . '/index.php/foreground/introduce/central"
						},
						{
							"type":"view",
							"name":"家用净水器",
							"url":"' . $base .  '/index.php/foreground/introduce/family"
						},
						{
							"type":"view",
							"name":"工程机",
							"url":"' . $base . '/index.php/foreground/introduce/project"
						},
						{
							"type":"view",
							"name":"案例",
							"url":"' . $base . '/index.php/foreground/introduce/example"
						}
					]
				},
				{
					"name":"我的净水",
					"sub_button":
					[
						{
							"type":"view",
							"name":"登录",
							"url":"' . $base . '/index.php/foreground/login"
						},
						{
							"type":"view",
							"name":"退出",
							"url":"' . $base . '/index.php/foreground/quit"
						}
					]
				}
			]
		}';
		$this->load->library('Wecontrol');
		echo $this->wecontrol->add_menu($json_array);
	}

    public function show_locate()
    {
        $this->load->view('foreground/locate.html');
    }

    public function get_access()
    {
        $this->load->library('Wecontrol');
        echo $this->wecontrol->test();
    }
}
