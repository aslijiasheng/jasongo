<?php

/**
 * Contants_model 
 * 联系人通讯录
 * @uses CI
 * @uses _Model
 * @package 
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <sky@php.net> 
 * @license PHP Version 5.3
 */
class Contants_model extends CI_Model {

    private $portalDB;
    private $_contantsData = array();
    private $_offset = 0;
    private $_limit = 10;
    private $_where = "";
    private static $modelRequired = TRUE;
    private $table = "tc_contants";

    function __construct(){
        parent::__construct();
        $this->portalDB = $this->load->database('portal', TRUE);
        $this->load->model('www/marteria_model', 'marteria');
    }

    public function init($offset, $limit, $contantsData){
        $this->_offset       = intval($offset);
        $this->_limit        = intval($limit);
        $this->_contantsData = $contantsData;
        $this->initBegin();
        $this->initData();
        $this->initEnd();
    }

    protected function initBegin(){
    }

    /*
     *_contantsData
     *key 数据库字段
     *value0 值
     *value1 操作符 = <= >= != PRELIKE SUFFIXLIKE BOTHLIKE BETWEEN < > IN
     *value2 连接符 and or 不建议采用多表联接
     */
    protected function initData(){
        if(!is_array($this->_contantsData))
            return FALSE;
        foreach($this->_contantsData as $key => $value){
            switch($value[1]){
                case "=":
                    $this->_where .= $key . $value[1] . $value[0] . " " . $value[2]. " ";
                    break;
                case "<":
                    $this->_where .= $key . $value[1] . $value[0] . " " . $value[2]. " ";
                    break;
                case "<=":
                    $this->_where .= $key . $value[1] . $value[0] . " " . $value[2]. " ";
                    break;
                case ">":
                    $this->_where .= $key . $value[1] . $value[0] . " " . $value[2]. " ";
                    break;
                case ">=":
                    $this->_where .= $key . $value[1] . $value[0] . " " . $value[2]. " ";
                    break;
                case "!=":
                    $this->_where .= $key . $value[1] . $value[0] . " " . $value[2]. " ";
                    break;
                case "PRELIKE":
                    $this->_where .= $key . " like %" . $value[0] . " " . $value[2]. " ";
                    break;
                case "SUFFIXLIKE":
                    $this->_where .= $key . " like " . $value[0] . "%" . " " . $value[2]. " ";
                    break;
                case "BOTHLIKE":
                    $this->_where .= $key . " like %" . $value[0] . "%" . " " . $value[2]. " " ;
                    break;
                case "BETWEEN":
                    $this->_where .= $key . " BETWEEN " . $value[0][0] . "AND" . $value[0][1] . " " . $value[2]. " ";
                case "IN":
                    $this->_where .= $key . " IN('" . implode(",'", $value[0]) . "')" . " " . $value[2]. " ";
                    break;
            }
        }
        return TRUE;
    }

    protected function initEnd(){
    }

    /**
     * getFieldsName 
     * ORMFields
     * @access private
     * @return void
     */
    private static function getFieldsName(){
        return array(
            "`ignore#type:auto#length:0#isPrimary:true#relation:''`|contants_id",
            "`required#type:string#length:11#isPrimary:false#relation:''`|contants_phone",
            "`required#type:string#length:100#isPrimary:false#relation:tc_marteria`|contants_materials",
            "`required#type:string#length:20#isPrimary:false#relation:''`|contants_name",
            "`required#type:string#length:13#isPrimary:false#relation:''`|contants_tel",
            "`required#type:string#length:20#isPrimary:false#relation:''`|contants_user_gonghao",
        );
    }

    private static function initRule($contants){
        $modelRet = modelRequired(self::$modelRequired, self::getFieldsName(), $contants);
        return $modelRet['succ'];
    }

    /**
     * getcontantsData 
     * 单条查询联系人信息
     * @param string $contantsID 
     * @access public
     * @return void
     */
    public function getcontantsData($contantsID){
        $sql = "select * from tc_contants where contants_id = ?";
        return $this->portalDB->query($contantsSql, array($contantsID))->result_array();
    }

    /**
     * queryPageContantsData 
     * 分页查询
     * @param mixed $contantsData 
     * @param int $limit 
     * @param int $offset 
     * @access public
     * @return void
     */
    public function queryPageContantsData($contantsData, $offset = 0, $limit = 10){
        $contantsSqlPrefix = "";
        $contantsSqlPage   = "";
        $contantsSqlWhere  = "";
        $contantsSql       = "";
        $this->_contantsData = $contantsData;
        if(empty($contantsData))
            return false;
        $rule = self::initRule($contantsData);
        if(FALSE === $rule){
            return FALSE;
        }
        $this->init($offset, $limit, $contantsData);
        $contantsSqlPrefix = "select * from tc_contants";
        $contantsSqlWhere = $this->_where;
        $contantsSqlPage   = " limit {$this->_limit} offset {$this->_offset}";
        $contantsSql = $contantsSqlPrefix . " " . $contantsSqlPage;
        if(!empty($this->_where)){
            $contantsSql = $contantsSqlPrefix . " where " . $contantsSqlWhere . " " . $contantsSqlPage;
        }
        return $this->portalDB->query($contantsSql)->result_array();
    }

    /**
     * queryAllContantsData 
     * 不建议使用 全部查询通讯录人员
     * @access public
     * @return void
     */
    public function queryAllContantsData(){
        $sql = "select * from tc_contants";
        return $this->portalDB->query($sql)->result_array();
    }

    /**
     * addcontants 
     * 新增
     * @param mixed $contantsData 
     * @access public
     * @return void
     */
    public function addContants($contantsData){
        if(empty($contantsData))
            return false;
        $rule = self::initRule($contantsData);
        if(FALSE === $rule){
            return FALSE;
        }
        $fieldsKeys = implode(",", array_keys($contantsData));
        $fieldsValues = implode("','", array_values($contantsData));
        $contantsSql  = " insert into tc_contants ({$fieldsKeys}) values('{$fieldsValues}')";
        return FALSE === $this->portalDB->query($contantsSql) ? FALSE : $this->portalDB->insert_id();
    }

    /**
     * updatecontants 
     * 更新
     * @param mixed $contantsID 
     * @param mixed $contantsData 
     * @access public
     * @return void
     */
    public function updateContants($contantsID, $contantsData = array()){
        $strSet = array();
        if(empty($contantsData))
            return FALSE;
        $rule = self::initRule($contantsData);
        if(FALSE === $rule){
            return FALSE;
        }
        foreach($contantsData as $contantsKey => $contantsValue){
            $strSet[] = " {$contantsKey} = {$contantsValue} ";
        }
        $contantsSql  = " update tc_contants set ";
        $contantsSql .= implode(",", $strSet);
        $contantsSql .= " where contantsID = {$contantsID}";
        return $this->portalDB->query($contantsSql);
    }

    /**
     * delContants 
     * 根据条件删除
     * @param mixed $contantsID 
     * @access public
     * @return void
     */
    public function delContants($contantsID){
        $contantsSql  = " delete from tc_contants where contants_id = ? ";
        return $this->portalDB->query($contantsSql, array($contantsID));
    }

    /**
     * truncatContants 
     * 全部删除  警告!! 慎用!!
     * @access public
     * @return void
     */
    public function truncatContants(){
        $contantsSql  = " delete from tc_contants ";
        return $this->portalDB->query($contantsSql);
    }
}

?>
