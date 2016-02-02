<?php
class Message_model extends CI_Model{
	public function __construct() {
        parent :: __construct();
        $this->load->model('www/objects_model', 'objects');
        $this->load->model('admin/obj_model', 'obj');
        $this->load->model('admin/format_model', 'format');
        $this->load->model('admin/attr_model', 'attr');
        $this->load->model('www/user_model','user');
        $this->load->model('www/type_model','type');
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
        //获取列表需要显示的字段
        $list_format = $this->format->GetListFormat($org_id, $OBJ_TYPE, 0);
        //查询列表数据
        $lead_attr = $this->attr->object_attr($org_id, $obj_name);
        $ColNames = $this->objects->GetListColNames($list_format, $lead_attr, $obj_data);
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
                        'Url' => site_url("www/message/view") . "?id={" . $obj_data['KEY_ATTR_NAME'] . "}&obj_type=" . $obj_type,
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


    public function InfoPagerList($ListFormat, $LeadAttr, $obj_data, $OrgID, $user_id,$CurrentPage = 1, $PageSize = 10, $where = array()) {
        $first_tbl = $obj_data["MAIN_TABLE"]; //主表名称
        $tbl_data = $this->GetTbl($ListFormat, $LeadAttr); //获取表名
        $fld_data = $this->GetFld($ListFormat, $LeadAttr); //获取字段名
        array_push($fld_data, $first_tbl . "." . ucfirst($obj_data["KEY_ATTR_FLD"]));
        $count_tbl = count($tbl_data); //获取得到的表数目
//判断查询是否有过滤条件
        if (!empty($where)) {
            $w = $this->objects->parse_w($OrgID, $where);
            $this->db->where($w, NULL, FALSE);
        }
        $this->db->select($fld_data);
//如果只有一张表无需使用join
        if ($count_tbl == 1) {
            $this->db->from(current($tbl_data));
        } else {
            $this->db->from($first_tbl);
            foreach ($tbl_data as $k => $v) {
                $join_on = "$first_tbl." . $obj_data["KEY_ATTR_FLD"] . " = $v." . $obj_data["KEY_ATTR_FLD"] . " and $v.ORG_ID = $OrgID";
                if ($v == $first_tbl) {
                    continue;
                }
                $this->db->join($v, $join_on, "inner");
            }
        }
        /**
         * 拼接过滤条件 判断 表中主键是否大于0 是否为已删除数据  按创建时间 排序
         */
        $FilterPriKey = $first_tbl . "." . $obj_data["KEY_ATTR_FLD"] . ">=";
//$obj_data['IS_RECYCLABLE'] 是否放入回收站  如果是0则直接删除 如果是1则逻辑删除所以需在查询时做逻辑判断
        if ($obj_data['IS_RECYCLABLE']) {
            $IsDEleteKey = $first_tbl . "." . "is_deleted";
            $FilterWhere[$IsDEleteKey] = 0;
        }
        $FilterWhere[$FilterPriKey] = 1;
        $OrgIDKey = $first_tbl . "." . "org_id";
        $FilterWhere[$OrgIDKey] = 1;
        $FilterWhere['rel_message_user.user_id'] = $user_id;
        $this->db->where($FilterWhere);

        $this->db->order_by($first_tbl . "." . $obj_data["KEY_ATTR_FLD"], "desc");
        /**
         * 拼接过滤条件结束
         */
//开始分页
        $this->db->limit($PageSize, ($CurrentPage - 1) * $PageSize + 1);
        $data = $this->db->get()->result_array();

//将ID主键压入返回的数组字段中
        array_push($ListFormat, $obj_data["KEY_ATTR_NAME"]);
        foreach ($data as $k => $v) {
            if($v['READ_FLAG']==0){    
                $data[$k]['MESSAGE_SUBJECT'] = $v['MESSAGE_SUBJECT']."<span class=\"badge badge-pink\">NEW!</span>";
            }
        }
        if (!empty($data)) {
            $data = $this->objects->FldConverName($data, $ListFormat,$LeadAttr);
            /*             * *开始解析数据类型*** */
            $data = $this->objects->GetDataType($data, $LeadAttr, $OrgID);
            if (!empty($res_data)) {
                $data = _HashData($data, $obj_data["KEY_ATTR_NAME"]);
                $this->objects->InfoMerger($data, $res_data, $attr_data, $for_data);
            } else {
                $res_data = $data;
            }
        }


        $count = $this->InoPageCount($first_tbl, $obj_data, $where, $w, $w1, $w_in,$user_id,$tbl_data);
        return $this->objects->InfoPagers($CurrentPage, $count, $PageSize, $res_data);
    }
    private function GetTbl($NeedAttr, $LeadAttr) {
        $res_data = array(); //要返回的数组信息
        foreach ($NeedAttr as $k => $v) {
            $res_data[$k] = $LeadAttr[$v]["tbl_name"];
        }
        return array_filter(array_unique($res_data));
    }
     private function GetFld($NeedAttr, $LeadAttr) {
        $res_data = array(); //要返回的数组信息
        foreach ($NeedAttr as $k => $v) {
            if (!empty($LeadAttr[$v]["attr_type"])) {
                switch ($LeadAttr[$v]["attr_type"]) {
                    case 4://短字符型 判断是否为引用
                        if (!empty($LeadAttr[$v]["referred_by"])) {
                            $res_data[$k] = $LeadAttr[$LeadAttr[$v]["referred_by"]]["tbl_name"] . "." . $LeadAttr[$LeadAttr[$v]["referred_by"]]["fld_name"];
                        } else {
                            $res_data[$k] = $LeadAttr[$v]["tbl_name"] . "." . $LeadAttr[$v]["fld_name"];
                        }
                        break;
                    case 15://日期型
                        $res_data[$k] = "to_char(" . $LeadAttr[$v]["tbl_name"] . "." . $LeadAttr[$v]["fld_name"] . ", 'yyyy-mm-dd hh24:ii:ss') as " . $LeadAttr[$v]["fld_name"];
                        break;
                    case 16://时间型
                        $res_data[$k] = "to_char(" . $LeadAttr[$v]["tbl_name"] . "." . $LeadAttr[$v]["fld_name"] . ", 'yyyy-mm-dd hh24:ii:ss') as " . $LeadAttr[$v]["fld_name"];
                        break;
                    default://其他
                        $res_data[$k] = $LeadAttr[$v]["tbl_name"] . "." . $LeadAttr[$v]["fld_name"];
                        break;
                }
            }
        }

        return array_unique($res_data);

    }
    private function InoPageCount($first_tbl, $obj_data, $where, $w, $w1, $w_in,$user_id,$tbl_data) {
        /*         * *完成解析数据类型*** */
        
        if (!empty($where)) {
            $this->db->where($w, NULL, FALSE);
        }
        if (!empty($w_in)) {
            $this->db->where($w_in, NULL, FALSE);
        }
        /**
         * 拼接过滤条件 判断 表中主键是否大于0 是否为已删除数据  按创建时间 排序
         */
            $this->db->from($first_tbl);
            foreach ($tbl_data as $k => $v) {
                $join_on = "$first_tbl." . $obj_data["KEY_ATTR_FLD"] . " = $v." . $obj_data["KEY_ATTR_FLD"] . " and $v.ORG_ID = 1";
                if ($v == $first_tbl) {
                    continue;
                }
                $this->db->join($v, $join_on, "inner");
            }
        
        $FilterPriKey = $first_tbl . "." . $obj_data["KEY_ATTR_FLD"] . ">=";
//$obj_data['IS_RECYCLABLE'] 是否放入回收站  如果是0则直接删除 如果是1则逻辑删除所以需在查询时做逻辑判断
        if ($obj_data['IS_RECYCLABLE']) {
            $IsDEleteKey = $first_tbl . "." . "is_deleted";
            $FilterWhere[$IsDEleteKey] = 0;
        }
        $FilterWhere[$FilterPriKey] = 1;
        $OrgIDKey = $first_tbl . "." . "org_id";
        $FilterWhere[$OrgIDKey] = 1;
        $FilterWhere['rel_message_user.user_id'] = $user_id;
        $this->db->where($FilterWhere);
        /**
         * 拼接数据权限
         */
        if(!empty($w1)){
            
            $this->db->where($w1, NULL, FALSE);
        }
        /**
         * 拼接数据权限结束
         */
        $count = $this->db->count_all_results();
        return $count;
    }

    public function countn4($user_id){
        $this->db->select('a.message_subject,a.message_id');
        $this->db->from('tc_message_info a');
        $join_on = 'a.org_id=b.org_id and a.message_id =b.message_id';
        $this->db->join('rel_message_user b', $join_on, "inner");
        /**
         * 拼接过滤条件 判断 表中主键是否大于0 是否为已删除数据  按创建时间 排序
         */
        $FilterPriKey = "a.message_id>=";
        $IsDEleteKey = "a.is_deleted";
        $FilterWhere[$IsDEleteKey] = 0;
        $FilterWhere[$FilterPriKey] = 1;
        $OrgIDKey = "a.org_id";
        $FilterWhere[$OrgIDKey] = 1;
        $FilterWhere['b.user_id'] = $user_id;
        $FilterWhere['b.read_flag'] =0;
        $this->db->where($FilterWhere);

        $this->db->order_by("a.message_id", "desc");
        /**
         * 拼接过滤条件结束
         */

        $this->db->limit(4,1);
        $ret['data'] = $this->db->get()->result_array();
        foreach ($ret['data'] as $k => $v) {
            if(mb_strlen ( $v['MESSAGE_SUBJECT'],'utf-8')>=15){
                $ret['data'][$k]['MESSAGE_SUBJECT'] = mb_substr($v['MESSAGE_SUBJECT'],0,15,'utf-8').'...';
            }else{
                $ret['data'][$k]['MESSAGE_SUBJECT'] = $v['MESSAGE_SUBJECT'];
            }
            
            $ret['data'][$k]['url'] = 'index.php/www/message/view?id='.$v['MESSAGE_ID'].'&obj_type=170';
        }
        $this->db->select('message_subject');
        $this->db->from('tc_message_info a');
        $this->db->join('rel_message_user b', $join_on, "inner");
        $this->db->where($FilterWhere);
        $this->db->order_by("a.message_id", "desc");
        $ret['count'] = $this->db->count_all_results();
        return $ret;
    }
    //消息新增
    public function Getinfo($OrgID, $ObjAttr, $user_id,$TypeID){
        foreach ($ObjAttr as $k => $v) {
            switch ($v['attr_type']) {
                case 4://是否为引用
                    if ($v['is_ref_obj']) {
                        //对象查到表
                        $RefObjName = $v["ref_obj_name"]; //取obj_name名称
                        $obj_data = $this->obj->NameGetObj($OrgID, $RefObjName);
                        //属性名转换为字段名
                        $AttrBean["select"] = "TBL_NAME,FLD_NAME,OBJ_NAME"; //字段
                        $AttrBean["where"]["attr_name"] = $obj_data["NAME_ATTR_NAME"];
                        $AttrBean["where"]["org_id"] = $OrgID;
                        $attr_data = $this->attr->SelectOne($AttrBean);
                        //查出值
                        $fld_name = strtoupper($ObjAttr[$v['referred_by']]['fld_name']);
                        $object_data[$k . '.RefUrl'] = $this->RefUrl . $obj_data['OBJ_TYPE'];
                        $object_data[$k . '.Name'] = "";
                        $object_data[$k . '.ObjName'] = $DictAttr[$attr_data["OBJ_NAME"]]["LABEL"];
                        $object_data[$k . '.DialogWidth'] = $this->DialogWidth;
                        $object_data[$k . '.ID'] = "";
                        $object_data[$k] = "";
                        
                    } else { 
                        $object_data[$k] = "";
                    }
                    break;
                case 12:
                    $AttrBean["select"] = "ENUM_ATTR_NAME"; //字段
                    $AttrBean["where"]["attr_name"] = $k;
                    $AttrBean["where"]["org_id"] = $OrgID;
                    $attr_data = $this->attr->SelectOne($AttrBean);
                    $LangId = 2;
                    $AttrName = empty($attr_data["ENUM_ATTR_NAME"]) ? $k : $attr_data["ENUM_ATTR_NAME"];
                    $fld_name = strtoupper($v['fld_name']);
                    
                    $object_data[$k] = "";
                    $object_data[$k . '.enum_key'] = "";
                    $object_data[$k . '.value'] = "";
                    $enum_arr = $this->enum->GetEnumArr($OrgID, $AttrName, $LangID = 2);
                    $object_data[$k . '.attr_type'] = $v['attr_type'];
                    $object_data[$k . '.enum_arr'] = $enum_arr;
                    break;
                case 13://下拉单选查出枚举值
                    $AttrBean["select"] = "ENUM_ATTR_NAME"; //字段
                    $AttrBean["where"]["attr_name"] = $k;
                    $AttrBean["where"]["org_id"] = $OrgID;
                    $attr_data = $this->attr->SelectOne($AttrBean);
                    $LangId = 2;
                    $AttrName = empty($attr_data["ENUM_ATTR_NAME"]) ? $k : $attr_data["ENUM_ATTR_NAME"];
                    $fld_name = strtoupper($v['fld_name']);
                    $object_data[$k] = "";
                    $object_data[$k . '.enum_key'] = "";
                    $object_data[$k . '.value'] = "";
                    $enum_arr = $this->enum->GetEnumArr($OrgID, $AttrName, $LangID = 2);
                    $object_data[$k . '.attr_type'] = $v['attr_type'];
                    $object_data[$k . '.enum_attr_name'] = $AttrName;
                    $object_data[$k . '.enum_arr'] = $enum_arr;
                    
                    break;
                case 14:
                    break;
                case 15://日期型
                    $object_data[$k] = "";
                    break;
                case 16://时间型
                    $object_data[$k] = "";
                    break;
                default:
                    $object_data[$k] = "";
                    break;
            }
        }
        $time = date("Y-m-d H:i:s");
        //特殊
        $userdata = $this->user->SelectOne($OrgID, $user_id);
        $object_data['MessageItem.CreatedByID'] = $user_id;
        $object_data['MessageItem.CreatedBy.ID'] = $user_id;
        $object_data['MessageItem.CreatedBy'] = $userdata['Name'];
        $object_data['MessageItem.CreatedBy.Name'] = $userdata['Name'];
        $object_data['MessageItem.CreatedTime'] = $time;
        $object_data['MessageItem.Type.enum_key'] = $TypeID;
        if($TypeID==1){
            $object_data['MessageItem.Type'] = "系统消息";
            $object_data['MessageItem.Type.value'] = "系统消息";
        }else{
            $object_data['MessageItem.Type'] = "员工消息";
            $object_data['MessageItem.Type.value'] = "员工消息";
        }
        
        return $object_data;
    }
    //更新是否已读
    public function read_flag($id,$org_id,$user_id){
        $update['read_flag'] = 1;
        $where = array(
            'message_id' => $id,
            'org_id' => $org_id,
            'user_id' => $user_id
            );
        $this->db->where($where);
        $this->db->update('rel_message_user',$update);
    }


    //截取字符串英文
    public function substr_cut($str_cut,$length){
        if (strlen($str_cut) > $length){
            for($i=0; $i < $length; $i++)
            if (ord($str_cut[$i]) > 128)    $i++;
            $str_cut = substr($str_cut,0,$i)."..";
        }
        return $str_cut;
    }
}
?>