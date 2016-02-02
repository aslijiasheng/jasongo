<?php
class Relobjectprofile_model extends CI_Model {
//special 预留明细查询关键字
//addnew 预留新增查询关键字
    private $RefUrl = "";
    function __construct() {
        parent::__construct();
        $this->load->model('www/user_model','user');
        $this->RefUrl = $this->config->base_url() . $this->config->item(@index_page) . "/www/objects/ajax_lists?obj_type=";
    }

    

//客户 Account
    public function RelObjectProfile_1(){
        $arr = array(
        	26=>array(
        		'partnercontract.Account' => array(),
				'rtjl.Account' => array(),
				'REL_USER' => array(
					'REL_OBJ_TYPE' => 99,
					'buttons' => array(
        				0 => array(
        					'name' => '加入负责员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=1&rel_obj_type=99&is_self=1'
        					),
        				1 => array(
        					'name' => '加入相关员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=1&rel_obj_type=99&is_self=0'
        					)
        				)
					),
				'REL_ACCOUNT_SURVEY_ANSWER' => array(),
				'REL_ACCOUNTFORCEREL' => array(
					// 'REL_OBJ_TYPE' => 70,
     //    			'buttons' => array( 
     //    				0 => array(
     //    					'name' => '增加可影响本客户的关系',
     //    					'url' => $this->RefUrl . 1
     //    					),
     //    				1 => array(
     //    					'name' => '增加本客户可影响的关系',
     //    					'url' => $this->RefUrl . 1
     //    					),
     //    				2 => array(//special预留：现url与属性未知，无法确定url值，有待详细明细列表完成后
     //    					'name' => '修改影响关系',
     //    					'url' => $this->RefUrl . 70
     //    					)
     //    				)
					),
				'REL_ACCOUNT_REL' => array(
					'REL_OBJ_TYPE' => 1,
					'buttons' => array(
        				0 => array(
        					'name' => '加入相关客户',
        					'url' => $this->RefUrl . 1,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=1&rel_obj_type=1'
        					),
        				)
					),
				'REL_ACCOUNT_SUBORDINATE' => array(),
				'REL_WORKSHEET' => array(),
				'REL_ACTIVITIES' => array(
                    'REL_OBJ_TYPE' => 10,
                    'buttons' => array(
                        0 => array(
                            'name' => '新建行动',
                            'sub' => $this->subbut(1,1,14,26)
                            )
                        )
                    ),
				'REL_ASSET' => array(),
				'REL_ATTACH' => array(),
				'REL_CAMPAIGN' => array(),
				'REL_CMPTCNTRT' => array(),
				'REL_COMMENT' => array(),
				'REL_COMPETITOR' => array(),
				'REL_CONTACT' => array(
                    'REL_OBJ_TYPE' => 2,
                    'buttons' => array(
                        0 => array(
                            'name' => '新建',
                            'sub' => $this->subbut(1,1,2,26)
                            )
                        )
                    ),
				'REL_CONTRACT' => array(),
				'REL_DELIVER' => array(),
				'REL_DSS_UPDATE_HISTORY' => array(),
				'REL_EMAIL_ITEM' => array(),
				'REL_HISTORY' => array(),
				'REL_INVOICE' => array(),
				'REL_LEAD' => array(),
				'REL_OPPORTUNITY' => array(
                    'REL_OBJ_TYPE' => 4,
                    'buttons' => array(
                        0 => array(
                            'name' => '新建',
                            'sub' => $this->subbut(1,1,4,26)
                            )
                        )
                    ),
				'REL_PRIVILEGE' => array(
					'REL_OBJ_TYPE' => 85,
					'buttons' => array(
        				0 => array(
        					'name' => '新增部门',
        					'url' => $this->RefUrl . 95,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=1&rel_obj_type=95'
        					)
        				)
					),
				'REL_QUOTE' => array(),
				'REL_RECEIPT' => array(),
				'REL_RESOURCE_ALLOCATE' => array(),
				'REL_SCOREDETAIL' => array(),
				'REL_SERVICE_PLAN' => array(),
				'REL_SERVICE_REQUEST' => array(),
				'REL_SMS_ITEM' => array(),
        		),
			90 => array(
				'REL_EMAIL_ITEM' => array(),
				'REL_HISTORY' => array(),
				'REL_SCOREDETAIL' => array(),
				'REL_SERVICE_PLAN' => array(),
				'REL_SERVICE_REQUEST' => array(),
				'partnercontract.Account' => array(),
				'rtjl.Account' => array(),
				'REL_ACCOUNTFORCEREL' => array(
					// 'REL_OBJ_TYPE' => 70,
     //    			'buttons' => array( 
     //    				0 => array(
     //    					'name' => '增加可影响本客户的关系',
     //    					'url' => $this->RefUrl . 1
     //    					),
     //    				1 => array(
     //    					'name' => '增加本客户可影响的关系',
     //    					'url' => $this->RefUrl . 1
     //    					),
     //    				2 => array(//special预留：现url与属性未知，无法确定url值，有待详细明细列表完成后
     //    					'name' => '修改影响关系',
     //    					'url' => $this->RefUrl . 70
     //    					)
     //    				)
					),
				'REL_ACCOUNT_REL' => array(
					'REL_OBJ_TYPE' => 1,
					'buttons' => array(
        				0 => array(
        					'name' => '加入相关客户',
        					'url' => $this->RefUrl . 1,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=1&rel_obj_type=1'
        					),
        				)
					),
				'REL_ACCOUNT_SUBORDINATE' => array(),
				'REL_ACCOUNT_SURVEY_ANSWER' => array(),
				'REL_INVOICE' => array(),
				'REL_LEAD' => array(),
				'REL_OPPORTUNITY' => array(
                    'REL_OBJ_TYPE' => 4,
                    'buttons' => array(
                        0 => array(
                            'name' => '新建',
                            'sub' => $this->subbut(1,1,4,90)
                            )
                        )
                    ),
				'REL_PRIVILEGE' => array(
					'REL_OBJ_TYPE' => 85,
					'buttons' => array(
        				0 => array(
        					'name' => '新增部门',
        					'url' => $this->RefUrl . 95,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=1&rel_obj_type=95'
        					)
        				)
					),
				'REL_QUOTE' => array(),
				'REL_RECEIPT' => array(),
				'REL_RESOURCE_ALLOCATE' => array(),
				'REL_ACTIVITIES' => array(
                    'REL_OBJ_TYPE' => 10,
                    'buttons' => array(
                        0 => array(
                            'name' => '新建行动',
                            'sub' => $this->subbut(1,1,14,90)
                            )
                        )
                    ),
				'REL_ASSET' => array(),
				'REL_ATTACH' => array(),
				'REL_CAMPAIGN' => array(),
				'REL_CMPTCNTRT' => array(),
				'REL_COMMENT' => array(),
				'REL_COMPETITOR' => array(),
				'REL_CONTACT' => array(
                    'REL_OBJ_TYPE' => 2,
                    'buttons' => array(
                        0 => array(
                            'name' => '新建',
                            'sub' => $this->subbut(1,1,2,90)
                            )
                        )
                    ),
				'REL_CONTRACT' => array(),
				'REL_DELIVER' => array(),
				'REL_DSS_UPDATE_HISTORY' => array(),
				'REL_SMS_ITEM' => array(),
				'REL_USER' => array(
					'REL_OBJ_TYPE' => 99,
					'buttons' => array(
        				0 => array(
        					'name' => '加入负责员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=1&rel_obj_type=99&is_self=1'
        					),
        				1 => array(
        					'name' => '加入相关员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=1&rel_obj_type=99&is_self=0'
        					)
        				)
					),
				'REL_WORKSHEET' => array(),
				'REL_CONTRACTITEM' => array(),
				'REL_OPPORTUNITYITEM' => array(),
				'REL_WORKSHEETITEM' => array(),
				'agreementItem.account' => array(),
				'partnercontract.partner' => array()

				),
        	2057=>array(
        		'REL_ACCOUNT_REL' => array(
        			'REL_OBJ_TYPE' => 1,
					'buttons' => array(
        				0 => array(
        					'name' => '加入相关客户',
        					'url' => $this->RefUrl . 1,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=1&rel_obj_type=1'
        					),
        				)
        			),
				'REL_ACCOUNT_SUBORDINATE' => array(),
				'REL_ACCOUNT_SURVEY_ANSWER' => array(),
				'REL_ASSET' => array(),
				'REL_ATTACH' => array(),
				'REL_CAMPAIGN' => array(),
				'REL_CMPTCNTRT' => array(),
				'REL_COMMENT' => array(),
				'REL_COMPETITOR' => array(),
				'REL_CONTACT' => array(
                    'REL_OBJ_TYPE' => 2,
                    'buttons' => array(
                        0 => array(
                            'name' => '新建',
                            'sub' => $this->subbut(1,1,2,2057)
                            )
                        )
                    ),
				'REL_CONTRACT' => array(),
				'REL_EMAIL_ITEM' => array(),
				'REL_HISTORY' => array(),
				'REL_INVOICE' => array(),
				'REL_LEAD' => array(),
				'REL_OPPORTUNITY' => array(
                    'REL_OBJ_TYPE' => 4,
                    'buttons' => array(
                        0 => array(
                            'name' => '新建',
                            'sub' => $this->subbut(1,1,4,2057)
                            )
                        )
                    ),
				'REL_QUOTE' => array(),
				'REL_RESOURCE_ALLOCATE' => array(),
				'REL_SERVICE_PLAN' => array(),
				'REL_SERVICE_REQUEST' => array(),
				'REL_SMS_ITEM' => array(),
				'REL_USER' => array(
					'REL_OBJ_TYPE' => 99,
					'buttons' => array(
        				0 => array(
        					'name' => '加入负责员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=1&rel_obj_type=99&is_self=1'
        					),
        				1 => array(
        					'name' => '加入相关员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=1&rel_obj_type=99&is_self=0'
        					)
        				)
					),
				'REL_WORKSHEET' => array(),
				'REL_ACCOUNTFORCEREL' => array(
					// 'REL_OBJ_TYPE' => 70,
     //    			'buttons' => array( 
     //    				0 => array(
     //    					'name' => '增加可影响本客户的关系',
     //    					'url' => $this->RefUrl . 1
     //    					),
     //    				1 => array(
     //    					'name' => '增加本客户可影响的关系',
     //    					'url' => $this->RefUrl . 1
     //    					),
     //    				2 => array(//special预留：现url与属性未知，无法确定url值，有待详细明细列表完成后
     //    					'name' => '修改影响关系',
     //    					'url' => $this->RefUrl . 70
     //    					)
     //    				)
					),
				'REL_ACTIVITIES' => array(
                    'REL_OBJ_TYPE' => 10,
                    'buttons' => array(
                        0 => array(
                            'name' => '新建行动',
                            'sub' => $this->subbut(1,1,14,2057)
                            )
                        )
                    ),
				'REL_DSS_UPDATE_HISTORY' => array(),
				'REL_SCOREDETAIL' => array(),
				'REL_PRIVILEGE' => array(
					'REL_OBJ_TYPE' => 85,
					'buttons' => array(
        				0 => array(
        					'name' => '新增部门',
        					'url' => $this->RefUrl . 95,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=1&rel_obj_type=95'
        					)
        				)
					),
				'REL_DELIVER' => array(),
				'REL_RECEIPT' => array(),
				'rtjl.Account' => array(),
				'partnercontract.Account' => array(),
				'partnercontract.partner' => array()
        	    )
            );
		return $arr;
		
    }

//联系人 Contact
    public function RelObjectProfile_2(){
        $arr = array(
        	88=>array(
        		'REL_CONTACTFORCEREL' => array(
        			// 'REL_OBJ_TYPE' => 71,
        			// 'buttons' => array(
        			// 	0 => array(
        			// 		'name' => '增加可影响该联系人的关系',
        			// 		'url' => $this->RefUrl . 2
        			// 		),
        			// 	1 => array(
        			// 		'name' => '增加该联系人可影响的关系',
        			// 		'url' => $this->RefUrl . 2
        			// 		),
        			// 	2 => array(//special预留：现url与属性未知，无法确定url值，有待详细明细列表完成后
        			// 		'name' => '修改影响关系',
        			// 		'url' => $this->RefUrl . 2
        			// 		)
        			// 	)
        			),
				'REL_CONTACT_SURVEY_ANSWER' => array(),
				'REL_DSS_UPDATE_HISTORY' => array(),
				'REL_EMAIL_ITEM' => array(),
				'REL_HISTORY' => array(),
				'REL_LEAD' => array(),
				'REL_PRIVILEGE' => array(
					'REL_OBJ_TYPE' => 85,
					'buttons' => array(
        				0 => array(
        					'name' => '新增部门',
        					'url' => $this->RefUrl . 95,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=2&rel_obj_type=95'
        					)
        				)
					),
				'REL_RESOURCE_ALLOCATE' => array(),
				'REL_SERVICE_PLAN' => array(),
				'REL_SERVICE_REQUEST' => array(),
				'REL_SMS_ITEM' => array(),
				'REL_USER' => array(
					'REL_OBJ_TYPE' => 99,
					'buttons' => array(
        				0 => array(
        					'name' => '加入负责员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=2&rel_obj_type=99&is_self=1'
        					),
        				1 => array(
        					'name' => '加入相关员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=2&rel_obj_type=99&is_self=0'
        					)
        				)
					),
				'REL_WORKSHEET' => array(
					// 'REL_OBJ_TYPE' => 54,
					// 'buttons' => array(
     //    				0 => array(
     //    					'name' => '新增工单',
     //    					'url' => $this->RefUrl . 54
     //    					)
     //    				)
					),
				'rtjl.Contact' => array(),
				'REL_ACCOUNT_CONTACT' => array(),
				'REL_ACTIVITIES' => array(
                    'REL_OBJ_TYPE' => 10,
                    'buttons' => array(
                        0 => array(
                            'name' => '新建行动',
                            'sub' => $this->subbut(1,2,14,88)
                            )
                        )
                    ),
				'REL_ATTACH' => array(),
				'REL_CAMPAIGN' => array(),
				'REL_COMMENT' => array()
        		)
        	);
		return $arr;
    }

