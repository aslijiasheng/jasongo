<?php

/**
 * 	例：tc_type ORM文件
 */
class Department_model extends CI_Model {

    public function __construct() {
        parent :: __construct();
    }

    /**
     * 	SelectOne					单一查询
     * 	@params	int	$OrgID	查询条件
     * 	@params	int	$DeptId	查询条件
     * 	@return array				返回查询的结果集(限制一条)
     */
    public function SelectOne($OrgID, $DeptId) {
        $where = array(
            "dept_id" => $DeptId,
            "org_id" => $OrgID,
        );
        $select = array(
            "dept_name",
            "dept_id",
            "tree_path",
        );
        $this->db->select($select);
        $this->db->where($where);
        $this->db->from("tc_department");
        $data = $this->db->get()->result_array();
        $new_data["Name"] = $data[0]["DEPT_NAME"];
        $new_data["ID"] = $data[0]["DEPT_ID"];
        $new_data["TreePath"] = $data[0]["TREE_PATH"];
        return $new_data;
    }

}
