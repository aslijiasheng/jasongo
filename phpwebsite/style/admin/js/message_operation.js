﻿
<<<<<<< .mine

	//zjf_url = 'http://192.168.0.95'; //正式环境
	zjf_url = 'http://192.168.35.18'; //测试环境
=======
	//正式环境
	// zjf_url = 'http://192.168.0.95';
	zjf_url = 'http://localhost/leegii';
>>>>>>> .r1970
	function confirm_salespay(id,url,sel_data){
		// id=$(this).attr('id');
		// alert(11);
		$.ajax({
			'type':'get',
			'data':'salespay_id='+id,
			'success':function(data){
				$('#operation_dialog').html(data);
			},
			'url':url,
			'cache':false
		});
		// alert(11);
		$('#operation_dialog').dialog({
			modal: true,
			title:'确认到帐',
			width:800,
			height:450,
			buttons: [{
				text:"确认到帐",
				Class:"btn btn-primary",
				click: function(){
					//alert('确认到帐');
					$this = $(this);
					formdata = $("#confirm-form").serializeArray();
					formdata[formdata.length]={"name":"salespay[salespay_status]","value":"1002"}; //添加1个参数
					var status = leeConditionJudgment(formdata);
					 if(status == false) return;
					//dump_obj(formdata);
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("已确认到帐");
								$this.dialog("close");
								$("#message_list").ajaxHtml({
									url:zjf_url+"/index.php/www/message/new_ajax_select",
									data:sel_data,
								});

								//$('#message_list').ajaxHtml();
							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/salespay/ajax_update_post',
						'async':false
					});
				}
			},{
				text:"到帐不成功",
				Class:"btn btn-success",
				click: function(){
					if(!confirm("确认要做此操作吗?"))
					{
						return false;
					}

					$this = $(this);

					formdata = $("#confirm-form").serializeArray();
					formdata[formdata.length]={"name":"salespay[salespay_status]","value":"1003"}; //添加1个参数
					//alert(formdata);
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("到帐不成功");
								//$this.dialog("close");
								//$('#profile').ajaxHtml(); //再次加载1次页面
									$("#message_list").ajaxHtml({
									url:zjf_url+"/index.php/www/message/new_ajax_select",
									data:sel_data,
								});
									$('#operation_dialog').dialog("close");
							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/salespay/ajax_update_post',
						'async':false
					});
				}
			},{
				text:"取消",
				Class:"btn bottom-margin",
				click: function(){
					alertify.error("你取消了确认到帐");
					$(this).dialog("close");
				}
			}]

		});
		//alert(22);
		 $('#operation_dialog').dialog('open');
	}
// })

function edit_salespay(id,url,sel_data){

		$.ajax({
			'type':'get',
			'data':'salespay_id='+id,
			'success':function(data){
				$('#operation_dialog').html(data);
			},
			'url':url,
			'cache':false
		});
		$('#operation_dialog').dialog({
			modal: true,
			title:'修改入款项',
			width:800,
			height:450,
			buttons: [{
				text:"保存",
				Class:"btn btn-primary",
				click: function(){
					$this = $(this);
					formdata = $("#salespay-form").serializeArray();
					var status = leeConditionJudgment(formdata);
					 if(status == false) return;
					//本次入账金额
					$pay_amount = $('[name="salespay[salespay_pay_amount]"]').val();
					//可入账金额
					$krz_amount = $('#kdz_amount').html();
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("修改成功");
									$("#message_list").ajaxHtml({
									url:zjf_url+"/index.php/www/message/new_ajax_select",
									data:sel_data,
								});
								$('#operation_dialog').dialog("close");
							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/salespay/ajax_update_post',
						'async':false
					});
				}
			},{
				text:"取消",
				Class:"btn bottom-margin",
				click: function(){
					alertify.error("你取消了修改入款项");
					$(this).dialog("close");
				}
			}]
		});
		$('#operation_dialog').dialog('open');
	}


