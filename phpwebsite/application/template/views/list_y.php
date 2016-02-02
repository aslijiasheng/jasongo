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
<a id="{#obj_name#}_seniorquery" class="btn btn-primary" url="<?php echo site_url('www/{#obj_name#}/seniorquery')?>" leetype='seniorquery'>
<i class="icon-search"></i>
高级查询
</a>
<input id="select_json" type="hidden">
<div id="{#obj_name#}_seniorquery_dialog_div" style="display:none;">
	<div style="padding:20px;">
		<li>在下面的行中设定属性的限制条件</li>
		<li>查询条件会在下面的文本区域中组合</li>
		<li>你可以改变条件的组合方式(如 1 or (2 and 3))</li>
		<br>
		<form class="form-horizontal no-margin">
			<div style="padding:5px 0;">
				<select id="aaa_1" class="span2">
					<option value="1"> 部门名称 </option>
					<option value="2"> 上次部门 </option>
					<option value="3"> 部门编码 </option>
				</select>
				<select id="DateOfBirthMonth"  class="span2 input-left-top-margins" >
					<option value="1"> 包含 </option>
					<option value="2"> 不包含 </option>
					<option value="3"> = </option>
					<option value="4"> 为空 </option>
					<option value="5"> 不为空 </option>
				</select>
				<input id="aaa" type="text" class="span4 input-left-top-margins" >
			</div>
			<div style="padding:5px 0;">
				<select id="DateOfBirthMonth" class="span2">
					<option value="1"> 部门名称 </option>
					<option value="2"> 上次部门 </option>
					<option value="3"> 部门编码 </option>
				</select>
				<select id="DateOfBirthMonth"  class="span2 input-left-top-margins" >
					<option value="1"> 包含 </option>
					<option value="2"> 不包含 </option>
					<option value="3"> = </option>
					<option value="4"> 为空 </option>
					<option value="5"> 不为空 </option>
				</select>
				<input id="aaa" type="text" class="span4 input-left-top-margins" >
			</div>
			<div style="padding:5px 0;">
				<select id="DateOfBirthMonth" class="span2">
					<option value="1"> 部门名称 </option>
					<option value="2"> 上次部门 </option>
					<option value="3"> 部门编码 </option>
				</select>
				<select id="DateOfBirthMonth"  class="span2 input-left-top-margins" >
					<option value="1"> 包含 </option>
					<option value="2"> 不包含 </option>
					<option value="3"> = </option>
					<option value="4"> 为空 </option>
					<option value="5"> 不为空 </option>
				</select>
				<input id="aaa" type="text" class="span4 input-left-top-margins" >
			</div>
			<div style="padding:5px 0;">添加行 删除行</div>
			<textarea id="description3" class="input-block-level span8" placeholder="Description" name="description3" rows="3"> </textarea>
		</form>
	</div>
</div>
<!--lee 按钮部分 end-->
						</div>
						<div id="divmessagelist">

<div class="grid-view">
	<div id="{#obj_name#}_list">
		{#foreach#}
<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th width="100">操作</th>
				
				<th><?php echo $labels['department_name'];?></th>
				
				<th><?php echo $labels['department_uid'];?></th>
				
				<th><?php echo $labels['department_code'];?></th>
				
				<th><?php echo $labels['department_treepath'];?></th>
				
				<th><?php echo $labels['department_treelevel'];?></th>
				
			</tr>
		</thead>
		<tbody>
		<?php foreach($listData as $listData_value){?>
			<tr>
				<td style="font-size:16px;">
					<a href="<?php echo site_url('www/department/department_view').'?department_id='.$listData_value['department_id'] ?>" rel="tooltip" title="查看"><span class="icon-file"></span></a>
				</td>
				
				
				<td>
				
				<?php echo $listData_value['department_name'];?>
				
				</td>
				
				
				<td>
				
				<?php 
					if ($listData_value['department_uid']!=0 and $listData_value['department_uid']!=""){
						echo $listData_value['department_uid_arr']['department_name'];
					}else{
						echo "&nbsp";
					}
				?>
				
				</td>
				
				
				<td>
				
				<?php echo $listData_value['department_code'];?>
				
				</td>
				
				
				<td>
				
				<?php echo $listData_value['department_treepath'];?>
				
				</td>
				
				
				<td>
				
				<?php echo $listData_value['department_treelevel'];?>
				
				</td>
				
				
			</tr>
		<?php }?>
		</tbody>
	</table>
		{#/foreach#}
	</div>
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
		url:"<?php echo site_url('www/{#obj_name#}/ajax_select'); ?>", //ajax查询的地址
		perNumber:5 //每页显示多少条数据
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

