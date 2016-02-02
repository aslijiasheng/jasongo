$.fn.LeeForm={}; //创建一个全局对象 用于存储form里的各种属性


//test
//根据不同属性类型处理不同
$.fn.TypeGetFrom = function($data){
	var $ID;
	var $Type;
	var $form_data
	$ID = $(this).attr("id");
	$form_data = $data.form_data;
	$Type = $data.type;
	switch ($Type) {
		case '6'://超长文本
			$("#"+$ID).FormTextarea({
				form_data:$form_data
			});
			break;
		case '8'://email类型
			$("#"+$ID).FormEmail({
				form_data:$form_data
			});
			break;
		case '10'://电话类型
			$("#"+$ID).FormPhone({
				form_data:$form_data
			});
			break;
		case '11'://CHECK
			$("#"+$ID).FormCheck({
				form_data:$form_data
			});
			break;
		case '12'://RADIO
			$("#"+$ID).FormRadio({
				form_data:$form_data
			});
			break;
		case '13'://这里是下拉框枚举类型
			$("#"+$ID).FormSelect({
				form_data:$form_data
			});
			break;
		case '15'://日期类型
			$("#"+$ID).FormDate({
				form_data:$form_data
			});
			break;
		case '16'://时间类型
			$("#"+$ID).FormTime({
				form_data:$form_data
			});
			break;
		case '18':
		case '19'://引用类型
			$("#"+$ID).FormQuote({
				form_data:$form_data
			});
			break;
		default://其他
			$("#"+$ID).FormText({
				form_data:$form_data
			});
			break;
	}
}



//时间类型
$.fn.FormTime = function($data){
	var $ID;
	var $form_data;
	var $InputID;
	$ID = $(this).attr("id");
	$form_data = $data.form_data;
	$InputID = "Input_"+$ID;
	if($form_data.value==null){
		$form_data.value = "";
	}
	//$("#"+$ID).append('<div class="form-group"><input id="'+$InputID+'" type="text" class="col-xs-12" value="'+$form_data.value+'"></div>');
	$("#"+$ID).append('<div class="form-group input-group"><input class="form-control date-picker" id="'+$InputID+'" type="text" /><span class="input-group-addon"><i class="fa fa-clock-o bigger-110"></i></span></div>');
	
	$("#"+$InputID).attr("name",$form_data.name);
	$("#"+$InputID).attr("value",$form_data.value);
	//$("#"+$InputID).addClass('isPhone');
	if($form_data.required==true){
		$("#"+$InputID).addClass('required');
	}
	if($form_data.notedit==1){
		$("#"+$InputID).attr('disabled',true);
	}else{
		$("#"+$InputID).attr('disabled',false);
	}

	//加载时间控件
	$("#"+$InputID).datetimepicker({
		language:  'zh-CN',
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
		format:'yyyy-mm-dd hh:ii:00'
	});
}

//日期类型
$.fn.FormDate = function($data){
	var $ID;
	var $form_data;
	var $InputID;
	$ID = $(this).attr("id");
	$form_data = $data.form_data;
	$InputID = "Input_"+$ID;
	if($form_data.value==null){
		$form_data.value = "";
	}
	//$("#"+$ID).append('<div class="form-group"><input id="'+$InputID+'" type="text" class="col-xs-12" value="'+$form_data.value+'"></div>');
	$("#"+$ID).append('<div class="form-group input-group"><input class="form-control date-picker" id="'+$InputID+'" type="text" data-date-format="yyyy-mm-dd" /><span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span></div>');
	
	$("#"+$InputID).attr("name",$form_data.name);
	$("#"+$InputID).attr("value",$form_data.value);
	//$("#"+$InputID).addClass('isPhone');
	if($form_data.required==true){
		$("#"+$InputID).addClass('required');
	}
	if($form_data.notedit==1){
		$("#"+$InputID).attr('disabled',true);
	}else{
		$("#"+$InputID).attr('disabled',false);
	}

	//加载日期控件
	$('#'+$InputID).datetimepicker({
		language:  'zh-CN',
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0,
		format:'yyyy-mm-dd'
	});
}