//执行开票
function confirm_invoice(id,url,sel_data){

		//id=$(this).attr('id');
		$.ajax({
			'type':'get',
			'data':'invoice_id='+id,
			'success':function(data){
				$('#operation_dialog').html(data);
			},
			'url':url,
			'cache':false
		});
		$('#operation_dialog').dialog({
			modal: true,
			title:'执行开票',
			width:800,
			height:450,
			buttons: [{
				text:"确认",
				Class:"btn btn-primary",
				click: function(){
					formdata = $("#confirm_invoice_form").serializeArray();
					var status = leeConditionJudgment(formdata);
					 if(status == false) return;
					//formdata[formdata.length]={"name":"refund[refund_status]","value":"1002"}; //添加1个参数
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("通过开票");
									$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select",
										data:sel_data,
									});

								$('#operation_dialog').dialog("close");

							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/invoice/update_confirm_invoice',
						'async':false
					});
				}
			},{
				text:"驳回",
				Class:"btn btn-success",
				click: function(){
					if(!confirm("确认要做此操作吗?"))
					{
						return false;
					}
					formdata = $("#confirm_invoice_form").serializeArray();
					//formdata[formdata.length]={"name":"refund[refund_status]","value":"1003"}; //添加1个参数
					//alert(formdata);
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("驳回开票");
									$("#message_list").ajaxHtml({
									url:zjf_url+"/index.php/www/message/new_ajax_select",
									data:sel_data,
								});
								$('#operation_dialog').dialog("close");
							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/invoice/return_do_invoice',
						'async':false
					});
				}
			},{
				text:"取消",
				Class:"btn bottom-margin",
				click: function(){
					alertify.error("你取消了审批返点");
					$(this).dialog("close");
				}
			}]
		});
		$('#operation_dialog').dialog('open');
	}

	function edit_invoice(id,url,sel_data){
		//id=$(this).attr('id');
		$.ajax({
			'type':'get',
			'data':'invoice_id='+id,
			'success':function(data){
				$('#operation_dialog').html(data);
			},
			'url':url,
			'cache':false
		});
		$('#operation_dialog').dialog({
			modal: true,
			title:'编辑发票',
			width:800,
			height:450,
			buttons: [{
				text:"保存",
				Class:"btn btn-primary",
				click: function(){
					$this = $(this);
					formdata = $("#invoice-form").serializeArray();
					var status = leeConditionJudgment(formdata);
					 if(status == false) return;
					//本次入账金额
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("操作成功");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");
							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/invoice/ajax_update_post',
						'async':false
					});
				}
			},{
				text:"取消",
				Class:"btn bottom-margin",
				click: function(){
					$(this).dialog("close");
				}
			}]
		});
		$('#operation_dialog').dialog('open');
	}

//执行废票

  function invalidate_invoice(id,url,sel_data){
		$.ajax({
			'type':'get',
			'data':'invoice_id='+id,
			'success':function(data){
				$('#operation_dialog').html(data);
			},
			'url':url,
			'cache':false
		});
		$('#operation_dialog').dialog({
			modal: true,
			title:'执行废票',
			width:800,
			height:450,
			buttons: [{
				text:"保存",
				Class:"btn btn-primary",
				click: function(){
					$this = $(this);
					formdata = $("#invalidate_invoice-form").serializeArray();
					var status = leeConditionJudgment(formdata);
					 if(status == false) return;
					//本次入账金额
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("操作成功");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");
							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/invoice/do_invalidate_invoice',
						'async':false
					});
				}
			},{
				text:"驳回",
				Class:"btn btn-success",
				click: function(){
					if(!confirm("确认要做此操作吗?"))
					{
						return false;
					}
					formdata = $("#invalidate_invoice-form").serializeArray();
					//formdata[formdata.length]={"name":"refund[refund_status]","value":"1003"}; //添加1个参数
					//alert(formdata);
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("作废失败");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");
							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/invoice/return_invalidate_invoice',
						'async':false
					});
				}
			},{
				text:"取消",
				Class:"btn bottom-margin",
				click: function(){
					$(this).dialog("close");
				}
			}]
		});
		$('#operation_dialog').dialog('open');
	}



