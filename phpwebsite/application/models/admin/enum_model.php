<?php

/**
 * 例:tc_enum tc_enum_str ORM文件
 */
class Enum_model extends CI_Model {

    public function __construct() {
        parent :: __construct();
    }

    /**
     * 	SelectOneStr					单一查询
     * 	@params	int		$OrgID			ORGID
     * 	@params int		$LangID			语言标识
     * 	@params	string	$AttrName		要过滤的条件字段
     * 	@params	string	$EnumKey		要过滤的条件数据字段
     * 	@return array					返回查询的结果集(限制一条)
     */
    public function SelectOneStr($OrgID, $LangID, $AttrName, $EnumKey) {
        $select = "B.enum_value,A.Identify_Code,B.attr_name,B.enum_key";
        $where = array(
            "A.org_id" => $OrgID,
            "A.attr_name" => $AttrName,
            "B.enum_key" => $EnumKey,
            "B.lang_id" => $LangID,
        );
        $this->db->select($select);
        $this->db->from("tc_enum A");
        $this->db->join("tc_enum_str B", "A.Attr_Name = B.Attr_Name and B.org_id = $OrgID and B.lang_id = $LangID and A.ENUM_KEY = B.Enum_Key", "inner");
        $this->db->where($where);
        $data = $this->db->get()->result_array();

        return $data[0];
    }

    /**
     * 根据条件查询
     * @param int    $OrgID     标识ID
     * @param int    $LangId    语言ID
     * @param string $AttrName  属性名称
     * @return array 返回查询结果数据
     */
    public function SelectWhere($OrgID, $LangId, $AttrName) {
        $select = "B.enum_value ,A.Identify_Code,B.attr_name,B.enum_key";
        $where = array(
            "A.attr_name" => $AttrName,
            "B.lang_id" => $LangId,
        );
        $this->db->select($select);
        $this->db->from("tc_enum A");
        $this->db->join("tc_enum_str B", "A.Attr_Name = B.Attr_Name and B.org_id = $OrgID and B.lang_id = $LangId and A.ENUM_KEY = B.Enum_Key", "inner");
        $this->db->where($where);
        $data = $this->db->get()->result_array();

        return $data;
    }

    /**
     * 全部查询
     * @return array 返回查询的数组数据
     */
    public function SelectAll() {
        $data = $this->db->select("attr_name,parent_attr_name,root_attr_name")->from("tc_enum_relation")->get()->result_array();
        //p($data);die;
        foreach ($data as $key => $value) {
            $data_all['parent'][] = $value['PARENT_ATTR_NAME'];
            $data_all['child'][] = $value['ATTR_NAME'];
        }
        $data_all['parent'] = array_unique($data_all['parent']);
        $data_all['child'] = array_unique($data_all['child']);
        return $data_all;
    }

    public function SelectChild() {
        return $this->db->select("attr_name")->from("tc_enum_relation")->get()->result_array();
    }

    public function SelectParent() {
        return $this->db->select("parent_attr_name")->from("tc_enum_relation")->get()->result_array();
    }

    /**
     * 	SelectOneStr					单一查询
     * 	@param	int		$OrgID			ORGID
     * 	@param	string	$AttrName		要过滤的条件字段
     * 	@param  int		$LangID			语言标识 默认2 简体中文
     * 	@return array					返回查询的结果集(限制一条)
     */
    public function GetEnumArr($OrgID, $AttrName, $LangID = 2) {
        $select = array(
            'enum_value',
            'enum_key',
        );
        $where = array(
            "org_id" => $OrgID,
            "attr_name" => $AttrName,
            "lang_id" => $LangID,
        );
        $this->db->from("tc_enum_str");
        $this->db->select($select);
        $this->db->where($where);
        $data = $this->db->get()->result_array();
        $data_arr = array();
        foreach ($data as $k => $v) {
            $data_arr[$k]['enum_value'] = $v['ENUM_VALUE'];
            $data_arr[$k]['enum_key'] = $v['ENUM_KEY'];
        }
        //p($AttrName);
        return $data_arr;
    }

