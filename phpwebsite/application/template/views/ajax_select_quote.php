	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th width="100">操作</th>
				
				{#if {#is_obj_type#}==1#}
				<?php
				foreach($typelistlayout as $k=>$v){
					if ($_GET["type_id"]==$k){
						foreach($v as $k2=>$v2){
							echo "<th>".$labels[$v2]."</th>";
						}
					}
				}
				if($_GET["type_id"]=="" or $_GET["type_id"]==0){
					{#foreach from=list_layout.layout_arr item=lll key=lll_k#}
					echo "<th>".$labels['{#obj_name#}_{#lll|attr_name#}']."</th>";
					{#/foreach#}
				}
				?>
				{#else#}
					{#foreach from=list_layout.layout_arr item=lll key=lll_k#}
					<th><?php echo $labels['{#obj_name#}_{#lll|attr_name#}'];?></th>
					{#/foreach#}
				{#/if#}

			</tr>
		</thead>
		<tbody>
		<?php foreach($listData as $listData_value){?>
			<tr>
				<td style="font-size:16px;">
					<input type="radio" name="optionsRadios" value="<?php echo $listData_value['{#obj_name#}_id']; ?>" name_val="<?php echo $listData_value['{#obj_name#}_name']; ?>"<?php if($_GET["hidden_v"]!=''){$hidden_id=explode('?',$_GET["hidden_v"]);$hidden_id=$hidden_id[0]; if($hidden_id==$listData_value['{#obj_name#}_id']){echo "checked='checked'";}}?> >
				</td>
				
				{#if {#is_obj_type#}==1#}
						<?php switch ($_GET["type_id"]){ 
							case -1:
							break;
						?>
						{#foreach1 from=type_arr item=ta key=ta_k#}
						<?php case {#ta|type_id#}: ?>
						{#foreach2 from=list_layout.layout_arr item=lll key=lll_k#}
						<td>
						{#include views/attrtype/view/{#lll|attr_id_arr.attr_type_arr.attrtype_view_template#}#}
						</td>
						{#/foreach2#}
						<?php break; ?>
						{#/foreach1#}
						<?php default: ?>
						{#foreach from=list_layout.layout_arr item=lll key=lll_k#}
						<td>
						{#include views/attrtype/view/{#lll|attr_id_arr.attr_type_arr.attrtype_view_template#}#}
						</td>
						{#/foreach#}
						<?php } ?>
				{#else#}
					{#foreach from=list_layout.layout_arr item=lll key=lll_k#}
					<td>
					{#include views/attrtype/view/{#lll|attr_id_arr.attr_type_arr.attrtype_view_template#}#}
					</td>
					{#/foreach#}
				{#/if#}
				
				
			</tr>
		<?php }?>
		</tbody>
		
	</table>
	<div id="data-table_info" style="float:left">共查询到<?php echo $totalNumber;?>条</div>
	<input type='hidden' id='totalNumber' value="<?php echo $totalNumber;?>">
	</div>

