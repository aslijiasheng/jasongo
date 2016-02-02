<script type="text/javascript">
    $(document).ready(function () {
        $("#assign_staff_sub3").AjaxDialog({
            DialogUrl:   "<?php echo base_url()?>index.php/www/objects/recycling?sel=<?php echo $ObjType; ?>"+"&obj_type=99&div_id=<?php echo $div_id?>",
            DialogTitle: "批量回收员工",
            DialogWidth: 800,
            DialogHeight: 500});
        $("#qx").click(function(){
            $("#recycling").parent().dialog('close');
        })
    });
</script>
<div style="width:100px;margin-left: auto;margin-right: auto;margin-top: 30px" id="recycling">
    <div>
        <div><input type="radio" name="update" value="1">更新选中记录</div>
        <div style="margin-top: 15px"><input type="radio" name="update" value="2">更新当前列表</div>

    </div>
    <div style="margin-top: 40px">
        <input value="确定" type="button" id="assign_staff_sub3">
        <input value="取消" type="button" id="qx">
    </div>
</div>
