<div class="<?php echo $DialogID; ?>_ajax_page_content">

    <!--lee 这里是中间内容部分 start-->
    <div class="row">

        <div class="col-xs-12">
            <button id='<?php echo $DialogID; ?>_ajax_sale_seniorquery' class="btn btn-primary">
                <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                高级查询
            </button>
            <button id='GetSelectedAttr' class="btn btn-primary">
                <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                确定
            </button>
             <a id="GetSelectedAttr"> </a>
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

        <div class="col-xs-12" id="<?php echo $DialogID; ?>_page_content">
        </div><!-- /.col -->
    </div><!-- /.row -->
    <!--lee 这里是中间内容部分 end-->

</div><!-- /.page-content -->
<script type="text/javascript">
    jQuery(function ($) {
        var url = "<?php echo base_url(); ?>index.php/www/objects/list_json?obj_type=<?php echo $ObjType; ?>";
        var col = <?php echo json_encode($ColModel['Col']); ?>;
        var keyattrname = '<?php echo $ColModel['KeyAttrName'];?>';
        $("#<?php echo $DialogID; ?>_page_content").LeeTable({
            Url:url,
            Col:col,
            MultiSelect:true, //是否开启全选功能
            MultiType:0, //选着类型 0 多选（默认） 1 单选
            KeyAttrName:keyattrname //组件属性，用于给多选、单选、选中值等...
        });

        $('#GetSelectedAttr').click(function(){
            var attr = $("#<?php echo $objName[0]['LABEL'] ?>_page-content").LeeTable('GetSelectedAttr');
            var product;
            var standardPrice;
            var id ;
            $.each(attr,function(k,v){
                product =  v["Product.Name"]
                standardPrice =  v["Product.StandardPrice"]
                id =  v[" Product.ID"]
            });
            var input = $("#<?php echo $id?>").children().find("input")
            input.each(function(){
                var name = $(this).attr("name")
                 if(name.indexOf('Product.ID') != -1){
                    $(this).val(id)
                    $(this).prev().val(product)
                 }else if(name.indexOf('StandardPrice') != -1){
                    $(this).val(standardPrice)
                }

            })
        });



    });

</script>
