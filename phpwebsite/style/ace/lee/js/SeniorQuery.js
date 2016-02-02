//封装的高级查询组件 2014-04-11 start
/**
 * 封装的高级查询组件
 * selectAttr json 查询的属性
 */
$.fn.SeniorQuery = function ($data) {
    var $ID = $(this).attr("id");
    var $SelectAttr = $data.SelectAttr;
    var $ListUrl = $data.ListUrl;
    var $ListDiv = $data.ListDiv;
    var $DialogDivID; //dialog的页面ID
    var $FormID; //form的ID
    var $ConditionsID; //条件部分的ID
    var $RelID; //条件关系部分的ID （放 1 and 2 的地方）
    var $Line; //用户计算行数
    var $AddLineID;
    var $DelLineID;
    var $LineDivID;
    //首先新建一个dialog的div
    $DialogDivID = "dialog_div_" + $ID; //dialog的页面ID
    $FormID = "form_" + $ID; //form的ID
    $ConditionsID = 'conditions_' + $ID; //条件部分的ID
    $RelID = 'rel_' + $ID; //条件关系部分的ID （放 1 and 2 的地方）
    $AddLineID = 'add_line_' + $ID;
    $DelLineID = 'del_line_' + $ID;
    $LineDivID = "line_" + $ID;
    //先把里面的内容删除掉！防止重复弹出
    //创建dialog_div
    $("#" + $ID).after('<div id="' + $DialogDivID + '" style="display:none;" date-name="DialogDiv"></div>');
    //直接生成页面
    $("#" + $DialogDivID).html('' +
            '<div style="padding:20px;">' +
            '<ul>' +
            '<li>在下面的行中设定属性的限制条件</li>' +
            '<li>查询条件会在下面的文本区域中组合</li>' +
            '<li>你可以改变条件的组合方式(如 1 or (2 and 3))</li>' +
            '</ul>' +
            '<br>' +
            '<form class="form-horizontal no-margin" id="' + $FormID + '">' +
            '<div id="' + $ConditionsID + '">' +
            '</div>' +
            '<div style="padding:10px 0;">' +
            ' <a class="add_seniorquery_line" id="' + $AddLineID + '"><span class="label label-info"> 添加行 </span></a> ' +
            ' <a class="del_seniorquery_line" id="' + $DelLineID + '"><span class="label label-info"> 删除行 </span></a> ' +
            '</div>' +
            '<textarea id="' + $RelID + '" class="form-control" name="seniorquery[rel]" style="height:100px;resize:none;"></textarea>' +
            '</form>' +
            '</div>' +
            '');

    //添加按钮事件
    $("#" + $ID).click(function () {
        $("#" + $DialogDivID).dialog({
            title: "高级查询",
            modal: true,
            width: 800,
            height: 450,
            buttons: [{
                    text: "查询",
                    Class: "btn bottom-margin btn-primary",
                    click: function () {
                        var FormData = $("#" + $FormID).serializeObject();
                        //var FormData = $("#" + $FormID).serializeArray();
                        var gridParam = {postData: FormData};
                        //console.debug(FormData);
                        $($ListDiv).LeeTable('SetGridParam', gridParam);
                        $(this).dialog("close");
                    }
                }, {
                    text: "取消",
                    Class: "btn bottom-margin btn-danger ",
                    click: function () {
                        bootbox.dialog({
                            message: "你取消了高级查询?",
                            buttons: {
                                confirm: {
                                    label: "确定",
                                    className: "btn-primary btn-sm",
                                },
                                cancel: {
                                    label: "取消",
                                    className: "btn-sm",
                                }
                            },
                            callback: function (result) {
                                if (result) {
                                    //alert(111);
                                }
                            }
                        });
                        $(this).dialog("close");
                    }
                }]
        });
        $('#' + $DialogDivID).dialog('open');
    });

    $Line = 0; //用户计算行数
    $("#" + $ConditionsID).AddSeniorQueryLine({
        SelectAttr: $SelectAttr,
        RelID: $RelID,
        Line: $Line,
        LineDivID: $LineDivID
    });
    $Line++;
    $("#" + $ConditionsID).AddSeniorQueryLine({
        SelectAttr: $SelectAttr,
        RelID: $RelID,
        Line: $Line,
        LineDivID: $LineDivID
    });
    $Line++;
    $("#" + $RelID).CombinationRel($Line);
    $("#" + $AddLineID).click(function () {
        $("#" + $ConditionsID).AddSeniorQueryLine({
            SelectAttr: $SelectAttr,
            RelID: $RelID,
            Line: $Line,
            LineDivID: $LineDivID
        });
        $Line++;
        $("#" + $RelID).CombinationRel($Line);
    });
    $("#" + $DelLineID).click(function () {
        $Line = del_seniorquery_line({
            Line: $Line,
            LineDivID: $LineDivID
        });
        $("#" + $RelID).CombinationRel($Line);
    });

    function del_seniorquery_line($data) {
        var $Line = $data.Line;
        var $LineDivID = $data.LineDivID;
        if ($Line == 1) {
            alert('最后1条不能再删了!');
        } else {
            $("#" + $LineDivID + "_" + $Line).remove();
            $Line--;
        }
        return $Line;
    }
    ;


}

