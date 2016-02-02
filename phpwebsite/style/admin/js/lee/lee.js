/**********************************************************************************************************/
//cus lee 封装引用的dialog组件，用于触发（这个版本是没有参数的） start
$.fn.leeQuote = function($data){
	if(!$data){
		dialogwidth = $(this).attr('dialogwidth')//获取一下对应的宽度
		if(dialogwidth==""){
			dialogwidth=800;
		}
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
		$('#'+div_id).remove();//如果前面生成过div_id 需要把这个删掉
		$(this).after("<div id=\""+div_id+"\" style=\"display:none;\"><div id=\""+html_id+"\"></div></div>");
		$(this).after("<span id='"+btn_id+"' class='add-on btn' leetype='dialog_btn'><span data-icon='&#xe07f'></span></span>");
		//添加一个隐藏的输入框
		$(this).after('<input type="hidden" name="'+input_name+'" id="'+hidden_id+'" value="'+input_value_id+'">');

		//修改原来的name变成新name
		$(this).attr("name",new_input_name);
		//将他们框起来
		$("#"+span_id+",#"+input_id+",#"+btn_id).wrapAll("<div class='input-prepend input-append' style='width: 194px;'></div>");
		$("#"+btn_id).click(function(){

			btn_id = $(this).attr("id");
			input_id = btn_id.replace("_btn", "");
			html_id = input_id+"_dialog_html";
			url = $("#"+input_id).attr("url");
			//alert(url);
			hidden_id = input_id+"_hidden";
			//alert(input_id);
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
			title = $("#"+input_id).attr("title");
			div_id = input_id+"_dialog_div";
			$("#"+div_id).dialog({
				title:title,
				modal: true,
				width:dialogwidth,
				height:450,
				buttons: [{
					text:"确定",
					Class:"btn btn-primary",
					click: function(){
						radio = $(this).find('input[name=optionsRadios]:checked');
						radio_val = radio.val();
						radio_name = radio.attr("name_val");
						//赋值
						//alert(input_id);
						$("#"+input_id).val(radio_name);
						$("#"+hidden_id).val(radio_val);
						//最后关闭当前dialog
						$(this).dialog("close");
					}
				}]
			});
			$('#'+div_id).dialog('open');
		})
	}else{
		id = $(this).attr("id");
		if($data.url){
			$(this).attr("url",$data.url);
		}
		if($data.title){
			$(this).attr("title",$data.title);
		}
		input_id = $(this).attr("id");
		if($data.url){
			url=$data.url;
		}else{
			url = $(this).attr("url"); //取url参数，用于调用什么页面
		}
		//postdata = $data.data;
		if($data.data){
			$(this).attr("postdata",JSON.stringify($data.data));
		}
		//dump_obj(postdata);
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
		$('#'+div_id).remove();//如果前面生成过div_id 需要把这个删掉
		$(this).after("<div id=\""+div_id+"\" style=\"display:none;\"><div id=\""+html_id+"\"></div></div>");
		$(this).after("<span id='"+btn_id+"' class='add-on btn' leetype='dialog_btn'><span data-icon='&#xe07f'></span></span>");
		//添加一个隐藏的输入框
		$(this).after('<input type="hidden" name="'+input_name+'" id="'+hidden_id+'" value="'+input_value_id+'">');

		//修改原来的name变成新name
		$(this).attr("name",new_input_name);
		//将他们框起来
		$("#"+span_id+",#"+input_id+",#"+btn_id).wrapAll("<div class='input-prepend input-append' style='width: 194px;'></div>");
		$("#"+btn_id).click(function(){
			btn_id = $(this).attr("id");
			input_id = btn_id.replace("_btn", "");
			html_id = input_id+"_dialog_html";
			url = $("#"+input_id).attr("url");
			//alert(url);
			hidden_id = input_id+"_hidden";
			hidden_v = $("#"+hidden_id).val();
			//alert(input_id);
			if($("#"+input_id).attr("postdata")){
				postdata = JSON.parse($("#"+input_id).attr("postdata"));
			}else{
				postdata = "";
			}

			//postdata.hidden_v = hidden_v;
			//alert(postdata);
			$.ajax({
				'type':'post',
				'data':postdata,
				'success':function(data){
					$('#'+html_id).html(data);
				},
				'url':url+"&hidden_v="+hidden_v,
				'cache':false
			});
			title = $("#"+input_id).attr("title");
			div_id = input_id+"_dialog_div";
			$("#"+div_id).dialog({
				title:title,
				modal: true,
				width:800,
				height:450,
				buttons: [{
					text:"确定",
					Class:"btn btn-primary",
					click: function(){
						radio = $(this).find('input[name=optionsRadios]:checked');
						radio_val = radio.val();
						radio_name = radio.attr("name_val");
						//赋值
						//alert(input_id);
						$("#"+input_id).val(radio_name);
						$("#"+hidden_id).val(radio_val);
						//最后关闭当前dialog
						$(this).dialog("close");
					}
				}]
			});
			$('#'+div_id).dialog('open');
		})
	}
}
//cus lee 封装引用的dialog组件 end
/**********************************************************************************************************/

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
	$('#'+div_id).remove();//如果前面生成过div_id 需要把这个删掉
	$(this).after("<div id=\""+div_id+"\" style=\"display:none;\"><div id=\""+html_id+"\"></div></div>");
	$(this).after("<span id='"+btn_id+"' class='add-on btn' leetype='dialog_btn'><span data-icon='&#xe07f'></span></span>");
	//添加一个隐藏的输入框
	$(this).after('<input type="hidden" name="'+input_name+'" id="'+hidden_id+'" value="'+input_value_id+'">');

	//修改原来的name变成新name
	$(this).attr("name",new_input_name);
	//将他们框起来
	$("#"+span_id+",#"+input_id+",#"+btn_id).wrapAll("<div class='input-prepend input-append' style='width: 194px;'></div>");
	$("#"+btn_id).click(function(){
		btn_id = $(this).attr("id");
		input_id = btn_id.replace("_btn", "");
		html_id = input_id+"_dialog_html";
		url = $("#"+input_id).attr("url");
		hidden_id = input_id+"_hidden";
		//alert(input_id);
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
		title = $("#"+input_id).attr("title");
		div_id = input_id+"_dialog_div";
		$("#"+div_id).dialog({
			title:title,
			modal: true,
			width:800,
			height:450,
			buttons: [{
				text:"确定",
				Class:"btn btn-primary",
				click: function(){
					radio = $(this).find('input[name=optionsRadios]:checked');
					radio_val = radio.val();
					radio_name = radio.attr("name_val");
					//赋值
					//alert(input_id);
					$("#"+input_id).val(radio_name);
					$("#"+hidden_id).val(radio_val);
					//最后关闭当前dialog
					$(this).dialog("close");
				}
			}]
		});
		$('#'+div_id).dialog('open');
	})
});
//cus lee 首先是初始化所有添加dialog的页面 end

