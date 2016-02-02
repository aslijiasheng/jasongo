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
    function after(id, name, label, url) {
        $("#" + id).QuoteInitial({
            url: url,
            title: label,
            name: name
        });
    }
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
        var  mydata = $.parseJSON( $("#str").val() )
        var  operation = $.parseJSON( $("#operation").val() )
        var i = "<?php echo $index ?>";
        $('#GetSelectedAttr').click(function(){
            var attr = $("#<?php echo $objName[0]['LABEL'] ?>_page-content").LeeTable('GetSelectedAttr');
            var product;
            var standardPrice;
            var id ;
            $.each(attr,function(k,v){
                i++;
                product =  v["Product.Name"]
                standardPrice =  v["Product.StandardPrice"]
                id =  v[" Product.ID"]
                jQuery("#list4").jqGrid('addRowData', i, mydata);
                var inputs = $("#" + i).children().find("input");
                inputs.each(function () {
                    var index = $(this).attr("class");
                    if (index.indexOf(18) != -1) {
                        var idd = $(this).attr("id") + i
                        $(this).attr("id", idd)
                        var url = $(this).attr("u")
                        var label = $(this).attr("l")
                        var name = $(this).attr("name")
                        after(idd, name, label, url)

                        console.log(k)
                        console.log($(this).next())

                        $(this).next().val(k)
                        console.log($(this).next())

                    }
                    if (index.indexOf(operation["results"]) != -1) {
                        $(this).attr("readonly", "readonly")
                    }
                    if (index.indexOf(operation["first"]) != -1) {
                        var last_obj;
                        var results_obj;
                        inputs.each(function () {
                            var index2 = $(this).attr("class");
                            if (index2.indexOf(operation["last"]) != -1) {
                                last_obj = this;
                                $(this).val(0)
                            }
                            if (index2.indexOf(operation["results"]) != -1) {
                                results_obj = this;
                            }
                        })
                        $(this).blur(function () {
                            var first = parseFloat($(this).val())
                            var last = parseFloat($(last_obj).val())
                            if (operation["symbol"] == "/") {
                                if (last != 0) {
                                    $(results_obj).val(first/last)
                                }
                            }else if(operation["symbol"] == "*"){
                                $(results_obj).val(first*last)
                            }else if(operation["symbol"] == "-"){
                                $(results_obj).val(first-last)
                            } else if(operation["symbol"] == "+"){
                                $(results_obj).val(first+last)
                            }


                        })
                    }

                    if (index.indexOf(operation["last"]) != -1) {
                        var first_obj;
                        var results_obj;
                        inputs.each(function () {
                            var index3 = $(this).attr("class");
                            if (index3.indexOf(operation["first"]) != -1) {
                                first_obj = this;
                                $(this).val(0)
                            }
                            if (index3.indexOf(operation["results"]) != -1) {
                                results_obj = this;
                            }
                        })
                        $(this).blur(function () {
                            var last = parseFloat($(this).val());
                            var first = parseFloat($(first_obj).val());
                            if (operation["symbol"] == "/") {
                                if (last != 0) {
                                    $(results_obj).val(first/last)
                                }
                            }else if(operation["symbol"] == "*"){
                                $(results_obj).val(first*last)
                            }else if(operation["symbol"] == "-"){
                                $(results_obj).val(first-last)
                            } else if(operation["symbol"] == "+"){
                                $(results_obj).val(first+last)
                            }

                        })

                    }
                    if (index.indexOf(15) != -1) {
                        $(this).datepicker();
                    }
                    $(".3").onlyMoney();
                    $(".1").onlyNum();
                })
                $("#"+i).children().find(".add").AjaxDialog({
                    DialogUrl: "<?php echo base_url()?>index.php/www/objects/detail_single?obj_type=9&id="+i,
                    DialogTitle: "价格表产品",
                    DialogWidth: 800,
                    DialogHeight: 500,
                })
                $("#"+i).children().find(".del").click(function(){
                    $(this).parent().parent().remove();
                })

                inputs.each(function(){
                    var name = $(this).attr("name")
                    if(name.indexOf('Product') != -1){
                        $(this).val(product)
                    }else if(name.indexOf('StandardPrice') != -1){
                        $(this).val(standardPrice)
                    }

                })
            });
});



    });

</script>
