<select id="country" name="{#obj_name#}[{#lll|attr_id_arr.attr_field_name#}]">
<?php foreach(${#obj_name#}_{#lll|attr_id_arr.attr_name#}_enum as ${#lll|attr_id_arr.attr_name#}_v){ ?>
   <?php if(isset($id_aData) && ${#lll|attr_id_arr.attr_name#}_v['enum_key']==$id_aData['{#lll|attr_id_arr.attr_field_name#}_arr']['enum_key'] ){ ?>
     <option value="<?php echo ${#lll|attr_id_arr.attr_name#}_v['enum_key'];?>" selected="selected">
   <?php }else{ ?>
      <option value="<?php echo ${#lll|attr_id_arr.attr_name#}_v['enum_key'];?>"> 
   <?php }?>   
        <?php echo ${#lll|attr_id_arr.attr_name#}_v['enum_name'];?>                       
     </option>
   
   
<?php } ?>
                            
</select>