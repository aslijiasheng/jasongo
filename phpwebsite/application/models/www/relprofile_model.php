<?php
/**
 * Description of relprofile_model
 *
 * @author Administrator
 */
class relprofile_model extends CI_Model {

    //put your code here
    function __construct() {
        parent::__construct();
    }

    /**
     * 初始化解析数据
     */
    public function parse_profile($user_id, $org_id, $obj_type, $type_id, $is_init = false) {
        if ($is_init) {
            $sql = " select * from tc_user_rel_profile where  org_id = 1 and rel_tables is not null";
        } else {
            $sql = " select * from tc_user_rel_profile where  org_id = 1 and obj_type = $obj_type and type_id = $type_id and (user_id = $user_id or user_id = 0) and is_disp = 1";
        }
        $profile_data = $this->db->query($sql)->result_array();
        $cond_data = $this->rel_to_cond($profile_data, $org_id);
        echo json_encode($cond_data);
        foreach ($cond_data as $k => $v) {
            $obj_type = $v['obj_type'];
            $rel_obj_type = $v['rel_obj_type'];
            unset($v['obj_type']);
            unset($v['rel_obj_type']);
            $contion = json_encode($v);
            $w = array(
                "obj_type" => $obj_type,
                "rel_obj_type" => $rel_obj_type,
            );
            $contion = array(
                "lee_condition" => $contion,
            );
            $this->db->where($w);
            $this->db->update("tc_user_rel_profile", $contion);
        }
        die;
    }

    /**
     * 解析出新数据结构条件
     */
    public function rel_to_cond($profile_data, $org_id) {
        $cond_data = array();
        /**
         * 旧 rel_opportunity_user:Opportunity.ID,optnty_id,MF:User.ID,user_id,SF;rel_opportunity_stage_user:Opportunity.ID,optnty_id,MF:User.ID,user_id,SF;rel_object_role_user:Opportunity.ID,obj_id,MF:User.ID,user_id,SF:obj_type,4,FC
         * 新 array(
          rel_opportunity_user => array(
          rel_opportunity_user.optnty_id=>tc_opportunity.optnty_id
          ),
          tc_user=> array(
          tc_user.user_id=>rel_opportunity_user.user_id,
          tc_user.org_id => 1
          )
          )
         */
        foreach ($profile_data as $key_pro => $value_pro) {
            $main_obj = $value_pro['OBJ_TYPE']; //主对象
            $rel_obj = $value_pro['REL_OBJ_TYPE']; //子对象
            $cond_data[] = $this->get_attrs($main_obj, $rel_obj, $value_pro['REL_TABLES'], $org_id);
        }
        return $cond_data;
    }

