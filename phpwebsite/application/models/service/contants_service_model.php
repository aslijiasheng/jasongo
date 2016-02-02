<?php

/**
 * Contants_service_model 
 * 联系人通讯录业务处理类
 * @uses CI
 * @uses _Model
 * @package 
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <sky@php.net> 
 * @license PHP Version 5.3
 */
class Contants_service_model extends CI_Model {

    private  $_filePath = "";
    public   $_activeSheet = 0;
    public   $_headers = array("工号", "手机号", "联系电话", "姓名");

    function __construct(){
        parent::__construct();
        $this->load->model('www/contants_model', 'contants');
        $this->load->library('excel');
        $this->load->library('fileBase');
    }

    /**
     * fetchAllContants 
     * 全部查询
     * @access public
     * @return void
     */
    public function fetchAllContants(){
        return $this->contants->queryAllContantsData();
    }

    /**
     * appendContants 
     * 向通讯录中追加记录
     * @param mixed $filePath 
     * @param mixed $sheet 
     * @param mixed $headers 
     * @access public
     * @return void
     */
    public function appendContants($filePath, $sheet, $headers){
        $this->_filePath    = $filePath;
        $this->_activeSheet = $sheet;
        $this->_headers     = $headers;
        return $this->saveContants();
    }

    /**
     * truncatContants 
     * 清空通讯录再添加
     * @param mixed $filePath 
     * @param mixed $sheet 
     * @param mixed $headers 
     * @access public
     * @return void
     */
    public function truncatContants($filePath, $sheet, $headers){
        $this->_filePath    = $filePath;
        $this->_activeSheet = $sheet;
        $this->_headers     = $headers;
        $ret = $this->contants->truncatContants();
        if(FALSE === $ret){
            return FALSE;
        }
        return $this->saveContants();
    }

    /**
     * saveContants 
     * 存储通讯录
     * @access private
     * @return void
     */
    private function saveContants(){
        $ret = array("succ" => TRUE, "msg" => array());
        $excel = new Excel();
        $contantsArr = $excel->readExcel($this->_filePath, $this->_activeSheet, $this->_headers);
        foreach($contantsArr['contents'] as $key => $contant){
            if(FALSE === $this->contantsAddAttr($contant[0], $contant[1], $contant[2], $contant[3])){
                $ret['msg'] = FALSE;
                array_push($ret['msg'], "{$contant[3]} 插入失败");
            }
        }
        return $ret;
    }

    /**
     * contantsFileUpload 
     * 文件处理 
     * @param mixed $files 
     * @access public
     * @return void
     */
    public function contantsFileUpload($files){
        $ret = FALSE;
        $fileBase = new fileBase();
        $fileBase->init($files, __CLASS__);
        $fileBase->checkFileExtersion() && $ret = $fileBase->saveFilePath();
        return $ret;
    }

    /**
     * contantsAddAttr 
     * 单条插入
     * @param mixed $gonghao 
     * @param mixed $phone 
     * @param mixed $tel 
     * @param mixed $name 
     * @access public
     * @return void
     */
    public function contantsAddAttr($gonghao, $phone, $tel, $name){
        if(!regexPhone($phone)){
            return FALSE;
        }
        if(!regexTel($tel)){
            return FALSE;
        }
        $contantsAddArr                          = array();
        $contantsAddArr['contants_user_gonghao'] = mysqlEscapeStr($gonghao);
        $contantsAddArr['contants_phone']        = $phone;
        $contantsAddArr['contants_tel']          = $tel;
        $contantsAddArr['contants_name']         = mysqlEscapeStr($name);
        return $this->contants->addContants($contantsAddArr);
    }

}

?>
