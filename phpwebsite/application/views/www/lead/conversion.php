<form id="cluesIntoData" method="post" action="<?php echo base_url().'index.php/www/lead/cluesInto' ?>" >
    <input type="hidden" name="LEAD_ID"  value="<?php echo $id ?>">
    <input type="hidden" name="OBJ_TYPE" value="<?php echo $obj_type ?>">
    <label class="_left">
        <input type="checkbox" checked id="new_customer" value="Account" name="Account" class="ace"/><span class="lbl">新建客户</span>
    </label>
    <div class="_right">
        <table>
            <tbody>
            <tr><td><label>名称</label></td><td><input  name="Account.Name" class="cluesAccountName" value="<?php echo  $name ?>"></td></tr>
            <tr><td><label>所有者</label></td><td><div class="input-group"><input style="width:116px;" name="Account.Owner" value="<?php echo  $this->session->userdata('user_name') ?>" id="Account_Owner"></div></td></tr>
            <tr><td><label>请先定义对象类型</label></td>
                <td>
                    <select name="Account.Type.ID"  class="form-control">
                        <?php echo  $obj_type_select_arr[0] ?>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="_clear"></div>
<hr>
    <label class="_left">
        <input type="checkbox" checked class="ace new" value="Contact" name="Contact" id="contact" /><span class="lbl">新建联系人</span>
    </label>
    <div class="_right" style="margin-right: 18px">
        <table>
            <tbody>
            <tr><td><label>所有者</label></td><td><div class="input-group"><input style="width:116px;"  name="Contact.Owner" value="<?php echo  $this->session->userdata('user_name') ?>" id="Contact_Owner"></div></td></tr>
            <tr><td><label>请先定义对象类型</label></td><td>
                    <select name="Contact.Type.ID"  class="form-control">
                        <?php echo  $obj_type_select_arr[1] ?>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="_clear"></div>
<hr>
    <label class="_left new">
        <input type="checkbox" checked class="ace new" name="Opportunity"  value="Opportunity" id="opportunity" /><span class="lbl">新建销售机会</span>
    </label>
    <div class="_right">
        <table>
            <tbody>
            <tr><td><label>名称</label></td><td><input name="Opportunity.Name" class="cluesAccountName" value="<?php echo  $name ?>"></td></tr>
            <tr><td><label>所有者</label></td><td><div class="input-group"><input name="Opportunity.Owner" style="width:116px;" value="<?php echo  $this->session->userdata('user_name') ?>" id="Opportunity_Owner"></div></td></tr>
            <tr><td><label>请先定义对象类型</label></td>
                <td>
                    <select name="Opportunity.Type.ID" class="form-control">
                        <?php echo  $obj_type_select_arr[2] ?>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="_clear"></div>
</form>
<script type="text/javascript">
    jQuery(function($) {
        $("#Account_Owner").QuoteInitial({
            url:"<?php echo base_url()?>index.php/www/objects/ajax_lists?obj_type=99",
            title:"所有者",
            name:"Account.Owner"

        });


        $("#Contact_Owner").QuoteInitial({
            url:"<?php echo base_url()?>index.php/www/objects/ajax_lists?obj_type=99",
            title:"所有者",
            name:"Contact.Owner"
        });

        $("#Opportunity_Owner").QuoteInitial({
            url:"<?php echo base_url()?>index.php/www/objects/ajax_lists?obj_type=99",
            title:"所有者",
            name:"Opportunity.Owner"
        });

        $("#Account_Owner").next().val("<?php echo  $this->session->userdata('user_id') ?>")
        $("#Contact_Owner").next().val("<?php echo  $this->session->userdata('user_id') ?>")
        $("#Opportunity_Owner").next().val("<?php echo  $this->session->userdata('user_id') ?>")

        $("body").on("click","#new_customer",function(){
            if($(this).prop("checked")==false){
                $(".new").prop("checked",false);
                $("._right").css("display","none")
            }else{
                $(this).parent().next().css("display","block");
            }
        })

        $("body").on("click",".new",function(){
            if($(this).prop("checked")){
                $("#new_customer").prop("checked",true);
                $("#new_customer").parent().next().css("display","block");
                $(this).parent().next().css("display","block");
            }else{
                $(this).parent().next().css("display","none");
            }
        })

    });
</script>
<style>
    ._left{
        float: left;
        margin-top: 3px
    }
    ._right{
        float: right;

    }
    ._clear{
        clear: both;
    }
    ._right span{
        width: 5px;height: 10px;
    }
    input{
        background-color: #fff;
        border: 1px solid #d5d5d5;
        border-radius: 0 !important;
        box-shadow: none !important;
        color: #858585;
        font-family: inherit;
        font-size: 14px;
        transition-duration: 0.1s;
    }
</style>