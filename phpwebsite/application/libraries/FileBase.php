<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
/**
 * FileBase 
 * 文件处理类
 * @package 
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <sky@php.net> 
 * @license PHP Version 5.3
 */
class FileBase {

    private $_fileTmpPath  = "";
    private $_filePath     = "";
    private $_fileName     = "";
    private $_fileType     = "";
    public $_fileExtersion = array("image/gif", "image/jpg", "image/jpeg", "image/pjpeg", "image/png", "xls", "xlsx", "doc", "docx");

    public function init($files, $class){
        $this->_fileName    = $files['file']['name'];
        $this->_fileType    = pathinfo($files['file']['name'], PATHINFO_EXTENSION);;
        $this->_fileTmpPath = $files['file']['tmp_name'];
        $this->_filePath    = "images/{$class}/";
    }

    /**
     * checkFileExtersion 
     * 校验文件类型
     * @access public
     * @return void
     */
    public function checkFileExtersion(){
        if(!in_array($this->_fileType, $this->_fileExtersion))
            return FALSE;
        if(!file_exists($this->_filePath))
            return FALSE;
        return TRUE;
    }

    /**
     * saveFilePath 
     * 保存文件路径
     * @access public
     * @return void
     */
    public function saveFilePath(){
        $streamReader = file_get_contents($this->_fileTmpPath);
        file_put_contents($this->_filePath . $this->_fileName, $streamReader);
        return $this->_filePath . $this->_fileName;
    }
}
