<input type="text" maxlength="128" name="{#obj_name#}[{#lll|attr_id_arr.attr_field_name#}]" id="{#obj_name#}_{#lll|attr_name#}" value_id ="<?php if(isset($id_aData['{#obj_name#}_{#lll|attr_name#}_arr']['{#lll|attr_id_arr.attr_quote_id_arr.obj_name#}_id'])){echo $id_aData['{#obj_name#}_{#lll|attr_name#}_arr']['{#lll|attr_id_arr.attr_quote_id_arr.obj_name#}_id']; } ?>" value="<?php if(isset($id_aData['{#obj_name#}_{#lll|attr_name#}_arr']['{#lll|attr_id_arr.attr_quote_id_arr.obj_name#}_name'])){echo $id_aData['{#obj_name#}_{#lll|attr_name#}_arr']['{#lll|attr_id_arr.attr_quote_id_arr.obj_name#}_name'];} ?>" url="<?php echo site_url('www/{#lll|attr_id_arr.attr_quote_id_arr.obj_name#}/ajax_list') ?>"  leetype="quote">