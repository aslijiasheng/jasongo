<?php
class Lead extends WWW_controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('www/lead_model','lead');
        $this->load->model('admin/obj_model','obj');
        $this->load->model('admin/format_model','format');
        $this->load->model('admin/attr_model','attr');
        $this->load->model('www/objects_model', 'objects');
    }

    public function index(){
        //$this->lists();
    }
    /**
     * 转化线索界面
     */
    public function leadConversion(){
        //$objTypeArr 表示线索转化需要的key去查询相应的显示字段适合以后扩展
        $obj_type = array(
            "1",
            "2",
            "4"
        );
        //查询出来线索的下拉菜单
        $obj_type_data =  $this -> lead -> GetObjTypeData($obj_type);
        $id = $this -> input -> post("id");
        $obj_type = $this -> input -> post("obj_type");
        $name =  $this -> lead -> GetAccountName($id);
        foreach($obj_type_data as $v) {
            $obj_type_select = "";
            foreach($v as $value) {
               if($value["TYPE_ID"] == "2056"){
                   $obj_type_select .= '<option selected style="width:128px;" value="'.$value["TYPE_ID"].'">'.$value['TYPE_NAME'].'</option>';
                   continue;
               }
                $obj_type_select .= '<option style="width:128px;" value="'.$value["TYPE_ID"].'">'.$value['TYPE_NAME'].'</option>';
            }
            $obj_type_select_arr[] = $obj_type_select;

        }
        $data["obj_type_select_arr"] = $obj_type_select_arr;
        $data['name']                = $name;
        $data['id']                  = $id;
        $data['obj_type']            = $obj_type;
        $this->load->view('www/lead/conversion', $data);
        //组装线索转化事物界面和界面数据
    }
    /**
     * 转化线索界面新增客户相应
     */
    /**
     * 转化线索界面新增客户相应
     */
    public function cluesInto(){
        $org_id = $this -> session -> userdata("org_id");
        $user_id = $this -> session -> userdata("org_id");
        $id = $_POST["LEAD_ID"];
        //线索转化成客户
        if( $this -> input -> post("Account") && strlen($this -> input -> post("Account_Owner_ID")) ){    //必须选择
            //查询出lead线索所有属性和值（包括线索转客户，线索转联系人，等所有的属性和值）
            $tc_id = $this -> input -> post("LEAD_ID");
            $lead_data = $this->  objects -> GetOneContent("Lead", "1", $tc_id);   //查询出当前的线索具体数据
            //根据SQL查询线索对应的客户的属性已客户属性为主把$lead_attr数组的key替换成账户属性的字段 281代表线索转客户
            $dataAccount = $this -> lead -> getAccountArr($lead_data,281);
            //把前台带过来的三个属性加入数组
            $dataAccount['Account.Name']  = $this -> input -> post("Account_Name");
            $dataAccount['Account.Owner'] = $this -> input -> post("Account_Owner");
            $dataAccount['Account.Owner.ID'] = $this -> input -> post("Account_Owner_ID");
            $dataAccount['Account.Type']  = $this -> input -> post("Account_Type_ID");
            $dataAccount['Account.Type.ID']  = $this -> input -> post("Account_Type_ID");
            $dataAccount['Account.SrcType']  = 3;
            $dataAccount['Account.SrcID']  = $tc_id;



            //插入数据 并且开启事物

            $insert_id = $this -> objects -> _add($dataAccount,'Account',$org_id,$user_id);
            if( $insert_id['res'] != 'suc' ){
                //插入失败结束事物并且终止程序
                echo $insert_id["msg"];
            }else{
                //线索转化成联系人
                if($this -> input -> post("Contact")) {     //可选
                    if(strlen($this -> input -> post("Contact_Owner_ID"))){   //所有者必须填写
                        //根据SQL查询线索对应的客户的属性已联系人属性为主把$lead_attr数组的key替换成账户属性的字段 280代表线索转客户
                        $dataContact = $this -> lead -> getAccountArr($lead_data,280);
                        //把前台带过来的三个个属性和客户id加入数组
                        $dataContact['Contact.Owner']  = $this -> input -> post("Contact_Owner");
                        $dataContact['Contact.Owner.ID'] = $this -> input -> post("Contact_Owner_ID");
                        $dataContact['Contact.Type'] = $this -> input -> post("Contact_Type_ID");
                        $dataContact['Contact.Type.ID'] = $this -> input -> post("Contact_Type_ID");
                        $dataContact['Contact.Account'] = $insert_id['id'];
                        $dataContact['Contact.Account.ID'] = $insert_id['id'];
                        $dataContact['Contact.SourceObjectType'] = 3;
                        $dataContact['Contact.SourceObjectID'] = $tc_id;
                        //插入数据
                        $contact = $this -> objects -> _add($dataContact,'Contact',$org_id,$user_id);
                        if($contact["res"] != "suc"){
                            echo $contact['msg'];
                            die;
                        }
                    }else{
                        //选择了联系人但是没有选择所有者事物结束返回提示错误并且终止程序
                    }
                }
                //线索转化成销售机会
                if( $this -> input -> post("Opportunity") ) {    //可选所有者必须填写
                    if(strlen($this -> input -> post("Opportunity_Owner_ID"))){
                        //根据SQL查询线索对应的客户的属性已联系人属性为主把$lead_attr数组的key替换成账户属性的字段 282代表线索转销售机会
                        $dataOpportunity = $this -> lead -> getAccountArr($lead_data,282);
                        //把前台带过来的三个个属性和客户id加入数组
                        $dataOpportunity['Opportunity.Name']  = $this -> input -> post("Opportunity_Name");
                        $dataOpportunity['Opportunity.Owner'] = $this -> input -> post("Opportunity_Owner");
                        $dataOpportunity['Opportunity.Owner.ID'] = $this -> input -> post("Opportunity_Owner_ID");
                        $dataOpportunity['Opportunity.Type']  = $this -> input -> post("Opportunity_Type_ID");
                        $dataOpportunity['Opportunity.TypeID']  = $this -> input -> post("Opportunity_Type_ID");
                        $dataOpportunity['Opportunity.Account'] = $insert_id['id'];
                        $dataOpportunity['Opportunity.Account.ID'] = $insert_id['id'];
                        $dataOpportunity['Opportunity.SourceObjectType'] = 3;
                        $dataOpportunity['Opportunity.SourceObjectID'] = $tc_id;
                        $dataOpportunity["module_model"] = "Opportunity";
                        $dataOpportunity["module_action"] = "module_add";
                        //插入数据
                        $opportunity = $this -> objects -> init($dataOpportunity);
                        if($opportunity["res"] != "suc"){
                            echo $opportunity['msg'];
                            die;
                        }
                    }else{
                        //选择了销售机会但是没有选择所有者事物结束返回提示错误并且终止程序
                    }
                }
                //echo "success";
                $this -> lead -> lead_success($id);
                echo '<script>window.history.go(-1);alert("success")</script>';
            }

            //关闭事物
        }else{
            //echo "error";
            echo '<script>window.history.go(-1);alert("error")</script>';
        }
    }

}
?>