//cus lee 按钮触发ajax形成dialog start
/*
$("[leetype='dialog_btn']").click(function(){
	alert(111);
	btn_id = $(this).attr("id");
	alert('btn_id');
	input_id = btn_id.replace("_btn", "");
	url = $("#"+input_id).attr('url');
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
*/
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
		modal: true,
		width:800,
		height:450
	});
	$('#'+div_id).dialog('open');
});




/*
//cus lee 分页组件 start
//初始化
function Initialization_page(){
	$("[leetype='pagination']").each(function(){
		$(this).hide(); //本身这个div隐藏掉
		$tid = $(this).attr('id'); //获取ID
		$url = $(this).attr('url'); //获取url
		$ul_id = $tid+'_ul'; //首页的ID
		$perNumber=$(this).attr('perNumber'); //每页显示的记录数
		$page=$(this).attr('page'); //获得当前的页面值
		//$page=10; //获得当前的页面值
		if(!$page || $page<1){
			$page=1;
		}
		$totalNumber=$(this).attr('totalNumber'); //数据总数
		$totalPage=parseInt(($totalNumber-1)/$perNumber)+1; //总页数
		$forPageStart=(parseInt(($page-1)/$perNumber))*$perNumber+1; //循环从那页开始
		$forPageEnd=Number((parseInt(($page-1)/$perNumber))*$perNumber)+Number($perNumber); //循环从那页结束
		if ($forPageEnd>$totalPage){
			$forPageEnd = $totalPage; //如果循环结束页比总页数大！则循环到总页数为止
		}
		//$endPage=11;
		//alert($totalPage);
		//在下面生成想要的代码
		$(this).after('<div class="pagination no-margin"><ul id="'+$ul_id+'"></ul></div>');
		if($page==1){
			$("#"+$ul_id).append('<li class="disabled"><a>首页</a></li>');
			$("#"+$ul_id).append('<li class="disabled"><a>上一页</a></li>');
		}else{
			$("#"+$ul_id).append('<li class="hidden-phone"><a onclick="selectPage(\''+$url+'\',\'1\');">首页</a></li>');
			$("#"+$ul_id).append('<li class="hidden-phone"><a onclick="selectPage(\''+$url+'\',\''+($page-1)+'\');">上一页</a></li>');
		}
		for(i=$forPageStart;i<=$forPageEnd;i++){
			if(i==$page){
				$("#"+$ul_id).append('<li class="disabled"><a>'+i+'</a></li>');
			}else{
				$("#"+$ul_id).append('<li class="hidden-phone"><a onclick="selectPage(\''+$url+'\',\''+i+'\');">'+i+'</a></li>');
			}
		}
		if($page==$totalPage){
			$("#"+$ul_id).append('<li class="disabled"><a>下一页</a></li>');
			$("#"+$ul_id).append('<li class="disabled"><a>尾页</a></li>');
		}else{
			$("#"+$ul_id).append('<li class="hidden-phone"><a onclick="selectPage(\''+$url+'\',\''+(Number($page)+1)+'\');">下一页</a></li>');
			$("#"+$ul_id).append('<li class="hidden-phone"><a onclick="selectPage(\''+$url+'\',\''+$totalPage+'\');">尾页</a></li>');
		}
	});
}
Initialization_page();//默认先初始化1次
$("#select_json").val("");//第一次初始化将这个清空
//cus lee 分页组件 end



//cus lee 高级查询组件 start
//初始化

//弹出dialog
$("[leetype='seniorquery']").click(function(){
	//alert('高级查询');
	$url = $(this).attr('url');
	$title = $(this).attr('title');
	if ($title=="" || $title==null){
		$title="高级查询";
	}
	$btn_id = $(this).attr("id");
	$div_id = $btn_id+"_dialog_div";
	//$("#department_seniorquery_dialog_div").html("1212121212");
	$("#"+$div_id).dialog({
		title:$title,
		width:800,
		height:450
	});
	$('#'+div_id).dialog('open');
});
//cus lee 高级查询组件 end


//cus lee 列表上的简单查询按钮 start
$('#select_btn').click(function(){
	$attr = $("[name='select[attr]']").val();//获取2个值
	$value = $("[name='select[value]']").val();//获取2个值
	url=$(this).attr("url");//获取当前按钮的url地址
	$thing = {attr: $attr, value: $value};
	$json = JSON.stringify($thing);
	//将这个值传递给隐藏输入框select_json
	$("#select_json").val($json);
	//alert($("#select_json").val());
	//ajax获取需要查询的列表
	$.ajax({
		'type':'post',
		'data':{
			'select_json':$json
		},
		'success':function(data){
			$('#list_all').html(data);
			//再次初始化1次分页
			Initialization_page();
		},
		'url':url,
		'cache':false
	});
});
//cus lee 列表上的简单查询按钮 end
*/

