<?php foreach(${#obj_name#}_{#lll|attr_id_arr.attr_name#}_enum as ${#lll|attr_id_arr.attr_name#}_v){ ?>
<label class="radio inline">
<?php if(isset($id_aData) && ${#lll|attr_id_arr.attr_name#}_v['enum_key']==$id_aData['{#lll|attr_id_arr.attr_field_name#}_arr']['enum_key'] ){ ?>
<input id="inlineRadioB" type="radio" value="<?php echo ${#lll|attr_id_arr.attr_name#}_v['enum_key']?>" name="{#obj_name#}[{#lll|attr_id_arr.attr_field_name#}]" checked>
<?php }else{ ?>
	<input id="inlineRadioB" type="radio"  value="<?php echo ${#lll|attr_id_arr.attr_name#}_v['enum_key']?>" name="{#obj_name#}[{#lll|attr_id_arr.attr_field_name#}]">
<?php }?>
<?php echo ${#lll|attr_id_arr.attr_name#}_v['enum_name'];?>
</label>
<?php } ?>