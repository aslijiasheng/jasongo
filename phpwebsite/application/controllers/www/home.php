<?php
Class Home extends WWW_controller{
	public function __construction(){
		parent::__construction;
	}

	public function index(){
		$this->load->library('session');
		if(isset($this->session->userdata['user_id'])){
			$this->homes();
		}else{
			redirect('www/login');
		}
	}

	public function out(){
		$this->session->sess_destroy();
		redirect("www/");
	}

	/**
	 * home页面
	 */
	public function homes(){
		$data = "";
		// $this->load->model("www/message_model","message");
		// $OrgID = $this->session->userdata('org_id');
        // $this->load->model('admin/obj_model', 'obj');
        // $obj = $this->obj->getOneData(999);
        // $obj_name = $obj['OBJ_NAME'];
        // $data = $this->getLists($obj_name, __FUNCTION__);

        // $data["objName"] = $this ->obj-> getListName($obj_name);

        // $data["ObjType"] = 999;
		// $data['message'] = $this->message->countn4($this->session->userdata['user_id']);
		// $data['ColModel']['Col'][0]['Width']=800;
		//$data['ColModel']['Col'][1]['Width']=200;
        // $data['ColModel']['Col'][0]['Url']="{REMIND.url}";
       
		$this->render('www/home/index',$data);
	}
	public function getLists($obj_name, $func_name) {
		$this->load->model('www/remind_model','remind');
        $data['menu_active'] = 3; //选中的menu_id
        $org_id = $this->session->userdata('org_id');
        $LangID = $this->session->userdata('lang_id');
        $user_id = $this->session->userdata('user_id');
        $SeniorQueryAttrs = array(
            "org_id" => $org_id,
            "LangID" => $LangID,
            "obj_name" => $obj_name,
            "user_id" => $user_id,
        );
        return $this->remind->getListsAttr($SeniorQueryAttrs);
    }
}
?>