//写个方法用来组合rel条件（这个方案是直接每次条件不同清空从组条件）
$.fn.CombinationRel = function ($NewLine) {
    var $ID;
    $ID = $(this).attr("id");
    $RelVal = "";
    for (var i = 1; i <= $NewLine; i++) {
        if ($RelVal == "") {
            $RelVal = i;
        } else {
            $RelVal = $RelVal + " and " + i;
        }
    }
    $('#' + $ID).val($RelVal);
};


//创建条件部分
$.fn.AddSeniorQueryLine = function ($data) {
    var $ID;
    var $SelectAttr;
    var $Line;
    var $AttrID;
    var $OperID
    var $ContentID;
    var $LineDivID;
    $ID = $(this).attr("id");
    $SelectAttr = $data.SelectAttr;
    $RelID = $data.RelID;
    $Line = $data.Line;
    $Line = $Line + 1;
    $AttrID = "attr_" + $ID + "_" + $Line;
    $OperID = "oper_" + $ID + "_" + $Line;
    $ContentID = "content_" + $ID + "_" + $Line;
    $LineDivID = $data.LineDivID + "_" + $Line;
    $("#" + $ID).append('' +
            '<div class="row" style="margin-bottom:5px;" id="' + $LineDivID + '">' +
            '<div class="col-xs-12">' +
            '<div class="row">' +
            '<div class="pricing-box pull-left" style="margin-top:5px;">' +
            '<span class="badge badge-info">' + $Line + '</span>' +
            '</div>' +
            '<div class="input-group pull-left" id="' + $AttrID + '" style="margin-left:3px;"></div>' +
            '<div class="input-group pull-left" id="' + $OperID + '" style="margin-left:10px;">' +
            '<select class="form-control" style="width:130px;" name="seniorquery[where][' + $Line + '][action]">' +
            '<option value="1"> --请先选属性-- </option>' +
            '</select>' +
            '</div>' +
            '<div class="pricing-box pull-left" id="' + $ContentID + '" style="float:left;width:300px;margin-left:3px;">' +
            '<input type="text" class="col-sm-12" readonly="readonly">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '');
    //初始化attr部分
    $("#" + $AttrID).AttrSelectInitial({
        SelectAttr: $SelectAttr,
        Line: $Line,
        OperID: $OperID,
        ContentID: $ContentID
    });
}




//初始化Attr部分
$.fn.AttrSelectInitial = function ($data) {
    var $ID;
    var $SelectAttr;
    var $SelectID;
    var $Line;
    var $OperID;
    var $ContentID;
    $ID = $(this).attr("id");
    $SelectAttr = $data.SelectAttr;
    $SelectID = "Select_" + $ID;
    $Line = $data.Line;
    $OperID = $data.OperID;
    $ContentID = $data.ContentID;
    $("#" + $ID).html("" +
            '<select class="form-control" id="' + $SelectID + '" style="float:left;" name="seniorquery[where][' + $Line + '][attr]">' +
            '<option value="0"> --选择属性-- </option>' +
            '</select>' +
            "")
    $.each($SelectAttr, function (i, val) {
        $("#" + $SelectID).append('<option value="' + val.name + '"> ' + val.label + ' </option>');
    });

    $("#" + $SelectID).chosen({allow_single_deselect: true});
    $("#" + $SelectID).next().css({'width': '200px'});
    $("#" + $SelectID).next().css({'float': 'left'});
    $("#" + $SelectID).next().find('.chosen-results').css({'height': '140px'});

    //每次选择属性都需要处理一下
    $("#" + $SelectID).change(function () {
        var $AttrName = $(this).val();
        var $AttrObj;
        $.each($SelectAttr, function ($k, $v) {
            if ($v.name == $AttrName) {
                $AttrObj = $v;
            }
        });
        //调用根据类型判断初始化oper下拉框
        $("#" + $OperID).OperSelectInitial({
            Line: $Line,
            AttrObj: $AttrObj,
            ContentID: $ContentID
        });
    });
}

//
$.fn.ContentInitial = function ($data) {
    var $ID;
    var $Line;
    var $OperVal;
    var $AttrObj;
    var $form_data;
    var $ContentID;
    $ID = $(this).attr("id");
    $Line = $data.Line;
    $OperVal = $data.OperVal;
    $AttrObj = $data.AttrObj;
    $form_data = $AttrObj.form_data;
    $ContentID = $data.ContentID;
    // $("#"+$ID).ContentText({
    // 	Line:$Line
    // });
    switch ($OperVal) {
        case 'NULL': //为空
        case 'NOT_NULL': //不为空
            $("#" + $ID).ContentText({
                readonly: true,
                Line: $Line,
                ContentID: $ContentID
            });
            break;
        case 'EQUAL': //等于
        case 'NOT_EQUAL': //不等于
            //这里需要判断属性类型，引用的效果和其他的不一样
            //console.debug($AttrObj);
            switch ($AttrObj.attr_type) {
                case '12': //单选RADIO
                case '13': //下拉框
                    $("#" + $ID).ContentSelect({
                        readonly: true,
                        Line: $Line,
                        form_data: $form_data,
                        ContentID: $ContentID
                    });
                    break;
                case '18':
                case '19': //引用
                    //alert("引用还没做好");
                    $("#" + $ID).ContentText({
                        Line: $Line,
                        ContentID: $ContentID
                    });
                    break;
                default:
                    $("#" + $ID).ContentText({
                        Line: $Line,
                        ContentID: $ContentID
                    });
                    break;
            }
            break;
        case 'BEFORE': //早于
        case 'AFTER': //晚于
            switch ($AttrObj.attr_type) {
                case '15': //日期
                    $("#" + $ID).ContentDate({
                        Line: $Line,
                        ContentID: $ContentID
                    });
                    break;
                case '16': //时间
                    $("#" + $ID).ContentTime({
                        Line: $Line,
                        ContentID: $ContentID
                    });
                    break;
                default:
                    alert("特殊情况？？");
                    break;
            }
            break;
        case 'RANGE': //区间
            switch ($AttrObj.attr_type) {
                case '1': //数值
                case '2': //百分比
                case '3': //金额
                    $("#" + $ID).ContentRangeText({
                        Line: $Line,
                        ContentID: $ContentID
                    });
                    break;
                case '15': //日期
                    $("#" + $ID).ContentRangeDate({
                        Line: $Line,
                        ContentID: $ContentID
                    });
                    break;
                case '16': //时间
                    $("#" + $ID).ContentRangeTime({
                        Line: $Line,
                        ContentID: $ContentID
                    });
                    break;
                default:
                    alert("特殊情况2？？");
                    break;
            }
            break;
        case 'RECENT': //最近
        case 'FUTURE': //未来
            $("#" + $ID).ContentRecentFutureText({
                Line: $Line,
                ContentID: $ContentID
            });
            break;
        default:
            $("#" + $ID).ContentText({
                Line: $Line,
                ContentID: $ContentID
            });
            break;
    }
}


$.fn.OperSelectInitial = function ($data) {
    var $ID;
    var $Line;
    var $ContentID;
    var $OperSelectID;
    var $AttrObj;
    var $form_data;
    $ID = $(this).attr("id");
    $Line = $data.Line;
    $ContentID = $data.ContentID;
    $OperSelectID = "oper_select_" + $ID + "_" + $Line;
    $AttrObj = $data.AttrObj;
    $("#" + $ID).html("" +
            '<select class="form-control" id="' + $OperSelectID + '" style="width:130px;" name="seniorquery[where][' + $Line + '][action]">' +
            '<option value="1"> --请先选属性-- </option>' +
            '</select>' +
            "");
    $form_data = $AttrObj.form_data;
    switch ($AttrObj.attr_type) {
        case '1'://数值
        case '3'://金额
            $("#" + $OperSelectID).NumberSelect({
                Line: $Line,
                form_data: $form_data,
                ContentID: $ContentID
            });
            break;
        case '2'://百分数
            $("#" + $OperSelectID).NumberSelect({
                Line: $Line,
                form_data: $form_data,
                ContentID: $ContentID
            });
            break;
        case '11'://CHECK
            $("#" + $OperSelectID).OperCheck({
                Line: $Line,
                form_data: $form_data,
                ContentID: $ContentID
            });
            break;
        case '12'://RADIO
        case '13'://这里是下拉框枚举类型
            $("#" + $OperSelectID).OperSelect({
                Line: $Line,
                form_data: $form_data,
                ContentID: $ContentID
            });
            break;
        case '15'://日期类型
            $("#" + $OperSelectID).OperDate({
                Line: $Line,
                form_data: $form_data,
                ContentID: $ContentID
            });
            break;
        case '16'://时间类型
            $("#" + $OperSelectID).OperTime({
                Line: $Line,
                form_data: $form_data,
                ContentID: $ContentID
            });
            break;
        case '18':
        case '19'://引用类型
        case '20'://引用类型
            $("#" + $OperSelectID).OperQuote({
                Line: $Line,
                form_data: $form_data,
                ContentID: $ContentID
            });
            break;
        default://其他 默认是文本类型
            $("#" + $OperSelectID).OperText({
                Line: $Line,
                form_data: $form_data,
                ContentID: $ContentID
            });
            break;
    }
    //每次选择属性都需要处理一下
    $("#" + $OperSelectID).change(function () {
        //选择的Opera类型初始化Content内容
        var $OperVal = $(this).val();
        $("#" + $ContentID).ContentInitial({
            Line: $Line,
            OperVal: $OperVal,
            AttrObj: $AttrObj,
            ContentID: $ContentID
        });
    });
}


//Oper引用类型
$.fn.OperQuote = function ($data) {
    var $ID;
    var $form_data;
    var $Line;
    var $ContentID;
    $ID = $(this).attr("id");
    $Line = $data.Line;
    $form_data = $data.form_data;
    $ContentID = $data.ContentID;
    $("#" + $ID).html('' +
            '<option value="EQUAL"> 等于 </option>' +
            '<option value="NOT_EQUAL"> 不等于 </option>' +
            '<option value="NULL"> 为空 </option>' +
            '<option value="NOT_NULL"> 不为空 </option>' +
            '');
    //初始化Content
    $("#" + $ContentID).ContentQuote({
        Line: $Line,
        form_data: $form_data
    });
}

//Content引用类型
$.fn.ContentQuote = function ($data) {
    var $ID;
    var $Line;
    var $InputID;
    var $form_data;
    $ID = $(this).attr("id");
    $Line = $data.Line;
    $InputID = "Input_" + $ID + "_" + $Line;
    $form_data = $data.form_data;
    $DialogWidth = $data.width ? $data.width : 600;
    $DialogHeight = $data.height ? $data.height : 500;
    $DialogTitle = $data.title ? $data.title + "引用" : "引用";
    $("#" + $ID).html('' +
            '<div class="input-group">' +
            '<input class="form-control" id="' + $InputID + '" type="text" name="seniorquery[where][' + $Line + '][value1]" />' +
            '</div>' +
            '');
    //加载引用控件
    $("#" + $InputID).QuoteInitial({
        width: $form_data.DialogWidth,
        title: $form_data.ObjName,
        //name:"seniorquery[where]["+$Line + "][value1]",
        url: $form_data.RefUrl,
        HiddenName: 'seniorquery[where][' + $Line + '][value2]'
    });
}


//Oper日期类型
$.fn.OperDate = function ($data) {
    var $ID;
    var $form_data;
    var $Line;
    var $ContentID;
    $ID = $(this).attr("id");
    $Line = $data.Line;
    $form_data = $data.form_data;
    $ContentID = $data.ContentID;
    $("#" + $ID).html('' +
            '<option value="AFTER"> 晚于 </option>' +
            '<option value="BEFORE"> 早于 </option>' +
            '<option value="RANGE"> 区间 </option>' +
            '<option value="RECENT"> 最近 </option>' +
            '<option value="FUTURE"> 未来 </option>' +
            '<option value="NULL"> 为空 </option>' +
            '<option value="NOT_NULL"> 不为空 </option>' +
            '');
    //初始化Content
    $("#" + $ContentID).ContentDate({
        Line: $Line
    });
}

//Content日期类型
$.fn.ContentDate = function ($data) {
    var $ID;
    var $Line;
    var $InputID;
    $ID = $(this).attr("id");
    $Line = $data.Line;
    $InputID = "Input_" + $Line;
    $("#" + $ID).html('' +
            '<div class="input-group">' +
            '<input class="form-control date-picker" id="' + $InputID + '" type="text"  name="seniorquery[where][' + $Line + '][value]"/>' +
            '<span class="input-group-addon">' +
            '<i class="fa fa-calendar bigger-110"></i>' +
            '</span>' +
            '</div>' +
            '');
    //加载日期控件
    $('#' + $InputID).datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,
        format: 'yyyy-mm-dd'
    });
}

