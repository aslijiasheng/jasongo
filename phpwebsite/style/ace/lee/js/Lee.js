//小控件都放在这里
/**
 * 给jquery添加一个回车控件
 */
jQuery.fn.extend({
    enter: function(data){
        $(this).bind('keydown',function(event){
            if (event.keyCode == "13") {
                data($(this).val());
            }
        });
        return this;
    }
});



// ----------------------------------------------------------------------
// <summary>
// 限制只能输入数字
// </summary>
// ----------------------------------------------------------------------
$.fn.onlyNum = function () {
    $(this).keypress(function (event) {
        var eventObj = event || e;
        var keyCode = eventObj.keyCode || eventObj.which;
        if ((keyCode >= 48 && keyCode <= 57) || keyCode == 8)
            return true;
        else
            return false;
    }).focus(function () {
            //禁用输入法
            this.style.imeMode = 'disabled';
        }).bind("paste", function () {
            //获取剪切板的内容
            var clipboard = window.clipboardData.getData("Text");
            if (/^\d+$/.test(clipboard))
                return true;
            else
                return false;
        });
};

jQuery.prototype.serializeObject=function(){
    var obj=new Object();
    $.each(this.serializeArray(),function(index,param){
        if(!(param.name in obj)){
            obj[param.name]=param.value;
        }
    });
    return obj;
};

// ----------------------------------------------------------------------
// <summary>
// 限制只能输入金额
// </summary>
// ----------------------------------------------------------------------
$.fn.onlyMoney = function () {
    $(this).keypress(function (event) {
        var eventObj = event || e;
        var keyCode = eventObj.keyCode || eventObj.which;
        if ((keyCode >= 48 && keyCode <= 57) || keyCode == 46 || keyCode == 8)
            return true;
        else
            return false;
    }).focus(function () {
            this.style.imeMode = 'disabled';
        }).bind("paste", function () {
            var clipboard = window.clipboardData.getData("Text");
            if (/^(\d|[a-zA-Z])+$/.test(clipboard))
                return true;
            else
                return false;
        });
};