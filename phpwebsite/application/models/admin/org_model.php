<?php

class Org_model extends CI_Model {

    /**
     * NameGetId 根据组织名称查询组织
     * @param string $org_name 组织名称
     * @return int 返回组织ID 
     */
    public function NameGetId($OrgName) {
        $where["org_name"] = $OrgName;
        $select = array("org_id");
        $this->db->from("tc_org_profile");
        $this->db->select($select);
        $this->db->where($where);
        $data = $this->db->get()->result_array();
        $OrgID = $data[0]["ORG_ID"];
        return $OrgID;
    }

}

?>
