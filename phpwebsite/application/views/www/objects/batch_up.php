<style>
    .up_div{
        width:400px;margin-left: auto;margin-right: auto;
    }
    /*.content {*/
        /*border: 1px solid #0000ff;*/
    /*}*/
    .up_div div div {
        margin-top: 10px;
        margin-left: 50px;
    }
    .sub {
        margin-top: 10px;
    }
    .sub div input  {
        margin-left: 80px;
    }
    .input-group{
        width: 150px;
    }

</style>
<script type="text/javascript">
    $.ajax({
        url : "<?php echo base_url()?>index.php/www/objects/batch_up_attr?obj_type="+<?php echo $ObjType?>,
        dataType:"json",
        success:function(data){
            var html = '<option value=""></option>'
            $.each(data,function(idx,item){
                html += '<option value="'+item.ATTR_NAME+'">'+item.LABEL+'</option>'
            })
            $("#up_sel").html(html)
        }
    })
    $(function(){

       $.ajax({
            url:"<?php echo base_url()?>index.php/www/objects/select_data?obj_type="+<?php echo $ObjType?>,
            success:function(datas){
                $("#json").val(datas)
            }

        })
        $("#up_sel").change(function(){
            var value = $(this).val();
            var obj = eval('(' +  $("#json").val() + ')');
            $.each(obj,function(idx,item){
                if (item.name == value) {

                   if(item.attr_type == 13){
                       var html = '<select style="width:200px;" name="up_sel_value"><option value=""></option>'
                       $.each(item.form_data.enum_arr,function(idx,item){
                           html += '<option value="'+item.enum_key+'">'+item.enum_value+'</option>'
                       })
                       html += "</select>"
                       $("#update").html(html);
                   }else if(item.attr_type == 18){
                        var id = item.name.replace('.', "_")
                        var html = '<div class="input-group"><input name="up_sel_value" style="width:150px;" id="'+id+'"></div>';
                        $("#update").html(html);
                        ajax_after(id,item.name,item.form_data.ObjName,item.form_data.RefUrl)
                   }else if(item.attr_type == 11){
                        $("#update").html('<input type="checkbox" style="width:200px;" class="myCheck"><input type="hidden"  name="up_sel_value" value="0">')
                        ajax_check()
                   }else{
                       $("#update").html('<input name="up_sel_value" style="width:200px;">')
                   }
                }
            })


        })
        $("#qx").click(function(){
            $(".up_div").parent().dialog('close');
        })
        $("#submit").click(function(){

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

            if(radio == undefined || data.length == 0 ){
                alert("请选择更新数据")
                return
            }
            console.log(data);
            console.log($("#content").serialize())
            $.ajax({
                url:"<?php echo base_url()?>index.php/www/objects/batch_data?id="+data+"&obj_type="+<?php echo $ObjType?>,
                type:"post",
                data:$("#content").serialize(),
                success:function(data){
                    if(data =="success"){
                        alert("修改成功")
                        $(".up_div").parent().dialog('close');
                        jQuery("#<?php echo $div_id?>").LeeTable('SetGridParam');
                    }else{
                        alert(data)
                    }
                }
            })





        })

    })
    function ajax_check(){
       $(".myCheck").click(function(){
          if( $(this).prop("checked") ) {
              $(this).next().val("1")
          }else{
              $(this).next().val("0")
          }
       })
    }
    function ajax_after(id,name,label,url){
        $("#"+id).QuoteInitial({
            url:url,
            title:label,
            name:name
        });


    }

</script>
<div class="up_div">
    <form id="content">
        <div class="content">
            <div><input type="radio" name="update" value="1">更新选中记录</div>
            <div><input type="radio" name="update" value="2">更新当前列表</div>
            <div>　更新&nbsp;&nbsp;<select style="width:200px;" id="up_sel" name="up_sel_attr"></select>
            </div>
            <div><span style="float: left;">修改为&nbsp;&nbsp;</span><span id="update"><select style="width:200px;" name="up_sel_value"></select></span>
            </div>
            <div><input type="checkbox" name="background">后台处理</div>
            <div style="height: 50px"></div>
        </div>
    </form>
    <div class="sub">
        <div><input type="button" value="提交" id="submit" id=""><input type="button" value="取消" id="qx"></div>
    </div>
</div>
<input type="hidden" id="json">
