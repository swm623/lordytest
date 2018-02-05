<?php
/* Copyright (C) Traceclouds Systems, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Dr.NP <np@bsgroup.org>
 */

/**
 * @package slimlordy
 * @file Share.logic.php
 * @author swm623@qq.com
 * @date 01/26/2018
 * @version 0.0.1
 *
 * process event from client
 */
namespace Pixie\Logic;

if (!\defined('IN_LORDY') || !$app)
{
    die('Inject denied');
}

include_once __DIR__ . "/../sdk/wxBizDataCrypt.sdk.php";

/**
 * Event类
 * 
 * 功能1：处理客户端事件
 *
 * @author      swm623 <swm623@qq.com>
 * @access      public
 * @abstract 
 */

class User {
    const CACHE_KEY_PREFIX = 'sess_';
    const CACHE_LIFETIME = 3600;

    private $container = null;
    private $logger = null;
    private $redis = null;


    public function __construct($container)
    {
        $this->container = $container;
        $this->logger = $container->get('logger');
        $this->redis = $container->get('redis');        
        return;
    }

    public function info($request, $response, $args)
    {
        $token = $args['token'];
        $key = self::CACHE_KEY_PREFIX . $token;

        $value = $this->redis->get($key);


        $ret =  \json_decode($value, true);
        if(\is_array($ret)) {
            $this->logger->debug("this is array");
        }
        $save =array(
            'uid' => $ret['uid'],
            'openid' =>$ret['openid'],
            'expires_in' => $ret['expires_in'],
            'type' => $ret['type'],
            'session_key' => $ret['session_key']
        ); 
        //var_dump($ret);

        $this->container['result'] = $save;

        $this->logger->debug("info $key value: ".\json_encode($ret));
        return $response;
    }

    public function create($request, $response, $args) 
    {
        $uid = $args['uid'];
        $openid = 1;

        $ret = $this->createInfoInner($uid,$openid);
        $this->container['result'] = $ret;
        return $response;
    }

    private function createInfoInner($uid, $openId) 
    {
  
        $save =array(
            'uid' => $uid,
            'openid' => $openId,
            'expires_in' => User::CACHE_LIFETIME + \time(),
            'type' => 'mini_program',
            'session_key' => 'MTIzNDU2Nzg5MDEyMzQ1Njc4' // 123456789012345678
        );     
        $key = self::CACHE_KEY_PREFIX . $uid;
        $value = \json_encode($save);
        $rt =$this->redis->setEx($key, User::CACHE_LIFETIME, $value);
        $this->logger->debug("createInfoInner ,$key, $value,$rt ");
        return $save;
    }

    public function testShare($request, $response, $args) {
        $path = "http://test.share/pages/shares/share?ids=1&uid=1&cid=1";

        //用户分享给组1
        $openGid = 1;  //组名
        $uid = 1;  //用户id
        $openId =1; //用户openid
        $userInfo = $this->createInfoInner($uid,$openId);
        $session_key= $userInfo['session_key'];
        $auth =[
                 'bearer '. $uid  
        ];
        $authHeader ='bearer '. $uid;
        $this->logger->debug("testShare : $authHeader");
        $ret = $this->event("share",$path,$openGid,$session_key,$authHeader);

        //用户在组1查看
        \sleep(5);
        $path = "http://test.share/pages/shares/share?ids=1&uid=1&cid=1";
        $openGid = 1;  //组名
        $uid = 2;  //用户id
        $openId =1; //用户openid
        $userInfo = $this->createInfoInner($uid,$openId);
        $session_key= $userInfo['session_key'];
        $auth =[
                 'bearer '. $uid  
        ];
        $authHeader ='bearer '. $uid;
        $this->logger->debug("testShare show : $authHeader");
        $ret = $this->event("page_show",$path,$openGid,$session_key,$authHeader);


        $this->container['result'] = $ret;

        return $response;
    }
    public function event($type,$path,$openGid,$sessionKey,$auth)
    {

        $data = [
            "openGId" => $openGid,
            "watermark"=>
            [
                "appid"=>"wxa3e33354967e4641",
                "timestamp"=>time()
            ]
        ];
        $datajson = \json_encode($data);
        $pc = new \WXBizDataCrypt('wxa3e33354967e4641', $sessionKey);
        $errCode = $pc->encryptData($datajson,$iv,$encryptData );

        $request =
        [
            "appId" => "appId",        // 标识当前的上报小程序的appID (目前试用微信的，将来可能换成自己的）
            "platformids" => "platformids",   //平台1代表Android，2代表iOS，4代表WinPhone，16代表HTML5
            "versions" => "1",   // 版本
            "events"=> [
                [
                    "event" => $type,   //  事件类型，launch 为小程序启动， show为小程序显示，  hide 为小程序被隐藏（其中 ttl为 小程序的存活时间）， page_show 为页面显示， page_hide 为页面隐藏（其中ttl为页面的存活时间）
                    "timestamp"=> "293509325",  // 以毫秒计的事件发生的时戳，以客户端事件为准。   （服务器可以另行以服务器为准记录上报的时戳，接受的时间与发送时间可能相差很大）             
                    "data" => [   // 其他参数，取决于事件类型
                        "options"=> "options",   // 对launch 事件，为微信定义的启动参数； 对show事件，为微信定义的 页面打开参数
                        "share_info" => [// 对 share事件，为分享的目标地；对view事件，为接受分享的来源地。
                            "encrypted_data" => $encryptData,  // 加密数据，参见 wx.getShareInfo 
                            "iv" => $iv,              // 解密用的iv，参见  wx.getShareInfo 
                        ],   //  分享的目标信息(群组)，如果是多个目标，一个分享会分拆成多次事件
                        "path"=>$path  // 对show ：打开的路径信息 （为options中的路径+options中的query信息合成。"/pages/shares/share?id=10&uid=20"）。  其中的uid为分享者的uid，作为share_info的补充信息。
                    ]
                ]
            ]
        ] ;     
        $this->logger->debug("event :".\json_encode($request)); 
        //$domain = $this->container->get('settings')['lordy']['host'];
        $host = 'http://_internal.api.lordy.fabuge.com/events';
        return $this->post($host,$request,$auth);
    }
    public function post($url, $post_data , $auth){//curl  
        $this->logger->debug("host:".$url);
        /*
        $ch = curl_init($url);     
        curl_setopt($ch, CURLINFO_HEADER_OUT, "TRUE");                                                                          
        curl_setopt($ch, CURLOPT_POST, 1);                                                                    
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);                                                                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($data_string),
            'HTTP_AUTHORIZATION: '.$auth
            )                                                                       
        );                                                                                                                                                                                                           
        $result = curl_exec($ch);

        var_dump(curl_getinfo($ch));
        curl_close($ch);
        */
        $response = \Httpful\Request::post($url)                  // Build a PUT request...
        ->sendsJson()                               // tell it we're sending (Content-Type) JSON...
        ->addHeader('Authorization', $auth)  
        ->body($post_data)             // attach a body/payload...
        ->send();  
        return $response->body;
    }  

}


