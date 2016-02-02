<div class="all-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<div class="title">
						{#obj_label#}
						<span class="mini-title">
						{#obj_name#}
						</span>
					</div>
					<span class="tools">
						<!-- 窗口按钮部门
						<a class="fs1" data-icon="&#xe090;" aria-hidden="true" data-original-title=""></a>
						-->
					</span>
				</div>
				<div class="widget-body">
					<div class="row-fluid">
						<div class="leebutton">
<!--lee 按钮部分 start-->
<a id="new{#obj_name#}" class="btn btn-primary" href="<?php echo site_url('www/{#obj_name#}/add')?>">
<i class="icon-file"></i>
新增{#obj_label#}
</a>
<!--lee 按钮部分 end-->
						</div>
						<div id="divmessagelist">
							<div>
<div id="objlist" class="grid-view">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th width="100">操作</th>
				{#foreach from=list_layout.layout_arr item=lll key=lll_k#}
				<th><?php echo $labels['{#obj_name#}_{#lll|attr_name#}'];?></th>
				{#/foreach#}
			</tr>
		</thead>
		<tbody>
		<?php foreach($listData as $listData_value){?>
			<tr>
				<td style="font-size:16px;">
					<a href="<?php echo site_url('www/{#obj_name#}/{#obj_name#}_view').'?{#obj_name#}_id='.$listData_value['{#obj_name#}_id'] ?>" rel="tooltip" title="查看"><span class="icon-file"></span></a>
				</td>
				{#foreach from=list_layout.layout_arr item=lll key=lll_k#}
				//*这里判断不同属性类型执行不同的*//
				<td>
				{#if {#lll|attr_id_arr.attr_type#}==19#}
				<?php 
					if ($listData_value['{#obj_name#}_{#lll|attr_name#}']!=0 and $listData_value['{#obj_name#}_{#lll|attr_name#}']!=""){
						echo $listData_value['{#obj_name#}_{#lll|attr_name#}_arr']['{#lll|attr_id_arr.attr_obj_id_arr.obj_name#}_name'];
					}else{
						echo "&nbsp";
					}
				?>
				{#else#}
				<?php echo $listData_value['{#obj_name#}_{#lll|attr_name#}'];?>
				{#/if#}
				</td>
				{#/foreach#}
				
			</tr>
		<?php }?>
		</tbody>
		
	</table>
	<div id="aaa" leetype="pagination" page="2" totalNumber="99" perNumber="10">分页</div>
	<!--
	<div class="pagination no-margin">
		<ul>
			<li class="disabled">
				<a>上一页</a>
			</li>
			<li class="active">
				<a>1</a>
			</li>
			<li class="hidden-phone">
				<a href="#" data-original-title="">2</a>
			</li>
			<li class="hidden-phone">
				<a href="#" data-original-title="">3</a>
			</li>
			<li class="hidden-phone">
				<a href="#" data-original-title="">4</a>
			</li>
			<li class="hidden-phone">
				<a href="#" data-original-title="">5</a>
			</li>
			<li class="hidden-phone">
				<a href="#" data-original-title="">6</a>
			</li>
			<li class="hidden-phone">
				<a href="#" data-original-title="">7</a>
			</li>
			<li class="hidden-phone">
				<a href="#" data-original-title="">8</a>
			</li>
			<li>
				<a href="#" data-original-title="">下一页</a>
			</li>
		</ul>
	</div>
	-->
</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
$(document).ready(function () {
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
		
		//$(this).after('</ul></div>');
	});
});
</script>