    //线索 Lead
    public function RelObjectProfile_3(){
        $arr = array(
        	0=>array(
        		'REL_DSS_UPDATE_HISTORY' =>array(),
				'REL_USER' =>array(
                    'REL_OBJ_TYPE' => 99,
                    'buttons' => array(
                        0 => array(
                            'name' => '加入负责员工',
                            'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=3&rel_obj_type=99&is_self=1'
                            ),
                        1 => array(
                            'name' => '加入相关员工',
                            'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=3&rel_obj_type=99&is_self=0'
                            )
                        )
                    ),
				'REL_PRIVILEGE' =>array(
                    'REL_OBJ_TYPE' => 85,
                    'buttons' => array(
                        0 => array(
                            'name' => '新增部门',
                            'url' => $this->RefUrl . 95,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=3&rel_obj_type=95'
                            )
                        )
                    ),
				'REL_RESOURCE_ALLOCATE' =>array(),
				'REL_SMS_ITEM' =>array(),
				'REL_ACCOUNT_CONVERSION' =>array(),
				'REL_ACTIVITIES' =>array(
                    'REL_OBJ_TYPE' => 10,
                    'buttons' => array(
                        0 => array(
                            'name' => '新建行动',
                            'sub' => $this->subbut(1,3,14,91)
                            
                            )
                        )
                    ),
				'REL_ATTACH' =>array(),
				'REL_COMMENT' =>array(),
				'REL_CONTACT_CONVERSION' =>array(),
				'REL_EMAIL_ITEM' =>array(),
				'REL_HISTORY' =>array(),
				'REL_LEAD_SURVEY_ANSWER' =>array(),
				'REL_OPPORTUNITY_CONVERSION' =>array(),
				'Opportunity.SourceLead' =>array(),
        		)
        	);
		return $arr;
    }
    //商机 Opportunity
    public function RelObjectProfile_4(){
        $arr = array(
        	0=>array(
        		'REL_AUDITLOG' =>array(),
				'REL_CONTACTOPPREL' =>array(
					// 'REL_OBJ_TYPE' => 72,
     //    			'buttons' => array(
     //    				0 => array(//addnew
     //    					'name' => '增加联系人影响关系',
     //    					'url' => $this->RefUrl . 2
     //    					)
     //    				)
					),
				'REL_SOLUTION' =>array(),
				'REL_USER' =>array(
					'REL_OBJ_TYPE' => 99,
					'buttons' => array(
        				0 => array(
        					'name' => '加入负责员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=4&rel_obj_type=99&is_self=1'
        					),
        				1 => array(
        					'name' => '加入相关员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=4&rel_obj_type=99&is_self=0'
        					)
        				)
					),
				'REL_PRIVILEGE' =>array(
					'REL_OBJ_TYPE' => 85,
					'buttons' => array(
        				0 => array(
        					'name' => '新增部门',
        					'url' => $this->RefUrl . 95,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=4&rel_obj_type=95'
        					)
        				)
					),
				'REL_ACTIVITIES' =>array(
					'REL_OBJ_TYPE' => 10,
					'buttons' => array(
        				0 => array(
        					'name' => '新建行动',
        					'sub' => $this->subbut(1,4,14,0)
        					
        					)
        				)
					),
				'REL_ATTACH' =>array(),
				'REL_CMPT_CNTRT' =>array(),
				'REL_CMPT_PROD' =>array(),
				'REL_COMMENT' =>array(),
				'REL_COMPETITOR' =>array(),
				'REL_CONTACT' =>array(
					'REL_OBJ_TYPE' => 2,
					'buttons' => array(
        				0 => array(
        					'name' => '加入联系人',
        					'url' => $this->RefUrl . 2,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=4&rel_obj_type=2'
        					),
        				)
					),
				'REL_CONTRACT' =>array(),
				'REL_EVENT_CONFIG' =>array(
					// 'REL_OBJ_TYPE' => 164,
					// 'buttons' => array(
     //    				0 => array(//addnew
     //    					'name' => '新建提醒设置',
     //    					'url' => $this->RefUrl . 164
     //    					),
     //    				)
					),
				'REL_HISTORY' =>array(),
				'REL_OBJECT_ROLE' =>array(),
				'REL_OPPORTUNITY_ITEMS' =>array(),
				'REL_OPPORTUNITY_ITEMS_SPLIT' =>array(),
				'REL_OPPORTUNITY_STAGE' =>array(
					// 'REL_OBJ_TYPE' => 821,
					// 'buttons' => array(
     //    				0 => array(//special预留：现url与属性未知，无法确定url值，有待详细明细列表完成后
     //    					'name' => '编辑',
     //    					'url' => $this->RefUrl . 821
     //    					),
     //    				)
					),
				'REL_OPPORTUNITY_STAGE_PATH' =>array(),
				'REL_QUOTE' =>array(),
				'REL_RESOURCE_ALLOCATE' =>array(),
        		),
        	2=>array(
        		'REL_USER' =>array(
        			'REL_OBJ_TYPE' => 99,
					'buttons' => array(
        				0 => array(
        					'name' => '加入负责员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=4&rel_obj_type=99&is_self=1'
        					),
        				1 => array(
        					'name' => '加入相关员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=4&rel_obj_type=99&is_self=0'
        					)
        				)
        			),
				'REL_ACTIVITIES' =>array(
					'REL_OBJ_TYPE' => 10,
					'buttons' => array(
        				0 => array(
        					'name' => '新建行动',
        					'sub' => $this->subbut(1,4,14,2056)
        					
        					)
        				)
					),
				'REL_ATTACH' =>array(),
				'REL_AUDITLOG' =>array(),
				'REL_CMPT_CNTRT' =>array(),
				'REL_CMPT_PROD' =>array(),
				'REL_COMMENT' =>array(),
				'REL_COMPETITOR' =>array(),
				'REL_CONTACT' =>array(
					'REL_OBJ_TYPE' => 2,
					'buttons' => array(
        				0 => array(
        					'name' => '加入联系人',
        					'url' => $this->RefUrl . 2,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=4&rel_obj_type=2'
        					),
        				)
					),
				'REL_CONTACTOPPREL' =>array(
					// 'REL_OBJ_TYPE' => 72,
     //    			'buttons' => array(
     //    				0 => array(//addnew
     //    					'name' => '增加联系人影响关系',
     //    					'url' => $this->RefUrl . 2
     //    					)
     //    				)
					),
				'REL_CONTRACT' =>array(),
				'REL_EVENT_CONFIG' =>array(
					// 'REL_OBJ_TYPE' => 164,
					// 'buttons' => array(
     //    				0 => array(//addnew
     //    					'name' => '新建提醒设置',
     //    					'url' => $this->RefUrl . 164
     //    					),
     //    				)
					),
				'REL_HISTORY' =>array(),
				'REL_OBJECT_ROLE' =>array(),
				'REL_OPPORTUNITY_ITEMS' =>array(),
				'REL_OPPORTUNITY_ITEMS_SPLIT' =>array(),
				'REL_OPPORTUNITY_STAGE' =>array(
					// 'REL_OBJ_TYPE' => 821,
					// 'buttons' => array(
     //    				0 => array(//special预留：现url与属性未知，无法确定url值，有待详细明细列表完成后
     //    					'name' => '编辑',
     //    					'url' => $this->RefUrl . 821
     //    					),
     //    				)
					),
				'REL_OPPORTUNITY_STAGE_PATH' =>array(),
				'REL_PRIVILEGE' =>array(
					'REL_OBJ_TYPE' => 85,
					'buttons' => array(
        				0 => array(
        					'name' => '新增部门',
        					'url' => $this->RefUrl . 95,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=4&rel_obj_type=95'
        					)
        				)
					),
				'REL_QUOTE' =>array(),
				'REL_RESOURCE_ALLOCATE' =>array(),
				'REL_SOLUTION' =>array(),
        		),
                2056=>array(
                'REL_USER' =>array(
                    'REL_OBJ_TYPE' => 99,
                    'buttons' => array(
                        0 => array(
                            'name' => '加入负责员工',
                            'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=4&rel_obj_type=99&is_self=1'
                            ),
                        1 => array(
                            'name' => '加入相关员工',
                            'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=4&rel_obj_type=99&is_self=0'
                            )
                        )
                    ),
                'REL_ACTIVITIES' =>array(
                    'REL_OBJ_TYPE' => 10,
                    'buttons' => array(
                        0 => array(
                            'name' => '新建行动',
                            'sub' => $this->subbut(1,4,14,2056)
                            
                            )
                        )
                    ),
                'REL_ATTACH' =>array(),
                'REL_AUDITLOG' =>array(),
                'REL_CMPT_CNTRT' =>array(),
                'REL_CMPT_PROD' =>array(),
                'REL_COMMENT' =>array(),
                'REL_COMPETITOR' =>array(),
                'REL_CONTACT' =>array(
                    'REL_OBJ_TYPE' => 2,
                    'buttons' => array(
                        0 => array(
                            'name' => '加入联系人',
                            'url' => $this->RefUrl . 2,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=4&rel_obj_type=2'
                            ),
                        )
                    ),
                'REL_CONTACTOPPREL' =>array(
                    // 'REL_OBJ_TYPE' => 72,
     //             'buttons' => array(
     //                 0 => array(//addnew
     //                     'name' => '增加联系人影响关系',
     //                     'url' => $this->RefUrl . 2
     //                     )
     //                 )
                    ),
                'REL_CONTRACT' =>array(),
                'REL_EVENT_CONFIG' =>array(
                    // 'REL_OBJ_TYPE' => 164,
                    // 'buttons' => array(
     //                 0 => array(//addnew
     //                     'name' => '新建提醒设置',
     //                     'url' => $this->RefUrl . 164
     //                     ),
     //                 )
                    ),
                'REL_HISTORY' =>array(),
                'REL_OBJECT_ROLE' =>array(),
                'REL_OPPORTUNITY_ITEMS' =>array(),
                'REL_OPPORTUNITY_ITEMS_SPLIT' =>array(),
                'REL_OPPORTUNITY_STAGE' =>array(
                    // 'REL_OBJ_TYPE' => 821,
                    // 'buttons' => array(
     //                 0 => array(//special预留：现url与属性未知，无法确定url值，有待详细明细列表完成后
     //                     'name' => '编辑',
     //                     'url' => $this->RefUrl . 821
     //                     ),
     //                 )
                    ),
                'REL_OPPORTUNITY_STAGE_PATH' =>array(),
                'REL_PRIVILEGE' =>array(
                    'REL_OBJ_TYPE' => 85,
                    'buttons' => array(
                        0 => array(
                            'name' => '新增部门',
                            'url' => $this->RefUrl . 95,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=4&rel_obj_type=95'
                            )
                        )
                    ),
                'REL_QUOTE' =>array(),
                'REL_RESOURCE_ALLOCATE' =>array(),
                'REL_SOLUTION' =>array(),
                )
        	);
		return $arr;
    }
    //订单 Contract
    public function RelObjectProfile_6(){
        $arr = array(
        	0=>array(
        		'REL_AUDITLOG' =>array(),
				'REL_INVOICE' =>array(
					// 'REL_OBJ_TYPE' => 41,
					// 'buttons' => array(
     //    				0 => array(
     //    					'name' => '新建发票',
     //    					'sub' => $this->subbut(1,6,41,0)
     //    					)
     //    				)
					),
				'REL_DELIVER' =>array(
					// 'REL_OBJ_TYPE' => 1105,
					// 'buttons' => array(
     //    				0 => array(
     //    					'name' => '生成交付单',
     //    					'sub' => $this->subbut(1,6,1105,0)
     //    					)
     //    				)
					),
				'REL_RECEIPT' =>array(
					// 'REL_OBJ_TYPE' => 1200,
					// 'buttons' => array(
     //    				0 => array(
     //    					'name' => '生成收款单',
     //    					'sub' => $this->subbut(1,6,1200,0)
     //    					)
     //    				)
					),
				'REL_PRIVILEGE' =>array(
					'REL_OBJ_TYPE' => 85,
					'buttons' => array(
        				0 => array(
        					'name' => '新增部门',
        					'url' => $this->RefUrl . 95,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=6&rel_obj_type=95'
        					)
        				)
					),
				'REL_SOLUTION' =>array(),
				'REL_OBJECT_ROLE' =>array(),
				'REL_RESOURCE_ALLOCATE' =>array(),
				'REL_SERVICE_PLAN' =>array(),
				'REL_STAGE' =>array(
					// 'REL_OBJ_TYPE' => 163,
					// 'buttons' => array(
     //    				0 => array(//special预留：现url与属性未知，无法确定url值，有待详细明细列表完成后
     //    					'name' => '编辑',
     //    					'url' => $this->RefUrl . 163
     //    					),
     //    				)
					),
				'REL_STAGE_PATH' =>array(),
				'REL_USER' =>array(
					'REL_OBJ_TYPE' => 99,
					'buttons' => array(
        				0 => array(
        					'name' => '加入负责员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=6&rel_obj_type=99&is_self=1'
        					),
        				1 => array(
        					'name' => '加入相关员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=6&rel_obj_type=99&is_self=0'
        					)
        				)
					),
				'REL_ACTIVITIES' =>array(
					'REL_OBJ_TYPE' => 10,
					'buttons' => array(
        				0 => array(
        					'name' => '新建行动',
        					'sub' => $this->rel->subbut(1,6,14,0)
        					)
        				)
					),
				'REL_ATTACH' =>array(),
				'REL_COMMENT' =>array(),
				'REL_CONTACT' =>array(),
				'REL_CONTRACT_ITEMS' =>array(),
				'REL_CONTRACT_ITEMS_SPLIT' =>array(),
				'REL_EVENT_CONFIG' =>array(
					// 'REL_OBJ_TYPE' => 164,
					// 'buttons' => array(
     //    				0 => array(//addnew
     //    					'name' => '新建提醒设置',
     //    					'url' => base_url().'index.php/www/objects/relsave?obj_type=6&rel_obj_type=99&is_self=0'
     //    					),
     //    				)
					),
				'REL_HISTORY' =>array()
        		),
			40=>array(
				'REL_ACTIVITIES' =>array(
					'REL_OBJ_TYPE' => 10,
					'buttons' => array(
        				0 => array(
        					'name' => '新建行动',
        					'sub' => $this->subbut(1,6,14,40)
        					)
        				)
					),
				'REL_ATTACH' =>array(),
				'REL_AUDITLOG' =>array(),
				'REL_COMMENT' =>array(),
				'REL_CONTACT' =>array(),
				'REL_CONTRACT_ITEMS' =>array(),
				'REL_CONTRACT_ITEMS_SPLIT' =>array(),
				'REL_DELIVER' =>array(
					// 'REL_OBJ_TYPE' => 1105,
					// 'buttons' => array(
     //    				0 => array(
     //    					'name' => '生成交付单',
     //    					'sub' => $this->subbut(1,6,1105,0)
     //    					)
     //    				)
					),
				'REL_EVENT_CONFIG' =>array(
					// 'REL_OBJ_TYPE' => 164,
					// 'buttons' => array(
     //    				0 => array(//addnew
     //    					'name' => '新建提醒设置',
     //    					'url' => $this->RefUrl . 164
     //    					),
     //    				)
					),
				'REL_HISTORY' =>array(),
				'REL_INVOICE' =>array(
					// 'REL_OBJ_TYPE' => 41,
					// 'buttons' => array(
     //    				0 => array(
     //    					'name' => '新建发票',
     //    					'sub' => $this->subbut(1,6,41,0)
     //    					)
     //    				)
					 ),
				'REL_OBJECT_ROLE' =>array(),
				'REL_PRIVILEGE' =>array(
					'REL_OBJ_TYPE' => 85,
					'buttons' => array(
        				0 => array(
        					'name' => '新增部门',
        					'url' => $this->RefUrl . 95,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=6&rel_obj_type=95'
        					)
        				)
					),
				'REL_RECEIPT' =>array(
					// 'REL_OBJ_TYPE' => 1200,
					// 'buttons' => array(
     //    				0 => array(
     //    					'name' => '生成收款单',
     //    					'sub' => $this->subbut(1,6,1200,0)
     //    					)
     //    				)
					),
				'REL_RESOURCE_ALLOCATE' =>array(),
				'REL_SERVICE_PLAN' =>array(),
				'REL_SOLUTION' =>array(),
				'REL_STAGE' =>array(
					// 'REL_OBJ_TYPE' => 163,
					// 'buttons' => array(
     //    				0 => array(//special预留：现url与属性未知，无法确定url值，有待详细明细列表完成后
     //    					'name' => '编辑',
     //    					'url' => $this->RefUrl . 163
     //    					),
     //    				)
					),
				'REL_STAGE_PATH' =>array(),
				'REL_USER' =>array(
					'REL_OBJ_TYPE' => 99,
					'buttons' => array(
        				0 => array(
        					'name' => '加入负责员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=6&rel_obj_type=99&is_self=1'
        					),
        				1 => array(
        					'name' => '加入相关员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=6&rel_obj_type=99&is_self=0'
        					)
        				)
					),

				)
        	);
		return $arr;
    }
    //公共
    public function RelObjectProfile(){
        $arr = array(
        	0=>array(
        		'REL_USER' =>array(
					'REL_OBJ_TYPE' => 99,
					'buttons' => array(
        				0 => array(
        					'name' => '加入负责员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=<?php echo $v[\'OBJ_TYPE\'];?>&rel_obj_type=99&is_self=1'
        					),
        				1 => array(
        					'name' => '加入相关员工',
        					'url' => $this->RefUrl . 99,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=<?php echo $v[\'OBJ_TYPE\'];?>&rel_obj_type=99&is_self=0'
        					)
        				)
					),
        		'REL_ACTIVITIES' =>array(
					'REL_OBJ_TYPE' => 10,
					'buttons' => array(
        				0 => array(
        					'name' => '新建行动',
        					'sub' => $this->subbut(1,0,14,0)
        					)
        				)
					),
        		'REL_PRIVILEGE' =>array(
					'REL_OBJ_TYPE' => 85,
					'buttons' => array(
        				0 => array(
        					'name' => '新增部门',
        					'url' => $this->RefUrl . 95,
                            'save_url' => base_url().'index.php/www/objects/relsave?obj_type=<?php echo $v[\'OBJ_TYPE\'];?>&rel_obj_type=95'
        					)
        				)
					)
        		)
        	);
    }
    public function subbut($org_id=1,$obj_type,$rel_obj_type,$type_id){
        $data = $this->subbutton($org_id,$obj_type,$rel_obj_type,$type_id);
        foreach ($data as $k => $v) {
            $arr[$v['TYPE_ID']]['name'] = $v['TYPE_NAME'];
            $arr[$v['TYPE_ID']]['url'] = base_url().'index.php/www/objects/addout?obj_type='.$rel_obj_type.'&type_id='.$v['TYPE_ID'];
        }
        return $arr;
    }
    //查相关对象类型按钮
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
        if(isset($ban)){
            $this->db->where_not_in('type_id',$ban);
        }
        return $this->db->get()->result_array();
    }
}
?>