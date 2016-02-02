<?php

/**
 * 例：dd_object ORM文件
 */
class Obj_model extends CI_Model {

    public function __construct() {
        parent :: __construct();
    }

    public function getFields(){
        return array(
                'id',
                'cate',
                'series',
                'number',
                'id1',
                'id2',
                'name',
                'standard',
                'market_date',
                'unit',
                'small_box',
                'normal_box',
                'piece',
                'price',
                'price2',
                'price_tmp',
                'order_kou',
                'order_kou2',
                'order_kou3',
                'bigcate',
                'brand',
                'jde_attr',
                'zhengce',
        );                  
    }                       
                            
    public function getInfo(){
        $fields = implode(',', $this->getFields());
        $ret = $this->db->query("select {$fields} from orderPlacingMeeting.product order by number desc")->result_array();
        return $ret;        
    }                       
                            
    public function delete($data){
        unset($data['oper']);
        $id = $data["id"];
        $sql = "delete from orderPlacingMeeting.product where id = {$id}";
        $ret = $this->db->query("{$sql}");
        return $ret;
    }                       

    public function save($data){
        unset($data['oper']);
        if(isset($data['id']) && !empty($data['id'])){
            //update
            $id = $data['id'];
            unset($data['id']);
            $sqlStr = array();
            foreach($data as $key => $value){
                $sqlStr[] = "{$key} = '{$value}'";
            }
            $upfields = implode(",", $sqlStr);
            $sql = "update orderPlacingMeeting.product set {$upfields} where id = {$id}";
        }else{
            //save
            $fields = implode(',', $this->getFields());
            $datas = implode(',', $data);
            $sql = "insert into product($fields) values({$datas})";
        }
        $ret = $this->db->query("{$sql}");
        return $ret;
    }                       
}

