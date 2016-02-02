<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rel_model
 *
 * @author Administrator
 */
class Rel_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('www/user_model','user');
        $this->load->model('www/relobjectprofile_model','rop');
    }

    /**
     * 处理通用列表相关对象标签
     */
    public function rel_list_labels($rel) {
        if (is_array($rel)) {
            /**
             * select rel_list_flds,rel_list_name,rel_obj_type,rel_tables,cond_id,clear_ids,label from tc_user_rel_profile A
             * inner join dd_str_resource B on A.REL_LIST_NAME = B.STR_RES_NAME
             * where  B.org_id = 1 and 
             * obj_type = 4 and 
             * type_id = 2056 and 
             * -- rel_obj_type = 2003 and 
             * (user_id in (1)) and delete_flag != 0 and lang_id =2 ;
             */
			 /**
             * select rel_list_flds,rel_list_name,rel_obj_type,rel_tables,cond_id,clear_ids,label from tc_user_rel_profile A
             * inner join dd_str_resource B on A.REL_LIST_NAME = B.STR_RES_NAME
             * where  B.org_id = 1 and 
             * obj_type = 4 and 
             * type_id = 0 and 
             * -- rel_obj_type = 2003 and 
             * (user_id in (0)) and delete_flag != 0 and lang_id =2 ;
             */
            $this->db->select("obj_type,rel_list_flds,rel_list_name,rel_obj_type,rel_tables,cond_id,clear_ids,label,lee_condition,user_id");
            $this->db->from("tc_user_rel_profile A");
            $this->db->join("dd_str_resource B", "A.REL_LIST_NAME = B.STR_RES_NAME", "inner");
            $w = array(
                "B.org_id" => $rel['org_id'],
                "obj_type" => $rel['obj_type'],
                "type_id" => $rel['type_id'],
                "lang_id" => $rel['lang_id'],
                "is_disp" => 1,
            );
            $this->db->where($w);
            $w1 = array(
                $rel['user_id'],
                0,
            );
            $this->db->where_in("user_id", $w1);
			$this->db->order_by("rel_list_name");
            $res_data = $this->db->get()->result_array();
			if (empty($res_data)) {
				$this->db->select("obj_type,rel_list_flds,rel_list_name,rel_obj_type,rel_tables,cond_id,clear_ids,label,lee_condition,user_id");
				$this->db->from("tc_user_rel_profile A");
				$this->db->join("dd_str_resource B", "A.REL_LIST_NAME = B.STR_RES_NAME", "inner");
				$w = array(
					"B.org_id" => $rel['org_id'],
					"obj_type" => $rel['obj_type'],
					"type_id" => 0,
					"lang_id" => $rel['lang_id'],
					"is_disp" => 1,
				);
				$this->db->where($w);
				$w1 = array(
					0,
				);
				$this->db->where_in("user_id", $w1);
				$this->db->order_by("rel_list_name");
				$res_data = $this->db->get()->result_array();
			}
			$rel_list_name = '';
            
            foreach ($res_data as $k => &$v) {
				if ( $rel_list_name == $v['REL_LIST_NAME']) unset($res_data[$k]);
				$rel_list_name = $v['REL_LIST_NAME'];
            }
            // p($rel_list_name);
            // foreach ($res_data as $k => $v) {
            //     if($v['USER_ID']==1){
            //         foreach ($res_data as $kk => $vv) {
            //             if($vv['USER_ID']==0){
            //                 if($v['REL_OBJ_TYPE']==$vv['REL_OBJ_TYPE']){
            //                     unset($res_data[$kk]);
            //                 }
            //             }
            //         }
            //     }
            // }
            $res_data = $this->relbutton($rel, $res_data);

            return $res_data;
        }
        return null;
    }
	
    //相关对象按钮
    public function relbutton($rel, & $res_data){
        extract($rel, EXTR_OVERWRITE); //释放数组元素
        $func_name = "RelObjectProfile_" . $obj_type;        
        $rel_pro_data = $this->rop->$func_name();
        $rel_pro_data = empty($rel_pro_data[$type_id]) ? $rel_pro_data[0] : $rel_pro_data[$type_id];
        foreach ($res_data as $k => $v) {
            if (isset($rel_pro_data[$v['REL_LIST_NAME']]) && !empty($rel_pro_data[$v['REL_LIST_NAME']])) {
                $res_data[$k]['btn_action'] = $rel_pro_data[$v['REL_LIST_NAME']];
            }
        }
        return $res_data;
    }

    public function subbut($org_id=1,$obj_type,$rel_obj_type,$type_id){
        $data = $this->subbutton($org_id,$obj_type,$rel_obj_type,$type_id);
        foreach ($data as $k => $v) {
            $arr[$v['TYPE_ID']]['name'] = $v['TYPE_NAME'];
            $arr[$v['TYPE_ID']]['url'] = base_url().'www/objects/addout?obj_type='.$rel_obj_type.'&type_id='.$type_id;
        }
        return $arr;
    }
    /** 
     * 查相关对象类型按钮
     * @param org_id
     * @param obj_type
     * @param rel_obj_type
     * @param type_id
     */
    public function subbutton($org_id,$obj_type,$rel_obj_type,$type_id){
        $this->db->from('rel_object_task_type');
        $this->db->select('task_type');
        $this->db->where('obj_type',$obj_type);
        $where_in = array($type_id,'0');
        $this->db->where_in('type_id',$where_in);
        $banned = $this->db->get()->result_array();
       
        if(!empty($banned)){
            foreach ($banned as $k => $v) {
                $ban[] =$v['TASK_TYPE']; 
            }
        }
        
        //$ban = implode(',', $ban);

        $this->db->select('type_name,type_id');
        $this->db->from('tc_type');
        $where = array(
            'org_id' => $org_id,
            'obj_type' => $rel_obj_type
            );
        $this->db->where($where);
        if(!isset($ban)){
            $this->db->where_not_in('type_id',$ban);
        }
        return $this->db->get()->result_array();
    }

    public function GetRelData($stype,$rtype,$scontent){
        //p($stype);
        //p($rtype);
        $org_id=$this->session->userdata('org_id');
        $LangID = $this->session->userdata("lang_id");
        $user_id = $this->session->userdata('user_id');
        switch ($stype) {
            case '1':
                break;
            case '2':
                break;
            case '3':
                break;
            case '4':
                if($rtype==14){
                    $newdata['Task.Account'] = $scontent['Opportunity.Account'];
                    $newdata['Task.AccountID'] = $scontent['Opportunity.AccountID'];
                    $newdata['Task.Account.ID'] = $scontent['Opportunity.Account.ID'];
                    $newdata['Task.Account.Name'] = $scontent['Opportunity.Account.Name'];
                    $userdata = $this->user->SelectOne($org_id, $user_id);
                    $newdata['Task.AssignedTo'] = $userdata['Name'];
                    $newdata['Task.AssignedToID'] = $user_id;
                    $newdata['Task.AssignedTo.ID'] = $user_id;
                    $newdata['Task.AssignedTo.Name'] = $userdata['Name'];
                    $newdata['Task.RelatedToStage'] = $scontent['Opportunity.StageName'];
                    // $newdata['Task.RelatedToStageID'] = $scontent['Opportunity.StageNameID'];
                    // $newdata['Task.RelatedToStage.ID'] = $scontent['Opportunity.StageName.ID'];
                    $newdata['Task.RelatedToStage.value'] = $scontent['Opportunity.StageName.Name'];
                    $newdata['Task.RelatedToStage.enum_key'] = 1001;//$scontent['Opportunity.StageName.Name'];
                    $newdata['Task.RelatedToStage.enum_arr'] = Array(
                        0 => Array(
                                'enum_value' => $scontent['Opportunity.StageName'],
                                'enum_key' => 1001,
                            )
                    );
                    $newdata['Task.Status'] = '未开始';
                    $newdata['Task.Status.enum_key'] = 1;
                    $newdata['Task.Status.value'] = '未开始';
                    

                    //p($newdata);

                }
                break;
            case '5':
                break;
            case '6':
                break;
            case '7':
                break;
            case '8':
                break;
            default:
                # code...
                break;
        }
        return $newdata;
    }


    public function addnewdata($robj_data,$sobj_data,$scontent,$ID,&$newdata){
        //p($robj_data);
        //p($sobj_data);
        if($robj_data['OBJ_NAME']=='Task' && $sobj_data['OBJ_NAME']=='Opportunity'){
            $newdata['Task.Account'] = $scontent['Opportunity.Account'];
            $newdata['Task.AccountID'] = $scontent['Opportunity.AccountID'];
            $newdata['Task.Account.ID'] = $scontent['Opportunity.AccountID'];
            $newdata['Task.RelatedToStage'] = $scontent['Opportunity.StageName'];
            $newdata['Task.RelatedToStage.enum_key'] = $scontent['Opportunity.StageName.ID'];
            $newdata['Task.RelatedToStage.enum_arr'] = array(0=>array('enum_key' => $scontent['Opportunity.StageName.ID'],'enum_value' => $scontent['Opportunity.StageName']));
            $newdata['Task.Status'] = '未开始';
            $newdata['Task.Status.enum_key'] = 1;
            $newdata['Task.RelatedToID'] = $ID;
            $newdata['Task.RelatedToType'] = $sobj_data['OBJ_TYPE'];
        }
        if($robj_data['OBJ_NAME']=='Task' && $sobj_data['OBJ_NAME']=='Contract'){
            $newdata['Task.Account'] = $scontent['Contract.Account'];
            $newdata['Task.AccountID'] = $scontent['Contract.AccountID'];
            $newdata['Task.Account.ID'] = $scontent['Contract.AccountID'];
            $newdata['Task.RelatedToStage'] = $scontent['Contract.Stage'];
            $newdata['Task.RelatedToStage.enum_key'] = 1;
            $newdata['Task.RelatedToStage.enum_arr'] = array(0=>array('enum_key' => 1,'enum_value' => $scontent['Contract.Stage']));
            $newdata['Task.Status'] = '未开始';
            $newdata['Task.Status.enum_key'] = 1;
            $newdata['Task.RelatedToID'] = $ID;
            $newdata['Task.RelatedToType'] = $sobj_data['OBJ_TYPE'];
        }
        if($robj_data['OBJ_NAME']=='Contact' && $sobj_data['OBJ_NAME']=='Account'){
            $newdata['Contact.Account'] = $scontent['Account.Name'];
            $newdata['Contact.AccountID'] = $scontent['Account.ID'];
            $newdata['Contact.Account.ID'] = $scontent['Account.ID'];

        }
        if($robj_data['OBJ_NAME']=='Task' && $sobj_data['OBJ_NAME']=='Lead'){
            $newdata['Task.Status'] = '未开始';
            $newdata['Task.Status.enum_key'] = 1;
        }
        // if($robj_data['OBJ_NAME']=='Deliver' && $sobj_data['OBJ_NAME']=='Contract'){
        //     $newdata['Deliver.Quantity'] = 0;
        //     $newdata['Deliver.Amount'] = 0;
        //     $newdata['Deliver.Account'] = $scontent['Contract.Account'];
        //     $newdata['Deliver.Account.ID'] = $scontent['Contract.AccountID'];
        //     $newdata['Deliver.AccountID'] = $scontent['Contract.AccountID'];
        // }
        // if($robj_data['OBJ_NAME']=='Invoice' && $sobj_data['OBJ_NAME']=='Contract'){
        //     $newdata['Invoice.Account'] = $scontent['Contract.Account'];
        //     $newdata['Invoice.AccountID'] = $scontent['Contract.AccountID'];
        //     $newdata['Invoice.Account.ID'] = $scontent['Contract.AccountID'];
        // }
    }





}
