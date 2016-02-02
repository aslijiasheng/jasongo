<div id='{#obj_name#}_list_edit' style="overflow-x:auto;">
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th width="60">
				<a id="{#obj_name#}_detailed_add" title="添加明细"><span class="icon-plus"></span></a>
			</th>
			{#foreach from=edit_layout.layout_arr item=ell key=ell_k#}
			<th><?php echo $labels['{#obj_name#}_{#ell|attr_name#}'];?></th>
			{#/foreach#}
		</tr>
	</thead>
	<tbody>
	<?php foreach($listData as $listData_value){?>
		<tr>
			<td style="font-size:16px;">
				<a><span class="icon-remove-2"></span></a>
			</td>
			{#foreach from=edit_layout.layout_arr item=lll key=lll_k#}
			<td >
			{#include views/attrtype/edit/{#lll|attr_id_arr.attr_type_arr.attrtype_edit_template#}#}
			</td>
			{#/foreach#}
		</tr>
	<?php }?>
	</tbody>
</table>
</div>
<script type="text/javascript">
//Data Tables
$(document).ready(function () {
	//把列表的内容信息全部组成数组
	$attrArr = [
	{#foreach from=edit_layout.layout_arr item=ell key=ell_k#}
	{
		"obj_name":"{#obj_name#}"
		"attr_name": "{#ell|attr_id_arr.attr_name#}",
		"attr_label": "{#ell|attr_id_arr.attr_label#}",
		"attr_field_name": "{#ell|attr_id_arr.attr_field_name#}",
		"attr_type": "{#ell|attr_id_arr.attr_type#}",
		"attr_type_attr":{
			{#if {#ell|attr_id_arr.attr_type#}==19#}
			"quote_label":"{#ell|attr_id_arr.attr_quote_id_arr.obj_label#}",
			"url":"<?php echo site_url('www/{#ell|attr_id_arr.attr_quote_id_arr.obj_name#}/ajax_list'); ?>",
			{#/if#}
		}
	},
	{#/foreach#}
	];
	$('#{#obj_name#}_list_edit').leeDetailedEdit({
		"addId": "{#obj_name#}_detailed_add",
		"attrArr":$attrArr
	});
});
</script>

