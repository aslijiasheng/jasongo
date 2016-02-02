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
<a id="new{#obj_name#}" class="btn btn-primary" href="<?php if(!isset($_GET['type_id'])){echo site_url('www/{#obj_name#}');}else{echo site_url('www/{#obj_name#}?type_id='.$_GET['type_id']);}?>">
<i class="icon-file"></i>
返回
</a>
<!--lee 按钮部分 end-->
						</div>
						<div id="divmessagelist">
							<div>
<div id="objlist" class="grid-view">
<table class="leetable table table-bordered">
	<tbody>
			<?PHP $listData_value=$listLayout;?>
           
           <?php if({#is_obj_type#}==1){?>
           <?php if(!isset($_GET['type_id'])){ ?>
           {#foreach from=list_layout.layout_arr item=lll key=lll_k#}
             <tr>
				<td>
					<?php echo  $labels['{#lll|attr_id_arr.attr_field_name#}'];?>
				</td>
				<td>
				{#include views/attrtype/view/{#lll|attr_id_arr.attr_type_arr.attrtype_view_template#}#}
				</td>
			 </tr>
			 {#/foreach#}
           <?php }else{ ?>
						<?php switch ($_GET["type_id"]){ 
							case -1:
							break;
						?>
						{#foreach1 from=type_arr item=ta key=ta_k#}
						<?php case {#ta|type_id#}: ?>
						{#foreach2 from=list_layout.layout_arr item=lll key=lll_k#}
						<tr>
			
				//*这里判断不同属性类型执行不同的*//
								<td>
									<?php echo  $labels['{#lll|attr_id_arr.attr_field_name#}'];?>
								</td>
								<td>
								{#include views/attrtype/view/{#lll|attr_id_arr.attr_type_arr.attrtype_view_template#}#}
								</td>
				
		   				</tr>
						{#/foreach2#}
						<?php break; ?>
						{#/foreach1#}
						<?php default: ?>
						{#foreach from=list_layout.layout_arr item=lll key=lll_k#}
						<tr>
			
				//*这里判断不同属性类型执行不同的*//
								<td>
									<?php echo  $labels['{#lll|attr_id_arr.attr_field_name#}'];?>
								</td>
								<td>
								{#include views/attrtype/view/{#lll|attr_id_arr.attr_type_arr.attrtype_view_template#}#}
								</td>
				
		   				</tr>
						{#/foreach#}
						<?php } ?>
				<?php }}else{ ?>
					{#foreach from=list_layout.layout_arr item=lll key=lll_k#}
					<tr>
			
				//*这里判断不同属性类型执行不同的*//
								<td>
									<?php echo  $labels['{#lll|attr_id_arr.attr_field_name#}'];?>
								</td>
								<td>
								{#include views/attrtype/view/{#lll|attr_id_arr.attr_type_arr.attrtype_view_template#}#}
								</td>
				
		   				</tr>
					{#/foreach#}
				<?php }?>



	</tbody>
</table>


							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>