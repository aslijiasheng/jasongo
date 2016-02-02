<div class="<?php echo $DialogID; ?>_ajax_page_content">

    <!--lee 这里是中间内容部分 start-->
    <div class="row">

        <div class="col-xs-12">
            <button id='<?php echo $DialogID; ?>_ajax_sale_seniorquery' class="btn btn-primary">
                <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                高级查询
            </button>
            <button id="sub" class="btn btn-primary">
                <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                回收员工
            </button>
            <script type="text/javascript">
                //高级查询
                $(document).ready(function () {
                    var seniorquery_attr = <?php echo $SeniorQueryAttrJson; ?>;
                    $("#<?php echo $DialogID; ?>_ajax_sale_seniorquery").SeniorQuery({
                        SelectAttr: seniorquery_attr,
                        ListUrl: "<?php echo base_url(); ?>index.php/www/objects/list_json?obj_type=<?php echo $ObjType; ?>",
                        ListDiv: "#<?php echo $DialogID; ?>_grid_table"
                    });

                });

            </script>
            <div class="hr hr-dotted hr-16"></div>
        </div>

        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
           <div id=""></div>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
    <!--lee 这里是中间内容部分 end-->

</div><!-- /.page-content -->
<div>
    <input type="text" name="page_end" id="<?php echo $DialogID; ?>page_end"/>
</div>
<script type="text/javascript">
    jQuery(function ($) {
        var url = "<?php echo base_url(); ?>index.php/www/objects/list_json?obj_type=<?php echo $ObjType; ?>";
        var col = <?php echo json_encode($ColModel['Col']); ?>;
        var keyattrname = '<?php echo $ColModel['KeyAttrName'];?>';
        $("#<?php echo $DialogID; ?>_grid_pager").LeeTable({
            Url:url,
            Col:col,
            MultiSelect:true, //是否开启全选功能
            MultiType:0, //选着类型 0 多选（默认） 1 单选
            KeyAttrName:keyattrname //组件属性，用于给多选、单选、选中值等...
        });


        $("#sub").click(function(){
            var data = "";
            var radio =  $('input:radio[name=update]:checked').val();

            if(radio == "2") {
                $("#<?php echo $div_id?> input[type=checkbox]").each(function (k, v) {
                    if (data == "") {
                        data = $(this).val();
                    } else {
                        data = data + "," + $(this).val();
                    }
                });
            }else if (radio == "1"){
                $("#<?php echo $div_id?> input[type=checkbox]:checked").each(function (k, v) {
                    if (data == "") {
                        data = $(this).val();
                    } else {
                        data = data + "," + $(this).val();
                    }
                });
            }
            data = data.replace(/on,/, "");
            var employees = "";
            $("#"+"<?php echo $DialogID; ?>_grid_pager input[type=checkbox]:checked").each(function (k, v) {
                if (employees == "") {
                    employees = $(this).val();
                } else {
                    employees = employees + "," + $(this).val();
                }
            });
            if(data.length == 0){
                alert("请选择分配数据");
                return;
            }
            if(employees.length == 0){
                alert("请选择员工数据");
                return;
            }
           console.log(data);
           console.log(employees);
           $.ajax({
                url:"<?php echo base_url(); ?>index.php/www/objects/del_rel_user?obj_type=<?php echo $sel; ?>",
                type:"post",
                data:{id:data,users:employees},
                success:function(data){
                    if(data =="success"){
                        alert("批量更新成功")
                        window.history.go(0)
                    }else{
                        alert("批量更新失败")
                    }
                }
            })




        })
     });


</script>
