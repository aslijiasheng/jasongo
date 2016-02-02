<?php
class Remind_model extends CI_Model{
	public function __construct() {
        parent :: __construct();
    }


    //提醒列表数据
    public function ReminderList($org_id,$user_id,$is_desk=flase,$CurrentPage=1,$PageSize=10){
        $where = array(
            'tc_remind_person.to_obj_id' => 8620,
            'tc_remind_person.org_id' => $org_id,
            'tc_remind_person.to_desktop' =>1,
            'tc_remind_person.to_obj_type' => 99,
            );
        $this->db->select('tc_reminder.remind_mesg,tc_reminder.obj_type,tc_remind_person.is_new,tc_reminder.obj_id,');
        $this->db->select("to_char(tc_remind_person.remind_time, 'yyyy-mm-dd hh24:ii:ss') as remind_time");
        $this->db->where($where);
        $this->db->from('tc_remind_person');
        $join_on = 'tc_reminder.org_id = tc_remind_person.org_id and tc_reminder.remind_id = tc_remind_person.remind_id';
        $this->db->join('tc_reminder',$join_on,'inner');
        $this->db->order_by('tc_remind_person.remind_id', "desc");
        if($is_desk){
        	$this->db->limit(3,1);
        	//$data = $this->db->get()->result_array();	
        }else{
        	$this->db->limit($PageSize, ($CurrentPage - 1) * $PageSize + 1);
        	$data = $this->db->get()->result_array();
        	foreach ($data as $k => $v) {
        		$data[$k]['REMIND_TIME'] = $this->objects->isdatetime($v['REMIND_TIME'],8*60*60);
        	}
        	$count = $this->count($org_id,$user_id);
        	return $this->InfoPagers($CurrentPage, $count, $PageSize, $data);
        }
        foreach ($data as $k => $v) {
        	$data[$k]['REMIND_TIME'] = $this->objects->isdatetime($v['REMIND_TIME'],8*60*60);
        }
        return $data;
    }
    //$count
    public function count($org_id,$user_id){
    	$where = array(
            'tc_remind_person.to_obj_id' => 8620,
            'tc_remind_person.org_id' => $org_id,
            'tc_remind_person.to_desktop' =>1,
            'tc_remind_person.to_obj_type' => 99,
            );
        $this->db->select('tc_reminder.remind_mesg,tc_reminder.obj_type,tc_reminder.obj_id');
        $this->db->select("to_char(tc_remind_person.remind_time, 'yyyy-mm-dd hh24:ii:ss') as remind_time");
        $this->db->where($where);
        $this->db->from('tc_remind_person');
        $join_on = 'tc_reminder.org_id = tc_remind_person.org_id and tc_reminder.remind_id = tc_remind_person.remind_id';
        $this->db->join('tc_reminder',$join_on,'inner');
        $this->db->order_by('tc_remind_person.remind_id', "desc");
        $count = $this->db->count_all_results();
        return $count;
    }

    protected function InfoPagers($CurrentPage, $count, $PageSize, $data) {
		//要返回的JSON由 数据 当前页 总页数 数据总数 五部分组成
        $responce->page = $CurrentPage; //当前页
        $responce->records = $count;
        if ($count > 0) {
            $total_pages = ceil($count / $PageSize);
        } else {
            $total_pages = 0;
        }
        $responce->total = $total_pages; //分页总数
		//循环取出分页后的列表JSON
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $responce->rows[$k]["cell"] = $v;
            }
        }
		//总页数
        return json_encode($responce);
        die;
    }
            
}
?>