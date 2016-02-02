<?php

/**
 * Contants 
 * 联系人通讯录控制器类
 * @uses WWW
 * @uses _controller
 * @package 
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <sky@php.net> 
 * @license PHP Version 5.3
 */
Class Contants extends WWW_controller{

    private static $limit = 10;
    private static $_oper = "";

	function __construct(){
		parent::__construct();
        $this->load->model('service/contants_service_model', 'contantsService');
	}

    /**
     * view 
     * 展示
     * @access public
     * @return void
     */
    public function viewAction(){
        $contantsData = $this->contantsService->fetchAllContants();
        $data['contantsData'] = $contantsData;
        $this->render('www/portal/contacts', $data);
    }

    /**
     * addViewAction 
     * 新增
     * @access public
     * @return void
     */
    public function addViewAction(){
        $contantsData = array();
        $data['contantsData'] = $contantsData;
        $this->load->view('www/portal/viewContants', $data);
    }

    /**
     * importContants 
     * 导入预览类
     * @access public
     * @return void
     */
    public function importViewContantsAction(){
        $ret = "";
        $this->_oper = $this->input->post('oper');//判断是追加还是覆盖
        $data['url'] = site_url("www/contants/importContantsAction?oper={$this->_oper}");
        $this->load->view('www/objects/imagesupload', $data);
    }

    /**
     * importContants 
     * 导入处理类
     * @access public
     * @return void
     */
    public function importContantsAction(){
        $ret = array("succ" => TRUE, "msg" => array());
        $oper = $this->input->get('oper');//判断是追加还是覆盖
        $filePath = $this->contantsService->contantsFileUpload($_FILES);
        var_dump($filePath);
        if(!$filePath){
            $ret["succ"] = FALSE;
            $ret["msg"]  = "保存文件失败";
            $this->sender($ret);
        }
        switch($oper){
            case "append"  : $ret = $this->contantsService->appendContants($filePath, $this->contantsService->_activeSheet, $this->contantsService->_headers);break;//追加
            case "truncat" : $ret = $this->contantsService->truncatContants($filePath, $this->contantsService->_activeSheet, $this->contantsService->_headers);break;//清空
        }
        $this->sender($ret);
    }

    /**
     * addContants 
     * 新增联系人
     * @access public
     * @return void
     */
    public function addContantsAction(){
        $ret = array("succ" => FALSE, "msg" => array());
        $gonghao = $this->input->post('gonghao');
        $phone   = $this->input->post('phone');
        $tel     = $this->input->post('tel');
        $name    = $this->input->post('name');
        if(!regexPhone($phone)){
            $ret[] = '手机号码格式不正确';
            $this->sender($ret);
        }
        if(!regexTel($tel)){
            $ret[] = '固定电话号码格式不正确';
            $this->sender($ret);
        }
        $this->contantsService->contantsAddAttr($gonghao, $phone, $tel, $name);
        $this->sender($ret);
    }

}
?>
