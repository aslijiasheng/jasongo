<?php


class Format_model extends CI_Model {


    /**
     * 查询列表布局
     * @param int $obj_id 账套号ID
     * @param int $obj_type 对象类型ID
     * @return array 返回数组
     */
    public function GetListFormat($obj_id, $obj_type, $user_id = 0,$sub_type=0) {
        $select = array('quick_list_flds');
        $where["org_id"] = $obj_id;
        $where["user_id"] = $user_id;
        $where["obj_type"] = $obj_type;
        $where["sub_type"] = $sub_type;
        $this->db->from("tc_user_list_profile");
        $this->db->select($select);
        $this->db->where($where);
        $data = $this->db->get()->result_array();

        if(empty($data)){
            $select = array('quick_list_flds');
            $where["org_id"] = $obj_id;
            $where["user_id"] = 0;
            $where["obj_type"] = $obj_type;
            $where["sub_type"] = $sub_type;
            $this->db->from("tc_user_list_profile");
            $this->db->select($select);
            $this->db->where($where);
            $data = $this->db->get()->result_array();
        }
        //转化成数组
        $data_arr = explode(';', $data[0]["QUICK_LIST_FLDS"]);
        $obj_name= substr($data_arr[0],0,strpos($data_arr[0],'.'));
        return $data_arr;
    }
    /**
     * 查询全文索引需要显示的字段
     * @param int $obj_id 账套号ID
     * @param int $obj_type 对象类型ID
     * @return array 返回数组
     */
    public function GetFullIndexFormat($obj_id, $obj_type, $user_id = 0) {
        $select = array('display_fields');
        $where["org_id"] = $obj_id;
        $where["obj_type"] = $obj_type;
        $this->db->from("tc_search_profile");
        $this->db->select($select);
        $this->db->where($where);
        $data = $this->db->get()->result_array();
        //转化成数组
        $data_arr = explode(';', $data[0]["DISPLAY_FIELDS"]);
        return $data_arr;
    }

    /**
     * GetViewFormat 获取查看页面布局
     * @param int $org_id 账套号ID
     * @param int $obj_type 对象类型ID
     * @param int $type_id 类型ID
     * @return array $data_arr 返回数组
     */
    public function GetViewFormat($org_id, $obj_type, $type_id = 0) {
        $type_id = empty($type_id)?0:$type_id;
        $select = "format_content";
        $where["org_id"] = $org_id;
        $where["obj_type"] = $obj_type;
        $where["type_id"] = $type_id;
        $this->db->from("tc_card_format");
        $this->db->select($select);
        $this->db->where($where);
        $data = $this->db->get()->result_array();
        $json = base64_decode($data[0]["FORMAT_CONTENT"]->load());
        //json转化成数组
        $data_arr = json_decode($json, TRUE);

        return $data_arr;
    }

    /**
     * GetEditFormat 获取编辑页面布局
     * @param int $Obj_id 账套号ID
     * @param int $obj_type 对象类型
     * @param int $type_id 对象类型ID
     * @return array $data_arr返回数组
     */
   public function GetEditFormat($org_id, $obj_type, $type_id = 0 ,$is_detail=false) {
        $type_id = empty($type_id)?0:$type_id;
        $select = "format_content";
        $where["org_id"] = $org_id;
        $where["obj_type"] = $obj_type;
        $where["type_id"] = $type_id;
        $this->db->from("tc_edit_card_format");
        $this->db->select($select);
        $this->db->where($where);

        $data = $this->db->get()->result_array();
        if ($is_detail) {
            return $this->getInfoResult($data);
        }
        return $this->getResult($data);
    }
    
    /**
     * 明细布局  处理
     */
    protected function getInfoResult($format_content) {
        if (empty($format_content)) {
            return null;
        } 
        $json = base64_decode($format_content[0]["FORMAT_CONTENT"]->load());
        $data_arr = explode(";",$json);
        return $data_arr;
    }
    
    /**
     * 编辑 新增 布局 处理
     */
    protected function getResult($format_content){
        $json = base64_decode($format_content[0]["FORMAT_CONTENT"]->load());
        $data_arr = json_decode($json, TRUE);
        return $data_arr;
    }
}

?>
