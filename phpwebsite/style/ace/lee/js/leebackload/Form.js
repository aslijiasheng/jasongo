function FormLoad(){
    //引用类型加载
    $("[lee-type=quote]").each(function(){
        $IsLoad = $(this).attr("data-isload") //是否加载过
        if($IsLoad!=1){ //没有加载过的才能加载，不允许多次加载
            //var $ID = $(this).attr("id");
            $(this).QuoteInitial({
                width:700,
                title:$(this).attr("data-ObjName"),
                name:$(this).attr("name"),
                url:$(this).attr("data-url"),
                id_value:$(this).attr("data-valueid")
            });
            $(this).attr("data-isload",1); //标记已经加载过了
        }
    });

    //加载所有标记为leeform的属性
    $("[isleeform=1]").each(function(){
        var $ID = $(this).attr("id");
        var $attr = $.fn.LeeForm[$ID];
        if($attr){ //首先要有属性才去处理
            if($attr.IsLoad!=1){ //没有加载过的才能加载，不允许多次加载
                console.debug($.fn.LeeForm[$ID]);
                //这里处理所有触发点
                if($attr.is_calc_point==1){
                    $(this).FormCalcAttr($attr.formulra_data);
                }
                $attr.IsLoad=1; //标记已经加载过了
            }
        }
//        $IsLoad = $(this).attr("data-isload") //是否加载过
//        if($IsLoad!=1){ //没有加载过的才能加载，暂时不允许多次加载
//            //var $ID = $(this).attr("id");
//            $(this).QuoteInitial({
//                width:700,
//                title:$(this).attr("data-ObjName"),
//                name:$(this).attr("name"),
//                url:$(this).attr("data-url"),
//                id_value:$(this).attr("data-valueid")
//            });
//            $(this).attr("data-isload",1); //标记已经加载过了
//        }
    });
}
