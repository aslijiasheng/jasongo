<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
require_once APPPATH."/third_party/PHPExcel.php";
require_once APPPATH."/third_party/PHPExcel/IOFactory.php";

class Excel {

    /**
     * 读取一个excel并把内容放入到一个数组中
     * @param string $excel_file
     * @param integer $sheet_id
     * @param array $headers
     * @return array 
     */
    public function readExcel($excel_file, $sheet_id, $headers, $contain_all_column = true) {
        $excel_type = PHPExcel_IOFactory::identify($excel_file);
        $excel_reader = PHPExcel_IOFactory::createReader($excel_type);
        if(method_exists($excel_reader, "setReadDataOnly")) {
            $excel_reader->setReadDataOnly(true);
        }
        $excel = $excel_reader->load($excel_file);
        $count = $excel->getSheetCount();
        if($count< $sheet_id) {
            return "无效的excel，工作表个数不对";
        } else {
            $sheet = $excel->getSheet($sheet_id);
            $data = self::readSheet($sheet, $headers, $contain_all_column);
            return $data;
        }
    }
    
    private static function readSheet($sheet, $headers, $contain_all_column) {
        $contents = array();
        $index = 0;
        $header_seq = array();
        $column_seq = array();
        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            foreach ($cellIterator as $column => $cell) {
                if ($index == 0) {
                    $header = $cell->getValue();
                    
                    if(in_array($header, $headers)) {
                        $header_seq[] = $header;
                        $column_seq[] = $column;
                    } else {
                        if($contain_all_column == true) {
                            return "excel文件格式不对，无法识别的列" . $header;
                        } else {
                            continue;
                        }
                    }
                } else if(in_array($column, $column_seq)){
                    $contents[$index][] = $cell->getValue();
                }
            }
            $index++;
        }
        return array('headers' => $header_seq, 'contents' => $contents);
    }
}
