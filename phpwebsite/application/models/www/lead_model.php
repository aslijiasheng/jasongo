<?php

class Lead_model extends CI_Model {

    private $module = "Lead";
    
    private $RefType = "18";
    
    private $RefUrl = "";
    
    private $DialogWidth = 800;

    public function __construct() {
        parent :: __construct();
        $this->load->model("admin/obj_model", "obj");
        $this->load->model("admin/enum_model", "enum");
        $this->load->model("www/objects_model", "objects");
        $this->RefUrl = @$this->config->base_url() . @$this->config->item(@index_page) . "/www/objects/ajax_lists?obj_type=";
    }





    /**
     * GetInfoView 查看页面单个线索数据
     * @param int $OrgID 组织ID
     * @param array $ViewFormat 查看页面布局
     * @param array $LeadAttr 线索的所有属性信息
     * @param int $LeadID 线索ID
     * @return json 返回前台调用的json数组
     */
    public function GetInfoView($org_id, $ViewFormat, $lead_data, $LeadAttr, $lead_id) {
        foreach ($ViewFormat['tables'] as $k => $v) {
            foreach ($v['cells'] as $key => $value) {
                if (!empty($value['name'])) {
                    if ($value['label']) {
                        $ViewFormat['tables'][$k]['cells'][$key]['attr_label'] = $LeadAttr[$value['name']]['label'];
                    } else {
                        $ViewFormat['tables'][$k]['cells'][$key]['content'] = $lead_data[$value['name']];
                    }
                } else {
                    if ($value['label']) {
                        $ViewFormat['tables'][$k]['cells'][$key]['attr_label'] = "";
                    } else {
                        $ViewFormat['tables'][$k]['cells'][$key]['content'] = "";
                    }
                }
            }
        }
        return $ViewFormat;
    }

    /**
     * GetInfoEdit 查看页面单个线索数据
     * @param int $OrgID 组织ID
     * @param array $ViewFormat 查看页面布局
     * @param array $LeadAttr 线索的所有属性信息
     * @param int $LeadID 线索ID
     * @return json 返回前台调用的json数组
     */
    public function GetInfoEdit($org_id, $ViewFormat, $lead_data, $LeadAttr, $lead_id, $enum_attr) {
        foreach ($ViewFormat['tables'] as $k => $v) {
            foreach ($v['cells'] as $key => $value) {
                if (!empty($value['name'])) {
                    if ($value['label']) {
                        $ViewFormat['tables'][$k]['cells'][$key]['attr_label'] = $LeadAttr[$value['name']]['label'];
                    } else {

                        $ViewFormat['tables'][$k]['cells'][$key]['content'] = $lead_data[$value['name']];
                        if ($LeadAttr[$value['name']]['is_ref_obj']) {
                            $ViewFormat['tables'][$k]['cells'][$key]['attr_type'] = $this->RefType;
                        } else {
                            $ViewFormat['tables'][$k]['cells'][$key]['attr_type'] = $LeadAttr[$value['name']]['attr_type'];
                        }


                        $ViewFormat['tables'][$k]['cells'][$key]['form_data']['value'] = $lead_data[$value['name']];
                        $ViewFormat['tables'][$k]['cells'][$key]['form_data']['name'] = $value['name'];
                        $ViewFormat['tables'][$k]['cells'][$key]['form_data']['required'] = $value['required'];
                        $ViewFormat['tables'][$k]['cells'][$key]['form_data']['notedit'] = $LeadAttr[$value['name']]['notedit'];

                        if (isset($lead_data[$value['name'] . '.enum_arr'])) {
                            //$ViewFormat['tables'][$k]['cells'][$key]['enum_arr'] = $lead_data[$value['name'].'.enum_arr'];
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['key'] = isset($lead_data[$value['name'] . ".enum_key"]) ? $lead_data[$value['name'] . ".enum_key"] : "";
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['enum_arr'] = $lead_data[$value['name'] . '.enum_arr'];
                        }
                        if ($LeadAttr[$value['name']]['is_ref_obj']) {
                            $ViewFormat['tables'][$k]['cells'][$key]['referred_by'] = $LeadAttr[$value['name']]['referred_by'];
                            $ViewFormat['tables'][$k]['cells'][$key]['ref_obj_name'] = $LeadAttr[$value['name']]['ref_obj_name'];
                        }
                        if (isset($lead_data[$value['name'] . '.ObjName'])) {
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['ObjName'] = $lead_data[$value['name'] . ".ObjName"];
                        }
                        if (isset($lead_data[$value['name'] . '.RefUrl'])) {
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['RefUrl'] = $lead_data[$value['name'] . '.RefUrl'];
                        }
                        if (isset($lead_data[$value['name'] . '.DialogWidth'])) {
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['DialogWidth'] = $lead_data[$value['name'] . '.DialogWidth'];
                        }
                        $value_name = $value['name'];
                        if (isset($lead_data[$value['name'] . ".enum_attr_name"])) {
                            $value_name = $lead_data[$value['name'] . ".enum_attr_name"];
                        }
                        if (in_array($value_name, $enum_attr['parent'])) {
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['is_parent'] = 1;
                        } else {
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['is_parent'] = 0;
                        }
                        if (in_array($value_name, $enum_attr['child'])) {
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['is_child'] = 1;
                        } else {
                            $ViewFormat['tables'][$k]['cells'][$key]['form_data']['is_child'] = 0;
                        }
                    }
                } else {
                    if ($value['label']) {
                        $ViewFormat['tables'][$k]['cells'][$key]['attr_label'] = "";
                    } else {
                        $ViewFormat['tables'][$k]['cells'][$key]['content'] = "";
                    }
                }
            }
        }
       
        return $ViewFormat;
    }