//电话类型文本
$.fn.FormPhone = function($data){
	var $ID;
	var $form_data;
	var $InputID;
	$ID = $(this).attr("id");
	$form_data = $data.form_data;
	$InputID = "Input_"+$ID;
	if($form_data.value==null){
		$form_data.value = "";
	}
	$("#"+$ID).append('<div class="form-group"><input id="'+$InputID+'" type="text" class="col-xs-12" value="'+$form_data.value+'"></div>');
	$("#"+$InputID).attr("name",$form_data.name);
	$("#"+$InputID).addClass('isPhone');
	if($form_data.required==true){
		$("#"+$InputID).addClass('required');
	}
	if($form_data.notedit==1){
		$("#"+$InputID).attr('disabled',true);
	}else{
		$("#"+$InputID).attr('disabled',false);
	}

}

//大文本字段类型
$.fn.FormTextarea = function($data){
	var $ID;
	var $SelectID;
	var $form_data;
	var $TextareaID;
	$ID = $(this).attr("id");
	$form_data = $data.form_data;
	$TextareaID = "Textarea_"+$ID;
	if($form_data.value==null){
		$form_data.value = "";
	}
	$("#"+$ID).append('<div class="form-group"><textarea id="'+$TextareaID+'" class="form-control" style="height:100px;resize:none;">'+$form_data.value+'</textarea></div>');
	$("#"+$TextareaID).attr("name",$form_data.name);
}

//引用类型
$.fn.FormQuote = function($data){
	var $ID = $(this).attr("id");
	var $DivID = "div_"+$ID;
	var $InputID = "input_"+$ID;
	var $form_data = $data.form_data;
	$("#"+$ID).append('<div class="form-group input-group" id="'+$DivID+'"></div>');
	$("#"+$DivID).append('<input class="form-control" id="'+$InputID+'" type="text" />');
	$("#"+$InputID).attr("name",$form_data.name);
	$("#"+$InputID).val($form_data.value);
    //alert($form_data.name);
	$("#"+$InputID).QuoteInitial({
		width:$form_data.DialogWidth,
		title:$form_data.ObjName,
		name:$form_data.name,
		url:$form_data.RefUrl,
		id_value:$form_data.id_value
	});
}