//审批预开票申请
	function verify_invoice(id,url,sel_data){
		$.ajax({
			'type':'get',
			'data':'invoice_id='+id,
			'success':function(data){

				$('#operation_dialog').html(data);
			},
			'url':url,
			'cache':false
		});
		$('#operation_dialog').dialog({
			modal: true,
			title:'审批预开票申请',
			width:800,
			height:450,
			buttons: [{
				text:"确认",
				Class:"btn btn-primary",
				click: function(){
					formdata = $("#verify_invoice_form").serializeArray();
					var status = leeConditionJudgment(formdata);
					 if(status == false) return;
					//formdata[formdata.length]={"name":"refund[refund_status]","value":"1002"}; //添加1个参数
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("通过审批");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");

							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/invoice/update_verify_invoice',
						'async':false
					});
				}
			},{
				text:"驳回",
				Class:"btn btn-success",
				click: function(){
					if(!confirm("确认要做此操作吗?"))
					{
						return false;
					}
					formdata = $("#verify_invoice_form").serializeArray();
					//formdata[formdata.length]={"name":"refund[refund_status]","value":"1003"}; //添加1个参数
					//alert(formdata);
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("驳回审批");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");
							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/invoice/return_verify_invoice',
						'async':false
					});
				}
			},{
				text:"取消",
				Class:"btn bottom-margin",
				click: function(){
					alertify.error("你取消了审批预开票申请");
					$(this).dialog("close");
				}
			}]
		});
		$('#operation_dialog').dialog('open');
	}


