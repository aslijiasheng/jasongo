<?php

/**
 * Announcement_model 
 * announcement业务处理类最好使用CIORM进行ADO方式处理不得使用SQL拼接方式防止一次注入，取出字段时使用HTMLSPECIALCHARS函数对数据进行输出
 * @uses CI
 * @uses _Model
 * @package 
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <sky@php.net> 
 * @license PHP Version 5.3
 */
class Announcement_model extends CI_Model {

    private $portalDB;
    private $table = "tc_announcement";
    private $_announcementID;
    private static $modelRequired = TRUE;

    function __construct(){
        parent::__construct();
        $this->portalDB = $this->load->database('portal', TRUE);
        $this->load->model('www/marteria_model', 'marteria');
    }

    /**
     * getFieldsName 
     * ORMFields
     * @access private
     * @return void
     */
    private static function getFieldsName(){
        return array(
            "`ignore#type:auto#length:0#isPrimary:true#relation:''`|announcement_id",
            "`required#type:string#length:100#isPrimary:false#relation:''`|announcement_title",
            "`ignore#type:string#length:20#isPrimary:false#relation:tc_marteria`|announcement_pic",
            "`required#type:int#length:10#isPrimary:false#relation:''`|announcement_create_date",
            "`required#type:string#length:20#isPrimary:false#relation:''`|announcement_tag",
            "`required#type:string#length:50#isPrimary:false#relation:''`|announcement_html_path",
            "`required#type:boolean#length:1#isPrimary:false#relation:''`|announcement_is_status",
            "`ignore#type:string#length:10#isPrimary:false#relation:''`|announcement_create_user",
        );
    }

    private static function initRule($announcementData){
        $modelRet = modelRequired(self::$modelRequired, self::getFieldsName(), $announcementData);
        return $modelRet['succ'];
    }

    /**
     * getAnnouncementData 
     * 查询公告信息
     * @param string $announcementID 
     * @access public
     * @return void
     */
    public function getAnnouncementData($announcementID = ""){
        $announcementData = array();
        $this->_announcementID = $announcementID;
        $ret = $this->queryAnnouncement();
        foreach($ret as $key => $value){
            $id = $value['announcement_id'];
            $title = $value['announcement_title'];
            //如果有需要把图片单独查询可连接marteria表
            $pic = $value['announcement_pic'];
            $createDate = date('Y-m-d', $value['announcement_create_date']);
            $tag = $value['announcement_tag'];
            $userName = $value['announcement_create_user'];
            $htmlPath = '';
            file_exists($value['announcement_html_path']) && $htmlPath = file_get_contents($value['announcement_html_path']);
            $isStatus = $value['announcement_is_status'];
            $announcementData[$id] = array(
                'title' => $title,
                'pic' => $pic,
                'createDate' => $createDate,
                'tag' => $tag,
                'htmlPath' => $htmlPath,
                'isStatus' => $isStatus,
                'userName' => $userName,
            );
        }
        return $announcementData;
    }

    /**
     * queryAnnouncement 
     * 查询
     * @param mixed $announcementID 
     * @access private
     * @return void
     */
    private function queryAnnouncement(){
        $announcementSql = " select * from tc_announcement ";
        if(!empty($this->_announcementID)){
            $announcementSql .= "where announcement_id = ?";
            return $this->portalDB->query($announcementSql, array($this->_announcementID))->result_array();
        }
        return $this->portalDB->query($announcementSql)->result_array();
    }

    /**
     * addAnnouncement 
     * 新增
     * @param mixed $announcementData 
     * @access public
     * @return void
     */
    public function addAnnouncement($announcementData){
        if(empty($announcementData))
            return false;
        $rule = self::initRule($announcementData);
        if(FALSE === $rule){
            return FALSE;
        }
        $fieldsKeys = implode(",", array_keys($announcementData));
        $fieldsValues = implode("','", array_values($announcementData));
        $announcementSql  = " insert into tc_announcement ({$fieldsKeys}) values('{$fieldsValues}')";
        return FALSE === $this->portalDB->query($announcementSql) ? FALSE : $this->portalDB->insert_id();
    }

    /**
     * updateAnnouncement 
     * 删除与更新
     * @param mixed $announcementID 
     * @param mixed $announcementData 
     * @access public
     * @return void
     */
    public function updateAnnouncement($announcementID, $announcementData = array()){
        $strSet = array();
        if(empty($announcementData))
            return FALSE;
        $rule = self::initRule($announcementData);
        if(FALSE === $rule){
            return FALSE;
        }
        foreach($announcementData as $announcementKey => $announcementValue){
            $strSet[] = " {$announcementKey} = '{$announcementValue}' ";
        }
        $announcementSql  = " update tc_announcement set ";
        $announcementSql .= implode(",", $strSet);
        $announcementSql .= " where announcement_id = {$announcementID}";
        return $this->portalDB->query($announcementSql);
    }


}

?>
