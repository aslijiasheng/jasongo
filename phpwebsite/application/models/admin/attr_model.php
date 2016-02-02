<?php

/**
 * 例:dd_attribule ORM文件
 */
class Attr_model extends CI_Model {

    public function __construct() {
        parent :: __construct();
        $this->load->model('admin/enum_model', 'enum');
    }

    /**
     * GetObjAttr 获取对象的所有字段信息
     * @param int $OrgID 组织id 
     * @param string $ObjName 对象名称
     * @param bool  $isBoolQuery 用于高级查询过滤18属性字段
     * @return array 返回这个对象相关的所有属性信息的数组 按照属性名称分类
     */
    public function object_attr($OrgID, $ObjName, $isBoolQuery = false) {
        $select = array(
            'dd_attribute.attr_name',
            'dd_attribute.tbl_name',
            'dd_attribute.fld_name',
            'dd_attribute.attr_type',
            'dd_attribute.is_ref_obj',
            'dd_attribute.referred_by',
            'dd_dict_str.LABEL',
            'dd_attribute.REF_OBJ_NAME',
            'dd_attribute.IS_READONLY',
            'dd_attribute.IS_USER_EDITABLE',
            'dd_attribute.IS_HISTORY',
            'dd_attribute.LEE_IS_QUERY',
            'dd_attribute.IS_MUST',
            'dd_attribute.IS_DISP_LIST',
            'dd_attribute.Is_DISP_VIEW',
            'dd_attribute.REFER_TO',
            'dd_attribute.IS_HIDDEN',
            'dd_attribute.DISP_ORDER',
            'dd_attribute.LEE_CALC_FORMULA',
            'dd_attribute.LEE_IS_CALC_POINT',
            'dd_attribute.LEE_CALC_RESULT_ATTR',
            'dd_attribute.IS_UNIQUE',
        );
        $where["dd_attribute.org_id"] = $OrgID;
        if (!empty($ObjName)) {
            $where["dd_attribute.obj_name"] = $ObjName;
        }
        if ($isBoolQuery) {
            $where['dd_attribute.attr_type !='] = 18;
        }
        $this->db->from("dd_attribute");
        $this->db->join("dd_dict_str", "dd_dict_str.dict_name = dd_attribute.attr_name and dd_dict_str.ORG_ID = $OrgID and dd_dict_str.LANG_ID = 2", "inner");
        $this->db->select($select);
        $this->db->where($where);
        $data = $this->db->get()->result_array();
        //循环生成一个以属性名称为KEY的数组,顺便全部改成小写
        foreach ($data as $k => $v) {
            $data_arr[$v['ATTR_NAME']]['tbl_name'] = $v['TBL_NAME'];
            $data_arr[$v['ATTR_NAME']]['fld_name'] = $v['FLD_NAME'];
            $data_arr[$v['ATTR_NAME']]['attr_type'] = $v['ATTR_TYPE'];
            $data_arr[$v['ATTR_NAME']]['attr_cn_name'] = $v['FLD_NAME'];
            $data_arr[$v['ATTR_NAME']]['is_ref_obj'] = $v['IS_REF_OBJ'];
            $data_arr[$v['ATTR_NAME']]['ref_obj_name'] = $v['REF_OBJ_NAME'];
            $data_arr[$v['ATTR_NAME']]['lee_is_query'] = $v['LEE_IS_QUERY'];

            $data_arr[$v['ATTR_NAME']]['is_must'] = $v['IS_MUST'];
            $data_arr[$v['ATTR_NAME']]['is_disp_list'] = $v['IS_DISP_LIST'];
            $data_arr[$v['ATTR_NAME']]['is_disp_view'] = $v['Is_DISP_VIEW'];
            $data_arr[$v['ATTR_NAME']]['is_hidden'] = $v['IS_HIDDEN'];
            $data_arr[$v['ATTR_NAME']]['disp_order'] = $v['DISP_ORDER'];
            $data_arr[$v['ATTR_NAME']]['refer_to'] = $v['REFER_TO'];
            $data_arr[$v['ATTR_NAME']]['is_unique'] = $v['IS_UNIQUE'];
            if ($v['IS_READONLY'] == 1 or $v['IS_USER_EDITABLE'] == 0) {
                $data_arr[$v['ATTR_NAME']]['notedit'] = 1;
            } else {
                $data_arr[$v['ATTR_NAME']]['notedit'] = 0;
            }

            if ($v['IS_REF_OBJ'] == 1) {
                $data_arr[$v['ATTR_NAME']]['referred_by'] = $v['REFERRED_BY'];
                $data_arr[$v['ATTR_NAME']]['ref_obj_name'] = $v['REF_OBJ_NAME'];
            }
            $data_arr[$v['ATTR_NAME']]['label'] = $v['LABEL'];
            $data_arr[$v['ATTR_NAME']]['is_history'] = $v['IS_HISTORY'];
            $data_arr[$v['ATTR_NAME']]['lee_calc_formula'] = $v['LEE_CALC_FORMULA'];
            $data_arr[$v['ATTR_NAME']]['lee_is_calc_point'] = $v['LEE_IS_CALC_POINT'];
            $data_arr[$v['ATTR_NAME']]['lee_calc_result_attr'] = $v['LEE_CALC_RESULT_ATTR'];
        }
        return $data_arr;
    }
    /**
     *对象属性角色权限
     * @param $org_id
     * @param $user_id
     */
    public function RelAttrRole($org_id, $user_id){
        $this->db->select('rar.control,rar.attr_name');
        $this->db->from('rel_attribute_role rar');
        $this->db->join('rel_role_user rru','rru.role_id = rar.role_id and rru.org_id = rar.org_id','inner');
        $where = array('rar.org_id' => $org_id,'rru.user_id'=>$user_id);
        $data = $this->db->where($where)->get()->result_array();

        return $data;
    }
    

