<?php
Class Home extends ADMIN_controller{
	public function __construction(){
		parent::__construction;
	}

	public function index(){
		$this->load->library('session');
		if(isset($this->session->userdata['user_id'])){
			if($this->session->userdata['is_admin']==1){
				$this->homes();
			}
		}else{
			redirect('www/login');
		}
	}

	/**
	 * 清空session登录信息
	 */
	public function out(){
		$this->session->sess_destroy();
		redirect("admin/");
	}

	/**
	 * home页面
	 */
	public function homes(){
		$data = "";
		$this->render('admin/home/home',$data);
	}
}
?>