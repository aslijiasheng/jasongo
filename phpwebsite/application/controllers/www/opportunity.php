<?php
require APPPATH.'/libraries/oauth.php';
require APPPATH.'/libraries/Snoopy.php';
class Opportunity extends WWW_controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("www/opportunity_model", "opportunity");
        $this->load->model("www/api_model", "api");
    }

    /**
     *阶段申迁的视图
     *
     */
    public function phase_promotion(){
        $id = $_GET["id"];
        if($this -> opportunity -> get_opportunity_new_stage($id) != false){
            $data["id"] = $id;
            $data["res"] = $this -> opportunity -> get_opportunity_new_stage($id);
            $this->load->view('www/opportunity/phase_promotion',$data);
        }else{
            echo "没有可以申迁的阶段";
        }

    }
     /**
      *修改申迁的数据并且获取商业id修改数据
      */
    public function save_promotion_data(){
        $data["Opportunity.Stage"] = $_POST["new_stage"];
        $id  = $_POST["id"];
        $data["Opportunity.ID"] = $id;
        if(isset($_POST["success_hidden"]) && $_POST["success_hidden"] ==1 ){    //申迁的值 成功结束
           $account_id =  $this -> opportunity -> get_account_id($id);   //得到客户的id
           $account_emstatus = $this -> opportunity -> get_account_emstatus($account_id);   //得到客户是不是签约客户
           if($account_emstatus){                       //潜在客户继续申迁去查商业id 目的是得到正确的商业id
               $account_info = $this -> opportunity -> get_account_info($account_id);   //得到客户的信息
               //$account_info["ACCOUNT_EMAIL"] = "564277291@qq.com";
               //$account_info["ACCOUNT_EMAIL"] = "5465465@22325.com";
               //$account_info["ACCOUNT_MOBILE_PHONE"] = "15618916827";
               $res_email = $this -> api -> search_business_id($account_info["ACCOUNT_EMAIL"]);
               if($res_email["status"] == "success"){    //用邮箱查询到商业id
                   $bussion_count =  $this -> opportunity -> rechecking_id($res_email["data"]["uid"]);   //查询是不是存在重复的商业id
                   if($bussion_count){  //商业id不存在
                      echo "商业id重复";
                      die;
                   }else{
                       $business_id = $res_email["data"]["uid"];
                   }

               }else{
                   $res_phone = $this -> api -> search_business_id($account_info["ACCOUNT_MOBILE_PHONE"]);
                   if($res_phone["status"] == "success"){   //用手机号码查询到商业id
                       $bussion_count =  $this -> opportunity -> rechecking_id($res_phone["data"]["uid"]);   //查询是不是存在重复的商业id
                       if($bussion_count){  //商业id不存在
                           echo "商业id重复";
                           die;
                       }else{
                         $business_id = $res_phone["data"]["uid"];
                       }
                   }else{    //手机和邮箱查询不到商业id生成商业id
                       $params['username'] = $account_info["ACCOUNT_EMAIL"]; //是  帐户名
                       $params['usertype'] = 0; //是 	帐户类型，0=全网用户,1=淘宝会员 0或1
                       if ($account_info["ACCOUNT_TYPE"]==26){
                           $params['enttype'] = 1; //是 	用户类型，可选址：0=个人, 1=企业 0或1
                           $params['company'] = $account_info["ACCOUNT_NAME"]; //企业必填 公司名称
                       }
                       if ($account_info["ACCOUNT_TYPE"]==90){
                           $params['enttype'] = 0; //是 	用户类型，可选址：0=个人, 1=企业 0或1
                           $params['owner'] = $account_info["ACCOUNT_NAME"]; //企业必填 公司名称
                       }
                       $params['source'] = 'shopex_pbi'; //是 	注册来源（见产品类型分配值）
                       $params['email'] = $account_info["ACCOUNT_EMAIL"]; //否 	电子邮件
                       $params['province'] =  $this -> api -> province(strval($account_info["ACCT_INT04"])); //全网必填
                       $params['trade'] = 42; //全网必填 	所属行业；传递行业id值 	42
                       $params['mobile'] = $account_info["ACCOUNT_MOBILE_PHONE"];

                       // P($params);

                       $arrres = $this -> api -> generate_business_id($params);
                       if ($arrres["result"]=='false'){
                           echo $arrres["message"];
                           die;
                       }else{
                           $business_id = $arrres['shopex.user.add']['user']['entid']; //商业ID
                       }
                      //$business_id = 88150101249480;

                    }

               }
              //商业id查询插入客户表并且把客户修改成签约客户

             $buss_data["Account.ID"] =  $account_id;
             $buss_data["Account.customerID"] =  $business_id;
             $buss_data["Account.emstatus"] =  1002;
             $buss_data["Account.emstatus.enum_key"] =  1002;
             $buss_data["module_action"] =  "module_update";
             $buss_data["module_model"] =  "Account";
             $business_res = $this -> opportunity -> update_account_business_type($buss_data);
             if($business_res["res"] != "suc"){
                 echo "商业id写入失败";
                 die;
             }
           }else{
               //不是潜在客户处理逻辑 不做任何处理
           }
        }else{
            //不是成功结束逻辑 不做任何处理
        }
        // 申迁数据
        $data["module_action"] =  "module_update";
        $data["module_model"] =  "Opportunity";
        $res = $this -> opportunity -> opportunity_save($data);

        if($res["res"] == "suc"){
           echo 1;
        }else{
           echo $res["msg"];
        }


    }
}