//Content日期类型区间
$.fn.ContentRangeDate = function ($data) {
    var $ID;
    var $Line;
    var $InputID;
    $ID = $(this).attr("id");
    $Line = $data.Line;
    $InputID = "Input_" + $Line;
    $("#" + $ID).html('' +
            '<div class="input-group" style="margin-bottom: 3px">' +
            '<input class="form-control date-picker" id="time_' + $Line + '_1" type="text"  name="seniorquery[where][' + $Line + '][value1]"/>' +
            '<span class="input-group-addon">' +
            '<i class="fa fa-calendar bigger-110"></i>' +
            '</span>' +
            '</div>' +
            '<div class="input-group">' +
            '<input class="form-control date-picker" id="time_' + $Line + '_2" type="text"  name="seniorquery[where][' + $Line + '][value2]"/>' +
            '<span class="input-group-addon">' +
            '<i class="fa fa-calendar bigger-110"></i>' +
            '</span>' +
            '</div>' +
            '');
    //加载日期控件
    $('#time_' + $Line + '_1').datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,
        format: 'yyyy-mm-dd'
    });
    $('#time_' + $Line + '_2').datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,
        format: 'yyyy-mm-dd'
    });
}

//Oper时间类型
$.fn.OperTime = function ($data) {
    var $ID;
    var $form_data;
    var $Line;
    var $ContentID;
    var $InputID;
    $ID = $(this).attr("id");
    $Line = $data.Line;
    $form_data = $data.form_data;
    $ContentID = $data.ContentID;
    $InputID = "Input_" + $Line;
    $("#" + $ID).html('' +
            '<option value="AFTER"> 晚于 </option>' +
            '<option value="BEFORE"> 早于 </option>' +
            '<option value="RANGE"> 区间 </option>' +
            '<option value="RECENT"> 最近 </option>' +
            '<option value="FUTURE"> 未来 </option>' +
            '<option value="NULL"> 为空 </option>' +
            '<option value="NOT_NULL"> 不为空 </option>' +
            '');
    //初始化Content
    $("#" + $ContentID).ContentTime({
        Line: $Line
    });
}