    public function get_attrs($main_obj, $rel_obj, $rel_tables, $org_id) {
        $main_obj_res = $this->get_obj_attr($main_obj, $org_id); //主对象详细信息
        $rel_obj_res = $this->get_obj_attr($rel_obj, $org_id); //子对象详细信息
        $res_data = array();
        $res_data['obj_type'] = $main_obj;
        $res_data['rel_obj_type'] = $rel_obj;
        $main_tbl_name = $main_obj_res['objects']['MAIN_TABLE'];
        $main_primary_key = $main_obj_res['objects']['KEY_ATTR_FLD'];
        if (!strpos($rel_tables, ";")) { //没有找到多余的;号证明只有一个结构体
            //取出的结构体tc_product_virtual:Product.ID,m_prod_id,MF:Product.ID,s_prod_id,SF
            $rel_struct = explode(":", $rel_tables);
            $rel_tbl_struct = $rel_struct[0];
            unset($rel_struct[0]);
            /**
             * tc_product_virtual
             *  Product.ID,m_prod_id MF
             *  Product.ID,s_prod_id SF
             */
            //=>
            /**
             * array(
             *  tc_product_virtual => array(
             *      主.主ID = tc_product_virtual.m_prod_id
             *  ),
             *  子对象表名 => array(
             *      tc_product_virtual.s_prod_id = 子.s_prod_id
             *  )
             * )
             */
            foreach ($rel_struct as $k => $v) {
                $rel_v = explode(",", $v);
                if ($rel_v[2] == "MF") {
                    $res_data[$main_obj_res['objects']['MAIN_TABLE']][$rel_tbl_struct] = array(
                        $main_obj_res['objects']['MAIN_TABLE'] . "." . $main_obj_res['objects']['KEY_ATTR_FLD'] => $rel_tbl_struct . "." . $rel_v[1],
                    );
                } else if ($rel_v[2] == "SF") {
                    $res_data[$main_obj_res['objects']['MAIN_TABLE']][$rel_obj_res['objects']['MAIN_TABLE']] = array(
                        $rel_tbl_struct . "." . $rel_v[1] => $rel_obj_res['objects']['MAIN_TABLE'] . "." . $rel_v[1],
                    );
                }
            }
            return $res_data;
        } else { //有N个结构体以;号隔开
            //rel_opportunity_user:Opportunity.ID,optnty_id,MF:User.ID,user_id,SF;rel_opportunity_stage_user:Opportunity.ID,optnty_id,MF:User.ID,user_id,SF;rel_object_role_user:Opportunity.ID,obj_id,MF:User.ID,user_id,SF:obj_type,4,FC
            $rel_sub_tables = substr($rel_tables, 0, strpos($rel_tables, ";"));
            $rel_struct = explode(":", $rel_sub_tables);
            $rel_tbl_struct = $rel_struct[0];
            unset($rel_struct[0]);
            foreach ($rel_struct as $k => $v) {
                $rel_v = explode(",", $v);
                if ($rel_v[2] == "MF") {
                    $res_data[$main_obj_res['objects']['MAIN_TABLE']][$rel_tbl_struct] = array(
                        $main_obj_res['objects']['MAIN_TABLE'] . "." . $main_obj_res['objects']['KEY_ATTR_FLD'] => $rel_tbl_struct . "." . $rel_v[1],
                    );
                } else if ($rel_v[2] == "SF") {
                    $res_data[$main_obj_res['objects']['MAIN_TABLE']][$rel_obj_res['objects']['MAIN_TABLE']] = array(
                        $rel_tbl_struct . "." . $rel_v[1] => $rel_obj_res['objects']['MAIN_TABLE'] . "." . $rel_v[1],
                    );
                }
            }
//            foreach ($rel_struct as $k => $v) {
//                $rel_v = explode(":", $v);
//                $rel_obj_v = $rel_v[0];
//                unset($rel_v[0]);
//                foreach ($rel_v as $key => $value) {
//                    $rel_obj_value = explode(",", $value);
//                    if ($rel_obj_value[2] == "MF") { //主对象与ID
//                        $res_data[$main_tbl_name][$rel_obj_v] = array(
//                            $rel_obj_v . "." . $rel_obj_value[1] => $main_tbl_name . "." . $main_primary_key,
//                        );
//                    } else if ($rel_obj_value[2] == "SF") { //子对象与ID
//                        $res_data[$rel_obj_res['objects']['MAIN_TABLE']][$rel_obj_v] = array(
//                            $rel_obj_res['objects']['MAIN_TABLE'] . "." . $rel_obj_res['objects']['KEY_ATTR_FLD'] => $rel_obj_v . "." . $rel_obj_value[1],
//                        );
//                    } else if ($rel_obj_value[2] == "FC") { //字段值 
//                    }
//                }
//            }
            return $res_data;
        }
    }

    public function get_obj_attr($obj, $org_id) {
        $objects = $this->obj->getOneData($obj);
        //查出主对象属性信息
        $attrs = $this->attr->object_attr($org_id, $objects['OBJ_NAME']);
        $res_data = array(
            "objects" => $objects,
            "attrs" => $attrs,
        );
        return $res_data;
    }

}
