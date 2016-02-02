<?php

/**
 * Platform_model 
 * 管理业务系统数据ORM
 * @uses CI
 * @uses _Model
 * @package 
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <sky@php.net> 
 * @license PHP Version 5.3
 */
class Platform_model extends CI_Model {

    private $portalDB;
    private $table = "tc_platform";
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
            "`ignore#type:auto#isPrimary:true`|platform_id",
            "`required#type:string#length:20`|platform_name",
            "`required#type:string#length:10`|platform_icon",
            "`required#type:string#length:20`|platform_url",
            "`required#type:int`|platform_desc",
        );
    }

    private static function initRule($data){
        $modelRet = modelRequired(self::modelRequired, self::getFieldsName(), $data);
        return $modelRet['succ'];
    }

    /**
     * getPlatformData 
     * 查询单一数据
     * @access public
     * @return void
     */
    public function getPlatformData($platformID){
        $sql = "select * from tc_platform where platform_id = ?";
        return $this->portalDB->query($sql, array($platformID))->result_array();
    }

    /**
     * queryAllContantsData 
     * 不建议使用 全部查询通讯录人员
     * @access public
     * @return void
     */
    public function queryAllPlatformData(){
        $sql = "select * from tc_platform";
        return $this->portalDB->query($sql)->result_array();
    }

    /**
     * updateplatform 
     * 更新
     * @param mixed $platformID 
     * @param mixed $platformData 
     * @access public
     * @return void
     */
    public function updatePlatform($platformID, $platformData = array()){
        $strSet = array();
        if(empty($platformData))
            return FALSE;
        $rule = self::initRule($platformData);
        if(FALSE === $rule){
            return FALSE;
        }
        foreach($platformData as $platformKey => $platformValue){
            $strSet[] = " {$platformKey} = {$platformValue} ";
        }
        $platformSql  = " update tc_platform set ";
        $platformSql .= implode(",", $strSet);
        $platformSql .= " where platform_id = {$platformID}";
        return $this->portalDB->query($platformSql);
    }

    /**
     * addPlatform 
     * 新增
     * @param mixed $platformData 
     * @access public
     * @return void
     */
    public function addPlatform($platformData){
        if(empty($platformData))
            return false;
        $rule = self::initRule($platformData);
        if(FALSE === $rule){
            return FALSE;
        }
        $fieldsKeys = implode(",", array_keys($platformData));
        $fieldsValues = implode(",", array_values($platformData));
        $platformSql  = " insert into tc_platform ({$fieldsKeys}) values({$fieldsValues})";
        return FALSE === $this->portalDB->query($platformSql) ? FALSE : $this->portalDB->insert_id();
    }

    /**
     * delPlatform 
     * 根据条件删除
     * @param mixed $platformID 
     * @access public
     * @return void
     */
    public function delPlatform($platformID){
        $platformSql  = " delete from tc_platform where platform_id = ? ";
        return $this->portalDB->query($platformSql, array($platformID));
    }
}

?>
