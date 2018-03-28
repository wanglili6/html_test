<?php  
/** 
  * 作者：smalle 
  * 网址：http://blog.csdn.net/oldinaction 
  * 微信公众号：smallelife 
  */  
  
//定义 token  
define("TOKEN", "smalle");  
//实例化对象  
$wechatObj = new wechatCallbackapiTest();  
//调用函数  
if (isset($_GET['echostr'])) {  
    $wechatObj->valid();  
}else{  
    $wechatObj->responseMsg();  
}  
  
class wechatCallbackapiTest  
{  
    public function valid()  
    {  
        $echoStr = $_GET["echostr"];  
        if($this->checkSignature()){  
            echo $echoStr;  
            exit;  
        }  
    }  
  
    public function responseMsg()  
    {  
  
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];  
        if (!empty($postStr)){  
                libxml_disable_entity_loader(true);//防止文件泄漏  
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);  
                $fromUsername = $postObj->FromUserName;  
                $toUsername = $postObj->ToUserName;  
                $msgType = $postObj->MsgType;  
                $media_id = $postObj->MediaId;  
                $keyword = trim($postObj->Content);  
                $time = time();  
                  
                if( $msgType == 'image' ) {  
                    $itemTpl = "<xml>  
                    <ToUserName><![CDATA[%s]]></ToUserName>  
                    <FromUserName><![CDATA[%s]]></FromUserName>  
                    <CreateTime>%s</CreateTime>  
                    <MsgType><![CDATA[image]]></MsgType>  
                    <Image>  
                        <MediaId><![CDATA[%s]]></MediaId>  
                    </Image>  
                    </xml>";  
                    $result = sprintf($itemTpl, $fromUsername, $toUsername, $time, $media_id);  
                    echo $result;  
                }else{  
                    echo "Input something...";  
                }  
  
        }else {  
            echo "";  
            exit;  
        }  
    }  
          
    private function checkSignature()  
    {  
        if (!defined("TOKEN")) {  
            throw new Exception('TOKEN is not defined!');  
        }  
        $signature = $_GET["signature"];  
        $timestamp = $_GET["timestamp"];  
        $nonce = $_GET["nonce"];  
        $token = TOKEN;  
        $tmpArr = array($token, $timestamp, $nonce);  
        sort($tmpArr, SORT_STRING);  
        $tmpStr = implode( $tmpArr );  
        $tmpStr = sha1( $tmpStr );  
          
        if( $tmpStr == $signature ){  
            return true;  
        }else{  
            return false;  
        }  
    }  
}  
  
?>  