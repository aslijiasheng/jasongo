<?php

class Customer_model extends CI_Model {

    public function __construct() {
        parent :: __construct();
    }

    public function getFields(){
        return array(
                'uid',
                'uname',
                // 'series',
                // 'number',
                // 'id1',
                // 'id2',
                // 'name',
                // 'standard',
                // 'market_date',
                // 'unit',
                // 'small_box',
                // 'normal_box',
                // 'piece',
                // 'price',
                // 'price2',
                // 'price_tmp',
                // 'order_kou',
                // 'order_kou2',
                // 'order_kou3',
                // 'bigcate',
                // 'brand',
                // 'jde_attr',
                // 'zhengce',
        );                  
    }                       
                            
    public function getInfo(){
        $fields = implode(',', $this->getFields());
        $ret = $this->db->query("select {$fields} from orderPlacingMeeting.user order by uid desc")->result_array();
        return $ret;        
    }                       
                            
    public function delete($data){
        $id = $data['uid'];
        unset($data['oper']);
        unset($data['uid']);
        $id = $data["id"];
        $sql = "delete from orderPlacingMeeting.user where uid = {$id}";
        $ret = $this->db->query("{$sql}");
        return $ret;
    }                       

    public function update($data){
        $id = $data['uid'];
        unset($data['id']);
        unset($data['oper']);
        //update
        $sqlStr = array();
        foreach($data as $key => $value){
            $sqlStr[] = "{$key} = '{$value}'";
        }
        $upfields = implode(",", $sqlStr);
        $sql = "update orderPlacingMeeting.user set {$upfields} where uid = {$id}";
        $ret = $this->db->query("{$sql}");
        return $ret;
    }                       

    public function save($data){
        unset($data['id']);
        unset($data['oper']);
        //save
        $fields = implode(',', $this->getFields());
        $datas = implode(',', $data);
        $sql = "insert into user($fields) values({$datas})";
        $ret = $this->db->query("{$sql}");
        return $ret;
    }
}