//Content时间类型
$.fn.ContentTime = function ($data) {
    var $ID;
    var $Line;
    var $InputID;
    $ID = $(this).attr("id");
    $Line = $data.Line;
    $InputID = "Input_" + $Line;
    $("#" + $ID).html('' +
            '<div class="input-group">' +
            '<input class="form-control date-picker" id="' + $InputID + '" type="text"  name="seniorquery[where][' + $Line + '][value]"/>' +
            '<span class="input-group-addon">' +
            '<i class="fa fa-clock-o bigger-110"></i>' +
            '</span>' +
            '</div>' +
            '');
    //加载时间控件
    $('#' + $InputID).datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        format: 'yyyy-mm-dd hh:ii:00'
    });
}

//Content时间类型区间
$.fn.ContentRangeTime = function ($data) {
    var $ID;
    var $Line;
    $ID = $(this).attr("id");
    $Line = $data.Line;
    $("#" + $ID).html('' +
            '<div class="input-group" style="margin-bottom: 3px">' +
            '<input class="form-control date-picker" id="time_' + $Line + '_1" type="text"  name="seniorquery[where][' + $Line + '][value1]"/>' +
            '<span class="input-group-addon">' +
            '<i class="fa fa-clock-o bigger-110"></i>' +
            '</span>' +
            '</div>' +
            '<div class="input-group">' +
            '<input class="form-control date-picker" id="time_' + $Line + '_2" type="text"  name="seniorquery[where][' + $Line + '][value2]"/>' +
            '<span class="input-group-addon">' +
            '<i class="fa fa-clock-o bigger-110"></i>' +
            '</span>' +
            '</div>' +
            '');
    //加载日期控件
    $('#time_' + $Line + '_1').datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        format: 'yyyy-mm-dd hh:ii:00'
    });
    $('#time_' + $Line + '_2').datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        format: 'yyyy-mm-dd hh:ii:00'
    });
}


