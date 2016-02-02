<a href="<?php echo base_url(); ?>index.php/www/objects/add?obj_type=<?php echo $ObjType; ?>&type_id=91">
    <button class="btn">
        <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
        新建线索
    </button>
</a>
<button id='lead_sale_seniorquery' class="btn btn-primary">
    <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
    高级查询
</button>
<script type="text/javascript">
    //高级查询
    $(document).ready(function () {
        var seniorquery_attr = <?php echo $SeniorQueryAttrJson; ?>;
        $("#lead_sale_seniorquery").SeniorQuery({
            SelectAttr: seniorquery_attr,
            ListUrl: "<?php echo base_url(); ?>index.php/www/objects/list_json?obj_type=<?php echo $ObjType; ?>",
            ListDiv: "<?php echo $ListDiv; ?>"
        });

    });
</script>
<button class="btn btn-info" id="import">
    <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
    批量导入
</button>
<script type="text/javascript">
    //导入
    $(document).ready(function () {

        $("#import").AjaxDialog({
            DialogUrl: "<?php echo base_url(); ?>index.php/www/objects/import?obj_type=<?php echo $ObjType; ?>",
            DialogTitle: "批量导入",
            DialogWidth: 700,
            DialogHeight: 400
        });
        $("#batch_up").AjaxDialog({
            DialogUrl: "<?php echo base_url(); ?>index.php/www/objects/batchUp",
            DialogTitle: "批量更新",
            DialogWidth: 500,
            DialogHeight: 400
        });


    });
</script>
<button class="btn btn-success" id="export">

    <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
    批量导出
</button>

<script type="text/javascript">
    //导出

    $("#export").click(function () {
        var data = "";
        $DialogDivID = "DialogDiv" + $(this).attr("id")
        $("input[type=checkbox]:checked").each(function (k, v) {
            if (data == "") {
                data = $(this).val();
            } else {
                data = data + "," + $(this).val();
            }

        });
        //alert(data);
        if ($('#' + $DialogDivID).length == 0) {
            $(this).after('<div id="' + $DialogDivID + '" style="display:none;"></div>');
        }
        $.ajax({
            'type': 'post',
            'data': {"data": data},
            'success': function (data) {
                $('#' + $DialogDivID).html(data);
            },
            'url': "<?php echo base_url(); ?>index.php/www/objects/export?obj_type=<?php echo $ObjType; ?>",
            'cache': false
        });
        $("#" + $DialogDivID).dialog({
            title: "批量导出",
            modal: true,
            width: 400,
            height: 220,
            buttons: [
                {
                    text: "查询",
                    Class: "btn bottom-margin btn-primary",
                    click: function () {

                        $(this).dialog("close");
                    }
                },
                {
                    text: "取消",
                    Class: "btn bottom-margin btn-danger ",
                    click: function () {
                        $(this).dialog("close");
                    }
                }
            ]
        });
        $('#' + $DialogDivID).dialog('open');
        /*
         $("#export").AjaxDialog({
         DialogUrl:"
        <?php echo base_url(); ?>index.php/www/objects/export?obj_type=
        <?php echo $ObjType; ?>",
         DialogTitle:"批量导出",
         DialogData:,
         DialogWidth:400,
         DialogHeight:220
         });
         */

    });


</script>


<button class="btn btn-warning" id="batch_up">
    <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
    批量更新
</button>
<!--                <button class="btn btn-danger">-->
<!--                    <i class="ace-icon fa fa-pencil align-top bigger-125"></i>-->
<!--                    批量回收公共池-->
<!--                </button>-->
<!--<button class="btn btn-inverse">-->
<!--    <i class="ace-icon fa fa-pencil align-top bigger-125"></i>-->
<!--    统计-->
<!--</button>-->
<button class="btn btn-pink">
    <i class="ace-icon fa fa-pencil align-top bigger-125"></i>
    查重
</button>
<div class="hr hr-dotted hr-16"></div>
