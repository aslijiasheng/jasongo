<?php
class {#obj_name_uc#}_model extends CI_Model{

	//这里代表那些字段在列表上显示出来
	public function listLayout(){
		return array(
			{#foreach from=list_layout.layout_arr item=ll key=ll_k#}
			'{#obj_name#}_{#ll|attr_name#}',
			{#/foreach#}
		);
	}

	//这里代表那些字段在编辑页面上显示出来
	public function editLayout(){
		return array(
			{#foreach from=edit_layout.layout_arr item=el key=el_k#}
			'{#obj_name#}_{#el|attr_name#}',
			{#/foreach#}
		);
	}

	//这里代表那些字段在查看页面上显示出来
	public function viewLayout(){
		return array(
			{#foreach from=view_layout.layout_arr item=vl key=vl_k#}
			'{#obj_name#}_{#vl|attr_name#}',
			{#/foreach#}
		);
	}

	//属性对应的中文标签
	public function attributeLabels(){
		return array(
			{#foreach from=obj_attr item=g key=k#}
			'{#obj_name#}_{#g|attr_name#}' => '{#g|attr_label#}',
			{#/foreach#}
		);
	}

	//列表查询
	/*
		$page //获得当前的页面值
		$perNumber //每页显示的记录数
	*/
	public function listGetInfo($where,$page=1,$perNumber=10,$like=""){
		$limitStart = ($page-1)*$perNumber+1-1; //这里因为数据库是从0开始算的！所以要减1
		$limit=array($perNumber,$limitStart);
		//p($limit);
		$data = $this->GetInfo($where,$limit,$like);
		return $data;
	}

	//用ID单个查询
	public function id_aGetInfo($id){
		$where=array(
			'{#obj_name#}_id'=>$id
		);
		$data = $this->GetInfo($where);
		$data = $data[0];
		return $data;
	}

	//单个修改
	public function update($data,$id){
		$this->db->where('{#obj_name#}_id',$id)->update('{#obj_table_name#}',$data);
	}

	//单个删除
	public function del($id){
		$this->db->where('{#obj_name#}_id',$id)->delete('{#obj_table_name#}');
	}

	//单个新增
	public function add($data){
		$this->db->insert('{#obj_table_name#}',$data);
		$insert_id=$this->db->insert_id();
		return $insert_id;
	}

	//根据条件查询出数据总数
	public function countGetInfo($where="",$like=""){
		$this->db->from('{#obj_table_name#}');
		if ($where!=""){
			$this->db->where($where);
		}
		if ($like!=""){
			$this->db->like($like);
		}
		$count = $this->db->count_all_results();
		return $count;
	}


	//所有的查询都写一个通用方法 参数$where为判断条件的数组
	public function GetInfo($where="",$limit="",$like=""){
		//首先查询出本身所有的内容
		$this->db->from('{#obj_table_name#}');
		if ($where!=""){
			$this->db->where($where);
		}
		if ($limit!=""){
			$this->db->limit($limit[0],$limit[1]);
		}
		if ($like!=""){
			$this->db->like($like);
		}
		$data=$this->db->get()->result_array();
		//然后循环查询出所有引用的内容
		
		foreach ($data as $k=>$v){
			//p($v);die;
			//所有属性循环
			{#foreach from=obj_attr item=oa key=oa_k#}
				{#if {#oa|attr_type#}==19#} //*当属性类型为引用类型时*//
			if($v['{#oa|attr_field_name#}']!="" and $v['{#oa|attr_field_name#}']!=0){
				$this->load->model('www/{#oa|attr_quote_id_arr.obj_name#}_model','{#oa|attr_quote_id_arr.obj_name#}');
				$data[$k]['{#oa|attr_field_name#}_arr'] = $this->{#oa|attr_quote_id_arr.obj_name#}->id_aGetInfo($v['{#oa|attr_field_name#}']);
			}
				{#/if#}
			
				{#if {#oa|attr_type#}==14#} //当属性类型为单选时
			if($v['{#oa|attr_field_name#}']!="" and $v['{#oa|attr_field_name#}']!=0){
				$this->load->model('admin/enum_model','enum');
				
				$data[$k]['{#oa|attr_field_name#}_arr']=$this->enum->getlist({#oa|attr_id#},$data[$k]['{#oa|attr_field_name#}']);
			}
				{#/if#}
			
				{#if {#oa|attr_type#}==15#} //当属性类型为下拉单选时
			if($v['{#oa|attr_field_name#}']!="" and $v['{#oa|attr_field_name#}']!=0){
				$this->load->model('admin/enum_model','enum');
				
				$data[$k]['{#oa|attr_field_name#}_arr']=$this->enum->getlist({#oa|attr_id#},$data[$k]['{#oa|attr_field_name#}']);
			}
				{#/if#}
			
			{#/foreach#}
		}
		return $data;
	}
}
?>