//Oper数值类型
$.fn.NumberSelect = function ($data) {
    var $ID;
    var $form_data;
    var $Line;
    var $ContentID;
    $ID = $(this).attr("id");
    $Line = $data.Line;
    $form_data = $data.form_data;
    $ContentID = $data.ContentID;
    $("#" + $ID).html('' +
            '<option value="EQUAL"> = </option>' +
            '<option value="GT"> > </option>' +
            '<option value="GT_EQUAL"> >= </option>' +
            '<option value="LE"> < </option>' +
            '<option value="LE_EQUAL"> <= </option>' +
            '<option value="NOT_EQUAL"> <> </option>' +
            '<option value="RANGE"> 区间 </option>' +
            '');
    $("#" + $ContentID).ContentText({
        Line: $Line
    });
}

//Oper下拉单选
$.fn.OperSelect = function ($data) {
    var $ID;
    var $form_data;
    var $Line;
    $ID = $(this).attr("id");
    $Line = $data.Line;
    $form_data = $data.form_data;
    $ContentID = $data.ContentID;
    $("#" + $ID).html('' +
            '<option value="EQUAL"> 等于 </option>' +
            '<option value="NOT_EQUAL"> 不等于 </option>' +
            '<option value="NULL"> 为空 </option>' +
            '<option value="NOT_NULL"> 不为空 </option>' +
            '');
    $("#" + $ContentID).ContentSelect({
        Line: $Line,
        form_data: $form_data
    });
}
//Content下拉单选
$.fn.ContentSelect = function ($data) {
    var $ID;
    var $Line;
    var $SelectID;
    $ID = $(this).attr("id");
    $Line = $data.Line;
    $SelectID = "content_select_" + $ID
    $form_data = $data.form_data
    $("#" + $ID).html('' +
            '<select id="' + $SelectID + '" class="col-sm-12" name="seniorquery[where][' + $Line + '][value]">' +
            '</select>' +
            '');
    //初始化下拉框
    $("#" + $SelectID).SelectInitial({
        not_null: true,
        enum_arr: $form_data.enum_arr
    });
};