/**********************************************************************************************************/
//cus lee 封装好的带有普通查询以及分页的Table表格（通过ajax获取表格数据） start
//扩展jquery封装成插件
$.fn.leeDataTable = function($data){
	$id=$(this).attr("id");
	//初始化
	$jdcx = "jdcx"+$id;
	$fy = "fy"+$id;
	$("#"+$jdcx).detach();//删除以前简单查询
	$("#"+$fy).detach();//删除以前简单查询

	$url=$data.url;

	if (!$_GET['type_id'])
	{
		$type_id=0;
	}else{
		$type_id = $_GET['type_id'];
	}
	if($data.postdata){
		$postdata = $data.postdata;
	}else{
		$postdata = {};
	}
	if($data.where){
		$where = $data.where;
	}else{
		$where ="";
	}
	$postdata.where=$where;
	var $get_attr = GetUrlPars(); //获取url传递的参数
	$postdata.get_attr=$get_attr;
	//生成一个隐藏的输入框，用于存放查询出来具体有总数据量
	var $totalNumber;
	var $page=1; //当前的页码 初始化默认为1
	$postdata.page=$page; //当前页码
	var $json; //定义全局变量$json
	var $perNumber=$data.perNumber; //每页显示多少条数据
	$postdata.perNumber=$perNumber;
	var $obj=$data.obj; //所属对象
	$postdata.obj=$obj;
	var $where_rel=$data.where_rel; //查询的关联关系
	$postdata.where_rel=$where_rel;
	//首先根据URL加载table
	url=$url+'?perNumber='+$perNumber+'&page='+$page+'&type_id='+$type_id;
	//在$get_attr参数里添加$perNumber
	//给当前的页面添加一个URL用于刷新页面
	$("#"+$id).attr('url',url);
	//dump_obj($postdata);
	$.ajax({
		'type':'post',
		'data':$postdata,
		'success':function(data){
			$("#"+$id).html(data);
			//获取内容里的totalNumber
			$totalNumber = $("#"+$id).find("#totalNumber").val();
		},
		'url':url,
		'async':false
	});
	//在这个列表DIV上面加载一个普通查询
	$selectId=$id+"_select";
	$selectAttrId=$id+"_select_attr";
	$selectAttrName=$id+"_select_attr_name";
	$selectValueName=$id+"_select_value_name";
	$selectBtnId=$id+"_select_Btn";
	$selectJsonId=$id+"_select_json";//隐藏输入框存json

	$(this).before('<div id="'+$jdcx+'" class="pagination-right lee_input_combination"><ul id="'+$selectId+'"></ul></div>');
	$("#"+$selectId).append("<li><select id='"+$selectAttrId+"' style='width:155px;' name='"+$selectAttrName+"'></select></li>");
	//alert($data.selectAttr);
	//循环出查询条件
	$($data.selectAttr).each(function(index){
		$("#"+$selectAttrId).append('<option value="'+this.value+'"> '+this.txt+' </option>');
	});
	$("#"+$selectId).append("<li><input type='text' name='"+$selectValueName+"'></li>");
	$("#"+$selectId).append('<li><a class="btn" id="'+$selectBtnId+'" data-listid="'+$id+'"><i class="icon-search"></i> 查询</a></li>');
	$("#"+$selectId).append("<li><input type='hidden' id='"+$selectJsonId+"'></li>");
	//IdDataTableSelect($("#"+$select));

	//在列表DIV下面生成分页
	$pageUlId = $id+'_page_ul'; //ul的ID
	$(this).after('<div id="'+$fy+'" class="pagination no-margin"><ul id="'+$pageUlId+'"></ul></div>');
	//在下面创建一个隐藏输入框，用于存放当前页码

	//当前页码$page和数据总数$totalNumber最上面已经定义好了

	Initialization_page($perNumber,$page,$totalNumber,$pageUlId,$id);
	function Initialization_page($perNumber,$page,$totalNumber,$pageUlId,$id){
		//alert($perNumber+"-"+$page+"-"+$totalNumber+"-"+$pageUlId+"-"+$id);
		$perPage = 5;//多少页一组
		//首先是吧ul里的内容全部清空
		$("#"+$pageUlId).html("");
		$totalPage=Number(parseInt((Number($totalNumber)-1)/($perNumber))+1); //总页数
		$forPageStart=(parseInt(($page-1)/$perPage))*$perPage+1; //循环从那页开始
		$forPageEnd=Number((parseInt(($page-1)/$perPage))*$perPage)+Number($perPage); //循环从那页结束
		if ($forPageEnd>$totalPage){
			$forPageEnd = $totalPage; //如果循环结束页比总页数大！则循环到总页数为止
		}
		//在下面生成想要的代码
		if($page==1){
			$("#"+$pageUlId).append('<li class="disabled"><a>首页</a></li>');
			$("#"+$pageUlId).append('<li class="disabled"><a>上一页</a></li>');
		}else{
			$("#"+$pageUlId).append('<li class="hidden-phone"><a page="1">首页</a></li>');
			$("#"+$pageUlId).append('<li class="hidden-phone"><a page="'+(Number($page)-1)+'">上一页</a></li>');
		}
		for(i=$forPageStart;i<=$forPageEnd;i++){
			if(i==$page){
				$("#"+$pageUlId).append('<li class="disabled"><a>'+i+'</a></li>');
			}else{
				$("#"+$pageUlId).append('<li class="hidden-phone"><a page="'+i+'">'+i+'</a></li>');
			}
		}
		if($page==$totalPage){
			$("#"+$pageUlId).append('<li class="disabled"><a>下一页</a></li>');
			$("#"+$pageUlId).append('<li class="disabled"><a>尾页</a></li>');
		}else{
			$("#"+$pageUlId).append('<li class="hidden-phone"><a page="'+(Number($page)+1)+'">下一页</a></li>');
			$("#"+$pageUlId).append('<li class="hidden-phone"><a page="'+$totalPage+'">尾页</a></li>');
		}

		$("#"+$pageUlId).find('[class="hidden-phone"]').find('a').click(function(){

			$page = $(this).attr('page');

			url=$url+'?perNumber='+$perNumber+'&page='+$page+'&type_id='+$type_id;
			$("#"+$id).attr('url',url);

			if($postdata){
				$new_postdata=$postdata;
			}else{
				$new_postdata={};
			}
			$new_postdata.page=$page;
			$new_postdata.select_json=$json;
			$new_postdata.new_select_json=$json;
			$.ajax({
				'type':'post',
				'data':$new_postdata,
				'success':function(data){
					$('#'+$id).html(data);
					//再次初始化1次分页
					//Initialization_page();
					//aaa();
					Initialization_page($perNumber,$page,$totalNumber,$pageUlId,$id);
				},
				'url':url,
				'async':false
			});
		});
	}
	$('#'+$selectBtnId).click(function(){
		//这里的$id需要再次获取！不然会出问题
		var $id = $(this).attr("data-listid");
		$attr = $("[name='"+$selectAttrName+"']").val();//获取2个值
		$value = $("[name='"+$selectValueName+"']").val();//获取2个值
		if($value!=""){
			$thing = {attr: $attr, value: $value};
			$json = JSON.stringify($thing);
		}else{
			$json="";
		}
		$page=1;//这里只要是查询！都默认给第1页
		url=$url+'?perNumber='+$perNumber+'&page='+$page+'&type_id='+$type_id;
		$("#"+$id).attr('url',url);
		if($postdata){
			if($postdata!=""){
				$new_postdata=$postdata;
				$new_postdata.select_json=$json;
				//dump_obj($new_postdata);
			}else{
				$new_postdata={select_json:$json};
			}
		}else{
			$new_postdata={select_json:$json};
		}
		$new_postdata.page = 1;//这里只要是查询！都默认给第1页
		//dump_obj($new_postdata);
		$.ajax({
			'type':'post',
			'data':$new_postdata,
			'success':function(data){
				$('#'+$id).html(data);
				$totalNumber = $("#"+$id).find("#totalNumber").val();
				//再次初始化1次分页
				//Initialization_page();
				Initialization_page($perNumber,$page,$totalNumber,$pageUlId,$id);
			},
			'url':url,
			'async':false
		});
	});
}
//cus lee 封装好的带有普通查询以及分页的Table表格（通过ajax获取表格数据） end
/**********************************************************************************************************/
//cus lee 单独写个刷新分页的方法 start
$.fn.RefreshPage = function($data){
	//alert('处理分页');
	$perPage = 5;//多少页一组
	$perNumber=$data.perNumber; //多少数据分页1次
	$page=$data.page; //当前多少页
	$listId = $(this).attr('id'); //分页ul的id
	$listUrl = $data.listUrl; //刷新列表的查询页面
	$pageUlId = $listId+"_page_ul"; 
	//alert($listUrl);
	$totalNumber = $data.totalNumber; //数据总数
	$sel_data = $data.sel_data; //查询内容
	//alert($perNumber+"-"+$page+"-"+$totalNumber+"-"+$pageUlId);
	//首先是吧ul里的内容全部清空
	$("#"+$pageUlId).html("");
	$totalPage=Number(parseInt((Number($totalNumber)-1)/($perNumber))+1); //总页数
	$forPageStart=(parseInt(($page-1)/$perPage))*$perPage+1; //循环从那页开始
	$forPageEnd=Number((parseInt(($page-1)/$perPage))*$perPage)+Number($perPage); //循环从那页结束
	if ($forPageEnd>$totalPage){
		$forPageEnd = $totalPage; //如果循环结束页比总页数大！则循环到总页数为止
	}
	//在下面生成想要的代码
	if($page==1){
		$("#"+$pageUlId).append('<li class="disabled"><a>首页</a></li>');
		$("#"+$pageUlId).append('<li class="disabled"><a>上一页</a></li>');
	}else{
		$("#"+$pageUlId).append('<li class="hidden-phone"><a page="1">首页</a></li>');
		$("#"+$pageUlId).append('<li class="hidden-phone"><a page="'+(Number($page)-1)+'">上一页</a></li>');
	}
	for(i=$forPageStart;i<=$forPageEnd;i++){
		if(i==$page){
			$("#"+$pageUlId).append('<li class="disabled"><a>'+i+'</a></li>');
		}else{
			$("#"+$pageUlId).append('<li class="hidden-phone"><a page="'+i+'">'+i+'</a></li>');
		}
	}
	if($page==$totalPage){
		$("#"+$pageUlId).append('<li class="disabled"><a>下一页</a></li>');
		$("#"+$pageUlId).append('<li class="disabled"><a>尾页</a></li>');
	}else{
		$("#"+$pageUlId).append('<li class="hidden-phone"><a page="'+(Number($page)+1)+'">下一页</a></li>');
		$("#"+$pageUlId).append('<li class="hidden-phone"><a page="'+$totalPage+'">尾页</a></li>');
	}
	//然后所有分页按钮的查询方法还需要设置
	$("#"+$pageUlId).find('[class="hidden-phone"]').find('a').click(function(){
		$page = $(this).attr('page');
		$sel_data.page = $page;
		$("#"+$listId).ajaxHtml({
			url:$listUrl,
			data:$sel_data,
		});
	});

}
//cus lee 单独写个刷新分页的方法 end
/**********************************************************************************************************/
//cus lee 封装明细编辑的leeDetailedEdit组件 start
$.fn.leeDetailedEdit = function($data){
	//首先定位一些需要用到的参数
	var $addId=$data.addId; //新增明细按钮的ID名称
	var $id = $(this).attr('id');
	var $SerialNumberMax=1;//已有多少个
	$attrArr = $data.attrArr;
	//初始化


	//新增明细按钮
	$(this).find('#'+$addId).click(function(){
		$tbody = $("#"+$id).find("tbody");//定位到该组件下的tbody
		$tbody.append("<tr SerialNumber='"+$SerialNumberMax+"'></tr>");//给tbody里添加内容
		//alert($SerialNumberMax);
		$dal_btn_id = "del_btn_"+$SerialNumberMax;
		$("[SerialNumber='"+$SerialNumberMax+"']").append('<td style="font-size:16px;"><a><span id="'+$dal_btn_id+'" class="icon-remove-2"></span></a></td>')
		//这里需要循环生成所有控件
		//alert($attrArr.length);
		for (var i=0;i<$attrArr.length;i++){

			$attr_name = $attrArr[i].attr_name;
			$attr_type = $attrArr[i].attr_type;
			$id2=$attr_name+"_"+$attr_type+"_"+$SerialNumberMax;

			var $html='';
			//引用类型
			if($attr_type==19){

				$title = "引用"+$attrArr[i].attr_type_attr.quote_label;
				$btn_id = $id2+"_btn";
				$input_id = $id2;
				$div_id = $id2+"_dialog_div";
				$dialog_html_id = $id2+"_dialog_html";
				$url = $attrArr[i].attr_type_attr.url;
				$hidden_id = $id2+"_hidden";
				$input_name = $attr_name; //取name参数，传递给隐藏字段

				$html += '<div class="input-prepend input-append">';
				$html += '<input id="'+$input_id+'" type="text" value="" value_id="" url="'+$url+'">';
				$html += '<span id="'+$btn_id+'" class="add-on btn"><span data-icon="&#xe07f"></span></span>';
				$html += '</div>';

				$html += '<input type="hidden" name="'+$input_name+'" id="'+$hidden_id+'" value="">';

				$html += '<div id="'+$div_id+'" style="display:none;"><div id="'+$dialog_html_id+'"></div></div>'

				$("[SerialNumber='"+$SerialNumberMax+"']").append('<td>'+$html+'</td>');

				//给这个引用按钮添加dialog
				$("#"+$btn_id).click(function(){
					btn_id = $(this).attr("id");
					ajax_dialog(btn_id);
				});

			}else{
				$("[SerialNumber='"+$SerialNumberMax+"']").append('<td><input type="text" name="order_d['+$attr_name+']"></td>');
			}

			//$("[SerialNumber='"+$SerialNumberMax+"']").append('<td><input type="text" name="order_d[aaa]"></td>');
		}
		//循环结束
		//初始化删除按钮功能
		$('#'+$dal_btn_id).d_del();
		$SerialNumberMax++;
	});

	//初始化删除按钮功能
	$('[class="icon-remove-2"]').d_del();
}

