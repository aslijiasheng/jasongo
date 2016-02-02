<?php
class Role_model extends CI_Model{

	private $user_data;

	/**
	 * 判断角色是否存在
	 */
	public function UserIsRole($user_id,$role_id,$org_id){
		$where["org_id"] = $org_id;
		$where["role_id"] = $role_id;
		$where["user_id"] = $user_id;
		$this->db->from("rel_role_user");
		//$this->db->select(array());
		$this->db->where($where);
		$count = $this->db->count_all_results();
		//p($count);exit;
		return $count;
	}
}
?>
