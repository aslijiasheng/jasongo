<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Relprivilege_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function pager_info_list($data, $user_id, $org_id, $CurrentPage, $PageSize) {

        $sql = "select * from tc_user where user_id = $user_id and org_id = $org_id";
        $row_data = $this->db->query($sql)->result_array();
        $row_data = $row_data[0];
        $DEPT_ID = $row_data['DEPT_ID'];

        $obj = $this->obj->getOneData($data['obj_type']);
        $obj_name = $obj['OBJ_NAME'];
        $key_attr_fld = $obj['KEY_ATTR_FLD'];
        $where[$key_attr_fld] = $data['id'];
        $tbl_center = strtolower($obj_name);
        $user_tbl = "rel_" . $tbl_center . "_privilege A";
        $fld_data = "dept_id,dept_name";
        $w = array();
        $w["B.DEPT_ID"] = $DEPT_ID;
        $this->db->select($fld_data);
        $this->db->from("$user_tbl");
        $this->db->join("tc_department B", "A.PRIVILEGE_ID = B.DEPT_ID", "inner");
        foreach ($where as $key => $value) {
            $w[$key] = $value;
        }
        $this->db->where($w);
        $this->db->limit($PageSize, ($CurrentPage - 1) * $PageSize + 1);
        $res_data = $this->db->get()->result_array();
        $this->db->from("$user_tbl");
        $this->db->join("tc_department B", "A.PRIVILEGE_ID = B.DEPT_ID", "inner");
        $this->db->where($w);
        $count = $this->db->count_all_results();
        $json_data = $this->objects->InfoPagers($CurrentPage, $count, $PageSize, $res_data);
        return $json_data;
    }

}
