<?php
class Remind_model extends CI_Model{
	public function __construct() {
        parent :: __construct();
        $this->load->model("admin/nextid_model","nextid");
    }


    /**
     * 获取高级查询数据下拉列表
     * @param type $SeniorQueryAttrs    传入的参数
     * @return type 返回查询出的结果
     */
    public function getListsAttr($SeniorQueryAttrs) {
        extract($SeniorQueryAttrs, EXTR_OVERWRITE); //释放数组元素
        $obj_data = $this->obj->NameGetObj($org_id, $obj_name);
        extract($obj_data, EXTR_OVERWRITE); //释放数组元素
        $list_format = array(1=>'REMIND_MESG',2=>'REMIND_TIME');
        //查询列表数据
        $lead_attr = $this->attr->object_attr($org_id, $obj_name);
        $ColNames = array(1=>'主题',2=>'提醒时间');
        $ColNames = json_encode($ColNames);
        $data["ColModel"] = $this->GetListColModel($list_format, $ColNames, $obj_data, $OBJ_TYPE);
        $data["SeniorQueryAttrJson"] = $this->objects->getSeniorQueryAttrs($org_id, $LangID, $obj_name, $obj_data, $lead_attr);
        return $data;
    }

    /**
     * GetListColModel 获取列表的ColModel关系json
     * @param array $ListFormat 列表布局
     * @param array $Width 每个栏位的宽度 默认200
     * @return json 返回前台调用的json数组
     */
    public function GetListColModel($ListFormat, $colNames, $obj_data, $obj_type, $Width = 200, $OperationWidth = 80, $r_type = "array", $func_name = null) {
        $res_data = array();
//        extract($is_bool, EXTR_OVERWRITE);
        /**
         * Operation:[
          *{Title:'查看',Url:'<?php echo site_url("www/objects/view"); ?>?id={<?php echo $obj_data["KEY_ATTR_NAME"]; ?>}&obj_type=<?php echo $ObjType; ?>',Css:'fa fa-search orange'},
          *{Title:'编辑',Url:'<?php echo site_url("www/objects/edit"); ?>?id={<?php echo $obj_data["KEY_ATTR_NAME"]; ?>}&obj_type=<?php echo $ObjType; ?>',Css:'fa fa-pencil blue'},
          *{Title:'删除',Url:'javascript:void(0);',Js:"dlt('{<?php echo $obj_data["KEY_ATTR_NAME"]; ?>}','<?php echo $ObjType; ?>','#LeeTable','<?php echo base_url(); ?>index.php/www/objects/list_json?obj_type=<?php echo $ObjType; ?>')",Css:'fa fa-trash-o red'},
          *{Title:'停用',Url:'javascript:void(0);',Css:'fa fa-power-off red',IfButton:[{If:"Lead.StopFlag",Etc:"是",Css:'fa fa-play green',Title:"启用"}]}
          *],
          *Col:[{Name:'Lead.Name',LangEn:'主题',Url:'<?php echo site_url("www/objects/view"); ?>?id={Lead.ID}'}],
         */
//        if (!$is_operation) {
//            $res_data['Operation'] = $this->operationListLabel($obj_data, $obj_type);
//        }
        $res_data['Col'] = array();
        $colNames = json_decode($colNames, true);
        foreach ($ListFormat as $k => $v) {
            if ($v == $obj_data['NAME_ATTR_NAME']) {
                if (empty($func_name)) {
                    $res_data['Col'][] = array(
                        'Name' => $v,
                        'LangEn' => $colNames[$k],
                        //'Url' => site_url("www/remind/view") . "?id={" . $obj_data['KEY_ATTR_NAME'] . "}&obj_type=" . $obj_type,
                    );
                } else {
                    $res_data['Col'][] = array(
                        'Name' => $v,
                        'LangEn' => $colNames[$k],
                    );
                }
            } else {
                $res_data['Col'][] = array(
                    'Name' => $v,
                    'LangEn' => $colNames[$k],
                );
            }
        }
        /**
         * MultiSelect:true, //是否开启全选功能
           * MultiType:1, //选着类型 0 多选（默认） 1 单选
            *KeyAttrName:'<?php echo $obj_data["KEY_ATTR_NAME"]; ?>' 
         */
//        if ($is_selected) {//判断是否有选择项
//            if ($is_checked) {//判断是否为多选
//                $res_data['MultiSelect'] = true;
//                $res_data['MultiType'] = 0;
//            } else {
//                $res_data['MultiSelect'] = true;
//                $res_data['MultiType'] = 0;
//            }
//        }
        $res_data['KeyAttrName'] = $obj_data["KEY_ATTR_NAME"];
//        $url_obj = $this->lists_url($func_name);
//        if (empty($primary_id)) {
//            $res_data['Url'] = base_url() . 'index.php/www/objects/' . $url_obj . '?obj_type=' . $obj_type;
//        } else {
//            $res_data['Url'] = base_url() . 'index.php/www/objects/' . $url_obj . '?obj_type=' . $obj_type . "&id=$primary_id";
//        }
        switch ($r_type) {
            case "array":
                return $res_data;
                break;
            case "json":
                return json_encode($res_data);
                break;
        }
        return json_encode($res_data);
        die;
        /*
          $res_data = array();
          $i = 0;
          $res_data[$i]["name"] = "operation";
          $res_data[$i]["index"] = "operation";
          $res_data[$i]["width"] = $OperationWidth;
          $res_data[$i]["fixed"] = true;
          $res_data[$i]["sortable"] = false;
          foreach ($ListFormat as $k => $v) {
          $i++;
          $res_data[$i]["name"] = $v;
          $res_data[$i]["width"] = $Width;
          $res_data[$i]["index"] = $v;
          $res_data[$i]["fixed"] = true;
          }
         */
        return json_encode($res_data);
    }