//引用类型的dialog初始化
$.fn.QuoteInitial = function($data){
	var $ID = $(this).attr("id");
	var $DialogDivID = "dialog_div_"+$ID;
	var $DialogHtmlID = "dialog_html_"+$ID;
	var $InputBtnID = "input_btn_"+$ID;
	var $InputKeyID = "input_key_"+$ID;
	var $DialogWidth = $data.width ? $data.width : 600;
	var $DialogHeight = $data.height ? $data.height : 500;
	var $DialogTitle  = $data.title ? $data.title : "引用";
	var $DialogUrl = $data.url;
	var $InputName = $data.name;
    var $NewInputName = $InputName+".ID";
    if($InputName){
        //alert($InputName);
        if($InputName.substring($InputName.length-1)=="]"){
            $NewInputName = $InputName.substring($InputName.length-1,0)+".ID]";
        }
    }
	var $InputHiddenName = $data.HiddenName ? $data.HiddenName : $NewInputName;
    //这里的name需要判断一下，通过结尾是否是]来判断这个ID是否加载[]内

	var $id_value = $data.id_value ? $data.id_value : "";

	if($('#'+$DialogDivID).length==0){
		$(this).closest("div").after('<div id="'+$DialogDivID+'" style="display:none;"><div id="'+$DialogHtmlID+'"></div></div>');
		$(this).after('<span class="input-group-addon" id="'+$InputBtnID+'"><i class="fa fa-list-alt bigger-110"></i></span>');
		//因此输入框，用于给ID
		$(this).after('<input class="form-control" id="'+$InputKeyID+'" type="hidden" />');
		$("#"+$InputKeyID).attr("name",$InputHiddenName);
		$("#"+$InputKeyID).val($id_value);
	}

	$("#"+$InputBtnID).click(function(){
		$.ajax({
			'type':'post',
			// 'data':{
			// 	'hidden_v':hidden_v
			// },
			'success':function(data){
				$('#'+$DialogHtmlID).html(data);
			},
			'url':$DialogUrl,
			'cache':false
		});
		$("#"+$DialogDivID).dialog({
			title:$DialogTitle,
			title_html: true,
			modal: true,
			width:$DialogWidth,
			height:$DialogHeight,
			buttons: [{
				text:"确定",
				Class:"btn btn-primary",
				click: function(){
					radio = $('#'+$DialogHtmlID).find('input[type=radio]:checked');
					radio_val = radio.val();
					radio_name = radio.attr("data-name");
					//赋值
                    //alert(radio_name);
					$("#"+$ID).val(radio_name);
					$("#"+$InputKeyID).val(radio_val);
					//最后关闭当前dialog
					$(this).dialog("close");
				}
			}]
		});
		$("[aria-describedby="+$DialogDivID+"]").find(".ui-dialog-title").html("<div class='widget-header'><h4 class='smaller'>"+$DialogTitle+"</h4></div>");
		$('#'+$DialogDivID).dialog('open');
	});
}

//email邮箱
$.fn.FormEmail = function($data){
	//var $ID;
	//$ID = $(this).attr("id");
	//$("#"+$ID).append('这个是Email类型，还没做');
	var $ID;
	var $InputID;
	var $form_data;
	$ID = $(this).attr("id");
	$form_data = $data.form_data;
	//console.debug($data.form_data);
	$InputID = "Input_"+$ID;
	if($form_data.value==null){
		$form_data.value = "";
	}
	$("#"+$ID).append('<div class="form-group"><input id="'+$InputID+'" type="text" class="col-xs-12" value="'+$form_data.value+'"></div>');
	$("#"+$InputID).attr("name",$form_data.name);
	$("#"+$InputID).addClass('email');
	if($form_data.required==true){
		$("#"+$InputID).addClass('required');
	}
	if($form_data.notedit==1){
		$("#"+$InputID).attr('disabled',true);
	}else{
		$("#"+$InputID).attr('disabled',false);
	}
}

//是否类型的复选抗
$.fn.FormCheck = function($data){
	var $ID;
	var $SelectID;
	var $form_data;
	var $CheckID;
	var $CheckInputID;
	$ID = $(this).attr("id");
	$form_data = $data.form_data;
	console.debug($form_data);
	$CheckID = "Check_"+$ID;
	$CheckInputID = "Check_Input_"+$ID;
	$("#"+$ID).append('<div class="form-group" id="'+$CheckID+'" style="line-height: 14px;"></div>');
	$("#"+$CheckID).append('<label style="margin-bottom:0px;margin-top:8px;"><input name="switch-field-1" id="'+$CheckInputID+'" class="ace ace-switch" type="checkbox"/><span class="lbl"  data-lbl="是&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;否"></span></label>');
	if($form_data.value==1){
		$("#"+$CheckInputID).attr("checked",true);
	}else{
		$("#"+$CheckInputID).attr("checked",false);
	}
	$("#"+$CheckInputID).attr("name",$form_data.name);
	if($form_data.notedit==1){
		$("#"+$CheckInputID).attr('disabled',true);
	}else{
		$("#"+$CheckInputID).attr('disabled',false);
	}
}

