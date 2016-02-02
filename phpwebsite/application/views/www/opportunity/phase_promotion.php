<style>

</style>
<script type="text/javascript">
  $(function(){
      $("#cancel").click(function(){
         $("#phase").parent().dialog('close');
      })

      $('#promotion input[name="new_stage"]').click(function(){
          $('input[name="success_hidden"]').val("0")
          if($("#success").prop("checked")){
              $('input[name="success_hidden"]').val("1")
          }
      })

      $("#sub").click(function(){
         var r = $('#promotion input[name="new_stage"]:checked').val();
          if( r == undefined ){
             alert("请选择阶段申迁的数据");
          }else{
              $.ajax({
                  type:"post",
                  url:"<?php echo base_url(); ?>index.php/www/opportunity/save_promotion_data",
                  data:$("#promotion").serialize(),
                  success:function(data){
                      if(data == 1){
                          alert("申迁成功");
                          $("#phase").parent().dialog('close');
                          window.history.go(0)
                      }else{
                          alert(data);
                      }

                  }
              })
          }
      })
  })
</script>
<div style="height: 100px;width:100px;margin-left: auto;margin-right: auto" id="phase">
    <form id="promotion">
        <input type="hidden" value="<?php echo $id ?>" name="id">
        <?php
        foreach($res as $key => $value){
            if($value["DATA_VALUE"] == "成功结束"){
                echo '<input type="radio"  id = "success" name="new_stage" value="'.$value["NEW_STAGE"].'"> <input type="hidden" name="success_hidden">'.$value["DATA_VALUE"].'<br /><br />';
            }else{
                echo '<input type="radio" name="new_stage" value="'.$value["NEW_STAGE"].'">'.$value["DATA_VALUE"].'<br /><br />';
            }

        }
        ?>
    </form>
    <input type="button" value="提交" id="sub"><input type="button" value="取消" id="cancel">
</div>