    public function SelectAllStr($OrgID, $LangId, $AttrName) {
        $select = "B.enum_value,A.Identify_Code,B.attr_name,B.enum_key";
        $where = array(
            "A.org_id" => $OrgID,
            "A.attr_name" => $AttrName,
        );
        $this->db->select($select);
        $this->db->from("tc_enum A");
        $this->db->join("tc_enum_str B", "A.Attr_Name = B.Attr_Name and B.org_id = $OrgID and B.lang_id = $LangId and A.ENUM_KEY = B.Enum_Key", "inner");
        $this->db->where($where);
        $data = $this->db->get()->result_array();
        return $data;
    }

    public function SelectChildEnums($attr_name, $enum_key, $OrgID, $LangID, $obj_name) {
        $old_attr_name = $attr_name;
        $this->load->model("admin/attr_model", "attr");
        $AttrBean["select"] = "ENUM_ATTR_NAME"; //字段
        $AttrBean["where"]["attr_name"] = $attr_name;
        $AttrBean["where"]["org_id"] = $OrgID;
        $attr_data = $this->attr->SelectOne($AttrBean);

        $attr_name = empty($attr_data["ENUM_ATTR_NAME"]) ? $attr_name : $attr_data["ENUM_ATTR_NAME"];
        if(empty($enum_key)){
            $select = "attr_name";
            $this->db->select($select);
            $this->db->from('tc_enum_relation_value');
            $where = array("parent_attr_name" => $attr_name,
                );
            $this->db->where($where);
            $data = $this->db->get()->result_array();
            $enum_attr = $this->enum->SelectAll();
            $ATTR_NAME = $data[0]['ATTR_NAME'];
        }else{
            $select = "a.enum_key, a.enum_value,b.attr_name";
            $this->db->select($select);
            $this->db->from('tc_enum_str a');
            $this->db->join("tc_enum_relation_value b", "a.enum_key=b.attr_enum_key and a.attr_name=b.attr_name", "inner");
            $where = array("b.parent_attr_name" => $attr_name,
                "b.parent_enum_key" => $enum_key,
                "a.lang_id" => $LangID);
            $this->db->where($where);
            $data = $this->db->get()->result_array();
            $enum_attr = $this->enum->SelectAll();

            $AttrBean2["select"] = "ATTR_NAME"; //字段
            $AttrBean2["where"]["enum_attr_name"] = $data[0]['ATTR_NAME'];
            $AttrBean2["where"]["org_id"] = $OrgID;
            $AttrBean2["where"]["obj_name"] = $obj_name;
            $attr_data = $this->attr->SelectOne($AttrBean2);


            $ATTR_NAME = empty($attr_data) ? $data[0]['ATTR_NAME'] : $attr_data["ATTR_NAME"];
            
            if (!empty($data)) {
                foreach ($data as $k => $v) {

                    $r_data[$ATTR_NAME]['enum_arr'][$k]['enum_key'] = $v['ENUM_KEY'];
                    $r_data[$ATTR_NAME]['enum_arr'][$k]['enum_value'] = $v['ENUM_VALUE'];
                }
            }
        }
            $where = array(
                'PARENT_ATTR_NAME' => $attr_name,
                'org_id' => 1,
            );
            $child_data = $this->db->select("attr_name")->from("tc_enum_relation")->where($where)->get()->result_array();

            foreach ($child_data as $key => $v) {

                if (in_array($ATTR_NAME, $enum_attr['parent'])) {
                    $r_data[$ATTR_NAME]['is_parent'] = 1;
                } else {
                    $r_data[$ATTR_NAME]['is_parent'] = 0;
                }
                if (in_array($ATTR_NAME, $enum_attr['child'])) {
                    $r_data[$ATTR_NAME]['is_child'] = 1;
                } else {
                    $r_data[$ATTR_NAME]['is_child'] = 0;
                }
            }


        return $r_data;
    }

}
