<?php
Class Express extends WWW_Controller{

	function __construct(){
		parent::__construct();
        $this->load->model('service/express_service_model', 'expressService');
	}

    /**
     * expressManagerAction 
     * 快件主页面
     * @access public
     * @return void
     */
    public function expressManagerAction(){
        $expressListUsers = $this->expressService->expressListUsers(); 
        $data['expressListUsers'] = $expressListUsers;
        $this->render('www/portal/expressManager', $data);
    }

    /**
     * expressTakeDetailAction 
     * 用户快件详情页面
     * @access public
     * @return void
     */
    public function expressTakeDetailAction(){
        $userID = $this->input->post('userID');
        if(empty($userID)){
            $this->sender(array("succ" => FALSE));
        }
        $expressTakeDetail = $this->expressService->expressTakeDetail(intval($userID)); 
        $data['expressTakeDetail'] = $expressTakeDetail;
        $this->load->view('www/portal/viewExpressDetail', $data);
    }

    /**
     * expressTakeAction 
     * 快件到件通知
     * @access public
     * @return void
     */
    public function expressTakeAction(){
        $takeUserID = $this->input->post('takeUserID');
        if(empty($takeUserID)){
            $this->sender(array("succ" => FALSE));
        }
        $this->expressService->expressTakeEmail(intval($takeUserID), $this->session->userdata['user_id']); 
        $this->sender(array("succ" => TRUE));
    }

    /**
     * upExpressTakeAction 
     * 快件取件更新
     * @access public
     * @return void
     */
    public function upExpressTakeAction(){
        $userID = $this->input->post('userID');
        $expressID = $this->input->post('expressID');
        if(empty($userID)){
            $this->sender(array("succ" => FALSE, "data" => "userID is empty"));
        }
        if(empty($expressID)){
            $this->sender(array("succ" => FALSE, "data" => "expressID is empty"));
        }
        $this->expressService->expressTakeUp($userID, $expressID);
        $this->sender(array("succ" => TRUE, "data" => "200"));
    }

}
