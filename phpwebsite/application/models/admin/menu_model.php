<?php
class Menu_model extends CI_Model{

	/**
	 * GetMenuJson	根据模块查询目录
	 * @param int $org_id 账套号ID
	 * @param int $login_sys 模块id
	 * @param int $active 选中的menu_id
	 * @return json //返回一个用于递归生成menu页面的json数组
	 */
	public function GetMenuJson($org_id,$login_sys,$active=""){
		$sys_name = $this->sysIdGetName($login_sys);
		$sql = "
select * from dd_menu
		";
		$data = $this->db->query($sql)->result_array();
		foreach($data as $k=>$v){
			$data_arr[$k]["id"] = $v['menu_id'];
			$data_arr[$k]["pid"] = $v['menu_uid'];
			$data_arr[$k]["name"] = $v['menu_name'];
			$data_arr[$k]["icon"] = $v['menu_icon'];
			$data_arr[$k]["url"] = $v['menu_url'];
			if($v['menu_id'] == $active){
				$data_arr[$k]["active"] = 1;
			}else{
				$data_arr[$k]["active"] = 0;
			}
		}
		return json_encode($data_arr);
	}

	/**
	 * 根据模块ID转换模块名称
	 * @param int $sys_id 模块id
	 * @return string //返回模块名称
	 */
	public function sysIdGetName($sys_id){
		switch ($sys_id){
			case 20000:
				return 'KEY';
				break;
			case 1:
				return 'CRM';
				break;
			case 2:
				return 'PORTAL';
				break;
			default:
				echo "未知模块";exit;
				break;
		}
	}

	/**
	 * 根据menu_id获取中文标签
	 */

	/**
	 * 所有的查询都写一个通用方法
	 */
	public function GetInfo($info){
		//首先查询出本身所有的内容
		$this->db->from("tc_menu");
		extract($info);
		if (!empty($select)){
			$this->db->select($select);
		}
		if (!empty($where)){
			$this->db->where($where);
		}
		if (!empty($limit)){
			$this->db->limit($limit[0],$limit[1]);
		}
		if (!empty($like)){
			$this->db->like($like);
		}
		$data=$this->db->get()->result_array();
		if(count($data)==1){
			$data = $data[0];
		}
		return $data;
	}

	/**
	 * 根据条件查询出数据总数
	 * $where //判断条件
	 */
	public function countGetInfo($info){
		$this->db->from('tc_user');
		extract($info);
		if (!empty($where)){
			$this->db->where($where);
		}
		if (!empty($like)){
			$this->db->like($like);
		}
		$count = $this->db->count_all_results();
		return $count;
	}

	/**
	 * 通过对象名称，类型，关联菜单ID
	 */
	public function ObjGetMenuID($ObjType,$Type){

	}
}
?>