    //提醒列表数据
    public function ReminderList($org_id,$user_id,$is_desk=flase,$CurrentPage=1,$PageSize=10){
        $where = array(
            'tc_remind_person.to_obj_id' => $user_id,
            'tc_remind_person.org_id' => $org_id,
            //'tc_remind_person.to_desktop' =>1,
            'tc_remind_person.to_obj_type' => 99,
            );
        $this->db->select('tc_reminder.remind_id,tc_reminder.remind_type,tc_reminder.remind_mesg,tc_reminder.obj_type,tc_reminder.obj_id,tc_remind_person.is_new');
        $this->db->select("to_char(tc_remind_person.remind_time, 'yyyy-mm-dd hh24:ii:ss') as remind_time");
        $this->db->where($where);
        $this->db->from('tc_remind_person');
        $join_on = 'tc_reminder.org_id = tc_remind_person.org_id and tc_reminder.remind_id = tc_remind_person.remind_id';
        $this->db->join('tc_reminder',$join_on,'inner');
        $this->db->order_by('tc_remind_person.remind_time', "desc");
        if($is_desk){
        	$this->db->limit(8,1);
            $this->db->where('tc_remind_person.to_desktop',1);
        	$data = $this->db->get()->result_array();	
        }else{
        	$this->db->limit($PageSize, ($CurrentPage - 1) * $PageSize + 1);
            $this->db->where('tc_remind_person.to_desktop',0);
        	$data = $this->db->get()->result_array();
        	foreach ($data as $k => $v) {
        		$data[$k]['REMIND_TIME'] = $this->objects->isdatetime($v['REMIND_TIME'],8*60*60);
        	}
            $data = $this->mreplace($data);
        	$count = $this->count($org_id,$user_id);
        	return $this->InfoPagers($CurrentPage, $count, $PageSize, $data);
        }
        foreach ($data as $k => $v) {
        	$data[$k]['REMIND_TIME'] = $this->objects->isdatetime($v['REMIND_TIME'],8*60*60);
        }
        $data = $this->mreplace($data);
        return $this->InfoPagers(1, 1, 8, $data);
    }
    public function mreplace($data){
        foreach ($data as $k => $v) {
            //if($v['IS_NEW']){
                $r_data[$k]['REMIND_MESG'] = $v['REMIND_MESG']."<span class=\"badge badge-pink\">NEW!</span>";
                $r_data[$k]['REMIND_TIME'] = $v['REMIND_TIME'];
                if($v['REMIND_TYPE']!=4){                    
                    $r_data[$k]['REMIND.url'] = base_url()."index.php/www/objects/view?id=".$v['OBJ_ID']."&obj_type=".$v['OBJ_TYPE'];
                }else{
                    $r_data[$k]['REMIND.url'] = 'javascript:void(0);';
                }
                $r_data[$k]['REMIND.ID'] = $v['REMIND_ID'];
            //}
        }
        return $r_data;
    }
    //$count
    public function count($org_id,$user_id){
    	$where = array(
            'tc_remind_person.to_obj_id' => 8620,
            'tc_remind_person.org_id' => $org_id,
            'tc_remind_person.to_desktop' =>1,
            'tc_remind_person.to_obj_type' => 99,
            );
        $this->db->select('tc_reminder.remind_mesg,tc_reminder.obj_type,tc_reminder.obj_id');
        $this->db->select("to_char(tc_remind_person.remind_time, 'yyyy-mm-dd hh24:ii:ss') as remind_time");
        $this->db->where($where);
        $this->db->from('tc_remind_person');
        $join_on = 'tc_reminder.org_id = tc_remind_person.org_id and tc_reminder.remind_id = tc_remind_person.remind_id';
        $this->db->join('tc_reminder',$join_on,'inner');
        $this->db->order_by('tc_remind_person.remind_id', "desc");
        $count = $this->db->count_all_results();
        return $count;
    }

