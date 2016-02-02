<div class="<?php echo $DialogID; ?>_ajax_page_content">

    <!--lee 这里是中间内容部分 start-->
    <div class="row">

        <div class="col-xs-12">
            <button id='<?php echo $DialogID; ?>_sub' class="btn btn-primary">
                <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
                高级查询
            </button>
            <script type="text/javascript">
                //高级查询
                $(document).ready(function () {
                    var seniorquery_attr = <?php echo $SeniorQueryAttrJson; ?>;

                    $("#<?php echo $DialogID; ?>_sub").SeniorQuery({
                        SelectAttr: seniorquery_attr,
                        ListUrl: "<?php echo base_url(); ?>index.php/www/objects/list_json?obj_type=<?php echo $ObjType; ?>",
                        ListDiv: "#<?php echo $DialogID; ?>_page_content"
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
        var nameattrname = '<?php echo $obj_data['NAME_ATTR_NAME']?>';
        var postData = null;
        var obj_type = '<?php echo $ObjType;?>';
        var robj_type = '<?php echo $rObjType;?>';
        if(obj_type == '300'){
            postData = {seniorquery:{where:{1:{attr:'Type.ObjectType',action:'EQUAL',value:robj_type}},rel:'1'}};
        }
        $("#<?php echo $DialogID; ?>_page_content").LeeTable({
            Url:url,
            Col:col,
            MultiSelect:true, //是否开启全选功能
            MultiType:1, //选着类型 0 多选（默认） 1 单选
            KeyAttrName:keyattrname, //组件属性，用于给多选、单选、选中值等...
            NameAttrName:nameattrname, //引用的单选需要赋值用
            postData:postData
        });
    });

</script>