    /**
     * 查重去除引用
     * @param type $ObjName 对象名称
     * @return type 返回数据数组
     */
    public function object_attr_distnct($ObjName) {
        $this->db->select("distinct ref_obj_name");
        $this->db->where("obj_name", $ObjName);
        $this->db->from("dd_attribute");
        $dis_data = $this->db->get()->result_array();
        return $dis_data;
    }
    /**
     * 单个属性转换字段名
     * @param $name
     */
    public function name2fld($org_id,$name,$obj_name){
        $this->db->select("fld_name");
        $this->db->from("dd_attribute");
        $this->db->where('attr_name',$name);
        $this->db->where('org_id',$org_id);
        $this->db->where('obj_name',$obj_name);
        $data = $this->db->get()->result_array();
        return $data[0]['FLD_NAME'];
    }

    /**
     * GetSeniorQueryAttr高级查询需要的属性json
     * @param int $OrgID 组织id 
     * @param string $ObjName 对象名称
     * @param $LangID 语言ID 默认2 代表简体中文
     * @return json 返回
     */
    public function GetSeniorQueryAttr($OrgID, $ObjName, $LangID = 2) {
        $select = array(
            'dd_attribute.attr_name',
            'dd_attribute.attr_type',
            'dd_attribute.is_ref_obj',
            'dd_attribute.enum_attr_name',
            'dd_dict_str.LABEL',
        );
        $where["dd_attribute.org_id"] = $OrgID;
        $where["dd_attribute.obj_name"] = $ObjName;
        $where["dd_attribute.attr_type !="] = '18'; //条件里去掉所有的ID后缀的称呼
        $this->db->from("dd_attribute");
        $this->db->join("dd_dict_str", "dd_dict_str.dict_name = dd_attribute.attr_name and dd_dict_str.ORG_ID = $OrgID and dd_dict_str.LANG_ID = $LangID", "inner");
        $this->db->select($select);
        $this->db->where($where);
        $data = $this->db->get()->result_array();
        //循环生成一个以属性名称为KEY的数组,顺便全部改成小写
        $data_arr = array();
        foreach ($data as $k => $v) {
            $data_arr[$v['ATTR_NAME']]['attr_type'] = $v['ATTR_TYPE'];
            $data_arr[$v['ATTR_NAME']]['is_ref_obj'] = $v['IS_REF_OBJ'];

            //这里要根据几种类型来分别处理点东西
            switch ($v['ATTR_TYPE']) {
                case 4://短字符型 判断是否为引用
                    if ($v['IS_REF_OBJ'] == 1) {
                        //引用类型
                    }
                    break;
                //单选枚举
                //case 11://CHECK 是否，这种是否枚举待查看
                case 12://RADIO
                case 13://下拉单选
                case 14://多选枚举
                    //p($v);
                    if ($v['ENUM_ATTR_NAME'] == "") {
                        $data_arr[$v['ATTR_NAME']]['enum_arr'] = $this->enum->GetEnumArr($OrgID, $v['ATTR_NAME'], $LangID);
                    } else {
                        $data_arr[$v['ATTR_NAME']]['enum_arr'] = $this->enum->GetEnumArr($OrgID, $v['ENUM_ATTR_NAME'], $LangID);
                    }
                    break;
                default://其他
                    //待写
                    break;
            }
            $data_arr[$v['ATTR_NAME']]['label'] = $v['LABEL'];
        }
        return json_encode($data_arr);
        //echo json_encode($data);exit;
        //p($data);exit;
    }

    /**
     * 	SelectOne					单一查询
     * 	@params	array	$AttrBean	查询条件
     * 	@return array				返回查询的结果集(限制一条)
     */
    public function SelectOne($AttrBean) {
        extract($AttrBean, EXTR_OVERWRITE);
        $select = empty($select) ? "*" : $select;
        $this->db->select($select);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->from("dd_attribute");
        $data = $this->db->get()->result_array();
        //p($data);
        if (empty($data)) {
            return $data;
        } else {
            return $data[0];
        }
    }

    /**
     * 	UnderlineToPoint			    把前台post的带有下划线的key转换成点
     * 	@params	array	$arr	    前台传递过来的post数值
     * 	@return array		$post		处理过的数组
     */
    public function UnderlineToPoint($arr) {
        if (!( is_array($arr) && count($arr) != 0 )) {
            return false;
        }

        $post = array();
        foreach ($arr as $key => $value) {
            if ($this->UnderlineCount($key, "_")) {
                $key = preg_replace('/_/', ".", $key, 1);
            }
            $post[$key] = $value;
        }

        return $post;
    }

    /**
     * 	UnderlineCount			    统计字符串下划线出现的次数
     * 	@params	string	$str	    前台传递过来的字符串
     * 	@return  bool				如果有下划线就是真
     */
    public function UnderlineCount($str, $find) {
        preg_match_all('/' . $find . '/', $str, $matches);
        if ($matches[0] > 0) {
            return true;
        } else {
            return false;
        }
    }

}