    protected function InfoPagers($CurrentPage, $count, $PageSize, $data) {
		//要返回的JSON由 数据 当前页 总页数 数据总数 五部分组成
        $responce->page = $CurrentPage; //当前页
        $responce->records = $count;
        if ($count > 0) {
            $total_pages = ceil($count / $PageSize);
        } else {
            $total_pages = 0;
        }
        $responce->total = $total_pages; //分页总数
		//循环取出分页后的列表JSON
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $responce->rows[$k]["cell"] = $v;
            }
        }
		//总页数
        return json_encode($responce);
        die;
    }

    private function GetOne($org_id,$obj,$obj_id){
        $fld = $this->attr->name2fld($org_id,$obj['NAME_ATTR_NAME'],$obj['OBJ_NAME']);
        if($obj['OBJ_NAME']=='Task'){
            $this->db->select('a.assigned_user_id,b.user_name,a.'.$fld." as name");
            $this->db->from($obj['MAIN_TABLE'].' a');
            $this->db->join('tc_user b','a.assigned_user_id = b.user_id and a.org_id=b.org_id','inner');
        }else{
            $this->db->select('a.owner_user_id,b.user_name,a.'.$fld." as name");
            $this->db->from($obj['MAIN_TABLE'].' a');
            $this->db->join('tc_user b','a.owner_user_id = b.user_id and a.org_id=b.org_id','inner');
        }
        
        $where['a.org_id'] = $org_id;
        $where['a.'.$obj['KEY_ATTR_FLD']] = $obj_id;
        $this->db->where($where);
        $r_data = $this->db->get()->result_array();
        return $r_data[0];
    }

    private function GetUser($org_id,$user_id){
        $this->db->select('USER_NAME,OWNER_USER_ID');
        $this->db->where('user_id',$user_id);
        $this->db->where('org_id',$org_id);
        $this->db->from('tc_user');
        $user_data = $this->db->get()->result_array();
        return $user_data[0];
    }

    public function AddRemind($obj_type,$org_id,$event_type,$obj_id){
        // switch ($event_type) {
        //     case '1'://对象创建时
                
        //         break;
        //     case '2'://对象更新时
        //         # code...
        //         break;
        //     case '3'://对象删除时
        //         # code...
        //         break;
        //     case '4'://对象恢复时
        //         # code...
        //         break;
        //     case '5'://对象的相关对象创建时
        //         # code...
        //         break;
        //     case '6'://对象的相关对象删除时
        //         # code...
        //         break;
        //     case '7'://定时消息
        //         # code...
        //         break;
        //     default:
        //         # code...
        //         break;
        // }
        $data = $this->checkname($event_type,$obj_type,$org_id);
        $obj = $this->obj->getOneData($obj_type);
        $r_data = $this->GetOne($org_id,$obj,$obj_id);
        foreach ($data as $k => $v) {
            switch ($v['TO_TYPE']) {
                case '0':
                    $user_data = $this->GetUser($org_id,$v['USER_ID']);

                    $next_id = $this->InsertReminder($org_id,$r_data['NAME'],$v['EVENT_NAME'],$obj_type,$obj_id);
                    $this->InsertRemindPerson($org_id,$v['USER_ID'],$next_id,$v['TO_DESKTOP'],99);
                    $this->InsertMessage($org_id,$user_data['USER_NAME'],$r_data['NAME'],$v['USER_ID'],$v['EVENT_NAME'],$obj_type,$obj_id);
                    
                    if($v['TO_PARENT']){
                        if($user_data['OWNER_USER_ID'] != 0){
                            $owner_user_data = $this->GetUser($org_id,$user_data['OWNER_USER_ID']);
                            
                            $this->InsertRemindPerson($org_id,$user_data['OWNER_USER_ID'],$next_id,$v['TO_DESKTOP'],99);
                            $this->InsertMessage($org_id,$owner_user_data['USER_NAME'],$r_data['NAME'],$user_data['OWNER_USER_ID'],$v['EVENT_NAME'],$obj_type,$obj_id);
                        }
                        
                    }
                    break;
                case '1'://所有者
                    
                    $next_id = $this->InsertReminder($org_id,$r_data['NAME'],$v['EVENT_NAME'],$obj_type,$obj_id);
                    $this->InsertRemindPerson($org_id,$r_data['OWNER_USER_ID'],$next_id,$v['TO_DESKTOP'],99);
                    $this->InsertMessage($org_id,$r_data['USER_NAME'],$r_data['NAME'],$r_data['OWNER_USER_ID'],$v['EVENT_NAME'],$obj_type,$obj_id);
                    
                    if($v['TO_PARENT']){
                        if($user_data['OWNER_USER_ID'] != 0){
                            $owner_user_data = $this->GetUser($org_id,$user_data['OWNER_USER_ID']);

                            $this->InsertRemindPerson($org_id,$user_data['OWNER_USER_ID'],$next_id,$v['TO_DESKTOP'],99);
                            $this->InsertMessage($org_id,$owner_user_data['USER_NAME'],$r_data['NAME'],$user_data['OWNER_USER_ID'],$v['EVENT_NAME'],$obj_type,$obj_id);
                        }
                    }
                    break;
                case '2'://相关员工
                    $this->db->select('user_id');
                    $where = array(
                        'org_id' => $org_id,
                        $obj['OBJ_NAME'].'_ID' =>$obj_id,
                        'is_self' => 0
                        );
                    $this->db->where($where);
                    $this->db->from('rel_'.$obj['OBJ_NAME'].'_user');
                    $ru_data = $this->db->get()->result_array();
                    
                    if(!empty($ru_data)){
                        $user_data = $this->GetUser($org_id,$ru_data[0]['USER_ID']);
                        
                        $next_id = $this->InsertReminder($org_id,$r_data['NAME'],$v['EVENT_NAME'],$obj_type,$obj_id);
                        $this->InsertRemindPerson($org_id,$ru_data[0]['USER_ID'],$next_id,$v['TO_DESKTOP'],99);
                        $this->InsertMessage($org_id,$user_data['USER_NAME'],$r_data['NAME'],$ru_data[0]['USER_ID'],$v['EVENT_NAME'],$obj_type,$obj_id);
                        if($v['TO_PARENT']){
                            if($user_data['OWNER_USER_ID'] != 0){
                                $owner_user_data = $this->GetUser($org_id,$user_data['OWNER_USER_ID']);

                                $this->InsertRemindPerson($org_id,$user_data['OWNER_USER_ID'],$next_id,$v['TO_DESKTOP'],99);
                                $this->InsertMessage($org_id,$owner_user_data['USER_NAME'],$r_data['NAME'],$user_data['OWNER_USER_ID'],$v['EVENT_NAME'],$obj_type,$obj_id);
                            }
                        }
                    }
                    
                    break;
                case '4'://角色
                    $this->db->select('user_id');
                    $where = array(
                        'org_id' => $org_id,
                        'role_ID' =>$v['ROLE_ID'],
                        );
                    $this->db->where($where);
                    $this->db->from('rel_role_user');
                    $ro_data = $this->db->get()->result_array();
                    if(!empty($ro_data)){
                        $next_id = $this->InsertReminder($org_id,$r_data['NAME'],$v['EVENT_NAME'],$obj_type,$obj_id);

                        foreach ($ro_data as $kk => $vv) {
                            $user_data = $this->GetUser($org_id,$vv['USER_ID']);
                            $this->InsertRemindPerson($org_id,$vv['USER_ID'],$next_id,$v['TO_DESKTOP'],99);
                            $this->InsertMessage($org_id,$user_data['USER_NAME'],$r_data['NAME'],$vv['USER_ID'],$v['EVENT_NAME'],$obj_type,$obj_id);
                            if($v['TO_PARENT']){
                                if($user_data['OWNER_USER_ID'] != 0){
                                    $owner_user_data = $this->GetUser($org_id,$user_data['OWNER_USER_ID']);
                                    $this->InsertRemindPerson($org_id,$user_data['OWNER_USER_ID'],$next_id,$v['TO_DESKTOP'],99);
                                    $this->InsertMessage($org_id,$owner_user_data['USER_NAME'],$r_data['NAME'],$user_data['OWNER_USER_ID'],$v['EVENT_NAME'],$obj_type,$obj_id);
                                }
                            }
                        }
                    }
                    
                    break;
                case '5'://员工
                    $user_data = $this->GetUser($org_id,$v['USER_ID']);
                    
                    $next_id = $this->InsertReminder($org_id,$r_data['NAME'],$v['EVENT_NAME'],$obj_type,$obj_id);
                    $this->InsertRemindPerson($org_id,$v['USER_ID'],$next_id,$v['TO_DESKTOP'],99);
                    $this->InsertMessage($org_id,$user_data['USER_NAME'],$r_data['NAME'],$v['USER_ID'],$v['EVENT_NAME'],$obj_type,$obj_id);
                    
                    if($v['TO_PARENT']){
                        $owner_user_data = $this->GetUser($org_id,$user_data['OWNER_USER_ID']);
                        if($user_data['OWNER_USER_ID'] != 0){
                            $this->InsertRemindPerson($org_id,$user_data['OWNER_USER_ID'],$next_id,$v['TO_DESKTOP'],99);
                            $this->InsertMessage($org_id,$owner_user_data['USER_NAME'],$r_data['NAME'],$user_data['OWNER_USER_ID'],$v['EVENT_NAME'],$obj_type,$obj_id);
                        }
                    }
                    break;
                case '6'://负责员工
                    $this->db->select('user_id');
                    $where = array(
                        'org_id' => $org_id,
                        $obj['OBJ_NAME'].'_ID' =>$obj_id,
                        'is_self' => 1
                        );
                    $this->db->where($where);
                    $this->db->from('rel_'.$obj['OBJ_NAME'].'_user');
                    $ru_data1 = $this->db->get()->result_array();
                    if(!empty($ru_data1)){
                        $user_data = $this->GetUser($org_id,$ru_data1[0]['USER_ID']);

                        $next_id = $this->InsertReminder($org_id,$r_data['NAME'],$v['EVENT_NAME'],$obj_type,$obj_id);
                        $this->InsertRemindPerson($org_id,$ru_data1[0]['USER_ID'],$next_id,$v['TO_DESKTOP'],99);
                        $this->InsertMessage($org_id,$user_data['USER_NAME'],$r_data['NAME'],$ru_data1[0]['USER_ID'],$v['EVENT_NAME'],$obj_type,$obj_id);
                        if($v['TO_PARENT']){
                            if($user_data['OWNER_USER_ID'] != 0){
                                $owner_user_data = $this->GetUser($org_id,$user_data['OWNER_USER_ID']);

                                $this->InsertRemindPerson($org_id,$user_data['OWNER_USER_ID'],$next_id,$v['TO_DESKTOP'],99);
                                $this->InsertMessage($org_id,$owner_user_data['USER_NAME'],$r_data['NAME'],$user_data['OWNER_USER_ID'],$v['EVENT_NAME'],$obj_type,$obj_id);
                            }
                        }
                    }
                    break;
                default:
                    break;
            }
        }
        
    }
    
    private function checkname($event_type=1,$obj_type=3,$org_id=1){
        $this->db->from('tc_event_config');
        $where['tc_event_config.event_type'] = $event_type;
        $where['tc_event_config.obj_type'] = $obj_type;
        $where['tc_event_config.org_id'] = $org_id;
        $this->db->where($where);
        $join_on = 'tc_event_config_user.event_id = tc_event_config.event_id';
        $this->db->join('tc_event_config_user',$join_on,'inner');
        $data = $this->db->get()->result_array();
        foreach ($data as $k => $v) {
            $data[$k]['COND_OBJECT'] = base64_decode($v['COND_OBJECT']->load());
        }
        return $data;
    }
    public function InsertMessage($org_id=1,$user_name,$name,$user_id,$event_name="",$obj_type="",$obj_id=""){
        $time = date("Y-m-d H:i:s");
        $time = $this->objects->isdatetime($time,-8*60*60);
        $next_id = $this->nextid->NewID('TC_MESSAGE_INFO',$org_id);
        $insert = array(
            'org_id' => $org_id,
            'message_id' => $next_id,
            'message_type' => 1,
            'message_to' => $user_name,
            'message_from_id' => 1,
            'message_subject' => $event_name.':'.$name,
            'message_content' => $event_name.':'.$name,
            'create_user_id' => 1,
            'rel_obj_type' => $obj_type,
            'rel_obj_id' =>$obj_id
            );
        $this->db->set('create_time',"to_date('" . $time . "','yyyy-mm-dd hh24:mi:ss')", false);
        if($this->db->insert('TC_MESSAGE_INFO',$insert)){
            $insert2 = array(
                'org_id' => $org_id,
                'message_id' => $next_id,
                'user_id' =>$user_id
                );
            $this->db->insert('REL_MESSAGE_USER',$insert2);
            return $next_id;
        }else{
            return false;
        }
    }

    private function InsertReminder($org_id=1,$name,$event_name,$obj_type,$obj_id){
        
        $next_id = $this->nextid->NewID('TC_REMINDER',$org_id);
        $insert = array(
            'org_id' => $org_id,
            'remind_id' => $next_id,
            'remind_type' => 2,
            'obj_type' => $obj_type,
            'obj_id' =>$obj_id,
            'remind_mesg' => $event_name.':'.$name,
            );
        if($this->db->insert('TC_REMINDER',$insert)){
            return $next_id;
        }else{
            return false;
        }   
    }

    private function InsertRemindPerson($org_id=1,$to_obj_id,$next_id,$is_desktop=1,$to_obj_type=99,$time=null,$arr=array()){
        $time = !empty($time) ? $time : date("Y-m-d H:i:s");
        $time = $this->objects->isdatetime($time,-8*60*60);
        $next_id2 = $this->nextid->NewID('TC_REMIND_PERSON',$org_id);
        $insert = array(
            'org_id' => $org_id,
            'remind_d_id' => $next_id2,
            'remind_id' => $next_id,
            'to_obj_type' => $to_obj_type,
            'to_obj_id' => $to_obj_id,
            'to_desktop' => $is_desktop,
            'is_new' =>1
            );
        $this->db->set('remind_time',"to_date('" . $time . "','yyyy-mm-dd hh24:mi:ss')", false);
        if($this->db->insert('TC_REMIND_PERSON',$insert)){
            return $next_id;
        }else{
            return false;
        }
    }       
}
?>