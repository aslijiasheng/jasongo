<?php
/**
 * Platform 
 * 管理业务系统控制器类
 * @uses WWW
 * @uses _controller
 * @package 
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <sky@php.net> 
 * @license PHP Version 5.3
 */
Class Platform extends WWW_controller{

	function __construct(){
		parent::__construct();
        $this->load->model('service/platform_service_model', 'platformService');
	}

    /**
     * view 
     * 展示
     * @access public
     * @return void
     */
    public function viewAction(){
        $platformData = $this->platformService->fetchAllPlatform();
        $this->render('www/portal/platform', $platformData);
    }

    /**
     * addAction 
     * 新增业务系统
     * @access public
     * @return void
     */
    public function addAction(){
        $ret = array("succ" => TRUE, "msg" => array());
        $platformName = mb_substr($this->input->post('platformName'), 0, 20, 'UTF-8');
        $platformUrl  = mb_substr($this->input->post('platformUrl'), 0, 20, 'UTF-8');
        $platformDesc = mb_substr($this->input->post('platformDesc'), 0, 20, 'UTF-8');
        if(empty($platformName) && empty($platformUrl)){
            $ret['succ'] = FALSE;
            $ret['msg'] = "地址或者标题不能为空";
            $this->sender($ret);
        }
        $ret = $this->platformService->addPlatform($platformName, $platformUrl, $platformDesc);
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
        $platformID = intval($this->input->post('platformID'));
        $platformName = mb_substr($this->input->post('platformName'), 0, 20, 'UTF-8');
        $platformUrl  = mb_substr($this->input->post('platformUrl'), 0, 20, 'UTF-8');
        $platformDesc = mb_substr($this->input->post('platformDesc'), 0, 20, 'UTF-8');
        if($platformID > 0){
            $ret['succ'] = FALSE;
            $ret['msg'] = "非法请求";
            $this->sender($ret);
        }
        if(empty($platformName) && empty($platformUrl)){
            $ret['succ'] = FALSE;
            $ret['msg'] = "地址或者标题不能为空";
            $this->sender($ret);
        }
        $this->platformService->updatePlatform($platformID, $platformName, $platformUrl, $platformDesc);
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
        $platformID = intval($this->input->post('platformID'));
        $ret = $this->platformService->delPlatform($platformID);
        $this->sender($ret);
    }
}
?>
