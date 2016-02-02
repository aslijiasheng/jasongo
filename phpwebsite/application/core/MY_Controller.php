<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class ADMIN_Controller extends CI_Controller {
	public function __construct() {
		parent::__construct();
		define('css_url', base_url() . 'style/css');
		$this->output->enable_profiler(TRUE);
		$this->load->helper("form");
	}

	/**
	 * 更新判断session的时间 如果超时返回登录页面，未超时更新时间
	 */
	public function session_time(){
		if(isset($this->session->userdata)){
			if(!isset($this->session->userdata['user_id'])){
				if($this->session->userdata['is_admin']!=1){
					redirect('admin/login');
				}
			}
		}
	}

	public function render($view, $data = '') {
		$this->session_time();
		$this->load->view('admin/layouts/head', $data);
		$this->load->view($view, $data);
		$this->load->view('admin/layouts/bottom');
	}

	public function label($filter_label, $filter_name) {
		$label_filter = array(
			'class' => 'control-label',
		);
		return form_label($filter_label . "<span class='required'>*</span>", $filter_name, $label_filter);
	}

	//文本框类型
	public function textField($data, $obj_name) {
		$filter_name = $obj_name . '_' . $data['attr_name'];
		$filter_label = $data['attr_label'];
		$filter_type = $data['attr_field_type'];
		$text_filter = array(
			'name' => $filter_name,
			'id' => $filter_name,
			'placeholder' => $filter_label,
		);

		$html_componant = "";
		$html_componant .= "<div class='control-group'>" . $this->label($filter_label, $filter_name);
		$html_componant .="<div class='controls'>" . form_input($text_filter) . "</div></div>
		   ";
		return $html_componant;
	}

	//引用类型
	public function reference($data, $obj_name) {

	}

	//复选框类型
	public function checkbox($data, $obj_name) {

	}

	//单选框类型
	public function radiobutton($data, $obj_name) {
		$filter_name = $obj_name . '_' . $data['attr_name'];
		$filter_label = $data['attr_label'];
		//$filter_type=$data['attr_field_type'];
		$radio_filter = array(
			'name' => $filter_name,
		);
		$html_componant = "<div class='control-group'>" . $this->label($filter_label, $filter_name);
		$html_componant.="<div class='controls'>" . "<label class='radio inline'>" . form_radio($radio_filter) . "aa</label>";
		$html_componant.="<label class='radio inline'>" . form_radio($radio_filter) . "aa</label></div></div>
		   ";
		return $html_componant;
	}

	//下拉框类型
	public function dropdownlist($data, $obj_name) {
		$filter_name = $obj_name . '_' . $data['attr_name'];
		$filter_label = $data['attr_label'];
		$html_componant = "<div class='control-group'>" . $this->label($filter_label, $filter_name);
		$html_componant.="<div class='controls'>" . "<selected name='{$filter_name}'>
		 	                  " . "";
	}

}

class WWW_Controller extends CI_Controller {

	public function __construct() {
		parent::__construct();
		define('css_url', base_url() . 'style/css');
		//$this->output->enable_profiler(TRUE);
		$this->session_time();
		$this->load->model('www/user_model', 'user');
	}

	/**
	 * 更新判断session的时间 如果超时返回登录页面，未超时更新时间
	 */
	public function session_time(){
		if(isset($this->session->userdata)){
			if(!isset($this->session->userdata['user_id'])){
				redirect('www/login');
			}
		}
	}

	public function render($view, $data = '') {
		$this->session_time(); //判断登录session
		//p($this->session->userdata);
		//获取菜单信息
		$this->load->model('admin/menu_model','menu');
		if(isset($data['menu_active'])){
			$menu_active = $data['menu_active'];
		}else{
			$menu_active = "";
		}
        $data['menu_json'] = $this->menu->GetMenuJson($this->session->userdata['org_id'],$this->session->userdata['login_sys'],$menu_active);
        $data['session']['user_name'] = $this->session->userdata['user_name'];
        $this->load->view('www/layouts/head', $data);
		$this->load->view($view, $data);
		$this->load->view('www/layouts/bottom');
	}

    public function sender($data){
        header('Content-type: application/json');
        echo json_encode($data);
        die;
    }

	public function render_ajax($view, $data = '') {
		$this->load->view('www/layouts/head_ajax');
		$this->load->view($view, $data);
		$this->load->view('www/layouts/bottom_ajax');
	}