//审批返点
function examine_rebate(id,url,sel_data){
		$.ajax({
			'type':'get',
			'data':'rebate_id='+id,
			'success':function(data){
				$('#operation_dialog').html(data);
			},
			'url':url,
			'cache':false
		});
		$('#operation_dialog').dialog({
			modal: true,
			title:'审批返点',
			width:800,
			height:450,
			buttons: [{
				text:"审批通过",
				Class:"btn btn-primary",
				click: function(){
					formdata = $("#examine_rebate_form").serializeArray();
					formdata[formdata.length]={"name":"rebate[rebate_status]","value":"1002"}; //添加1个参数
					//alert(formdata);
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("审批通过");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");

							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/rebate/ajax_update_post',
						'async':false
					});
				}
			},{
				text:"驳回审批",
				Class:"btn btn-success",
				click: function(){
					if(!confirm("确认要做此操作吗?"))
					{
						return false;
					}
					formdata = $("#examine_rebate_form").serializeArray();
					formdata[formdata.length]={"name":"rebate[rebate_status]","value":"1003"}; //添加1个参数
					//alert(formdata);
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("驳回审批");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");
							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/rebate/ajax_update_post',
						'async':false
					});
				}
			},{
				text:"取消",
				Class:"btn bottom-margin",
				click: function(){
					alertify.error("你取消了审批返点");
					$(this).dialog("close");
				}
			}]

		});
		$('#operation_dialog').dialog('open');
	}


	//确认返点
	function confirm_rebate(id,url,sel_data){
		$.ajax({
			'type':'get',
			'data':'rebate_id='+id,
			'success':function(data){
				$('#operation_dialog').html(data);
			},
			'url':url,
			'cache':false
		});
		$('#operation_dialog').dialog({
			modal: true,
			title:'已经执行返点',
			width:800,
			height:450,
			buttons: [{
				text:"已经执行返点",
				Class:"btn btn-primary",
				click: function(){
					formdata = $("#examine_rebate_form").serializeArray();
					formdata[formdata.length]={"name":"rebate[rebate_status]","value":"1004"}; //添加1个参数
					//alert(formdata);
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("已经执行返点");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");

							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/rebate/ajax_update_post',
						'async':false
					});
				}
			},{
				text:"执行不成功",
				Class:"btn btn-success",
				click: function(){
					if(!confirm("确认要做此操作吗?"))
					{
						return false;
					}
					formdata = $("#examine_rebate_form").serializeArray();
					formdata[formdata.length]={"name":"rebate[rebate_status]","value":"1005"}; //添加1个参数
					//alert(formdata);
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("执行不成功");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");
							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/rebate/ajax_update_post',
						'async':false
					});
				}
			},{
				text:"取消",
				Class:"btn bottom-margin",
				click: function(){
					alertify.error("你取消了审批返点");
					$(this).dialog("close");
				}
			}]

		});
		$('#operation_dialog').dialog('open');
	}

	function edit_rebate(id,url,sel_data){
		//id=$(this).attr('id');
		$.ajax({
			'type':'get',
			'data':'rebate_id='+id,
			'success':function(data){
				$('#operation_dialog').html(data);
			},
			'url':url,
			'cache':false
		});
		$('#operation_dialog').dialog({
			modal: true,
			title:'修改返点',
			width:800,
			height:450,
			buttons: [{
				text:"保存",
				Class:"btn btn-primary",
				click: function(){
					formdata = $("#rebate-form").serializeArray();
					var status = leeConditionJudgment(formdata);
					 if(status == false) return;
					//本次返点金额
					$rebate_amount = $('[name="rebate[rebate_amount]"]').val();
					//可返点金额
					$kfd_amount = $('#kfd_amount').html();
					if(($kfd_amount-$rebate_amount)<0){
						alertify.alert("【本次返点金额】不得大于【可返点金额】");
						return false;
					}
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("修改返点成功");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");
							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/rebate/ajax_update_post',
						'async':false
					});
				}
			},{
				text:"取消",
				Class:"btn bottom-margin",
				click: function(){
					alertify.error("你取消了修改入款项");
					$(this).dialog("close");
				}
			}]
		});
		$('#operation_dialog').dialog('open');
	}


//审批退款
	function examine_refund(id,url,sel_data){
		$.ajax({
			'type':'get',
			'data':'refund_id='+id,
			'success':function(data){
				$('#operation_dialog').html(data);
			},
			'url':url,
			'cache':false
		});
		$('#operation_dialog').dialog({
			modal: true,
			title:'审批退款',
			width:800,
			height:450,
			buttons: [{
				text:"审批通过",
				Class:"btn btn-primary",
				click: function(){
					formdata = $("#examine_refund_form").serializeArray();
					formdata[formdata.length]={"name":"refund[refund_status]","value":"1002"}; //添加1个参数
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("审批通过");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");

							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/refund/ajax_update_post',
						'async':false
					});
				}
			},{
				text:"驳回审批",
				Class:"btn btn-success",
				click: function(){
					if(!confirm("确认要做此操作吗?"))
					{
						return false;
					}
					formdata = $("#examine_refund_form").serializeArray();
					formdata[formdata.length]={"name":"refund[refund_status]","value":"1003"}; //添加1个参数
					//alert(formdata);
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("驳回审批");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");
							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/refund/ajax_update_post',
						'async':false
					});
				}
			},{
				text:"取消",
				Class:"btn bottom-margin",
				click: function(){
					alertify.error("你取消了审批返点");
					$(this).dialog("close");
				}
			}]

		});
		$('#operation_dialog').dialog('open');
	}

