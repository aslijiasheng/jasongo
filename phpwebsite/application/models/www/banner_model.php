<?php

/**
 * Banner_model 
 * banner业务处理类最好使用CIORM进行ADO方式处理不得使用SQL拼接方式防止一次注入，取出字段时使用HTMLSPECIALCHARS函数对数据进行输出
 * @uses CI
 * @uses _Model
 * @package 
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <sky@php.net> 
 * @license PHP Version 5.3
 */
class Banner_model extends CI_Model {

    private $portalDB;
    private $table = "tc_banner";

    function __construct(){
        parent::__construct();
        $this->portalDB = $this->load->database('portal', TRUE);
    }

    /**
     *  banner查询
     */
    public function queryBanner($bannerIsStatus = FALSE){ 
        $bannerSql = "select * from tc_banner where banner_is_status = '?'";
        return $this->portalDB->query($bannerSql, array($bannerIsStatus))->result_array();
    }

    /**
     * delBanner 
     * banner删除
     * @param mixed $bannerID 
     * @access public
     * @return void
     */
    public function delBanner($bannerID){
        if(!empty($bannerID)){
            $ret = $this->getBanner($bannerID);
            /**
             *  先查询bannerid是否为false如果为则直接返回不予更新
             */
            if('t' !== $ret['banner_is_status'])
                return true;
            $bannerDelSql = "update tc_banner set banner_is_status = 'FALSE' where banner_id = ?";
            return $this->portalDB->query($bannerDelSql, array($bannerID));
        }
        return false;
    }

    /**
     * getBanner 
     * 单一查询
     * @param mixed $bannerID 
     * @access public
     * @return void
     */
    public function getBanner($bannerID){
        if(!empty($bannerID)){
            $bannerSql = "select * from tc_banner where banner_id = ?";
            $ret = $this->portalDB->query($bannerSql, array($bannerID))->result_array();
            return $ret[0];
        }
        return false;
    }

}

?>
