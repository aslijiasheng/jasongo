<?php

/**
 * Info_model 
 * 企业文化数据ORM
 * @uses CI
 * @uses _Model
 * @package 
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <sky@php.net> 
 * @license PHP Version 5.3
 */
class Info_model extends CI_Model {

    private $portalDB;
    private $table = "tc_info";
    private $modelRequired = TRUE;

    function __construct(){
        parent::__construct();
        $this->portalDB = $this->load->database('portal', TRUE);
    }

    /**
     * getFieldsName 
     * ORMFields
     * @access private
     * @return void
     */
    private static function getFieldsName(){
        return array(
            "`ignore#type:auto#isPrimary:true`|info_id",
            "`required#type:int`|info_year",
            "`required#type:int`|info_order",
            "`required#type:string#length:20`|info_desc",
        );
    }

    private static function initRule($data){
        $modelRet = modelRequired(self::modelRequired, self::getFieldsName(), $data);
        return $modelRet['succ'];
    }

    /**
     * getinfoData 
     * 查询单一数据
     * @access public
     * @return void
     */
    public function getInfoData($infoID){
        $sql = "select * from tc_info where info_id = ?";
        return $this->portalDB->query($sql, array($infoID))->result_array();
    }

    /**
     * queryAllContantsData 
     * 不建议使用 全部查询通讯录人员
     * @access public
     * @return void
     */
    public function queryAllInfoData(){
        $sql = "select * from tc_info";
        return $this->portalDB->query($sql)->result_array();
    }

    /**
     * updateinfo 
     * 更新
     * @param mixed $infoID 
     * @param mixed $infoData 
     * @access public
     * @return void
     */
    public function updateInfo($infoID, $infoData = array()){
        $strSet = array();
        if(empty($infoData))
            return FALSE;
        $rule = self::initRule($infoData);
        if(FALSE === $rule){
            return FALSE;
        }
        foreach($infoData as $infoKey => $infoValue){
            $strSet[] = " {$infoKey} = {$infoValue} ";
        }
        $infoSql  = " update tc_info set ";
        $infoSql .= implode(",", $strSet);
        $infoSql .= " where info_id = {$infoID}";
        return $this->portalDB->query($infoSql);
    }

    /**
     * addinfo 
     * 新增
     * @param mixed $infoData 
     * @access public
     * @return void
     */
    public function addInfo($infoData){
        if(empty($infoData))
            return false;
        $rule = self::initRule($infoData);
        if(FALSE === $rule){
            return FALSE;
        }
        $fieldsKeys = implode(",", array_keys($infoData));
        $fieldsValues = implode(",", array_values($infoData));
        $infoSql  = " insert into tc_info ({$fieldsKeys}) values({$fieldsValues})";
        return FALSE === $this->portalDB->query($infoSql) ? FALSE : $this->portalDB->insert_id();
    }

    /**
     * delinfo 
     * 根据条件删除
     * @param mixed $infoID 
     * @access public
     * @return void
     */
    public function delInfo($infoID){
        $infoSql  = " delete from tc_info where info_id = ? ";
        return $this->portalDB->query($infoSql, array($infoID));
    }

}

?>
