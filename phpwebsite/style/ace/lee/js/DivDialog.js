/**
 * 封装的AjaxDialog组件
 * 属性：
 * DialogUrl ajax连接控制器载入HTML的地址
 * DialogWidth Dialog宽度
 * DialogHeight Dialog高度
 * DialogTitle Dialog的标题
 * DialogButtons Dialog的按钮部分 数组格式
 */
$.fn.AjaxDialog = function ($data) {
    var $ID = $(this).attr("id");
    var $DialogDivID = "dialog_div_" + $ID;
    var $DialogUrl = $data.DialogUrl;
    var $DialogData = $data.DialogData ? $data.DialogData : false;
    var $DialogWidth = $data.DialogWidth ? $data.DialogWidth : 600;
    var $DialogHeight = $data.DialogHeight ? $data.DialogHeight : 300;
    var $DialogTitle = $data.DialogTitle ? $data.DialogTitle : "弹出框";
    var $DialogButtons = $data.DialogButtons ? $data.DialogButtons : false;
    //给这个方法下面添加一个div用于弹出dialog,这里需要判断一下这个div是否存在
    if ($('#' + $DialogDivID).length == 0) {
        $(this).after('<div id="' + $DialogDivID + '" style="display:none;"></div>');
    }

    $(this).on('click', function () {
        $.ajax({
            'type': 'post',
            'data': $DialogData,
            'success': function (data) {
                $('#' + $DialogDivID).html(data);
            },
            'url': $DialogUrl,
            'cache': false
        });
        $("#" + $DialogDivID).dialog({
            title: $DialogTitle,
            modal: true,
            width: $DialogWidth,
            height: $DialogHeight,
            buttons: $DialogButtons
        });
        $('#' + $DialogDivID).dialog('open');
    });
};

/**
 * 封装的Ajax组件
 * 属性：
 * Data 参数，默认post传值
 * Url 地址
 */
$.fn.AjaxHtml = function ($data) {
    var $this = $(this);
    var $Data = $data.Data ? $data.Data : false;
    var $Url = $data.Url;
    $.ajax({
        'type': 'post',
        'data': $Data,
        'success': function (data) {
            $this.html(data);
        },
        'url': $Url,
        'cache': false
    });
};