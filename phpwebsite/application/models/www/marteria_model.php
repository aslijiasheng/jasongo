<?php
/**
 * Marteria_model 
 * 图库统一调用处理类
 * @uses CI
 * @uses _Model
 * @package 
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <sky@php.net> 
 * @license PHP Version 5.3
 */
class Marteria_model extends CI_Model {

    private $portalDB;
    private $table = "tc_marteria";
    private $_announcementID;
    private static $modelRequired = TRUE;
    private static $marteriaPath = "images/marteriaImages/";
    private static $fileExtension = array("jpg", "png", "jpeg");

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
            "`ignore#type:auto#isPrimary:true`|marteria_id",
            "`required#type:string#length:100`|marteria_path",
            "`required#type:boolean`|marteria_is_status",
        );
    }

    private static function initRule($marteriaData){
        $modelRet = modelRequired(self::$modelRequired, self::getFieldsName(), $marteriaData);
        return $modelRet['succ'];
    }

    /**
     * saveMarteriaPath 
     * 保存图片到物理磁盘
     * @param mixed $fileTmpDir 
     * @param mixed $fileName 
     * @access private
     * @return void
     */
    public function saveMarteriaPath($fileTmpDir, $fileName){
        $fileWritePath = self::$marteriaPath . $fileName;
        if(file_exists($fileWritePath)) return $fileWritePath;
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        if(!in_array($fileExtension, self::$fileExtension)) return 'EXTENSION IS FALSE';
        $streamReader = file_get_contents($fileTmpDir);
        file_put_contents($fileWritePath, $streamReader);
        return $fileWritePath;
    }

    /**
     * addMarteria 
     * 新增图片及图文素材
     * @param mixed $marteriaData 
     * @access public
     * @return void
     */
    public function addMarteria($marteriaData){
        if(empty($marteriaData))
            return false;
        $rule = self::initRule($marteriaData);
        if(FALSE === $rule) return FALSE;
        $fieldsKeys   = implode(",", array_keys($marteriaData));
        $fieldsValues = implode("','", array_values($marteriaData));
        $marteriaSql  = " insert into tc_marteria ({$fieldsKeys}) values('{$fieldsValues}')";
        return FALSE === $this->portalDB->query($marteriaSql) ? FALSE : $this->portalDB->insert_id();
    }

    /**
     * upMarteria 
     * 删除与是更新
     * @param mixed $marteriaID 
     * @param array $marteriaData 
     * @access public
     * @return void
     */
    public function upMarteria($marteriaID, $marteriaData = array()){
        $strSet = array();
        if(empty($marteriaData))
            return FALSE;
        $rule = self::initRule($marteriaData);
        if(FALSE === $rule) return FALSE;
        foreach($marteriaData as $marteriaKey => $marteriaValue){
            $strSet[] = " {$marteriaKey} = {$marteriaValue} ";
        }
        $marteriaSql  = " update tc_marteria set ";
        $marteriaSql .= implode(",", $strSet);
        $marteriaSql .= " where marteriaID = {$marteriaID}";
        return $this->portalDB->query($marteriaSql);
    }

    /**
     * getQueryMarteria 
     * 查询图片与图文
     * @param array $marteriaIDs 
     * @access public
     * @return void
     */
    public function getQueryMarteria($marteriaIDs = array()){
        if(!is_array($marteriaIDs)) return FALSE;
        $marteriaInData = implode(',', $marteriaIDs);
        $marteriaSqlPreix = "select marteria_id, marteria_path, marteria_path from tc_marteria";
        $marteriaInData = str_replace(",", "','", $marteriaInData);
        $marteriaSql = $marteriaSqlPreix . " where marteria_id in ('{$marteriaIDs}')";
        return $this->portalDB->query($marteriaSql)->result_array();
    }
}

?>
