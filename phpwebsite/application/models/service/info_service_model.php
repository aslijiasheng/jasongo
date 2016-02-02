<?php

/**
 * Info_service_model 
 * 企业文件业务数据逻辑处理类
 * @uses CI
 * @uses _Model
 * @package 
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <sky@php.net> 
 * @license PHP Version 5.3
 */
class Info_service_model extends CI_Model {

    function __construct(){
        parent::__construct();
        $this->load->model('www/info_model', 'info');
    }
    
    /**
     * fetchAllinfo 
     * 查询业务系统数据
     * @access public
     * @return void
     */
    public function fetchAllInfo(){
        return $this->info->queryAllInfoData();
    }

    /**
     * updateinfo 
     * 修改
     * @param mixed $infoID 
     * @param mixed $infoData 
     * @access public
     * @return void
     */
    public function updateInfo($infoID, $infoYear, $infoOrder, $infoDesc){
        $infoData = array();
        $infoID = intval($infoID);
        $ret = $this->info->getInfoData($infoID);
        if(!$ret)
            return FALSE;
        $infoData['info_year']  = $infoYear;
        $infoData['info_order'] = $infoOrder;
        $infoData['info_desc']  = $infoDesc;
        return $this->info->updateInfo($infoID, $infoData);
    }

    /**
     * addinfo 
     * 新增
     * @param mixed $infoData 
     * @access public
     * @return void
     */
    public function addInfo($infoName, $infoUrl, $infoDesc){
        $infoData = array();
        $infoData['info_year']  = mysqlEscapeStr($infoYear);
        $infoData['info_order'] = mysqlEscapeStr($infoOrder);
        $infoData['info_desc']  = mysqlEscapeStr($infoDesc);
        return $this->info->addInfo($infoData);

    }

    /**
     * delinfo 
     * 删除
     * @param mixed $infoID 
     * @access public
     * @return void
     */
    public function delInfo($infoID){
        $infoID = intval($infoID);
        $ret = $this->info->getInfoData($infoID);
        if(!$ret)
            return FALSE;
        $this->info->delInfo($infoID);
    }
}

?>
