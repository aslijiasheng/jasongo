		<?php 
			if ($listData_value['{#obj_name#}_{#lll|attr_name#}']!=0 and $listData_value['{#obj_name#}_{#lll|attr_name#}']!=""){
				echo $listData_value['{#obj_name#}_{#lll|attr_name#}_arr']['{#lll|attr_id_arr.attr_quote_id_arr.obj_name#}_name'];
			}else{
				echo "&nbsp";
			}
		?>