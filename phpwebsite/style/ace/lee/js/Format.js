//查看、编辑、新增页面布局的table生成 2014-10-30 start
$.fn.Format = function($data){
	var $ID = $(this).attr("id");
	var $FormatData = $data.FormatData;
	//console.debug($FormatData);
	var $FromID = $data.FromID;
	var $TabTitleUlID;
	var $TabContentID;
	var $TabTitleLiID;
	var $type = $data.type;
	var $SelectChildEnumsUrl = $data.SelectChildEnumsUrl;
	//根据这个数组
	//添加页签
	$TabTitleUlID = "tab_title_ul_"+$ID;
	$("#"+$ID).append('<div class="tabbable"><ul id="'+$TabTitleUlID+'"  class="nav nav-tabs"></ul></div>');
	$TabContentID = "tab_ccontent_"+$ID;
	$("#"+$ID).append('<div id="'+$TabContentID+'" class="tab-content"></div>');

	//添加验证控件
	$('#'+$FromID).validate({
		errorElement: 'div',
		errorClass: 'help-block',
		focusInvalid: true,
		// rules: {
		// 	'Lead.Email': {
		// 		required: true,
		// 		email:true
		// 	}
		// },
		highlight: function (e) {
			$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
		},
		success: function (e) {
			$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
			$(e).remove();
		}
	});

	//循环
	$.each($FormatData.tables,function($k,$v){
		//循环出几个页签的签名
		$TabTitleLiID = $ID+"tab_title_li_"+$k;
        $TabID = $ID+"Tab_"+$k;
		if($k==0){
			$("#"+$TabTitleUlID).append('<li class="active" id="'+$TabTitleLiID+'"></li>');
		}else{
			$("#"+$TabTitleUlID).append('<li id="'+$TabTitleLiID+'"></li>');
		}
		if($v.tableNames[2]=="" || $v.tableNames==""){
			if($k==0){
				$("#"+$TabTitleLiID).append('<a data-toggle="tab" href="#'+$TabID+'">主面板</a>');
			}else{
				$("#"+$TabTitleLiID).append('<a data-toggle="tab" href="#'+$TabID+'">面板'+$k+'</a>');
			}
		}else{
			$("#"+$TabTitleLiID).append('<a data-toggle="tab" href="#'+$TabID+'">'+$v.tableNames[2]+'</a>');
		}

		//循环出几个切换的内容部分

		if($k==0){
			$("#"+$TabContentID).append('<div id="'+$TabID+'" class="tab-pane in active"></div>');
		}else{
			$("#"+$TabContentID).append('<div id="'+$TabID+'" class="tab-pane"></div>');
		}
		
		$('#'+$TabID).TabContent({
			TableData: $v.cells,
			columns: $v.columns,
			type:$type
		});
	});

	switch ($type) {
		case 'edit':
			//二次循环 用于做枚举联动以及所有验证
			$.each($FormatData.tables,function($k,$v){
				$.each($v.cells,function($kk,$vv){
					//枚举联动
					//console.debug($vv);
					if($vv.label==0){
						if($vv.form_data.is_parent==1){
                            //console.debug($vv);
							$("[name='"+$vv.form_data.name+"']").EnumRel({
								SelectChildEnumsUrl:$SelectChildEnumsUrl
							});
						}
                        /*
                        //如果为子级
                        if($vv.form_data.is_child==1){
                            $("[name='"+$vv.form_data.name+"']").EnumRel({
                                SelectChildEnumsUrl:$SelectChildEnumsUrl
                            });
                        }
                        */
					}
				});
			});
			break;
		case 'view':
			//查看页面不需要二次循环
			break;
		default://其他
			break;
	}
	
}
//编辑页面布局的table生成 2014-10-30 end

//属性联动
$.fn.EnumRel = function($data){
	var $SelectChildEnumsUrl;
	$SelectChildEnumsUrl = $data.SelectChildEnumsUrl; //联动枚举需要调用的URL
	//当点击的时候
    //console.debug($data);
	$(this).change(function(){
        InitializationLinkage($(this).val(),$(this).attr('name'),$SelectChildEnumsUrl);
	});
    InitializationLinkage($(this).val(),$(this).attr('name'),$SelectChildEnumsUrl);

}

/**
 * 初始化联动
 * @param $enum_key
 * @param $attr_name
 * @param $SelectChildEnumsUrl
 * @constructor
 */
function InitializationLinkage($enum_key,$attr_name,$SelectChildEnumsUrl){
    //console.debug($enum_key+"|"+$attr_name+"|"+$SelectChildEnumsUrl);
    $.ajax({
        'type':'post',
        'data':{
            'enum_key':$enum_key,
            'attr_name':$attr_name
        },
        'success':function(data){
            var $data=eval("("+data+")");
            //console.debug($data);
            $.each($data,function($k,$v){
                //console.debug($v);
                //格式化子级枚举
                childInitial($k,$v.is_parent,$SelectChildEnumsUrl,$v.enum_arr)

            });
        },
        'url':$SelectChildEnumsUrl,
        'async':false
    });
}


/**
 * 子级判断是否还有子级的方法
 * @param $attr_name 属性名称
 * @param $is_parent
 * @param $SelectChildEnumsUrl
 * @param $enum_arr
 */
