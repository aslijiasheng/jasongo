<?php

class Express_service_model extends CI_Model {

    private $curl;

    private $url = array(
        "expressListUsers"    => "http://localhost:1323/expressListUsers",
        "expressQueryUsers"   => "http://localhost:1323/expressQueryUsers",
        "expressEmailMessage" => "http://localhost:1323/expressEmailMessage",
        "expressTake"         => "http://localhost:1323/expressTake",
        "expressTakeUser"     => "http://localhost:1323/expressTakeUser",

    );

    private static $error = array(
        10001 =>'待发',
        10002 =>'发送成功',
        10003 =>'发送失败',
        10004 =>'重发',
        10005 =>'用户查询失败',
        10006 =>'用户不能为空',
        10007 =>'用户手机不能为空',
        10008 =>'用户邮件发送失败',
        10009 =>'用户邮件发送成功',
        10010 =>'用户取件更新失败',
        10011 =>'用户取件更新成功',
        10012 =>'用户ID不能为空',
        10013 =>'用户取件查询失败',
    );

    function __construct(){
        parent::__construct();
        $this->load->library('curl');
    }

    public function expressListUsers(){
        $curl = new Curl();
        $curl->get($this->url['expressListUsers']);
        return $curl->response;
    }

    public function expressTakeDetail($userID){
        $curl = new Curl();
        $expressTakeDetail = $curl->get($this->url['expressTakeUser']."?userID=".$userID);
        $expressListUsers = $curl->get($this->url['expressListUsers']);
        foreach($expressTakeDetail as $key => &$takeDetail){
            $createUserID = $takeDetail->express_create_user_id;
            $fromUserID = $takeDetail->express_from_user_id;
            $toUserID = $takeDetail->express_to_user_id;
            $takeDetail->express_create_user_id_attr = $expressListUsers->$createUserID; 
            $takeDetail->express_from_user_id_attr = $expressListUsers->$fromUserID; 
            $takeDetail->express_to_user_id_attr = $expressListUsers->$toUserID; 
            $takeDetail->express_take_status_attr = self::$error[$takeDetail->express_take_status]; 
        }
        return $expressTakeDetail;
    }

    public function expressTakeEmail($takeUserID, $userID){
        $curl = new Curl();
        $data = array("takeUserID" => $takeUserID, "userID" => $userID);
        $curl->post($this->url['expressEmailMessage'], $data);
        return $curl->response;
    }

    public function expressTakeUp($userID, $expressID){
        $curl = new Curl();
        $data = array("expressID" => $expressID, "userID" => $userID);
        $curl->post($this->url['expressTake'], $data);
        return $curl->response;
    }

}
