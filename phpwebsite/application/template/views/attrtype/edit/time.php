<div class="input-append date form_date" id="{#obj_name#}_{#lll|attr_name#}">
	<input size="16" type="text" name="{#obj_name#}[{#lll|attr_id_arr.attr_field_name#}]" value="2012-01-01 00:00:00" style="width: 154px;" value="<?php if(isset($id_aData['{#obj_name#}_{#lll|attr_name#}_arr']['{#lll|attr_id_arr.attr_quote_id_arr.obj_name#}_name'])){echo $id_aData['{#obj_name#}_{#lll|attr_name#}_arr']['{#lll|attr_id_arr.attr_quote_id_arr.obj_name#}_name'];} ?>">
	<span class="add-on"><i class="icon-close"></i></span>
	<span class="add-on"><i class="icon-clock"></i></span>
</div>
<script type="text/javascript">
$(document).ready(function () {
	$('#{#obj_name#}_{#lll|attr_name#}').datetimepicker({
        language:  'zh-CN',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 1,
		forceParse: 0,
		format:'yyyy-mm-dd hh:ii:ss'
    });
});
</script>