//单选
$.fn.FormRadio = function($data){
	var $ID;
	var $SelectID;
	var $form_data;
	var $RadioID;
	var $InputID;
	$ID = $(this).attr("id");
	$form_data = $data.form_data;
	$RadioID = "Radio_"+$ID;
	$("#"+$ID).append('<div class="form-group" id="'+$RadioID+'" style="line-height: 33px;"></div>');
	$.each($form_data.enum_arr,function($k,$v){
		$InputID="Input_"+$ID+"_"+$k;
		$("#"+$RadioID).append(' <label style="margin-bottom:0px;margin-top:0px;"><input class="ace" name="'+$form_data.name+'" type="radio" name="name" value="'+$v.enum_key+'"><span class="lbl">'+$v.enum_value+'</span></label> ');
		if($form_data.notedit==1){
			$("#"+$InputID).attr('disabled',true);
		}else{
			$("#"+$InputID).attr('disabled',false);
		}
	});
}

//下拉框枚举
$.fn.FormSelect = function($data){
    //console.debug($data);
	var $ID;
	var $SelectID;
	var $form_data;
	$ID = $(this).attr("id");
	$form_data = $data.form_data;
	$SelectID = "Select_"+$ID;
	$("#"+$ID).append('<div class="form-group"><select id="'+$SelectID+'" class="form-control"></select></div>');
	//初始化枚举
	//if($form_data.is_child!=1){
		$("#"+$SelectID).SelectInitial({
			enum_arr:$form_data.enum_arr
		});
	//}
	//给这个value对于的枚举给上selected
	if($form_data.key!=""){
		$("#"+$SelectID).find("[value='"+$form_data.key+"']").attr('selected',true);
	}
	if($data.name==undefined){
		$("#"+$SelectID).attr("name",$form_data.name);
	}else{
		$("#"+$SelectID).attr("name",$data.name);
	}
	if($form_data.notedit==1){
		$("#"+$SelectID).attr('disabled',true);
	}else{
		$("#"+$SelectID).attr('disabled',false);
	}
}

//下拉框枚举初始化
$.fn.SelectInitial = function($data){
	var $ID;
	var $enum_arr;
	$ID = $(this).attr("id");
    //console.debug($ID);
	$enum_arr = $data.enum_arr;
	if($data.not_null==undefined||$data.not_null==false){
		$("#"+$ID).html('<option value=""></option>');
	}else{
		$("#"+$ID).html('');
	}
	if($enum_arr!=undefined){
		$.each($enum_arr,function($k,$v){
			$("#"+$ID).append('<option value="'+$v.enum_key+'">'+$v.enum_value+'</option>');
		});
	}
}

//最普通的文本样式
$.fn.FormText = function($data){
	//console.debug($data);
	var $ID;
	var $InputID;
	var $form_data;
	$ID = $(this).attr("id");
	$form_data = $data.form_data;
	$InputID = "Input_"+$ID;
	if($form_data.value==null){
		$form_data.value = "";
	}
	$("#"+$ID).append('<div class="form-group"><input id="'+$InputID+'" type="text" class="col-xs-12" value="'+$form_data.value+'"></div>');
	$("#"+$InputID).attr("name",$form_data.name);
    $("#"+$InputID).attr("data-attrname",$form_data.name);
	if($form_data.required==true){
		$("#"+$InputID).addClass('required');
	}
	if($form_data.notedit==1){
		$("#"+$InputID).attr('disabled',true);
	}else{
		$("#"+$InputID).attr('disabled',false);
	}
}

