<div class="all-sidebar">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget">
				<div class="widget-header">
					<div class="title">
						<?php if(isset($_GET['type_id'])){
							if(isset($type_arr[$_GET['type_id']]['type_name'])){
								echo $type_arr[$_GET['type_id']]['type_name'];
							}else{
								echo '{#obj_label#}';
							}
						}else{
							echo '{#obj_label#}';
						}
						?>
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

						</div>
						<div id="divmessagelist">

<div class="grid-view">
	<div id="{#obj_name#}_list"></div>
<div>
<script type="text/javascript">
$(document).ready(function () {
	$selectAttr=[
		{#foreach from=list_layout.layout_arr item=lll key=lll_k#}
		{'value':'{#lll|attr_id_arr.attr_name#}','txt':'{#lll|attr_id_arr.attr_label#}'},
		{#/foreach#}
	];
	
	$("#{#obj_name#}_list").leeDataTable({
		selectAttr:$selectAttr, //简单查询的查询属性
		url:"<?php echo site_url('www/{#obj_name#}/ajax_select_quote?hidden_v='.$_GET['hidden_v']); ?>", //ajax查询的地址
		perNumber:5, //每页显示多少条数据
		
	});
});

</script>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

