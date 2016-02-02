<?php
/**
 * Customer 
 * 
 * @uses WWW
 * @uses _controller
 * @package 
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <sky@php.net> 
 * @license PHP Version 5.3
 */
class Customer extends WWW_controller {
	
    public function __construct() {
        parent::__construct();
        $this->load->model('admin/customer_model', 'cus');
    }
	
    /**
     * 公用列表页面头部区域
     */
    public function lists() {
        $OrgID = $this->session->userdata('org_id');
        $UserID = $this->session->userdata('user_id');
        $this->render('www/customer/list', $data);
    }

    /**
     * 公用引用列表页面
     */
    public function ajax_lists() {
        $this->load->view('www/customer/ajax_list', $data);
    }

    public function list_object_json() {
        $listData = $this->cus->getInfo();
        echo json_encode($listData);
        die;
    }

    public function oper(){
        $oper = $_POST['oper'];
        $this->$oper($_POST);
    }

    public function save($data) {
        $ret = $this->cus->save($data);
        die($ret);
    }

    public function edit($data) {
        $ret = $this->cus->update($data);
        die($ret);
    }

    public function del($data) {
        $ret = $this->cus->delete($data);
        die($ret);
    }
}

?>
