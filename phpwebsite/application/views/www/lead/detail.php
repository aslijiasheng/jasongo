<style>
    #list4 tr td input {
        width: 90px !important;
    }
</style>
<?php
//$colNames[] = "操作";
//$colModel[] = array("name" => "operation", "index" => "operation", "width" => 130, "sorttype" => "string");
//$mydata["operation"] = '<img src="/delete.gif" class="del"><img src="/lookup.gif" class="add">';
$all_attr = json_decode($all_attr, true);
foreach ($detail_title as $key => $value) {
    $colNames[] = $value;
    $keys = str_replace(".", "_", $key);
    $colModel[] = array("name" => $keys, "index" => $keys, "width" => 130, "sorttype" => "string");
    foreach ($all_attr as $k => $v) {
        if ($key == $v["name"]) {
            $mydata[$key]["AttrType"] = $v["attr_type"];
            $mydata[$key]["Name"] = $v["name"];
            $mydata[$key]["LangEn"] = $v["label"];
            $mydata[$key]["FromData"]["name"] = $v["name"];
        }
    }
    p($mydata);
}
?>
<table id="list4"></table>
<script type="text/javascript">
    $(function () {
        var i = 0;
        function after(id, name, label, url) {
            $("#" + id).QuoteInitial({
                url: url,
                title: label,
                name: name
            });
        }

        jQuery("#list4").jqGrid({
            datatype: "local",
            height: 250,
            colNames:<?php echo  json_encode($colNames);?>,
            colModel:<?php echo  json_encode($colModel);?>,
            caption: '<img src="/add.gif" id="add">&nbsp;&nbsp;&nbsp;&nbsp;<img src="/lookup.gif" id="add_much">'
        });

        var mydata = [<?php echo  json_encode($mydata);?>];
        var str = JSON.stringify(mydata[0]);
        var operation = JSON.stringify(<?php echo  json_encode($operation);?>)
        $("#operation").val(operation)
        $("#str").val(str)
        $("#add").click(function () {

            if($("#list4").children().find("tr").last().attr("id") == undefined){
                i = 0
            }else{
                i = parseInt($("#list4").children().find("tr").last().attr("id")) +1
            }

            jQuery("#list4").jqGrid('addRowData', i, mydata[0]);
            var inputs = $("#" + i).children().find("input");
            inputs.each(function () {
                var index = $(this).attr("class");
                if (index.indexOf(18) != -1) {
                    var id = $(this).attr("id") + i
                    $(this).attr("id", id)
                    var url = $(this).attr("u")
                    var label = $(this).attr("l")
                    var name = $(this).attr("name")
                    after(id, name, label, url)
                }
                if (index.indexOf("<?php echo $operation["results"]?>") != -1) {
                    $(this).attr("readonly", "readonly")
                }
                if (index.indexOf("<?php echo $operation["first"]?>") != -1) {
                    var last_obj;
                    var results_obj;
                    inputs.each(function () {
                        var index2 = $(this).attr("class");
                        if (index2.indexOf("<?php echo $operation["last"]?>") != -1) {
                            last_obj = this;
                            $(this).val(0)
                        }
                        if (index2.indexOf("<?php echo $operation["results"]?>") != -1) {
                            results_obj = this;
                        }
                    })
                    $(this).blur(function () {
                        var first = parseFloat($(this).val())
                        var last = parseFloat($(last_obj).val())
                        if ("<?php echo $operation["symbol"]?>" == "/") {
                            if (last != 0) {
                                $(results_obj).val(first<?php echo $operation["symbol"]?>last)
                            }
                        }else{
                            $(results_obj).val(first<?php echo $operation["symbol"]?>last)
                        }


                    })
                }

                if (index.indexOf("<?php echo $operation["last"]?>") != -1) {
                    var first_obj;
                    var results_obj;
                    inputs.each(function () {
                        var index3 = $(this).attr("class");
                        if (index3.indexOf("<?php echo $operation["first"]?>") != -1) {
                            first_obj = this;
                            $(this).val(0)
                        }
                        if (index3.indexOf("<?php echo $operation["results"]?>") != -1) {
                            results_obj = this;
                        }
                    })
                    $(this).blur(function () {
                        var last = parseFloat($(this).val());
                        var first = parseFloat($(first_obj).val());
                        if ("<?php echo $operation["symbol"]?>" == "/") {
                            if (last != 0) {
                                $(results_obj).val(first<?php echo $operation["symbol"]?>last)
                            }
                        }else{
                            $(results_obj).val(first<?php echo $operation["symbol"]?>last)
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
                DialogHeight: 500
             })
            $("#"+i).children().find(".del").click(function(){
                $(this).parent().parent().remove();
            })
            $("#add_much").unbind("click")
            $("#add_much").AjaxDialog({
                DialogUrl: "<?php echo base_url()?>index.php/www/objects/detail_check?obj_type=9&index="+i,
                DialogTitle: "价格表产品",
                DialogWidth: 800,
                DialogHeight: 500
            })
        })



    })
</script>
<input type="hidden" id="str">
<input type="hidden" id="operation">
