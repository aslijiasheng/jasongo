<?php
/**
 *	��tc_campaign ORM�ļ�
 */
class Campaign_model extends CI_Model {
	
	public function __construct() {
		parent :: __construct();
	}

	/**
	 *	SelectOne					��һ��ѯ
	 *	@params	int	$OrgID	��ѯ����
	 *	@params	int	$CmpgId	��ѯ����
	 *	@return array				���ز�ѯ�Ľ��(����һ��)
	 */
	public function SelectOne($OrgID, $CmpgId) {
		$where	= array(
			"cmpg_id"		=> $CmpgId,
			"org_id"		=> $OrgID,
		);
		$select	= array(
			"CMPG_NAME",
			"CMPG_ID",
		);
		$this->db->select($select);
		$this->db->where($where);
		$this->db->from("tc_campaign");
		$data = $this->db->get()->result_array();
		$new_data["Name"] = $data[0]["CMPG_NAME"];
		$new_data["ID"] = $data[0]["CMPG_ID"];
		return $new_data;
	}

}