    /**
     * Getinfo 查出线索所有内容
     * @param $OrgID    组织id
     * @param $LeadID   线索id
     * return array $content
     */
    public function GetAll($OrgID,$LeadID) {
        $this->db->from("tc_lead ");
        $this->db->join("tc_lead_1_1 ", "tc_lead_1_1.org_id=$OrgID and tc_lead_1_1.lead_id=$LeadID", "inner");
        $this->db->join("tc_lead_attr", "tc_lead_attr.org_id=$OrgID and tc_lead_attr.lead_id=$LeadID", "inner");
        $this->db->join("tc_lead_xattr ", "tc_lead_xattr.org_id=$OrgID and tc_lead_xattr.lead_id=$LeadID", "inner");
        $this->db->where("tc_lead.org_id=$OrgID and tc_lead.lead_id=$LeadID");
        $content = $this->db->get()->result_array();
        return $content;
    }

    /**
     * Getinfo 查出线索所有内容
     * @param $OrgID    组织id
     * @param $DictAttr  查询出的属性对应中文名称数据
     * @param $LeadAttr  属性信息
     * @param $LeadID   线索id
     * @param $is_edit  是否为编辑页面
     * return array $lead_data
     */
    public function Getinfo($OrgID, $DictAttr, $LeadAttr, $LeadID, $is_edit = false) {
        if ($LeadID) {
            $content = $this->GetAll($OrgID, $LeadID);
        }
        foreach ($LeadAttr as $k => $v) {

            switch ($v['attr_type']) {
                case 4://是否为引用
                    if ($v['is_ref_obj']) {
                        //对象查到表
                        $ObjName = $v["ref_obj_name"]; //取obj_name名称
                        $obj_data = $this->obj->NameGetObj($OrgID, $ObjName);
                        //属性名转换为字段名
                        $AttrBean["select"] = "TBL_NAME,FLD_NAME,OBJ_NAME"; //字段
                        $AttrBean["where"]["attr_name"] = $obj_data["NAME_ATTR_NAME"];
                        $AttrBean["where"]["org_id"] = $OrgID;
                        $attr_data = $this->attr->SelectOne($AttrBean);
                        //查出值
                        $fld_name = strtoupper($LeadAttr[$v['referred_by']]['fld_name']);
                        if (isset($content)) {
                            $module = "www/" . $attr_data["OBJ_NAME"] . "_model";
                            $this->load->model($module, $ObjName);
                            $r_data = $this->$ObjName->SelectOne($OrgID, $content[0][$fld_name]);
                            $lead_data[$k] = $r_data['Name'];
                            $lead_data[$k . '.Name'] = $r_data['Name'];
                            $lead_data[$k . '.ObjName'] = $DictAttr[$attr_data["OBJ_NAME"]]["LABEL"];
                            $lead_data[$k . '.RefUrl'] = $this->RefUrl . $obj_data['OBJ_TYPE'];
                            $lead_data[$k . '.DialogWidth'] = $this->DialogWidth;
                            $lead_data[$k . '.ID'] = $r_data['ID'];
                            $LeadAttr[$k]['content'] = $r_data;
                        } else {
                            $lead_data[$k] = "";
                        }
                    } else {
                        $fld_name = strtoupper($v['fld_name']);
                        if (!empty($fld_name)) {
                            if (isset($content)) {
                                if (is_object($content[0][$fld_name])) {
                                    $content[0][$fld_name] = base64_decode($content[0][$fld_name]->load());
                                }
                                $lead_data[$k] = $content[0][$fld_name];
                            } else {
                                $lead_data[$k] = "";
                            }
                        } else {
                            $lead_data[$k] = "";
                        }
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
                    if (!empty($content[0][$fld_name])) {
                        
                        $enum = $this->enum->SelectOneStr($OrgID, $LangId, $AttrName, $content[0][$fld_name]);
                        
                        $lead_data[$k] = $enum['ENUM_VALUE'];
                        $lead_data[$k . '.enum_key'] = $content[0][$fld_name];
                        $lead_data[$k . '.value'] = $enum['ENUM_VALUE'];
                    } else {
                        $lead_data[$k] = "";
                        $lead_data[$k . '.enum_key'] = "";
                        $lead_data[$k . '.value'] = "";
                    }
                    if ($is_edit) {

                        $enum_arr = $this->enum->GetEnumArr($OrgID, $AttrName, $LangID = 2);
                        $lead_data[$k . '.attr_type'] = $v['attr_type'];
                        $lead_data[$k . '.enum_arr'] = $enum_arr;
                    }
                    break;
                case 13://下拉单选查出枚举值
                    $AttrBean["select"] = "ENUM_ATTR_NAME"; //字段
                    $AttrBean["where"]["attr_name"] = $k;
                    $AttrBean["where"]["org_id"] = $OrgID;
                    $attr_data = $this->attr->SelectOne($AttrBean);
                    $LangId = 2;
                    $AttrName = empty($attr_data["ENUM_ATTR_NAME"]) ? $k : $attr_data["ENUM_ATTR_NAME"];
                    $fld_name = strtoupper($v['fld_name']);
                    if (!empty($content[0][$fld_name])) {
                      
                        $enum = $this->enum->SelectOneStr($OrgID, $LangId, $AttrName, $content[0][$fld_name]);
                       
                        $lead_data[$k] = $enum['ENUM_VALUE'];
                        $lead_data[$k . '.enum_key'] = $content[0][$fld_name];
                        $lead_data[$k . '.enum_attr_name'] = $AttrName;
                        $lead_data[$k . '.value'] = $enum['ENUM_VALUE'];
                    } else {
                        $lead_data[$k] = "";
                        $lead_data[$k . '.enum_key'] = "";
                        $lead_data[$k . '.enum_attr_name'] = $AttrName;
                        $lead_data[$k . '.value'] = "";
                    }
                    if ($is_edit) {
                        $enum_arr = $this->enum->GetEnumArr($OrgID, $AttrName, $LangID = 2);
                        $lead_data[$k . '.attr_type'] = $v['attr_type'];
                        $lead_data[$k . '.enum_attr_name'] = $AttrName;
                        $lead_data[$k . '.enum_arr'] = $enum_arr;
                    }
                    break;
                case 14:
                    break;
                case 15://日期型
                    $fld_name = strtoupper($v['fld_name']);
                    if (isset($content)) {
                        $content[0][$fld_name] = $this->ToChar($fld_name, $v['tbl_name'], $OrgID, $LeadID);
                        if (empty($content[0][$fld_name])) {
                            $lead_data[$k] = "";
                        } else {
                            $lead_data[$k] = $this->isDate($content[0][$fld_name], 8 * 60 * 60);
                        }
                    } else {
                        $lead_data[$k] = "";
                    }
                    break;
                case 16://时间型
                    $fld_name = strtoupper($v['fld_name']);
                    if (isset($content)) {
                        $content[0][$fld_name] = $this->ToChar($fld_name, $v['tbl_name'], $OrgID, $LeadID);
                        if (empty($content[0][$fld_name])) {
                            $lead_data[$k] = "";
                        } else {
                            $lead_data[$k] = $this->isDateTime($content[0][$fld_name], 8 * 60 * 60);
                        }
                    } else {
                        $lead_data[$k] = "";
                    }
                    break;
                default:
                    $fld_name = strtoupper($v['fld_name']);
                    if (!empty($fld_name)) {
                        if (isset($content)) {
                            if (is_object($content[0][$fld_name])) {
                                $content[0][$fld_name] = base64_decode($content[0][$fld_name]->load());
                            }
                            $lead_data[$k] = $content[0][$fld_name];
                        } else {
                            $lead_data[$k] = "";
                        }
                    } else {
                        $lead_data[$k] = "";
                    }
                    break;
            }
        }
        return $lead_data;
    }

    private function ToChar($fld_name, $tbl_name, $OrgID, $LeadID) {
        $this->db->select("to_char(" . $fld_name . ", 'yyyy-mm-dd hh24:ii:ss') as d");
        $this->db->from($tbl_name);
        $where = array('LEAD_ID' => $LeadID,
            'ORG_ID' => $OrgID);
        $this->db->where($where);
        $dddd = $this->db->get()->result_array();
        return($dddd[0]['D']);
    }

    public function update($leadID, $LeadAttr, $data) {
        foreach ($data as $k => $v) {
            if ($LeadAttr[$k]['tbl_name'] == 'tc_lead') {
                $fld_name = strtoupper($LeadAttr[$k]['fld_name']);
                $update1[$fld_name] = $v;
            } elseif ($LeadAttr[$k]['tbl_name'] == 'tc_lead_1_1') {
                $fld_name = strtoupper($LeadAttr[$k]['fld_name']);
                $update2[$fld_name] = $v;
            } elseif ($LeadAttr[$k]['tbl_name'] == 'tc_lead_attr') {
                $fld_name = strtoupper($LeadAttr[$k]['fld_name']);
                $update3[$fld_name] = $v;
            } else {
                $fld_name = strtoupper($LeadAttr[$k]['fld_name']);
                $update4[$fld_name] = $v;
            }
        }
        $this->db->update('tc_lead', $update1, 'LEAD_ID=' . $leadID);
        $this->db->update('tc_lead', $update2, 'LEAD_ID=' . $leadID);
        $this->db->update('tc_lead', $update3, 'LEAD_ID=' . $leadID);
        $this->db->update('tc_lead', $update4, 'LEAD_ID=' . $leadID);
    }

    /**
     * 	isDate					日期转换
     * 	@params	date	$date	要转换的日期
     * 	@parmas	int		$experis时间间隔
     * 	@return	date			返回日期格式数据
     */
    private function isDate($date, $experis) {
        return date("Y-m-d", strtotime($date) + $experis);
    }

    /**
     * 	isDateTime				日期时间转换
     * 	@params	date	$date	要转换的日期
     * 	@parmas	int		$experis时间间隔
     * 	@return	date			返回日期格式数据
     */
    private function isDateTime($DateTime, $experis) {
        return date("Y-m-d h:i:s", strtotime($DateTime) + $experis);
    }
    /**
     * 	GetObjTypeData				得到线索界面下拉框的数据
     * 	@params	arrar 	     $objTypeArr	对象线索的名称
     *  @return arrar $type_data_arr 线索界面下拉框的数组
     */

    public function GetObjTypeData($objTypeArr){
        if( ! ( is_array($objTypeArr) && count($objTypeArr) != 0 ) ){
            return false;
        }
        $select = array(
            "TYPE_NAME",
            "Type_ID"
        );
        $type_data_arr = array();
        foreach($objTypeArr as $value) {
            $condition = array(
                'OBJ_TYPE' => $value,
                "ORG_ID" => "1"
            );
            $type_data_arr[$value] = $this ->db
                -> select($select) -> where($condition)
                -> from("tc_type") -> get()
                ->result_array();
        }
        return $type_data_arr;
    }
    /**
     * 	GetAccountName		得到线索的名称
     * 	@params	int 	$id	 线索的id
     * 	@return	string	  线索的名称
     */
    public function GetAccountName($id) {
        $select = "LEAD_NAME";
        $condition = array(
            'LEAD_ID' => $id,
            "ORG_ID" => "1"
        );
        $data = $this ->db
            -> select($select) -> where($condition)
            -> from("TC_LEAD") -> get()
            -> row_array();

        
        if(count($data)){
            return $data[$select];
        }else{
            return false;
        }

    }
    /**
     * 	getAccountArr		得到线索转化联系人的数组
     * 	@params	array 	$arr	 线索的全部数据数组
     * 	@params	int 	$profile_id	 线索转化的类型
     * 	@return	array	 线索转化联系人的数组
     */
    public function getAccountArr($arr,$profile_id){
        if( ! ( is_array($arr) && count($arr) != 0 ) ){
            return false;
        }

        $tc_account = $this -> SrcTransformDest($profile_id);
        /*
         * 循环组合好的数组如果组合数组的value和总数组的key一样说明匹配到了 把组合字段的key作为新数组的key 总数组的值作为value
         * 拼成待插入的数组
         *
        * */
        $data = array();
        foreach($tc_account as $key => $value){
            foreach($arr as $k => $v){
                if( $k == $value){   // strpos($k,$value) !== false
                    $data[$key] = $v;
                    foreach($arr as $kk => $vv){
                        if(strpos($kk,$k.'.') !== false){
                            $new = str_replace($k,$key,$kk);
                            $data[$new] = $vv;
                        }
                    }
                }
            }
        }
        return $data;
    }
    /**
     * 	getAccountArr		查询在tc_profile_trans表中账户和线索的对应关系数组
     * 	@params	array 	$arr	 线索的全部数据数组
     * 	@return	array	 线索转化后的数组
     */
    public function SrcTransformDest($profile_id){
        $select = array(
           'SRC_ATTRIBUTE',
           'DEST_ATTRIBUTE'
        );
        $condition = array(
            'pt.profile_id' => $profile_id,
        );
        $query = $this -> db -> from("tc_profile_trans pt") ->
                        join('dd_attribute a1','pt.src_attribute = a1.attr_name and a1.org_id = 1','inner') ->
                        join('dd_attribute a2','pt.dest_attribute = a2.attr_name and a2.org_id = 1','inner') ->
                        where($condition) -> where('a1.attr_name IS NOT NULL', null, false)  ->
                        where('a2.attr_name is not null', null, false) ->
                        select($select) ->get() ->result_array();
        $data = array();
        //按照结果集中一一对应的关系组合成一个key是需要的字段作为插入数据库的字段名称value是和总数组匹配的字段得到总数组的值作为插入数据库的值
        foreach($query as $value){
            if( ! empty($value["DEST_ATTRIBUTE"]) ) {
                $data[$value["DEST_ATTRIBUTE"]] = $value["SRC_ATTRIBUTE"];
            }

        }
        return $data;
    }

    /**
     * 线索转化成功之后修改线索转化的标示
     * $id 是线索的id编号
     * $res 修改之后的结果
     */
    public function lead_success($id){
        $data["Lead_int04"] = 1001;
        $data["last_transfer_date"] =  "to_date('" . dataReduce8("Y-m-d H:i:s", date('Y-m-d H:i:s')) . "','yyyy-mm-dd hh24:mi:ss')";
        foreach ($data as $k => $v) {
            if ($k == "last_transfer_date") {
                $this->db->set($k, $v, false);
            } else {
                $this->db->set($k, $v);
            }
        }
        $this->db->where('lead_id', $id);
        $res = $this->db->update('tc_lead');
        return $res;
    }

}

?>
