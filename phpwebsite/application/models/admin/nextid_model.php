<?php
class Nextid_model extends 	CI_Model{
	
	public function NewID($parent_name,$org_id=1){
		$where['TSYS_PARENT_NAME'] = strtoupper($parent_name);
		$where['org_id'] = $org_id;
		$this->db->select('TSYS_NEXT_ID');
		$this->db->from('TSYS_CRM_NEXTID');
		$this->db->where($where);
		$data=$this->db->get()->result_array();
		if(empty($data)){

			$update['TSYS_PARENT_NAME'] = $parent_name;
			$id=0;
			$update['TSYS_NEXT_ID'] =$id;
			$update['ORG_ID']=$org_id;
			$this->db->insert('TSYS_CRM_NEXTID',$update);
		}else{
			$id=$data[0]['TSYS_NEXT_ID'];
		}
		
		$id++;
		//p($id);die;
		$update['TSYS_NEXT_ID']=$id;
		$this->db->where($where);
		$this->db->update('TSYS_CRM_NEXTID',$update);

		return $id;
	}

	public function NewFormatID($type_id,$obj_data,$org_id=1){
		$where=array(
			'OBJ_NAME' => $obj_data['OBJ_NAME'],
			'ORG_ID' => $org_id,
			);
		$this->db->select('ATTR_NAME,SEQ_FORMAT as format,SEQ_START_NO as START_NUMBER');
		$this->db->from('dd_attribute');
		$this->db->where($where);
		$this->db->where('SEQ_FORMAT is not null');
		$code_format = $this->db->get()->result_array();
		if(!empty($code_format)){
			$c_where=array(
				'TYPE_ID' => $type_id,
				'OBJ_TYPE' => $obj_data['OBJ_TYPE'],
				'ORG_ID' => $org_id,
				);
			$this->db->select('ATTR_NAME,CODE_FORMAT as format,START_NUMBER');
			$this->db->from('TC_CODE_FORMAT');
			$this->db->where($c_where);
			$c_code_format = $this->db->get()->result_array();
		}else{
			return $code_format;
		}
		if(empty($c_code_format)){
			return $code_format[0];
		}else{
			return $c_code_format[0];
		}
		

		//return $code_format[0];
	}

	
}
?>