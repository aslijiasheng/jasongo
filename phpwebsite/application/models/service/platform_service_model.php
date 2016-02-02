<?php

/**
 * Platform_service_model 
 * 管理业务系统数据业务处理类
 * @uses CI
 * @uses _Model
 * @package 
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <sky@php.net> 
 * @license PHP Version 5.3
 */
class Platform_service_model extends CI_Model {

    private static $platformIcon = "fa fa-dropbox color-grey";

    function __construct(){
        parent::__construct();
        $this->load->model('www/platform_model', 'platform');
    }
    
    /**
     * fetchAllPlatform 
     * 查询业务系统数据
     * @access public
     * @return void
     */
    public function fetchAllPlatform(){
        return $this->platform->queryAllPlatformData();
    }

    /**
     * updatePlatform 
     * 修改
     * @param mixed $platformID 
     * @param mixed $platformData 
     * @access public
     * @return void
     */
    public function updatePlatform($platformID, $platformName, $platformUrl, $platformDesc){
        $platformData = array();
        $platformID = intval($platformID);
        $ret = $this->platform->getPlatformData($platformID);
        if(!$ret)
            return FALSE;
        $platformData['platform_name'] = $platformName;
        $platformData['platform_url']  = $platformUrl;
        $platformData['platform_desc'] = $platformDesc;
        return $this->platform->updatePlatform($platformID, $platformData);
    }

    /**
     * addPlatform 
     * 新增
     * @param mixed $platformData 
     * @access public
     * @return void
     */
    public function addPlatform($platformName, $platformUrl, $platformDesc){
        $platformData = array();
        $platformData['platform_name'] = mysqlEscapeStr($platformName);
        $platformData['platform_url']  = mysqlEscapeStr($platformUrl);
        $platformData['platform_desc'] = mysqlEscapeStr($platformDesc);
        $platformData['platform_icon'] = self::platformIcon;
        return $this->platform->addPlatform($platformData);

    }

    /**
     * delPlatform 
     * 删除
     * @param mixed $platformID 
     * @access public
     * @return void
     */
    public function delPlatform($platformID){
        $platformID = intval($platformID);
        $ret = $this->platform->getPlatformData($platformID);
        if(!$ret)
            return FALSE;
        $this->platform->delPlatform($platformID);
    }
}

?>
