<?php

class WechatCallbackapi
{
    public $TOKEN;

    public function __construct($token)
    {
        $this->TOKEN = $token;
    }

    public function valid()
    {
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg($data , $base)
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr))
        {
            libxml_disable_entity_loader(true);//防止文件泄漏
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $msgType = $postObj->MsgType;
            $event = $postObj->Event;
            $eventKey = $postObj->EventKey;
            $keyword = trim($postObj->Content);
            $time = time();

            if($msgType == 'text')
            {
                if($keyword == '地址')
                {
                    $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
                    $message = "枫庐新天地B区";
                    $result = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType , $message);
                    echo $result;
                }
            }
            if($msgType == 'event')
            {
                if($event == 'CLICK' && $eventKey == 'C1001_LASTED_ACTIVITY')
                {
                    $newsTpl = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <ArticleCount>1</ArticleCount>
                                <Articles>
                                <item>
                                <Title><![CDATA[%s]]></Title> 
                                <Description><![CDATA[%s]]></Description>
                                <PicUrl><![CDATA[%s]]></PicUrl>
                                <Url><![CDATA[%s]]></Url>
                                </item>
                                </Articles>
                                </xml>";
                    $newsType = "news";
                    $createtime = $data->createtime;
                    $title = $data->articlename;
                    $description = $data->articleintro;
                    $picurl = $data->articleimg;
                    $url = $base . "/index.php/foreground/article/check/" . $data->articleid;
                    $result = sprintf($newsTpl , $fromUsername , $toUsername , $createtime , $newsType , $title , $description , $picurl , $url);
                    echo $result;
                }
            }
            else
            {
                echo "Input something...";
            }
        }
        else
        {
            echo "";
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = $this->TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}