//先创建html后加载模式 start--------------------------------------------
//通过类型获取不同的html内容(明细)
function TypeGetFormHtml($data){
    var $AttrType;
    var $FormData;
    $FormData = $data.FormData;
    $AttrType = $data.AttrType;
    switch ($AttrType) {
        case '6'://超长文本
            return FormTextareaHtml($FormData);
            break;
        case '8'://email类型
            return FormEmailHtml($FormData);
            break;
//        case '10'://电话类型
//            $("#"+$ID).FormPhone({
//                form_data:$form_data
//            });
//            break;
//        case '11'://CHECK
//            $("#"+$ID).FormCheck({
//                form_data:$form_data
//            });
//            break;
//        case '12'://RADIO
//            $("#"+$ID).FormRadio({
//                form_data:$form_data
//            });
//            break;
//        case '13'://这里是下拉框枚举类型
//            $("#"+$ID).FormSelect({
//                form_data:$form_data
//            });
//            break;
//        case '15'://日期类型
//            $("#"+$ID).FormDate({
//                form_data:$form_data
//            });
//            break;
//        case '16'://时间类型
//            $("#"+$ID).FormTime({
//                form_data:$form_data
//            });
//            break;
        case '18':
        case '19'://引用类型
            return FormQuoteHtml($FormData);
            break;
        default://其他 其他全部是文本类型
            return FormTextHtml($FormData);
            break;
    }
}

// 6 超长文本
function FormTextareaHtml($FormData){
    var $Html = '<div class="form-group"><textarea ';
    if($FormData.ID){ $Html = $Html+'id="'+$FormData.ID+'" ';}
    if($FormData.Name){ $Html = $Html+'name="'+$FormData.Name+'" ';}
    $Html = $Html+' class="form-control" style="height:100px;resize:none;">';
    if($FormData.Value){$Html = $Html+$Value;}
    $Html = $Html+'</textarea></div>';
    return $Html;
}

// 8 email类型
function FormEmailHtml($FormData){
    var $Html = '<div class="form-group"><input ';
    if($FormData.ID){ $Html = $Html+'id="'+$FormData.ID+'" ';}
    if($FormData.Name){ $Html = $Html+'name="'+$FormData.Name+'" ';}
    if($FormData.Value){$Html = $Html+'value="'+$FormData.Value+'" ';}
    $Html = $Html+'type="text" lee-type="email" class="col-xs-12" ';
    $Html = $Html+'></div>';
    return $Html;
}

// 18、19 引用类型
function FormQuoteHtml($FormData){
    //console.debug($FormData);
    var $ID = $FormData.ID;
    var $DivID = "div_"+$ID;
    var $InputID = "input_"+$ID;
    var $Html = '<div class="form-group input-group col-xs-12" id="'+$DivID+'"><input ';
    if($FormData.ID){ $Html = $Html+'id="'+$InputID+'" ';}
    if($FormData.Name){ $Html = $Html+'name="'+$FormData.Name+'" ';}
    if($FormData.Value){$Html = $Html+'value="'+$FormData.Value+'" ';}
    if($FormData.ValueID){$Html = $Html+'data-valueid="'+$FormData.ValueID+'" ';}
    if($FormData.RefUrl){$Html = $Html+'data-url="'+$FormData.RefUrl+'" ';}
    if($FormData.ObjName){$Html = $Html+'data-ObjName="'+$FormData.ObjName+'" ';}
    $Html = $Html+'type="text" lee-type="quote" class="col-xs-12" ';
    $Html = $Html+'></div>';
    return $Html;
}

// 其他 文本类型
function FormTextHtml($FormData){
    var $Html = '<div class="form-group"><input ';
    if($FormData.ID){$Html = $Html+'id="'+$FormData.ID+'" ';}
    if($FormData.Name){ $Html = $Html+'name="'+$FormData.Name+'" ';}
    if($FormData.Value){$Html = $Html+'value="'+$FormData.Value+'" ';}
    if($FormData.attr_name){$Html = $Html+'data-attrname="'+$FormData.attr_name+'" ';}
    if($FormData.row){$Html = $Html+'data-row="'+$FormData.row+'" ';}
    $Html = $Html+'isleeform="1" ';
    $Html = $Html+'type="text" class="col-xs-12" ';
    $Html = $Html+'></div>';
    return $Html;
}



//先创建html后加载模式 end----------------------------------------------------