//Oper文本类型
$.fn.OperText = function ($data) {
    var $ID;
    var $form_data;
    var $Line;
    $ID = $(this).attr("id");
    $Line = $data.Line;
    $form_data = $data.form_data;
    $ContentID = $data.ContentID;
    $("#" + $ID).html('' +
            '<option value="LIKE"> 包含 </option>' +
            '<option value="NOT_LIKE"> 不包含 </option>' +
            '<option value="EQUAL"> 等于 </option>' +
            '<option value="NOT_EQUAL"> 不等于 </option>' +
            '<option value="NULL"> 为空 </option>' +
            '<option value="NOT_NULL"> 不为空 </option>' +
            '');
    //content内容部分默认就直接一个文本输入框吧
    $("#" + $ContentID).ContentText({
        Line: $Line
    });

}
//Content文本类型
$.fn.ContentText = function ($data) {
    var $ID;
    var $Line;
    var $readonly;
    var $InputID;
    $ID = $(this).attr("id");
    $Line = $data.Line;
    $readonly = $data.readonly;
    $InputID = "content_input_" + $ID
    $("#" + $ID).html('' +
            '<input class="col-sm-12" id="' + $InputID + '" name="seniorquery[where][' + $Line + '][value]" type="text" />' +
            '');
    if ($readonly == true) {
        $("#" + $InputID).attr("readonly", true);
    }
};