function childInitial($attr_name,$is_parent,$SelectChildEnumsUrl,$enum_arr){
    //console.debug($attr_name+"|"+$is_parent+"|"+$SelectChildEnumsUrl+"|"+$enum_arr);
    //抓取这个枚举原来的值
    var $old_val = $("[name='"+$attr_name+"']").val();

	$("[name='"+$attr_name+"']").SelectInitial({
		enum_arr:$enum_arr
	});
	if($is_parent==1){
		$.ajax({
			'type':'post',
			'data':{
				'enum_key':'',
				'attr_name':$attr_name
			},
			'success':function(data){
				var $data=eval("("+data+")");
				//console.debug(data);
				$.each($data,function($k,$v){
					//console.debug($v);
					//格式化子级枚举
					childInitial($k,$v.is_parent,$SelectChildEnumsUrl,$enum_arr);

				});
			},
			'url':$SelectChildEnumsUrl,
			'async':false
		});
	}
    //给这个value对于的枚举给上selected
    if($old_val){
        $("[name='"+$attr_name+"']").find("[value='"+$old_val+"']").attr('selected',true);
    }
}

//内容的table
$.fn.TabContent = function($data){
	var $ID;
	var $TableData;
	var $columns;
	var $type;
	var $TableID;
	var $i;
	var $tri;
	var $colspan;
	var $IsLabel;
	var $rowspan;
	var $trID;
	var $TdWidth;
	var $style;
	var $tdID;

	$ID = $(this).attr("id");
	$TableData=$data.TableData;
	$columns=$data.columns;
	$type=$data.type;
	$TableID = "table_"+$ID;
	$("#"+$ID).append('<table id="'+$TableID+'" class="table table-striped table-bordered"></table>');
	$i = 0;
	$tri = 1;
	$.each($TableData,function($k,$v){
		$colspan = $v.colspan;
		$IsLabel = $v.label;
		$rowspan = $v.rowspan;
		$i = $i + $colspan;
		$trID = "table_"+$ID+"_tr_"+$tri;
		if($('#'+$trID).length==0){
			$("#"+$TableID).append('<tr id="'+$trID+'"></tr>');
		}
		$TdWidth = GetTdWidth($i,$columns,$colspan,$IsLabel); //TD宽度
		if($IsLabel==1){
			$style = 'width:'+$TdWidth+';text-align:right;font-weight:bold;color:#0daed3;';
			if($type == 'edit'){
				$style = $style+"line-height: 34px;";
			}
		}else if($IsLabel==2){
			$style = 'width:'+$TdWidth+';text-align:left;';
		}else{
			$style = 'width:'+$TdWidth+';text-align:left;';
		}
		$tdID = "table_"+$ID+"_tr_"+$tri+"_td_"+$k;
		$("#"+$trID).append('<td id="'+$tdID+'" rowspan="'+$rowspan+'" colspan="'+$colspan+'" style="'+$style+'"></td>');
		
		if($IsLabel==1){
			$("#"+$tdID).append($v.attr_label);
		}else if($IsLabel==2){
			$("#"+$tdID).append($v.attr_label);
		}else{
			//这里是类容部分，根据类型edit、view 来处理不同的方式
			switch ($type) {
				case 'edit':
					//这里需要调用Form.js
					$("#"+$tdID).TypeGetFrom({
						type:$v.attr_type,
						form_data:$v.form_data
					});
					break;
				case 'view':
					if($v.content==null){
						$v.content = "";
					}
                    $("#"+$tdID).addClass('lee_wrap');
					$("#"+$tdID).append($v.content);
					break;
				default://其他
					$("#"+$tdID).append("未知类型");
					break;
			}
			
		}
		if($i % $columns ==0){
			$tri++;
		}
		
	});

	/**
	 * 用于计算td的宽度的方法
	 * $i
	 * $columns
	 * $colspan
	 * $IsLabel
	 */
	function GetTdWidth($i,$columns,$colspan,$IsLabel){
		var $TdWidth;
		switch ($columns) {
			case 4:
				if($IsLabel>=1){
					switch ($colspan) {
						case 1:
							$TdWidth = "15%";
							break;
						case 2:
							$TdWidth = "50%";
							break;
						case 3:
							$TdWidth = "65%";
							break;
						case 4:
							$TdWidth = "100%";
							break;
					}
				}else{
					switch ($colspan) {
						case 1:
							$TdWidth = "35%";
							break;
						case 2:
							$TdWidth = "50%";
							break;
						case 3:
							$TdWidth = "75%";
							break;
						case 4:
							$TdWidth = "100%";
							break;
					}
				}
				break;
			case 6:
				if($IsLabel>=1){
					switch ($colspan) {
						case 1:
							$TdWidth = "10%";
							break;
						case 2:
							$TdWidth = "33%";
							break;
						case 3:
							$TdWidth = "43%";
							break;
						case 4:
							$TdWidth = "66%";
							break;
						case 5:
							$TdWidth = "76%";
							break;
						case 6:
							$TdWidth = "100%";
							break;
					}
				}else{
					switch ($colspan) {
						case 1:
							$TdWidth = "23%";
							break;
						case 2:
							$TdWidth = "33%";
							break;
						case 3:
							$TdWidth = "56%";
							break;
						case 4:
							$TdWidth = "66%";
							break;
						case 5:
							$TdWidth = "89%";
							break;
						case 6:
							$TdWidth = "100%";
							break;
					}
				}
				break;
		}
		return $TdWidth;
	}
}




