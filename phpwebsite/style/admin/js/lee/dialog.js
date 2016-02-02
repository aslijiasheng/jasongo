/************dialog************/
//cus lee 首先是初始化所有添加dialog的页面 start
$("[leetype='quote']").each(function(){
	input_id = $(this).attr("id"); 
	url = $(this).attr("url"); //取url参数，用于调用什么页面
	input_name = $(this).attr("name"); //取name参数，传递给隐藏字段
	input_value_id = $(this).attr("value_id"); //取value_id参数，传递给隐藏字段赋值
	new_input_name = "new_"+input_name; //给原来的name
	$(this).attr("style","width:180px");
	hidden_id = input_id+"_hidden";
	div_id = input_id+"_dialog_div";
	html_id = input_id+"_dialog_html";
	span_id = input_id+"_span";
	btn_id = input_id+"_btn";
	//在下面加上dialog的2个DIV
	$(this).after("<div id=\""+div_id+"\" style=\"display:none;\"><div id=\""+html_id+"\"></div></div>");
	$(this).after("<span id='"+btn_id+"' class='add-on btn' leetype='dialog_btn'><span data-icon='&#xe07f'></span></span>");
	//添加一个隐藏的输入框
	$(this).after('<input type="hidden" name="'+input_name+'" id="'+hidden_id+'" value="'+input_value_id+'">');
	
	//修改原来的name变成新name
	$(this).attr("name",new_input_name);
	//将他们框起来
	$("#"+span_id+",#"+input_id+",#"+btn_id).wrapAll("<div class='input-prepend input-append' style='width: 194px;'></div>");
});
//cus lee 首先是初始化所有添加dialog的页面 end

//cus lee 按钮触发ajax形成dialog start
$("[leetype='dialog_btn']").click(function(){
	input_id = btn_id.replace("_btn", "");
	url = $("#"+input_id).attr('url');
	btn_id = $(this).attr("id");
	title = $("#"+input_id).attr("title");
	div_id = input_id+"_dialog_div";
	html_id = input_id+"_dialog_html";
	hidden_v = $("#"+hidden_id).val();
	$.ajax({
		'type':'get',
		'data':{
			'hidden_v':hidden_v
		},
		'success':function(data){
			$('#'+html_id).html(data);
		},
		'url':url,
		'cache':false
	});
	$("#"+div_id).dialog({
		title:title,
		width:800,
		height:450,
		buttons: [{
			text:"确定",
			Class:"btn btn-primary",
			click: function(){
				/*获取单选按钮的值*/
				radio = $(this).find('input[name=optionsRadios]:checked');
				radio_val = radio.val();
				radio_name = radio.attr("name_val");
				//赋值
				$("#"+input_id).val(radio_name);
				$("#"+hidden_id).val(radio_val);
				//最后关闭当前dialog
				$(this).dialog("close");
			}
		}]
	});
	$('#'+div_id).dialog('open');
});
//cus lee 按钮触发ajax形成dialog end

/************icondialog************/
//cus lee 首先是初始化所有添加dialog的页面 start
$("[leetype='icondialog']").each(function(){
	input_id = $(this).attr("id");
	input_val = $(this).val();
	url = $(this).attr("url");
	div_id = input_id+"_dialog_div";
	html_id = input_id+"_dialog_html";
	span_id = input_id+"_span";
	btn_id = input_id+"_btn";
	$(this).attr("style","width:154px");
	//在上面加上2个显示图标页面和输入栏
	$(this).before('<span id="'+span_id+'" class="add-on"><span class="'+input_val+'" style="font-size: 16px;"></span></span>');
	//在下面加上dialog的2个DIV
	$(this).after("<div id=\""+div_id+"\" style=\"display:none;\"><div id=\""+html_id+"\"></div></div>");
	$(this).after("<span id='"+btn_id+"' url='"+url+"' class='add-on btn' leetype='icondialog_btn'><span class='icon-briefcase' style='font-size: 16px;'></span></span>");
	//将他们框起来
	$("#"+span_id+",#"+input_id+",#"+btn_id).wrapAll("<div class='input-prepend input-append'></div>");
});

//cus lee 首先是初始化所有添加dialog的页面 end
$("[leetype='icondialog_btn']").click(function(){
	url = $(this).attr('url');
	btn_id = $(this).attr("id");
	input_id = btn_id.replace("_btn", "");
	title = $("#"+input_id).attr("title");
	div_id = input_id+"_dialog_div";
	html_id = input_id+"_dialog_html";
	span_id = input_id+"_span";
	btn_id = input_id+"_btn";
	$.ajax({
		'type':'get',
		'data':{
			'div_id':div_id,
			'html_id':html_id,
			'span_id':span_id,
			'input_id':input_id
		},
		'success':function(data){
			$('#'+html_id).html(data);
		},
		'url':url,
		'cache':false
	});
	$("#"+div_id).dialog({
		title:title,
		width:800,
		height:450
	});
	$('#'+div_id).dialog('open');
});





//分页菜单
//初始化
$("[leetype='pagination']").each(function(){
	$(this).hide(); //本身这个div隐藏掉
	$tid = $(this).attr('id'); //获取ID
	$ul_id = $tid+'_ul'; //首页的ID
	$perNumber=$(this).attr('perNumber'); //每页显示的记录数
	$page=$(this).attr('page'); //获得当前的页面值
	//$page=10; //获得当前的页面值
	if(!$page || $page<1){
		$page=1;
	}
	$totalNumber=$(this).attr('totalNumber'); //数据总数
	$totalPage=parseInt(($totalNumber-1)/$perNumber)+1; //总页数
	$forPage=parseInt(($page-1)/$perNumber)+1; //循环从那页开始
	//$endPage=11;
	//alert($totalPage);
	//在下面生成想要的代码
	$(this).after('<div class="pagination no-margin"><ul id="'+$ul_id+'"></ul></div>');
	if($forPage==1){
		$("#"+$ul_id).append('<li class="disabled"><a>首页</a></li>');
	}else{
		$("#"+$ul_id).append('<li><a href="#">首页</a></li>');
	}
	if($page==1){
		$("#"+$ul_id).append('<li class="disabled"><a>上一页</a></li>');
	}else{
		$("#"+$ul_id).append('<li><a href="#">上一页</a></li>');
	}
	for(i=$forPage;i<=$perNumber;i++){
		if(i==$page){
			$("#"+$ul_id).append('<li class="disabled"><a>'+i+'</a></li>');
		}else{
			$("#"+$ul_id).append('<li><a href="#">'+i+'</a></li>');
		}
	}
	if($page==$totalPage){
		$("#"+$ul_id).append('<li class="disabled"><a>下一页</a></li>');
	}else{
		$("#"+$ul_id).append('<li><a href="#">下一页</a></li>');
	}
	
	$aaa = parseInt(($totalPage+1)/$perNumber)-1;
	$bbb = parseInt(($page+1)/$perNumber)-1;
	if($aaa==$bbb){
		$("#"+$ul_id).append('<li class="disabled"><a>尾页</a></li>');
	}else{
		$("#"+$ul_id).append('<li><a href="#">尾页</a></li>');
	}
});