	public function get_data_auth($obj_name) {
		$user_auth = $this->user->user_auth($this->session->userdata('user_id'));
		//p($this->session->userdata);
		//p($user_auth['data_auth_arr']);
		$data_auth = array();
		if (!empty($user_auth['data_auth_arr'])) {
			foreach ($user_auth['data_auth_arr'] as $key => $value) {
				$tem = explode('.', $value);

				if ($tem[0] == $obj_name) {
					$data_auth[] = $tem[1];
				}
			}
		}
		// p($data_auth);
		$max_auth_val = 0; //当前对象的最大数据访问权限值

		foreach ($data_auth as $key => $value) {
			if (data_auth_to_num($value) > $max_auth_val) {
				$max_auth_val = data_auth_to_num($value);
			}
		}


		$where = array();
		if ($max_auth_val == 3) {
			$where['w1'] = array(
				'attr' => 'owner.id',
				'value' => $this->session->userdata('user_id'),
				'action' => '=',
			);
			$where['w2'] = array(
				'attr' => 'department.id',
				'value' => $this->session->userdata('department_id'),
				'action' => 'BELONGTO'
			);
			$where_rel = "w1 or w2";
			$data['where'] = json_encode($where);
			$data['where_rel'] = $where_rel;
		} else if ($max_auth_val == 2) {
			$where['w1'] = array(
				'attr' => 'owner.id',
				'value' => $this->session->userdata('user_id'),
				'action' => '=',
			);
			$where['w2'] = array(
				'attr' => 'department.id',
				'value' => $this->session->userdata('department_id'),
				'action' => '='
			);
			$where_rel = "w1 or w2";
			$data['where'] = json_encode($where);
			$data['where_rel'] = $where_rel;
			//本级权限
			//	$where['']
		} else if ($max_auth_val == 4) {

			$where_rel = "w1";
			$data['where'] = "''";
			$data['where_rel'] = "";
		} else if ($max_auth_val == 1) {
			$where['w1'] = array(
				'attr' => 'owner',
				'value' => $this->session->userdata('user_id'),
				'action' => '=',
			);
			$where['w2'] = array(
				'attr' => 'typein',
				'value' => $this->session->userdata('user_id'),
				'action' => '=',
			);
			$where['w3'] = array(
				'attr' => 'finance',
				'value' => $this->session->userdata('user_id'),
				'action' => '=',
			);
			$where['w4'] = array(
				'attr' => 'review',
				'value' => $this->session->userdata('user_id'),
				'action' => '=',
			);
			$where['w5'] = array(
				'attr' => 'nreview',
				'value' => $this->session->userdata('user_id'),
				'action' => '=',
			);
			$where_rel = "w1 or w2 or w3 or w4 or w5";
			$data['where'] = json_encode($where);
			$data['where_rel'] = $where_rel;
			//p($data);
		} else {
			//无权限
			// $this->load->view('www/layouts/403');
			// return;
			return array();
		}

		return $data;
	}

//图片上传
	public function fileupload() {
		$config['upload_path'] = "./upload";
		$config['allowed_types'] = "gif|jpg|png|jpeg";
		$config['max_size'] = "10000";
		$config['file_name'] = time() . mt_rand(1000, 9999);
		$this->load->library("upload", $config);
		$status = $this->upload->do_upload('thumb');
		if (!$status) {
			error("没有上传图片");
		}
		$wrong = $this->upload->display_errors();
		if ($wrong) {
			error($wrong);
		}
		$info = $this->upload->data();
		$thconfig['image_library'] = 'gd2';
		$thconfig['source_image'] = $info['full_path'];
		$thconfig['create_thumb'] = FALSE;
		$thconfig['maintain_ratio'] = FALSE;
		$thconfig['width'] = 48;
		$thconfig['height'] = 48;
		$this->load->library('image_lib', $thconfig);
		$status = $this->image_lib->resize();

		return $config['file_name'];
		//$this->load->view('index/booklist/file.html');
	}

	// 获取客户ip
	function get_client_ip(){
		if ($_SERVER['REMOTE_ADDR']) {
			$cip = $_SERVER['REMOTE_ADDR'];
		} elseif (getenv("REMOTE_ADDR")) {
			$cip = getenv("REMOTE_ADDR");
		} elseif (getenv("HTTP_CLIENT_IP")) {
			$cip = getenv("HTTP_CLIENT_IP");
		} else {
			$cip = "unknown";
		}
		return $cip;
	}

}

?>
