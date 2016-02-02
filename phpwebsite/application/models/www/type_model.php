<?php
/**
 *	例：tc_type ORM文件
 */
class Type_model extends CI_Model {
	
	public function __construct() {
		parent :: __construct();
	}
	
	/**
	 *	SelectOne					单一查询
	 *	@params	int	$OrgID	查询条件
	 *	@params	int	$attr_value	查询条件
	 *	@return array				返回查询的结果集(限制一条)
	 */
	public function SelectOne($OrgID, $attr_value) {
		$where	= array(
			"type_id"		=> $attr_value,
			"org_id"		=> $OrgID,
		);
		$select	= array(
			"type_name",
			"type_id",
		);
		$this->db->select($select);
		$this->db->where($where);
		$this->db->from("tc_type");
		$data = $this->db->get()->result_array();
		$new_data["Name"] = $data[0]["TYPE_NAME"];
		$new_data["ID"] = $data[0]["TYPE_ID"];
		return $new_data;
	}

}