//cus lee 封装明细编辑的leeDetailedEdit组件 end
/**********************************************************************************************************/
//初始化删除按钮
$.fn.d_del =(function(){
	$(this).click(function(){
		//alert(111);
		//alert($(this).html());
		$(this).closest("tr").remove();
	});
});

function ajax_dialog(btn_id){
	input_id = btn_id.replace("_btn", "");
	html_id = input_id+"_dialog_html";
	url = $("#"+input_id).attr("url");
	hidden_id = input_id+"_hidden";
	//alert(input_id);
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
	title = $("#"+input_id).attr("title");
	div_id = input_id+"_dialog_div";
	$("#"+div_id).dialog({
		title:title,
		width:800,
		height:450,
		buttons: [{
			text:"确定",
			Class:"btn btn-primary",
			click: function(){
				radio = $(this).find('input[name=optionsRadios]:checked');
				radio_val = radio.val();
				radio_name = radio.attr("name_val");
				//赋值
				//alert(input_id);
				$("#"+input_id).val(radio_name);
				$("#"+hidden_id).val(radio_val);
				//最后关闭当前dialog
				$(this).dialog("close");
			}
		}]
	});
	$('#'+div_id).dialog('open');
}

/**********************************************************************************************************/
//ajaxHtml控件！专门通过ajax获取某个位子的内容
$.fn.ajaxHtml = function($data){
	$this=$(this);
	if($data){
		url=$data.url;
		ajax_data=$data.data;
		$.ajax({
			'type':'post',
			'data':ajax_data,
			'success':function(data){
				$this.html(data);
			},
			'url':url,
			'async':false
		});
	}else{
		url=$this.attr('url');
		$.ajax({
			'type':'post',
			'success':function(data){
				$this.html(data);
			},
			'url':url,
			'async':false
		});
	}
}


