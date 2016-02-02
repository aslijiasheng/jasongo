<div id="<?php echo $DialogID;?>_ajax_page_content">
</div><!-- /.page-content -->
<script type="text/javascript">
jQuery(function ($) {
        var params = {data_json:'<?php echo json_encode($data_json); ?>',condition:'<?php echo $condition;?>'};
        var url = "<?php echo base_url(); ?>index.php/www/objects/list_object_json?obj_type=<?php echo $ObjType; ?>";
        var col = <?php echo json_encode($ColModel['Col']); ?>;
        var keyattrname = '<?php echo $ColModel['KeyAttrName'];?>';
        var Operation = [
            {Title:'删除',Url:'javascript:void(0);',Js:"dlt('{"+keyattrname+"}','<?php echo $ObjType; ?>','#<?php echo $DialogID;?>_ajax_page_content','<?php echo base_url(); ?>index.php/www/objects/list_json?obj_type=<?php echo $ObjType; ?>')",Css:'fa fa-trash-o red'},
        ];
        $("#<?php echo $DialogID;?>_ajax_page_content").LeeTable({
            TableHeight:100,
            Url:url,
            Operation:Operation,
            Col:col,
            KeyAttrName:keyattrname, //组件属性，用于给多选、单选、选中值等...
            postData:params,//传入的参数
            IsNotPage:1
        });
    });

function dlt(id, obj_type, grid_selector, url) {
    if (confirm("确定要删除数据吗？")) {
        var del_url = "<?php echo site_url("www/objects/del?main_type=".$obj_type."&mid=".$id); ?>";
        $.ajax({
            type: "GET",
            url: del_url,
            data: {id: id, obj_type: obj_type},
            success: function (data) {
                if (data == "success") {
                    alert("删除数据成功");
                    jQuery(grid_selector).LeeTable('SetGridParam');
                } else {
                    alert(data);
                }
            }
        });
    }
}
</script>