//Content文本类型区间类(数值类型也使用该方法)
$.fn.ContentRangeText = function ($data) {
    var $ID;
    var $Line;
    var $InputID;
    $ID = $(this).attr("id");
    $Line = $data.Line;
    $InputID = "content_input_" + $ID
    $("#" + $ID).html('' +
            //'<input class="col-sm-6" name="seniorquery[where]['+$Line+'][value1]" type="text" />'+
            //'<input class="col-sm-6" name="seniorquery[where]['+$Line+'][value2]" type="text" />'+
            '<div class="input-daterange input-group col-sm-12">' +
            '<input class="input-sm form-control" type="text" name="seniorquery[where][' + $Line + '][value1]">' +
            '<span class="input-group-addon">' +
            '<i class="fa fa-exchange"></i>' +
            '</span>' +
            '<input class="input-sm form-control" type="text" name="seniorquery[where][' + $Line + '][value2]">' +
            '</div>' +
            '');
};

//Content文本类型最近未来类(时间类型使用该方法)
$.fn.ContentRecentFutureText = function ($data) {
    var $ID;
    var $Line;
    var $InputID;
    $ID = $(this).attr("id");
    $Line = $data.Line;
    $InputID = "content_input_" + $ID
    $("#" + $ID).html('' +
            //'<input class="col-sm-6" name="seniorquery[where]['+$Line+'][value1]" type="text" />'+
            //'<input class="col-sm-6" name="seniorquery[where]['+$Line+'][value2]" type="text" />'+
            '<div class="input-daterange input-group col-sm-12">' +
            '<input type="text" class="col-sm-9" name="seniorquery[where][' + $Line + '][value1]">' +
            '<select class="col-sm-3" name="seniorquery[where][' + $Line + '][value2]">' +
            '<option value="DAY"> 天 </option>' +
            '<option value="WEEK"> 周 </option>' +
            '<option value="MONTH"> 月 </option>' +
            '<option value="YEARS"> 年 </option>' +
            '</select>' +
            '</div>' +
            '');
};







//获取url里的get参数，转换成数组对象
function GetUrlPars() {
    var url = location.search;
    var theRequest = new Object();
    if (url.indexOf("?") != -1) {
        var str = url.substr(1);
        strs = str.split("&");
        for (var i = 0; i < strs.length; i++)
        {
            var sTemp = strs[i].split("=");
            theRequest[sTemp[0]] = (sTemp[1]);
        }
    }
    return theRequest;
}