/**********************************************************************************************************/
//写一个通用的方法，用来判断所有的类型的格式是否正确
function leeConditionJudgment(formdata){
	//alert('ok');
	//遍历formdata
	aaa = "";
	$.each(formdata,function(i,val){
		$name = val.name;

		$value = val.value;
		//alert($name+'--'+$value);
		//首先判断是否必填
		if($('[name="'+$name+'"]').is('[required="required"]')){

			if(!$value){

				//获取他对应的标题
				$attrid = $('[name="'+$name+'"]').attr('id');
				$name2 = $.trim($('[for="'+$attrid+'"]').html());
				if ($name2==""){
					$name2 = $(this).attr('title');
					//alert($name2);
				}
				//alertify.alert('【'+$name+'】不能为空');
				aaa = 1;
				return false;
			}
		}
	});
	if(aaa==1){
		alertify.alert('【'+$name2+'】不能为空');
		return false;
	}else{
		return true;
	}
};
/**********************************************************************************************************/
//封装的高级查询组件 2014-04-11 start
$.fn.seniorquery = function($data){
	$id = $(this).attr("id");//获取本身的ID名称，用于命名
	//首先新建一个空的div用于创建dialog
	$id_dialog_div = $id+"_dialog_div";
	$id_form = $id+"_form"; //form的ID
	$id_conditions = $id+'_conditions'; //条件部分的ID
	$id_rel = $id+'_rel'; //条件关系部分的ID （放 1 and 2 的地方）

	$seniorquery_attr = $data.selectAttr;
	$url = $data.url;
	$list_id = $data.list_id;
	$obj = $data.obj;
	$perNumber = $data.perNumber; //每页显示多少条数据

	var $get_attr = GetUrlPars(); //获取url传递的参数
	var $line = 0; //用户计算行数
	//先把里面的内容删除掉！防止重复弹出
	$("#"+$id_dialog_div).remove();
	$(this).after('<div id="'+$id_dialog_div+'" style="display:none;"></div>');
	//在这里需要考虑2个方案，方案1 通过ajax获取页面 方案2 直接生成页面
	//先写成方案2吧！直接生成页面
	$("#"+$id_dialog_div).append(''+
		'<div style="padding:20px;">'+
			'<ul>'+
				'<li>在下面的行中设定属性的限制条件</li>'+
				'<li>查询条件会在下面的文本区域中组合</li>'+
				'<li>你可以改变条件的组合方式(如 1 or (2 and 3))</li>'+
			'</ul>'+
			'<br>'+
			'<form class="form-horizontal no-margin" id="'+$id_form+'">'+
				'<table cellspacing="0">'+
					'<tbody id="'+$id_conditions+'">'+
					'</tbody>'+
				'</table>'+
				'<div style="padding:10px 0;">'+
					'<a class="add_seniorquery_line"><span class="label label-info"> 添加行 </span></a>'+
					' <a class="del_seniorquery_line"><span class="label label-info"> 删除行 </span></a>'+
				'</div>'+
				'<textarea id="'+$id_rel+'" class="span8" name="seniorquery[rel]" rows="3"></textarea>'+
			'</form>'+
		'</div>'+
	'');
	$(this).click(function(){
		$("#"+$id_dialog_div).dialog({
			title:"高级查询",
			modal: true,
			width:800,
			height:450,
			buttons: [{
				text: "查询",
				Class: "btn bottom-margin btn-primary",
				click: function() {
					//这里的$url需要重新获取！不然和外面的全局变量会有冲突
					var $url = $data.url;
					//alert($url);
					//alert('提交');
					//将条件附上再次加载1次列表页面
					$formdata = $("#"+$id_form).serializeArray();
					$formdata[$formdata.length]={"name":"perNumber","value":$perNumber}; //添加1个参数perNumber
					//这里需要将$get_attr转换成form表单对象的格式
					for (var key in $get_attr){
						$formdata[$formdata.length]={"name":"get_attr["+key+"]","value":$get_attr[key]};
					}
					//$data = {'where':$formdata};
					//alert_r($formdata);
					//alert($list_id);
					$("#"+$list_id).ajaxHtml({
						url:$url,
						data:$formdata,
					});
					//这里需要使用的是列表的方法，不能直接用页面刷新的方法，不然分页无法显示
					/*
					$("#"+$list_id).leeDataTable({
						selectAttr:$selectAttr, //简单查询的查询属性
						url:$url, //ajax查询的地址
						perNumber:$perNumber, //每页显示多少条数据
						obj:$obj,
						//where:'',
						//where_rel:'',
					});
					*/
					$(this).dialog("close");
				}
			},{
				text: "取消",
				Class: "btn bottom-margin btn-danger ",
				click: function() {
					alertify.error("你取消了高级查询");
					$(this).dialog("close");
				}
			}]
		});
		$('#'+$id_dialog_div).dialog('open');
	});

	function add_seniorquery_line($line){
		$line++;

		$("#"+$id_conditions).append(''+
			'<tr class="seniorquery_line" id="line_'+$line+'" data-line="'+$line+'">'+
				'<td>'+
					'<span class="badge badge-info">'+$line+'</span>'+
				'</td>'+
				'<td>'+
					'<select class="span2 input-left-top-margins" id="attr_'+$line+'" data-line="'+$line+'" name="seniorquery[where]['+$line+'][attr]">'+
						'<option value="0"> --选择属性-- </option>'+
					'</select>'+
				'</td>'+
				'<td>'+
					'<select class="span2 input-left-top-margins" id="oper_'+$line+'" data-line="'+$line+'" name="seniorquery[where]['+$line+'][action]">'+
						'<option value="1"> --请先选属性-- </option>'+
					'</select>'+
				'</td>'+
				'<td>'+
					'<div id="content_'+$line+'">'+
						'<input type="text" class="input-left-top-margins" readonly="readonly">'+
					'</div>'+
				'</td>'+
			'</tr>'+
		'');
		//循环出所有属性
		$.each($seniorquery_attr,function(i,val){
			$("#attr_"+$line).append('<option value="'+val.name+'"> '+val.label+' </option>');
		});


		//每次选择属性都需要处理一下
		$("#attr_"+$line).change(function(){
			combination_rel();//改变rel
			//接下来根据不同的属性类型处理
			$attr_name = $(this).val();
			$this_line = $(this).attr("data-line");
			//alert($seniorquery_attr);
			//循环出这个属性对应的对象（用于获取相关的值）
			$.each($seniorquery_attr,function(i,val){
				if(val.name==$attr_name){
					$attr_obj = val;
				}
			});
			if($attr_name=='0'){
				$attr_obj.attrtype='0';
			}
			switch($attr_obj.attrtype){
				case '19':
					//这里代表引用类型
					//先写oper操作类型
					//alert('引用类型');
					$("#oper_"+$this_line).html(''+
						'<option value="EQUAL"> 等于 </option>'+
						'<option value="NOT_EQUAL"> 不等于 </option>'+
						'<option value="NULL"> 为空 </option>'+
						'<option value="NOT_NULL"> 不为空 </option>'+
					'');
					//content内容部分
					$("#content_"+$this_line).html(''+
						'<input type="text" class="input-left-top-margins" id="input_'+$this_line+'" name="seniorquery[where]['+$this_line+'][value]">'+
					'');

					//给这个input转化成引用格式
					$("#input_"+$this_line).leeQuote({
						url:$attr_obj.sub_attr.url+"?tag_name="+$this_line+"_"+$attr_obj.name+"_quote_"+$attr_obj.sub_attr.quote_name,
						title:"引用"+$attr_obj.sub_attr.title,
					});
					break;
				case '17':
					//这里处理日期类型
					$("#oper_"+$this_line).html(''+
						'<option value="AFTER"> 晚于 </option>'+
						'<option value="BEFORE"> 早于 </option>'+
						'<option value="RANGE"> 区间 </option>'+
						'<option value="RECENT"> 最近 </option>'+
						'<option value="FUTURE"> 未来 </option>'+
						'<option value="NULL"> 为空 </option>'+
						'<option value="NOT_NULL"> 不为空 </option>'+
					'');
					$("#content_"+$this_line).html(''+
						'<div class="input-append date form_date" id="time_'+$this_line+'">'+
							'<input size="16" type="text" name="seniorquery[where]['+$this_line+'][value]" style="width: 154px;" readonly>'+
							'<span class="add-on"><i class="icon-close"></i></span>'+
							'<span class="add-on"><i class="icon-clock"></i></span>'+
						'</div>'+
					'');
					//添加时间控件
					$('#time_'+$this_line).datetimepicker({
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
					break;
				case '18':
					//这里处理时间类型
					$("#oper_"+$this_line).html(''+
						'<option value="AFTER"> 晚于 </option>'+
						'<option value="BEFORE"> 早于 </option>'+
						'<option value="RANGE"> 区间 </option>'+
						'<option value="RECENT"> 最近 </option>'+
						'<option value="FUTURE"> 未来 </option>'+
						'<option value="NULL"> 为空 </option>'+
						'<option value="NOT_NULL"> 不为空 </option>'+
					'');
					$("#content_"+$this_line).html(''+
						'<div class="input-append date form_date" id="time_'+$this_line+'">'+
							'<input size="16" type="text" name="seniorquery[where]['+$this_line+'][value]" style="width: 154px;" readonly>'+
							'<span class="add-on"><i class="icon-close"></i></span>'+
							'<span class="add-on"><i class="icon-clock"></i></span>'+
						'</div>'+
					'');
					//添加时间控件
					$('#time_'+$this_line).datetimepicker({
						language:  'zh-CN',
						weekStart: 1,
						todayBtn:  1,
						autoclose: 1,
						todayHighlight: 1,
						startView: 2,
						forceParse: 0,
						format:'yyyy-mm-dd hh:ii:00'
					});
					break;
				case '5'://数值
				case '7'://金额
					//这里是处理数值类型
					$("#oper_"+$this_line).html(''+
						'<option value="EQUAL"> = </option>'+
						'<option value="GT"> > </option>'+
						'<option value="GT_EQUAL"> >= </option>'+
						'<option value="LE"> < </option>'+
						'<option value="LE_EQUAL"> <= </option>'+
						'<option value="NOT_EQUAL"> <> </option>'+
						'<option value="RANGE"> 区间 </option>'+
					'');
					$("#content_"+$this_line).html(''+
						'<input type="text" class="input-left-top-margins" name="seniorquery[where]['+$this_line+'][value]">'+
					'');
					break;
				case '14'://单选
				case '15'://下拉单选
					$("#oper_"+$this_line).html(''+
						'<option value="EQUAL"> 等于 </option>'+
						'<option value="NOT_EQUAL"> 不等于 </option>'+
						'<option value="NULL"> 为空 </option>'+
						'<option value="NOT_NULL"> 不为空 </option>'+
					'');
					$("#content_"+$this_line).html(''+
						'<select id="enum_'+$this_line+'" data-line="'+$this_line+'" name="seniorquery[where]['+$this_line+'][value]">'+
						'</select>'+
					'');
					//给下拉框加上枚举
					$.each($attr_obj.sub_attr.enum,function(i,val){
						$("#enum_"+$this_line).append('<option value="'+val.enum_key+'"> '+val.enum_name+' </option>');
					});
					break;
				case '0':
					//alert(0);
					//这里代表没有选属性
					//先写oper操作类型
					$("#oper_"+$this_line).html(''+
						'<option value="1"> --请先选属性-- </option>'+
					'');
					//content内容部分默认就直接一个文本输入框吧
					$("#content_"+$this_line).html(''+
						'<input type="text" class="input-left-top-margins" readonly="readonly">'+
					'');
					break;
				default:
					//不是上面哪几种类型的统一格式
					//先写oper操作类型
					$("#oper_"+$this_line).html(''+
						'<option value="LIKE"> 包含 </option>'+
						'<option value="NOT_LIKE"> 不包含 </option>'+
						'<option value="EQUAL"> 等于 </option>'+
						'<option value="NOT_EQUAL"> 不等于 </option>'+
						'<option value="NULL"> 为空 </option>'+
						'<option value="NOT_NULL"> 不为空 </option>'+
					'');
					//content内容部分默认就直接一个文本输入框吧
					$("#content_"+$this_line).html(''+
						'<input type="text" class="input-left-top-margins" name="seniorquery[where]['+$this_line+'][value]">'+
					'');
			}
		});

		//每次选择操作类型都需要处理一下
		$("#oper_"+$line).change(function(){
			$oper_type = $(this).val();
			$this_line = $(this).attr("data-line");
			//这里还需要获取相对于的属性名称
			$attr_name = $("#attr_"+$this_line).val();
			//alert($oper_type+"-"+$this_line);
			//循环出这个属性对应的对象（用于获取相关的值）
			$.each($seniorquery_attr,function(i,val){
				if(val.name==$attr_name){
					$attr_obj = val;
				}
			});
			switch($oper_type){
				case 'NULL': //为空
				case 'NOT_NULL': //不为空
					$("#content_"+$this_line).html(''+
						'<input type="text" class="input-left-top-margins" readonly="readonly">'+
					'');
					break;
				case 'EQUAL': //等于
				case 'NOT_EQUAL': //不等于
					//这里需要判断属性类型，引用的效果和其他的不一样
					if($attr_obj.attrtype=='19'){
						$("#content_"+$this_line).html(''+
							'<input type="text" class="input-left-top-margins" id="input_'+$this_line+'" name="seniorquery[where]['+$this_line+'][value]">'+
						'');
						//给这个input转化成引用格式
						$("#input_"+$this_line).leeQuote({
							url:$attr_obj.sub_attr.url+"?tag_name="+$this_line+"_"+$attr_obj.name+"_quote_"+$attr_obj.sub_attr.quote_name,
							title:"引用"+$attr_obj.sub_attr.title,
						});
					}else if($attr_obj.attrtype=='14' || $attr_obj.attrtype=='15'){
						$("#content_"+$this_line).html(''+
							'<select id="enum_'+$this_line+'" data-line="'+$this_line+'" name="seniorquery[where]['+$this_line+'][value]">'+
							'</select>'+
						'');
						//给下拉框加上枚举
						$.each($attr_obj.sub_attr.enum,function(i,val){
							$("#enum_"+$this_line).append('<option value="'+val.enum_key+'"> '+val.enum_name+' </option>');
						});
					}else{
						//不是引用类型都用文本
						$("#content_"+$this_line).html(''+
							'<input type="text" class="input-left-top-margins" name="seniorquery[where]['+$this_line+'][value]">'+
						'');
					}
					break;
				case 'BEFORE': //早于
				case 'AFTER': //晚于
					$("#content_"+$this_line).html(''+
						'<div class="input-append date form_date" id="time_'+$this_line+'">'+
							'<input size="16" type="text" name="seniorquery[where]['+$this_line+'][value]" style="width: 154px;" readonly>'+
							'<span class="add-on"><i class="icon-close"></i></span>'+
							'<span class="add-on"><i class="icon-clock"></i></span>'+
						'</div>'+
					'');
					if($attr_obj.attrtype=='17'){
						//日期型的处理
						$('#time_'+$this_line).datetimepicker({
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
					}else if($attr_obj.attrtype=='18'){
						//时间型的处理
						$('#time_'+$this_line).datetimepicker({
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
					break;
				case 'RANGE': //区间
					if($attr_obj.attrtype=='5' || $attr_obj.attrtype=='7'){
						//时间或者日期类型
						$("#content_"+$this_line).html(''+
							'<div>'+
								'<input type="text" class="input-left-top-margins" name="seniorquery[where]['+$this_line+'][value1]">'+
							'</div>'+
							'<div>'+
								'<input type="text" class="input-left-top-margins" name="seniorquery[where]['+$this_line+'][value2]">'+
							'</div>'+
						'');
					}
					if($attr_obj.attrtype=='17'){
						//日期型的处理
						$("#content_"+$this_line).html(''+
							'<div class="input-append date form_date" id="time_'+$this_line+'_1">'+
								'<input size="16" type="text" name="seniorquery[where]['+$this_line+'][value1]" style="width: 154px;" readonly>'+
								'<span class="add-on"><i class="icon-close"></i></span>'+
								'<span class="add-on"><i class="icon-clock"></i></span>'+
							'</div>'+
							'<div class="input-append date form_date" id="time_'+$this_line+'_2">'+
								'<input size="16" type="text" name="seniorquery[where]['+$this_line+'][value2]" style="width: 154px;" readonly>'+
								'<span class="add-on"><i class="icon-close"></i></span>'+
								'<span class="add-on"><i class="icon-clock"></i></span>'+
							'</div>'+
						'');
						$('#time_'+$this_line+'_1').datetimepicker({
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
						$('#time_'+$this_line+'_2').datetimepicker({
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
					if($attr_obj.attrtype=='18'){
						//时间型的处理
						$("#content_"+$this_line).html(''+
							'<div class="input-append date form_date" id="time_'+$this_line+'_1">'+
								'<input size="16" type="text" name="seniorquery[where]['+$this_line+'][value1]" style="width: 154px;" readonly>'+
								'<span class="add-on"><i class="icon-close"></i></span>'+
								'<span class="add-on"><i class="icon-clock"></i></span>'+
							'</div>'+
							'<div class="input-append date form_date" id="time_'+$this_line+'_2">'+
								'<input size="16" type="text" name="seniorquery[where]['+$this_line+'][value2]" style="width: 154px;" readonly>'+
								'<span class="add-on"><i class="icon-close"></i></span>'+
								'<span class="add-on"><i class="icon-clock"></i></span>'+
							'</div>'+
						'');
						$('#time_'+$this_line+'_1').datetimepicker({
							language:  'zh-CN',
							weekStart: 1,
							todayBtn:  1,
							autoclose: 1,
							todayHighlight: 1,
							startView: 2,
							forceParse: 0,
							format:'yyyy-mm-dd hh:ii:00'
						});
						$('#time_'+$this_line+'_2').datetimepicker({
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
					break;
				case 'RECENT': //最近
				case 'FUTURE': //未来
					$("#content_"+$this_line).html(''+
						'<input type="text" class="input-left-top-margins" style="width:131px" name="seniorquery[where]['+$this_line+'][value1]">'+
						'<select class="input-left-top-margins" style="width:70px" id="oper_'+$line+'" data-line="'+$line+'" name="seniorquery[where]['+$line+'][value2]">'+
							'<option value="DAY"> 天 </option>'+
							'<option value="WEEK"> 周 </option>'+
							'<option value="MONTH"> 月 </option>'+
							'<option value="YEARS"> 年 </option>'+
						'</select>'+
					'');
					break;
				default:
					//alert('没有定义的类型');
					$("#content_"+$this_line).html(''+
						'<input type="text" class="input-left-top-margins" name="seniorquery[where]['+$this_line+'][value]">'+
					'');
			}
		});
		return $line;
	};

	function del_seniorquery_line($line){
		if($line==1){
			alert('最后1条不能再删了!');
		}else{
			$("#line_"+$line).remove();
			$line--;
			combination_rel($line);
		}
		return $line;
	};

	//写个方法用来组合rel条件（这个方案是直接每次条件不同清空从组条件）
	function combination_rel($new_line){
		if($new_line){
			$rel_val="";
			for(var i=1;i<=$new_line;i++){
				attr_val = $("#attr_"+i).val();
				if(attr_val!=0){
					if($rel_val==""){
						$rel_val = i;
					}else{
						$rel_val = $rel_val+" and "+i;
					}
				}
			}
			$('#'+$id_rel).val($rel_val);
		}else{
			$rel_val="";
			for(var i=1;i<=$line;i++){
				attr_val = $("#attr_"+i).val();
				if(attr_val!=0){
					if($rel_val==""){
						$rel_val = i;
					}else{
						$rel_val = $rel_val+" and "+i;
					}
				}
			}
			$('#'+$id_rel).val($rel_val);
		}
	};

	$(".add_seniorquery_line").click(function(){
		$line = add_seniorquery_line($line);

	});
	$(".del_seniorquery_line").click(function(){
		$line = del_seniorquery_line($line);
	});
	//默认最开始就添加2行
	$line = add_seniorquery_line($line);
	$line = add_seniorquery_line($line);
}
/**********************************************************************************************************/
/**********************************************************************************************************/
/**********************************************************************************************************/

//get参数转换成数组$_GET['']这种php的读取方式！
var $_GET = (function(){
	var url = window.document.location.href.toString();
	var u = url.split("?");
	if(typeof(u[1]) == "string"){
		u = u[1].split("&");
		var get = {};
		for(var i in u){
			var j = u[i].split("=");
			get[j[0]] = j[1];
		}
		return get;
	} else {
		return {};
	}
})();


//输出对象数组的方法
function dump_obj(myObject) {
	var s = "";
	for (var property in myObject) {
		s = s + "\n "+property +": " + myObject[property] ;
	}
	alert(s);
}
//上面这个方法记不住，改个名字
function alert_r(myObject) {
	var s = "";
	for (var property in myObject) {
		s = s + "\n "+property +": " + myObject[property] ;
	}
	alert(s);
}

//获取url里的get参数，转换成数组对象
function GetUrlPars(){
	var url=location.search;
	var theRequest = new Object();
	if(url.indexOf("?")!=-1){
		var str = url.substr(1);
		strs = str.split("&");
		for(var i=0;i<strs.length;i++)
		{
			var sTemp = strs[i].split("=");
			theRequest[sTemp[0]]=(sTemp[1]);
		}
	}
	return theRequest;
}