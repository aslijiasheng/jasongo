<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Reluser_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model("www/objects_model", "objects");
    }

    public function pager_info_list($data, $user_id, $org_id, $CurrentPage, $PageSize) {
        $obj = $this->obj->getOneData($data['obj_type']);
        $obj_name = $obj['OBJ_NAME'];
        $key_attr_fld = $obj['KEY_ATTR_FLD'];
        $where[$key_attr_fld] = $data['id'];
        $tbl_center = strtolower($obj_name);
        $user_tbl = "rel_" . $tbl_center . "_user";
        $fld_data = "A.user_id,user_name,mobile,dept_name,is_self";
        $w = array();
        $w["A.USER_ID"] = $user_id;
        $this->db->select($fld_data);
        $this->db->from("$user_tbl A");
        $this->db->join("tc_user B", "B.USER_ID = A.USER_ID", "inner");
        $this->db->join("tc_department C", "C.DEPT_ID = B.DEPT_ID", "inner");
        foreach ($where as $key => $value) {
            $w[$key] = $value;
        }
        $this->db->where($w);
        $this->db->limit($PageSize, ($CurrentPage - 1) * $PageSize + 1);
        $res_data = $this->db->get()->result_array();
        $this->db->from("$user_tbl A");
        $this->db->join("tc_user B", "B.USER_ID = A.USER_ID", "inner");
        $this->db->join("tc_department C", "C.DEPT_ID = B.DEPT_ID", "inner");
        $this->db->where($w);
        $count = $this->db->count_all_results();
        $json_data = $this->objects->InfoPagers($CurrentPage, $count, $PageSize, $res_data);
        return $json_data;
    }

}
