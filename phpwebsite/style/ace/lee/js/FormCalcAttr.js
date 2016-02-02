//给这个DOM做计算型字段事件
$.fn.FormCalcAttr = function ($data) {
    var $this = $(this);
    $(this).change(function(){
        CalcChange($this);
    });

    /**
     * 触发计算型字段的变化
     * @param $data
     * @param $this
     * @constructor
     */
    function CalcChange($this){
        var $thisattr=$.fn.LeeForm[$this.attr('id')]; //获取当前表格的相关数据
        if(!$thisattr){
            return;
        }
        //判断这个DOM是否为计算型字段
        if(parseInt($thisattr.is_calc_point)==1){
            console.debug($thisattr);
            $.each($thisattr.formulra_data,function($k,$v){
                var $calc_formula = $v.calc_formula; //公式
                var $calc_result_attr = $v.calc_result_attr; //传递的属性
                var $is_item = $thisattr.is_item; //是否明细
                var $Result = AnalyticalFormula($calc_formula,$this); //解析公式后转化的结果
                console.debug("$Result:"+$Result);
                //保留小数点后两位
                $Result = $Result.toFixed(2);
                //console.debug($is_item);
                if($is_item==1){
                    //这里代表是明细，值抓取明细
                    var $row = $thisattr.row;
                    //console.debug($row);
                    var $is_fz = 0; //是否赋值
                    $this.closest("form").find("[data-attrname='"+$calc_result_attr+"']").each(function(){
                        if($(this).attr('data-row')==$row){
                            if(!parseInt($(this).val())){
                                $(this).val(0);
                            }
                            if(!parseInt($Result)){
                                $Result = 0;
                            }
                            console.debug(parseInt($(this).val())+":"+parseInt($Result));
                            if(parseInt($(this).val())!=parseInt($Result)){
                                $(this).val($Result);
                                //递归触发下一个DOM的计算
                                CalcChange($(this));
                            }//结束
                            $is_fz=1;
                        }
                    });
                    console.debug("$is_fz:"+$is_fz);
                    if($is_fz==0){
                        //如果不是明细就直接给主表赋值
                        console.debug("[data-attrname='"+$calc_result_attr+"']");
                        $this.closest("form").find("[data-attrname='"+$calc_result_attr+"']").each(function(){
                            //console.debug($(this));
                            if(!parseInt($(this).val())){
                                $(this).val(0);
                            }
                            if(!parseInt($Result)){
                                $Result = 0;
                            }
                            console.debug(parseInt($(this).val())+":"+parseInt($Result));
                            if(parseInt($(this).val())!=parseInt($Result)){
                                $(this).val($Result);
                                //递归触发下一个DOM的计算
                                CalcChange($(this));
                            }//结束
                        });
                    }
                }else{
                    //如果不是明细就直接给主表赋值
                    $this.closest("form").find("[data-attrname='"+$calc_result_attr+"']").each(function(){
                        if(!parseInt($(this).val())){
                            $(this).val(0);
                        }
                        if(!parseInt($Result)){
                            $Result = 0;
                        }
                        console.debug(parseInt($(this).val())+":"+parseInt($Result));
                        if(parseInt($(this).val())!=parseInt($Result)){
                            $(this).val($Result);
                            //递归触发下一个DOM的计算
                            CalcChange($(this));
                        }//结束
                    });
                }
            });
        }
    }

    /**
     * 解析公式
     * @param $Formula
     * @param $this
     * @returns {*}
     * @constructor
     */
    function AnalyticalFormula($Formula,$this){
        //先用正则匹配到所有的函数
        //console.debug($Formula);
        $Formula = AnalyticalINTSUM($Formula,$this); //解析INTSUM函数
        //console.debug($Formula);
        $Formula = AnalyticalLast($Formula,$this); //最后替换所有的属性
        //console.debug($Formula);
        return $Formula;
    }

    /**
     * 解析INTSUM函数
     * @param $Formula
     * @param $this
     * @returns {*}
     * @constructor
     */
    function AnalyticalINTSUM($Formula,$this){
        //正则匹配
        //$zz = "";
        var $arr = $Formula.match(/\{INTSUM\(.*\)\}/ig);
        //console.debug($arr);
        if($arr){
            for (i=0;i<$arr.length;i++){
                var $Content = $arr[i]; //匹配到的内容
                //获取括号里的值
                $attr=$Content.replace(/\{INTSUM\(#(.*)#\)\}/ig,"$1");
                //获取所有这个data-attrname为这个属性的值 然后汇总
                $IntSum = 0;
                $this.closest("form").find("[data-attrname='"+$attr+"']").each(function(){
                    if($(this).val()){
                        $IntSum = $IntSum+parseInt($(this).val());
                    }else{
                        $IntSum = $IntSum+parseInt(0);
                    }
                });
                //把结果替换回去
                $Formula=$Formula.replace($Content,$IntSum);
            }
        }
        return $Formula;
    }

    /**
     * 最后替换所有的属性
     * @param $Formula
     * @param $this
     * @returns {*}
     * @constructor
     */
    function AnalyticalLast($Formula,$this){
        //最后再替换所有的值
        var $arr = $Formula.match(/#([^#]*)#/ig);
        //console.debug($arr);
        if($arr){
            for (i=0;i<$arr.length;i++){
                var $Content = $arr[i]; //匹配到的内容
                //console.debug($Content);
                //获取括号里的值
                $attr=$Content.replace(/#([^#]*)#/ig,"$1");
                //首先判断是否明细
                var $thisattr=$.fn.LeeForm[$this.attr('id')]; //获取当前表格的相关数据
                if($thisattr.is_item==1){
                    //这里代表是明细，值抓取明细
                    var $row = $thisattr.row;
                    //console.debug("[data-attrname='"+$attr+"'],[data-row='"+$row+"']");
                    var $IntSum = 0;
                    $this.closest("form").find("[data-attrname='"+$attr+"']").each(function(){
                        if($(this).attr('data-row')==$row){

                            //console.debug("parseInt"+parseInt($(this).val()));
                            if(!parseInt($(this).val())){
                                $(this).val(0);
                            }
                            $IntSum = $(this).val();
                            //console.debug($Formula+"|"+$IntSum);
                            $Formula=$Formula.replace($Content,$IntSum);
                        }
                    });
                    $Formula=$Formula.replace($Content,$IntSum);
                    //console.debug($Formula);
                }
            }
        }
        //最后在吧计算结果返回
        $Formula = eval($Formula);
        return $Formula;
    }
}