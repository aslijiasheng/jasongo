<?php 
class Enum extends WWW_controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('admin/enum_model','enum');
	}

	public function SelectChildEnums(){
		$OrgID = $this->session->userdata('org_id');
		$attr_name = $_POST['attr_name'];
		$arr = explode(".", $attr_name);
		$enum_key = $_POST['enum_key'];
		$LangID = 2;
 		$data=$this->enum->SelectChildEnums($attr_name,$enum_key,$OrgID,$LangID,$arr[0]);

		

 		//p($data);
 		echo json_encode($data);
	}
}
?>