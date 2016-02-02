<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Reluser extends WWW_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("www/reluser_model", "reluser");
    }

    public function ajax_lists() {
        $data['id'] = $_GET['id'];
        $data['obj_type'] = $_GET['obj_type'];
        $obj = $this->obj->getOneData($data['obj_type']);
        $obj_name = $obj['OBJ_NAME'];
        $data['primarty_id'] = $obj_name . "_" . time() . rand(100, 999);
        $this->load->view('www/reluser/ajax_reluser_lists', $data);
    }

    /**
     * 加载公用视图页面列表数据 相关员工及负责员工(user)
     */
    public function ajax_view_lists() {
        $org_id = $this->session->userdata('org_id');
        $user_id = $this->session->userdata('user_id');
        $data['id'] = $_REQUEST['id'];
        $data['obj_type'] = $_REQUEST['obj_type'];
        //用来解析查询数据
        $CurrentPage = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $PageSize = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : 10;
        $pages_data = $this->reluser->pager_info_list($data, $user_id, $org_id, $CurrentPage, $PageSize);
        echo $pages_data;
        die;
    }

}
