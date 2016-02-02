<?php

class Api_model extends CI_Model
{
    private $passport_api_url = 'http://my.shopex.cn/api.php'; //正式环境
    //private $passport_api_url = 'http://passport.ex-sandbox.com/api.php'; //测试环境

    private $passport_api_key = '123457';
    private $passport_secret = '347cfd8d66cb341272bf5d2473c13648';
    public function __construct()
    {
        parent :: __construct();
        $this->snoopy = new snoopy();
    }

    /**
     * @param $id 接口 查询商业id
     * @return mixed
     */
    public function search_business_id($id)
    {
        $config = array(
            'prism' => array(
                'key' => 'AQAFY1',
                'secret' => '3JD5ZICVHKMAT5EOAY6A',
                'site' => 'https://openapi.ishopex.cn',
                'oauth' => 'https://oauth.ishopex.cn',
            ),
        );
//关于新用户中心调用查看的接口方法
//测试环境可能有时间差！所以通过网络获取
//$snoopy = new Snoopy();
//$snoopy->submit("http://openapi.ishopex.cn/api/platform/timestamp","");
//$time = $snoopy->results;
        $time = time();
        $url = 'api/usercenter/passport/getinfo';
//echo date("Y-m-d H:i:s",$time);
//$data["uid"] = '441111082500';
//$data["login_name"] = "564277291@qq.com";
        $data["login_name"] = $id;
        $prism = new oauth2($config['prism']);
//echo "<pre>";print_r($prism);print_r($data);//exit;
        $r = $prism->request()->post($url, $data, $time);
//exit;
//print_r($r);exit;
        $res = $r->parsed();
        return $res;
    }

    /**
     * @return string 生成商业id
     *
     */
    public function generate_business_id($post){
        $params = array();
        //系统级参数
        $params['method'] = "shopex.user.add";
        $params['app_key'] = $this->passport_api_key;
        //测试环境可能有时间差！所以通过网络获取
        $snoopy = new Snoopy();
        $snoopy->submit("http://openapi.ishopex.cn/api/platform/timestamp","");
        $params['timestamp'] = $snoopy->results;
        //$params['timestamp'] = time();
        $params['format'] = 'json';
        $params['v'] = '1.0';

        //将$post的内容添加进$params
        $params = array_merge($params,$post);
//        print_r($params);
//        exit;

        $params['sign'] = $this->make_sign($params,$this->passport_secret,array('sign'));
        $this->snoopy->submit($this->passport_api_url,$params);
        $res = $this->snoopy->results;
        $arrres = json_decode($res,true);
        return $arrres;
    }
    //生成sign验算值
    public function make_sign($params,$k = '',$exclude){
        ksort($params);
        $str = '';
        foreach($params as $key=>$value){
            if(!in_array($key,$exclude)){
                $str.=$value;
            }
        }
        //echo $str.$k;echo "<br>";
        return $k == '' ? md5($str) : md5($str.$k);
    }
    //初始化设置错误信息表
    public function setError($error){
        $error_level = array(
            'system rule file not exists'=>'系统文件缺失异常!',
            'app rule file not exists' => '应用程序文件缺失异常!',
            'sign error' => '验证错误，请检查私钥和验证码生成规则是否正确!',
            'required empty' => '必填项为空!',
            'pid not exists' => '接口产品不存在!',
            'call time error' => '接口调用频率异常!',
            'default value error' => '不在选定值范围内',
            'username_exists' => '用户名存在',
        );
        if (array_key_exists($error,$error_level)){
            return $error_level[$error];
        }else{
            return $error;
        }

    }


    public function province($number){
        $province_arr = array(
            "2"=>"110000",
            "26"=>"120000",
            "24"=>"310000",
            "1001"=>"500000",
            "1002"=>"810000",
            "1004"=>"710000",
            "1003"=>"820000",
            "30"=>"650000",
            "19"=>"640000",
            "18"=>"150000",
            "6"=>"450000",
            "28"=>"540000",
            "9"=>"130000",
            "10"=>"410000",
            "17"=>"210000",
            "14"=>"220000",
            "11"=>"230000",
            "15"=>"320000",
            "32"=>"330000",
            "1"=>"340000",
            "3"=>"350000",
            "16"=>"360000",
            "21"=>"370000",
            "22"=>"140000",
            "12"=>"420000",
            "13"=>"430000",
            "5"=>"440000",
            "8"=>"460000",
            "25"=>"510000",
            "7"=>"520000",
            "31"=>"530000",
            "23"=>"610000",
            "4"=>"620000",
            "20"=>"630000",
        );
        if(isset($province_arr[$number])){
            return $province_arr[$number];
        }else{
            return 110000;
        }

    }
}