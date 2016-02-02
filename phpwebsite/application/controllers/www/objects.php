<?php

/**
 * Objects
 * 
 * @package ncrm
 * @copyright 2014
 * @version 1.0
 * @access public
 */
class Objects extends WWW_controller {
	
    public function __construct() {
        parent::__construct();
        $this->load->model('admin/obj_model', 'obj');
    }
	
    /**
     * 公用列表页面头部区域
     */
    public function lists() {
        $OrgID = $this->session->userdata('org_id');
        $UserID = $this->session->userdata('user_id');
        $this->render('www/objects/list', $data);
    }

    /**
     * 高清图片管理
     */
    public function imagesUpload() {
        $data['url'] = site_url('www/objects/multiFiles');
        $this->render('www/objects/imagesupload', $data);
    }

    /*
     * 上传图片
     */
    public function multiFiles(){
        $fileName = $_FILES['file']['name'];
        $fileTmpDir = $_FILES['file']['tmp_name'];
        $streamReader = file_get_contents($fileTmpDir);
        file_put_contents("images/HDImages/{$fileName}", $streamReader);
        die;
    }
    /**
     * 公用引用列表页面
     */
    public function ajax_lists() {
        $this->load->view('www/objects/ajax_list', $data);
    }

    public function list_object_json() {
        $listData = $this->obj->getInfo();
        echo json_encode($listData);
        die;
    }

    public function oper(){
        $oper = $_POST['oper'];
        $this->$oper($_POST);
    }

    public function save($data) {
        $ret = $this->obj->save($data);
        die($ret);
    }

    public function edit($data) {
        $ret = $this->obj->save($data);
        die($ret);
    }

    public function del($data) {
        $ret = $this->obj->delete($data);
        die($ret);
    }
}

?>