//执行退款
	function confirm_refund(id,url,sel_data){
		$.ajax({
			'type':'get',
			'data':'refund_id='+id,
			'success':function(data){
				//ert(data);
				$('#operation_dialog').html(data);
			},
			'url':url,
			'cache':false
		});
		$('#operation_dialog').dialog({
			modal: true,
			title:'确认退款',
			width:800,
			height:450,
			buttons: [{
				text:"退款成功",
				Class:"btn btn-primary",
				click: function(){
					formdata = $("#examine_refund_form").serializeArray();
					formdata[formdata.length]={"name":"refund[refund_status]","value":"1004"}; //添加1个参数
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("已退款");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");

							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/refund/ajax_update_post',
						'async':false
					});
				}
			},{
				text:"退款不成功",
				Class:"btn btn-success",
				click: function(){
					if(!confirm("确认要做此操作吗?"))
					{
						return false;
					}
					formdata = $("#examine_refund_form").serializeArray();
					formdata[formdata.length]={"name":"refund[refund_status]","value":"1005"}; //添加1个参数
					//alert(formdata);
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("退款不成功");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");
							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/refund/ajax_update_post',
						'async':false
					});
				}
			},{
				text:"取消",
				Class:"btn bottom-margin",
				click: function(){
					alertify.error("你取消了审批返点");
					$(this).dialog("close");
				}
			}]

		});
		$('#operation_dialog').dialog('open');
	}

	//编辑退款
	function edit_refund(id,url,sel_data){
		$.ajax({
			'type':'get',
			'data':'refund_id='+id,
			'success':function(data){
				$('#operation_dialog').html(data);
			},
			'url':url,
			'cache':false
		});
		$('#operation_dialog').dialog({
			modal: true,
			title:'修改退款',
			width:800,
			height:450,
			buttons: [{
				text:"保存",
				Class:"btn btn-primary",
				click: function(){
					formdata = $("#refund-form").serializeArray();
					var status = leeConditionJudgment(formdata);
					 if(status == false) return;
					//这里需要通过产品单个金额汇总【本次退款金额】
					var $refund_amount = 0; //本次退款金额
					$('.goods_refund_amount').each(function(){
						$goods_amount = $(this).closest('td').siblings('.goods_amount').html(); //这个商品的折后价
						$goods_refund_amount = $(this).val(); //这个商品的返款金额
						if($goods_refund_amount==""){
							$goods_refund_amount=0;
						}
						if(($goods_amount-$goods_refund_amount)<0){
							alert($goods_amount+"-"+$goods_refund_amount);
							alertify.alert('商品的【退款金额】不得大于商品【折后价】');
							return false;
						}
						$refund_amount += parseInt($goods_refund_amount);
					});
					//可退款金额
					$ktk_amount = $('#ktk_amount').html();
					if(($ktk_amount-$refund_amount)<0){
						alertify.alert("【本次退款金额】不得大于【可退款金额】");
						return false;
					}
					//return false;
					//将本次退款金额添加到formdata里
					formdata[formdata.length]={"name":"refund[refund_amount]","value":$refund_amount};

					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("修改退款成功");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");
							}else{
								alertify.alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/refund/ajax_update_post',
						'async':false
					});
				}
			},{
				text:"取消",
				Class:"btn bottom-margin",
				click: function(){
					alertify.error("你取消了修改");
					$(this).dialog("close");
				}
			}]
		});
		$('#operation_dialog').dialog('open');
	}


