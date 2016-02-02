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

	/**
	 * 通过obj_type获取这个对象有多少类型
	 * @param int	$OrgID 单位ID
	 * @param int	$ObjType 对象的ID
	 * @return 如果没有结果返回false，如果查询出结果给数组
	 */
	public function ObjGetType($OrgID,$ObjType){
		$where	= array(
			"obj_type"		=> $ObjType,
			"org_id"		=> $OrgID,
			"stop_flag"		=> 0,
		);
		$select	= array(
			"type_name",
			"type_id",
		);
		$this->db->select($select);
		$this->db->where($where);
		$this->db->from("tc_type");
		$data = $this->db->get()->result_array();
		if(isset($data[0])){
			foreach($data as $k=>$v){
				$new_data[$k]["Name"] = $v["TYPE_NAME"];
				$new_data[$k]["ID"] = $v["TYPE_ID"];
			}
			return $new_data;
		}else{
			return false;
		}
	}
}