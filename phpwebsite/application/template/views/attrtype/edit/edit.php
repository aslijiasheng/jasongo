<?php if(isset($id_aData)){ ?>
<input type="text" maxlength="128" name="{#obj_name#}[{#lll|attr_id_arr.attr_field_name#}]" value="<?php echo $id_aData['{#lll|attr_id_arr.attr_field_name#}'] ?>" id="{#obj_name#}_{#lll|attr_name#}">
<?php }else{ ?>
<input type="text" maxlength="128" name="{#obj_name#}[{#lll|attr_id_arr.attr_field_name#}]" id="{#obj_name#}_{#lll|attr_name#}">
<?php } ?>