//线下开通
	function BelowLineOpen(id,sel_data){
		//alert('线下开通');

		$.ajax({
			'type':'get',
			'data':'id='+id,
			'success':function(data){
				$('#operation_dialog').html(data);
			},
			'url':zjf_url+'/index.php/www/applyopen/ajax_belowline_open',
			'cache':false
		});
		$('#operation_dialog').dialog({
			modal: true,
			title:'线下确认开通',
			width:800,
			height:450,
			buttons: [{
				text:"线下确认开通",
				Class:"btn btn-primary",
				click: function(){
					//alert(111);
					$this = $("#operation_dialog");
					formdata = $("#belowline-form").serializeArray();
					$.ajax({
						'type': 'post',
						'data': formdata,
						'success': function(data) {
							if (data == 1) {
								alertify.success("线下开通成功");
								//$this.dialog("close");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select?module=applyopen",
										data:sel_data,
								});
								$('#operation_dialog').dialog("close");
							} else {
								alert('失败' + data);
							}
						},
						'url': zjf_url+'/index.php/www/applyopen/ajax_update_post',
						'async': false
					});
				}
			},{
				text:"取消",
				Class:"btn bottom-margin",
				click: function(){
					alertify.error("你取消了开通申请");
					$(this).dialog("close");
				}
			}]
		});
		$('#operation_dialog').dialog('open');
	}


	//主管审核作废
	function cancel_check(id,url,sel_data){
		cancel = $(this).attr('cancel');
		$.ajax({
			'e': 'get',
			'data': 'order_id=' + id + '&cancel=' + cancel,
			'success': function(data) {
			$('#operation_dialog').html(data);
		},
		'url': url,
		'cache': false
		});
	$('#operation_dialog').dialog({
			title: '审核',
			modal: true,
			width: 980,
			height: 550,
			buttons: [{
				text: "通过审核",
				Class: "btn btn-primary",
				click: function() {
					$this = $(this);
					formdata = $("#cancel-form").serializeArray();
					$.ajax({
						'type': 'post',
						'data': formdata,
						'success': function(data) {
							if (data == 1) {
								alertify.success("通过审核，待财务确认");
								$this.dialog("close");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select?module=order",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");
							} else if(data == 'remark error'){
								alertify.error("需要填写备注内容");
								$('#remark').focus();
							} else {
								alertify.error( '失败 ' + data );
							}
						},
						'url': zjf_url+'/index.php/www/cancel/ajax_cancel_check',
						'async': false
					});
				}
			},{
				text: "拒绝",
				Class: "btn btn-primary",
				click: function() {
					if(!confirm("确认要做此操作吗?"))
					{
						return false;
					}
					$this = $(this);
					formdata = $("#cancel-form").serializeArray();
					formdata[formdata.length] = {"name": "cancel[refuse]", "value": 1};
					$.ajax({
					'type': 'post',
					'data': formdata,
					'success': function(data) {
						if (data == 1) {
							alertify.error("已拒绝");
							$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select?module=order",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");
						} else if(data == 'remark error'){
							alertify.error("需要填写备注内容");
							$('#remark').focus();
						} else {
							alertify.error( '失败 ' + data );
						}
					},
					'url': zjf_url+'/index.php/www/cancel/ajax_cancel_check',
					'async': false
					});
				}
			},{
				text: "关闭",
				Class: "btn",
				click: function() {
					alertify.error("你取消了");
					$(this).dialog("close");
				}
			}]
		});
		$('#operation_dialog').dialog('open');
	}


	//财务确认作废
	function cancel_affirm(id,url,sel_data){
		cancel = $(this).attr('cancel');
		$.ajax({
			'e': 'get',
			'data': 'order_id=' + id + '&cancel=' + cancel,
			'success': function(data) {
			$('#operation_dialog').html(data);
		},
		'url': url,
		'cache': false
		});
	$('#operation_dialog').dialog({
			title: '审核',
			modal: true,
			width: 980,
			height: 550,
			buttons: [{
				text: "通过",
				Class: "btn btn-primary",
				click: function() {
					$this = $(this);
					formdata = $("#cancel-form").serializeArray();
					$.ajax({
						'type': 'post',
						'data': formdata,
						'success': function(data) {
							if (data == 1) {
								alertify.success("已处理");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select?module=order",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");
							} else if(data == 'remark error'){
								alertify.error("需要填写备注内容");
								$('#remark').focus();
							} else {
								alertify.error( '失败 ' + data );
							}
						},
						'url': zjf_url+'/index.php/www/cancel/ajax_cancel_affirm',
						'async': false
					});
				}
			},{
				text: "拒绝",
				Class: "btn btn-primary",
				click: function() {
					if(!confirm("确认要做此操作吗?"))
					{
						return false;
					}
					$this = $(this);
					formdata = $("#cancel-form").serializeArray();
					formdata[formdata.length] = {"name": "cancel[refuse]", "value": 1};
					$.ajax({
					'type': 'post',
					'data': formdata,
					'success': function(data) {
						if (data == 1) {
							alertify.error("已拒绝");
							$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select?module=order",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");
						} else if(data == 'remark error'){
							alertify.error("需要填写备注内容");
							$('#remark').focus();
						} else {
							alertify.error( '失败 ' + data );
						}
					},
					'url': zjf_url+'/index.php/www/cancel/ajax_cancel_affirm',
					'async': false
					});
				}
			},{
				text: "关闭",
				Class: "btn",
				click: function() {
					$this = $(this);
					alertify.error("你取消了");
					$(this).dialog("close");
				}
			}]
		});
		$('#operation_dialog').dialog('open');
	}


	//申请作废&变更
	function order_cancel(id,url,sel_data){
		cancel = $(this).attr('cancel');
		$.ajax({
			'e': 'get',
			'data': 'order_id=' + id + '&cancel=' + cancel,
			'success': function(data) {
				$('#operation_dialog').html(data);
			},
			'url': url,
			'cache': false
		});
		$('#operation_dialog').dialog({
			title: '申请作废&变更',
			modal: true,
			width: 980,
			height: 600,
			buttons: [{
				text: "提交",
				Class: "btn btn-primary",
				click: function() {
					$this = $(this);
					formdata = $("#cancel-form").serializeArray();
					$.ajax({
						'type': 'post',
						'data': formdata,
						'success': function(data) {
							if (data == 1) {
								alertify.success("已申请，等待审核");
								$("#message_list").ajaxHtml({
										url:zjf_url+"/index.php/www/message/new_ajax_select?module=order",
										data:sel_data,
									});
								$('#operation_dialog').dialog("close");
							} else if(data == 'remark error'){
								alertify.error("需要填写备注内容");
								$('#remark').focus();
							} else if(data == 'order error'){
								alertify.error("订单存在关联，无法申请作废&变更");
							} else if(data == 'none order'){
								alertify.error("变更订单不存在");
							} else {
								alertify.error( '失败 ' + data );
							}
						},
						'url': zjf_url+'/index.php/www/cancel/ajax_order_cancel',
						'async': false
					});
				}
			},{
				text: "关闭",
				Class: "btn",
				click: function() {
					$this = $(this);
					$this.dialog("close");
					alertify.error("你取消了");
					$(this).dialog("close");
				}
			}]
		});
		$('#operation_dialog').dialog('open');
	}

	// 审核款项
	function check_salespay(id,url,sel_data){
		$.ajax({
			'type':'get',
			'data':'salespay_id='+id,
			'success':function(data){
				$('#operation_dialog').html(data);
			},
			'url':url,
			'cache':false
		});
		$('#operation_dialog').dialog({
			modal: true,
			title:'审核款项',
			width:800,
			height:450,
			buttons: [{
				text:"通过审核",
				Class:"btn btn-primary",
				click: function(){
					$this = $(this);
					formdata = $("#confirm-form").serializeArray();
					formdata[formdata.length]={"name":"salespay[salespay_status]","value":"1005"}; //添加1个参数
					var status = leeConditionJudgment(formdata);
					if(status == false) return;
//					dump_obj(formdata);
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("审核通过");
								$this.dialog("close");
								$("#message_list").ajaxHtml({
									url:zjf_url+"/index.php/www/message/new_ajax_select",
									data:sel_data,
								});
							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/salespay/ajax_check_post_update',
						'async':false
					});
				}
			},{
				text:"拒绝",
				Class:"btn btn-success",
				click: function(){
					if(!confirm("确认要做此操作吗?"))
					{
						return false;
					}
					$this = $(this);
					formdata = $("#confirm-form").serializeArray();
					formdata[formdata.length]={"name":"salespay[salespay_status]","value":"1001"}; //添加1个参数
					//alert(formdata);
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("审核失败");
								//$this.dialog("close");
								//$('#profile').ajaxHtml(); //再次加载1次页面
									$("#message_list").ajaxHtml({
									url:zjf_url+"/index.php/www/message/new_ajax_select",
									data:sel_data,
								});
									$('#operation_dialog').dialog("close");
							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/salespay/ajax_check_post_update',
						'async':false
					});
				}
			},{
				text:"取消",
				Class:"btn bottom-margin",
				click: function(){
					alertify.error("你取消了审核款项");
					$(this).dialog("close");
				}
			}]
		});
		 $('#operation_dialog').dialog('open');
	}


	//js获取当前时间
	var myDate = new Date();
	var month = myDate.getMonth() < 10 ? '0'+(myDate.getMonth()+1) : myDate.getMonth()+1;
	var date = myDate.getDate() < 10 ? '0'+myDate.getDate() : myDate.getDate();
	var hours = myDate.getHours() < 10 ? '0'+myDate.getHours() : myDate.getHours();
	var minutes = myDate.getMinutes() < 10 ? '0'+myDate.getMinutes() : myDate.getMinutes();
	var seconds = myDate.getSeconds() < 10 ? '0'+myDate.getSeconds() :  myDate.getSeconds();
	var myTime = myDate.getFullYear()+'-'+month+'-'+date+' '+hours+':'+minutes+':'+seconds;
	//审批内划订单
	function examine_penny(id,url,sel_data){
		$.ajax({
			'type':'get',
			'data':'order_id='+id,
			'success':function(data){
				$('#operation_dialog').html(data);
			},
			'url':url,
			'cache':false
		});
		$('#operation_dialog').dialog({
			modal: true,
			title:'审批内划订单',
			width:800,
			height:450,
			buttons: [{
				text:"审批通过",
				Class:"btn btn-primary",
				click: function(){
					formdata = $("#examine_penny_form").serializeArray();
					formdata[formdata.length]={"name":"order[order_transfer_state]","value":"1002"}; //添加1个参数
					formdata[formdata.length]={"name":"order[order_transfer_date]","value":myTime}; //添加1个参数
					//alert(formdata);
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("审批通过");
								//再次加载1次列表页面
								$("#message_list").ajaxHtml({
									url:zjf_url+"/index.php/www/message/new_ajax_select",
									data:sel_data,
								});
								$('#operation_dialog').dialog("close");

							}else{
								alert('失败'+data);
							}
						},
						'url':zjf_url+'/index.php/www/order/ajax_update_post',
						'async':false
					});
				}
			},{
				text:"驳回审批",
				Class:"btn btn-success",
				click: function(){
					if(!confirm("确认要做此操作吗?"))
					{
						return false;
					}
					formdata = $("#examine_penny_form").serializeArray();
					formdata[formdata.length]={"name":"order[order_transfer_state]","value":"1003"}; //添加1个参数
					//alert(formdata);
					$.ajax({
						'type':'post',
						'data':formdata,
						'success':function(data){
							if(data==1){
								alertify.alert("驳回审批");
								//再次加载1次列表页面
								$("#message_list").ajaxHtml({
									url:zjf_url+"/index.php/www/message/new_ajax_select",
									data:sel_data,
								});
								$('#operation_dialog').dialog("close");
							}else{
								alert('失败'+data);
							}
						},
						'url': zjf_url+'/index.php/www/order/ajax_update_post',
						'async':false
					});
				}
			},{
				text:"取消",
				Class:"btn bottom-margin",
				click: function(){
					alertify.error("你取消了审批返点");
					$(this).dialog("close");
				}
			}]

		});
		$('#operation_dialog').dialog('open');
	}