<script type="text/javascript">
    jQuery(document).ready(function () {
        //选择一项
        $("#addOne").click(function () {
            $("#from option:selected").clone().appendTo("#to");
            $("#from option:selected").remove();
        });

        //选择全部
        $("#addAll").click(function () {
            $("#from option").clone().appendTo("#to");
            $("#from option").remove();
        });

        //移除一项
        $("#removeOne").click(function () {
            $("#to option:selected").clone().appendTo("#from");
            $("#to option:selected").remove();
        });

        //移除全部
        $("#removeAll").click(function () {
            $("#to option").clone().appendTo("#from");
            $("#to option").remove();
        });

        //移至顶部
        $("#Top").click(function () {
            var allOpts = $("#to option");
            var selected = $("#to option:selected");

            if (selected.get(0).index != 0) {
                for (i = 0; i < selected.length; i++) {
                    var item = $(selected.get(i));
                    var top = $(allOpts.get(0));
                    item.insertBefore(top);
                }
            }
        });

        //上移一行
        $("#Up").click(function () {
            var selected = $("#to option:selected");
            if (selected.get(0).index != 0) {
                selected.each(function () {
                    $(this).prev().before($(this));
                });
            }
        });

        //下移一行
        $("#Down").click(function () {
            var allOpts = $("#to option");
            var selected = $("#to option:selected");

            if (selected.get(selected.length - 1).index != allOpts.length - 1) {
                for (i = selected.length - 1; i >= 0; i--) {
                    var item = $(selected.get(i));
                    item.insertAfter(item.next());
                }
            }
        });

        //移至底部
        $("#Buttom").click(function () {
            var allOpts = $("#to option");
            var selected = $("#to option:selected");

            if (selected.get(selected.length - 1).index != allOpts.length - 1) {
                for (i = selected.length - 1; i >= 0; i--) {
                    var item = $(selected.get(i));
                    var buttom = $(allOpts.get(length - 1));
                    item.insertAfter(buttom);
                }
            }
        });


    });
</script>
<div class="row">
    <div class="col-xs-12 col-md-5">
        <div class="well">
        	<h3 class="header blue lighter smaller">
				<i class="ace-icon fa fa-calendar-o smaller-90"></i>
				可选属性
			</h3>
            <select name="from" id="from" style="width:100%;" size="10" multiple="multiple">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
            </select>
        </div>
    </div>
    <div class="col-xs-12 col-md-2">
        <div class="well with-header">
        	<h3 class="header blue lighter smaller">
				操作
			</h3>
            <div class="span2">
            	<a class="btn btn-white" id="Top" type="button" style="width: 50px;">
            		<i class="ace-icon fa fa-angle-double-up bigger-120 blue"></i>
            	</a>
            	<a class="btn btn-white" id="Up" type="button" style="width: 50px;">
            		<i class="ace-icon fa fa-angle-up bigger-120 blue"></i>
            	</a>
            	<a class="btn btn-white" id="addOne" type="button" style="width: 50px;">
            		<i class="ace-icon fa fa-angle-right bigger-120 blue"></i>
            	</a>
            	<a class="btn btn-white" id="removeOne" type="button" style="width: 50px;">
            		<i class="ace-icon fa fa-angle-left bigger-120 blue"></i>
            	</a>
            	<a class="btn btn-white" id="Down" type="button" style="width: 50px;">
            		<i class="ace-icon fa fa-angle-down bigger-120 blue"></i>
            	</a>
            	<a class="btn btn-white" id="Buttom" type="button" style="width: 50px;">
            		<i class="ace-icon fa fa-angle-double-down bigger-120 blue"></i>
            	</a>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-5">
        <div class="well with-header">
        	<h3 class="header blue lighter smaller">
				<i class="ace-icon fa fa-calendar-o smaller-90"></i>
				导出属性
			</h3>
            <select name="to" id="to" style="width:100%;" size="10" multiple="multiple">
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
            </select>
        </div>
    </div>
</div>
