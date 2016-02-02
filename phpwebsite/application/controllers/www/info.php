<?php
/**
 * Info 
 * 企业文化控制器
 * @uses WWW
 * @uses _controller
 * @package 
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <sky@php.net> 
 * @license PHP Version 5.3
 */
Class Info extends WWW_controller{

	function __construct(){
		parent::__construct();
        $this->load->model('service/info_service_model', 'infoService');
	}

    /**
     * view 
     * 展示
     * @access public
     * @return void
     */
    public function viewAction(){
        $infoData = $this->infoService->fetchAllInfo();
        $this->render('www/portal/info', $infoData);
    }

    /**
     * addAction 
     * 新增业务系统
     * @access public
     * @return void
     */
    public function addAction(){
        $ret = array("succ" => TRUE, "msg" => array());
        $infoYear  = intval($this->input->post('infoYear'));
        $infoOrder = intval($this->input->post('infoOrder'));
        $infoDesc  = mb_substr($this->input->post('infoDesc'), 0, 100, 'UTF-8');
        if(empty($infoYear) && empty($infoOrder) && empty($infoDesc)){
            $ret['succ'] = FALSE;
            $ret['msg'] = "年份或者排序或者描述为空";
            $this->sender($ret);
        }
        $ret = $this->infoService->addInfo($infoYear, $infoOrder, $infoDesc);
        $this->sender($ret);
    }

    /**
     * editAction 
     * 修改业务系统
     * @access public
     * @return void
     */
    public function editAction(){
        $ret = array("succ" => TRUE, "msg" => array());
        $infoID    = intval($this->input->post('infoID'));
        $infoYear  = intval($this->input->post('infoYear'));
        $infoOrder = intval($this->input->post('infoOrder'));
        $infoDesc  = mb_substr($this->input->post('infoDesc'), 0, 100, 'UTF-8');
        if($infoID > 0){
            $ret['succ'] = FALSE;
            $ret['msg'] = "非法请求";
            $this->sender($ret);
        }
        if(empty($infoYear) && empty($infoOrder) && empty($infoDesc)){
            $ret['succ'] = FALSE;
            $ret['msg'] = "年份或者排序或者描述为空";
            $this->sender($ret);
        }
        $this->infoService->updateInfo($infoID, $infoYear, $infoOrder, $infoDesc);
        $this->sender($ret);
    }

    /**
     * delAction 
     * 删除业务系统
     * @access public
     * @return void
     */
    public function delAction(){
        $ret = array("succ" => TRUE, "msg" => array());
        $infoID = intval($this->input->post('infoID'));
        $ret = $this->infoService->delInfo($infoID);
        $this->sender($ret);
    }
}
?>
