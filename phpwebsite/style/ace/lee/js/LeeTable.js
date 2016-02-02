/**
 * 封装的table组件
 * @param $data
 * @param $LeeData
 * @constructor
 */
$.fn.LeeTable = function ($data, $LeeData) {
    var $this = $(this);
    if (typeof $data == "object") {
        //是对象代表创建,调用创建方法
        $this.AddTable($data);
        //alert("是对象");
    } else {
        var $ID = $this.attr("ID");
        var $TableID = $ID + "_Table"; //table部分
        switch ($data){
            case 'GetSelectedID':
                return $.fn.LeeTable.SelectedID;
                break;
            case 'GetSelectedAttr':
                return $.fn.LeeTable.SelectedAttr;
                break;
            case 'GetPage':
                return $("#"+$TableID).getGridParam('page');
                break;
            case 'SetGridParam': //刷新列表
                var gridParam = {};
                if($LeeData){
                    gridParam.url = $LeeData.Url ? $LeeData.Url : $.fn.LeeTable.data[$ID].Url; //Url
                    gridParam.datatype = $LeeData.DataType ? $LeeData.DataType : "json"; //数据格式
                    gridParam.postData = $LeeData.postData ? $LeeData.postData : {}; //Post参数
                    gridParam.page = $LeeData.Page ? $LeeData.Page : $("#"+$TableID).getGridParam('page'); //页码
                    gridParam.rows = $LeeData.Rows ? $LeeData.Rows : $("#"+$TableID).getGridParam('rows');
                }else{
                    gridParam.url = $.fn.LeeTable.data[$ID].Url;
                    gridParam.page= $("#"+$TableID).getGridParam('page');
                    gridParam.datatype="json";
                    gridParam.postData={};
                    gridParam.rows = $("#"+$TableID).getGridParam('rows');
                }
                //console.debug(1111);
                //console.debug(gridParam);
                $("#"+$TableID).jqGrid('setGridParam',gridParam).trigger("reloadGrid");
                break;
            case 'AdaptiveGridWidth':
                $("#"+$TableID).jqGrid('setGridWidth', $("#"+$ID).width());
                break;
            case 'AddRowData':
                if($LeeData){
                    //循环这些数据赋值
                    $.each($LeeData,function($k,$v){
                        var $LineNumber=$.fn.LeeTable.data[$ID].LineNumber;

                        //组合出新增需要的json数组
                        var $new_json_data = {};
                        var $is_hidden_id = 0;
                        //循环COL把计算型字段数据添加进去
                        $.each($.fn.LeeTable.data[$ID].Col,function(k,v){
                            //转化成相应的数据
                            //根据不同类型来换取不同的控件
                            var $new_data = {};
                            $new_data.FormData = {};
                            $.each(v.FormData,function(kk,vv){
                                $new_data.FormData[kk] = vv;
                            });
                            $new_data.AttrType = v.AttrType;
                            $new_data.FormData.ID = "form_"+v.FormData.Name.replace(".","_")+"_"+$LineNumber;
                            $new_data.FormData.Name = "item["+$LineNumber+"]["+v.FormData.Name+"]";
                            //$new_data.FormData.Style = 'width:180px';
                            $new_data.FormData.Value = $v[v.FormData.Name];
                            if($new_data.AttrType==18){
                                $new_data.FormData.ValueID = $v[v.FormData.Name+"ID"];
                            }
                            if($is_hidden_id==0){
                                //给这刚添加隐藏输入框给ID
                                var $KeyAttrName = $.fn.LeeTable.data[$ID].KeyAttrName;
                                //console.debug($.fn.LeeTable.data[$ID].KeyAttrName);
                                $is_hidden_id = 1
                                $new_json_data[k] = '<input type="hidden" name="item['+$LineNumber+']['+$KeyAttrName+']" value="'+$v[$KeyAttrName]+'">'+TypeGetFormHtml($new_data);
                            }else{
                                $new_json_data[k] = TypeGetFormHtml($new_data);
                            }
                        });
                        $("#"+$TableID).jqGrid('addRowData',$LineNumber,$new_json_data);
                        FormLoad();
                        $.fn.LeeTable.data[$ID].LineNumber++;
                    });
                }else{
                    var $LineNumber=$.fn.LeeTable.data[$ID].LineNumber;
                    //组合出新增需要的json数组
                    var $new_json_data = {};
                    //循环COL把计算型字段数据添加进去
                    $.each($.fn.LeeTable.data[$ID].Col,function(k,v){
                        //转化成相应的数据
                        //根据不同类型来换取不同的控件
                        var $new_data = {};
                        $new_data.FormData = {};
                        $.each(v.FormData,function(kk,vv){
                            $new_data.FormData[kk] = vv;
                        });
                        //alert(k);
                        $new_data.AttrType = v.AttrType;
                        $new_data.FormData.ID = "form_"+v.FormData.Name.replace(".","_")+"_"+$LineNumber;
                        $new_data.FormData.Name = "item["+$LineNumber+"]["+v.FormData.Name+"]";
                        $new_data.FormData.attr_name = v.FormData.Name;
                        $new_data.FormData.row = $LineNumber;
                        if(v.is_calc_point==1){ //代表有触发点
                            if(!$.fn.LeeForm[$new_data.FormData.ID]){
                                $.fn.LeeForm[$new_data.FormData.ID] = {};
                            }
                            $.fn.LeeForm[$new_data.FormData.ID].is_calc_point = v.is_calc_point;
                            $.fn.LeeForm[$new_data.FormData.ID].formulra_data = v.formulra_data;
                            $.fn.LeeForm[$new_data.FormData.ID].is_item = 1;//是否明细 再这里生成的都是明细
                            $.fn.LeeForm[$new_data.FormData.ID].attr_name = v.FormData.Name; //属性名称
                            $.fn.LeeForm[$new_data.FormData.ID].row = $LineNumber; //行号
                        }
                        //$new_data.FormData.Style = 'width:180px';
                        //console.debug($new_data);
                        $new_json_data[k] = TypeGetFormHtml($new_data);
                    });
                    //return false;
                    $("#"+$TableID).jqGrid('addRowData',$LineNumber,$new_json_data);
                    FormLoad();
                    $.fn.LeeTable.data[$ID].LineNumber++;
                }
                break;
            case 'DelRowData':
                if(!$LeeData.row){
                    alert("没有序号不能删除");
                    return false;
                }else{
                    var $row = $LeeData.row;
                    $("#"+$TableID).jqGrid("delRowData", $row);
                }
                break;

            default:
                alert('未知方法');
        }
        //alert("不是对象");
        //console.debug($.fn.LeeTable.data);
    }
};

/**
 * 创建方法
 * @param $data
 * @constructor
 */
$.fn.AddTable = function ($data) {
    //var $this = $(this);
    //ID的名称，为了以后更好的操作
    console.debug('$data');
    console.debug($data);
    var $ID;
    //$.fn.LeeTable[$ID] = {}
    if ($data.ID) {
        $ID = $data.ID;
    } else if ($(this).attr("id")) {
        $ID = $(this).attr("id");
    } else {
        $ID = "LeeTable";
    }
    $data.ID = $ID;
    if(!$.fn.LeeTable.data){
        $.fn.LeeTable.data = {}
    }
    $.fn.LeeTable.data[$ID] = $data;
    //console.debug($ID);
    //console.debug($.fn.LeeTable.data);
    //return false;
    //再这个DIV下创建
    var $TableID = $ID + "_Table"; //table部分
    var $PagerID = $ID + "_Pager"; //分页部分
    var $paging_no = []; //当前页面的选中
    var $paging_other = []; //其他页的选中
    var $page_end = []; //两个选中的合并值，最终需要的结果
    var $paging_attr = {}; //选中的参数记录,一个对象
    $(this).append('<table id="' + $TableID + '"></table>');
    $(this).append('<div id="' + $PagerID + '"></div>');
    //这里定义一些必填阐述
    //if (!$data.Url || !$data.Col || !$data.KeyAttrName) {
    if ((!$data.Col || !$data.KeyAttrName )&& !$data.IsEdit) {
            alert("LeeTable必填参数不全：\r\n" +
            //"Url:获取数据地址 \r\n" +
            "Col:结构数据，用于转化colNames和colModel \r\n" +
            "KeyAttrName:组件属性，用于给多选、单选、选中值等...");
        return false;
    }
    var $Url = $data.Url?$data.Url:false;
    var $col = $data.Col;
    var $KeyAttrName = $data.KeyAttrName;
    var $NameAttrName = $data.NameAttrName ? $data.NameAttrName : "";
    var $TableHeight = $data.TableHeight ? $data.TableHeight : 420; //列表的高度
    var $rowNum = $data.rowNum ? $data.rowNum : 10; //每页多少条
    var $Operation = $data.Operation ? $data.Operation : false; //操作菜单
    var $OperationHead = $data.OperationHead ? $data.OperationHead : "菜单"; //操作菜单
    var $MultiSelect =  $data.MultiSelect ? $data.MultiSelect :false; //是否开启全选功能
    var $MultiType =  $data.MultiType ? $data.MultiType :0; //是否开启全选功能
    var $postData = $data.postData ? $data.postData : {}; //Post参数
    var $DataType = $data.DataType ? $data.DataType : "json"; //数据类型
    var $IsNotPage = $data.IsNotPage ? $data.IsNotPage : 0; //数据类型
    var $Pager = "#"+$PagerID;
    var json_data;
    if($IsNotPage==1){
        $Pager = false;
    }


    var $colNames = new Array(); //表头 json
    var $i = 0;
    var $OperationHeadNumber = 0;
    if($Operation || $data.IsEdit==true){
        var $OperationHeadHtml;
        if(typeof $OperationHead == "object"){
            //是对象将操作改成按钮
            $OperationHeadHtml = GetButtonHtml($OperationHead)
            $OperationHeadNumber = $OperationHead.length;
        }else{
            $OperationHeadHtml = $OperationHead;
        }
        //如果是编辑模式，自动添加两个按钮 start
        if($data.IsEdit==true){
            var $AddID = "Add_"+$ID;
            var $ButtonHtml = ' <a id="'+$AddID+'" title="添加行"><span class="fa fa-plus orange bigger-150"></span></a> ';
            $OperationHeadNumber = $OperationHeadNumber+1;
            if(typeof $OperationHead == "object"){
                $OperationHeadHtml = $ButtonHtml + $OperationHeadHtml;
            }else{
                $OperationHeadHtml = $ButtonHtml;
            }
        }
        $colNames[$i] = $OperationHeadHtml;
        //如果是编辑模式，自动添加两个按钮 end
        $i++
    }
    $.each($col,function(i,val){
        $colNames[$i] = val.LangEn;
        $i++
    });
    var $colModel = new Array(); //表体和表头的关系 json
    var $i = 0;
    if($Operation || $data.IsEdit==true){
        $colModel[$i] = new Object;
        $colModel[$i].name="operation";
        $colModel[$i].index="operation";
        $colModel[$i].fixed=true;
        //alert($OperationHeadNumber+"--"+$Operation.length);
        var $OperationLength;
        if($data.IsEdit==true){
            //如果是编辑，默认有一个删除按钮，所以总数+1
            $OperationLength = $Operation.length+1;
        }else{
            $OperationLength = $Operation.length;
        }
        //alert($OperationHeadNumber+"--"+$OperationLength);
        if($OperationHeadNumber<$OperationLength){
            $colModel[$i].width = 26*$OperationLength+4;
        }else{
            $colModel[$i].width = 26*$OperationHeadNumber+4;
        }
        $colModel[$i].sortable=false; //去掉排序
        $i++;
    }
    $.each($col,function(i,val){
        $colModel[$i] = new Object;
        $colModel[$i].name=val.Name;
        $colModel[$i].index=val.Name;
        $colModel[$i].width = val.Width ? val.Width : 200;
        $colModel[$i].sortable=false; //去掉排序
        $colModel[$i].fixed=true;
        if(val.Url){
            $colModel[$i].formatter=function(cellvalue, options, rowObject){
                return "<a href='"+val.Url+"'>"+cellvalue+"</a>";
            };
        }
        $i++;
    });

    //如果是编辑型列表，执行下面的东西 start
    if($data.IsEdit==true){
        $DataType = "local";
        $Pager = false;
        $rowNum = false;
        if(!$.fn.LeeTable.data[$ID].LineNumber){
            //var $LineNumber=1;
            //行号
            $.fn.LeeTable.data[$ID].LineNumber = 1;
        }

    }
    //如果是编辑型列表，执行下面的东西 end
    $("#"+$TableID).jqGrid({
        url: $Url,
        mtype: "POST",
        datatype: $DataType, //统一json格式
        postData: $postData,
        //width:10000;
        //autowidth:false,
        height: $TableHeight,
        colNames: $colNames,
        colModel: $colModel,
        viewrecords: true, //右下角的总数显示
        rowNum: $rowNum,
        pager: $Pager,
        altRows: true,
        multiselect: $MultiSelect, //开启全选功能
        //multiboxonly: true,
        //获取传回来的JOSN数据
        beforeRequest:function(){
            //初始化
            //如果是单选，把全选按钮屏蔽掉
            if($MultiType==1){
                $("#cb_"+$TableID).hide();
            }

            //如果是编辑模式，初始化 start
            if($data.IsEdit==true){

                //给添加的按钮添加方法
                $("#"+$AddID).click(function(){
                    $("#"+$ID).LeeTable("AddRowData");
                });
            }
            //如果是编辑模式，初始化 end
        },
        beforeProcessing: function (xhr) {
            json_data = xhr;
        },
        gridComplete: function () {
            var ids = $("#"+$TableID).getDataIDs();//jqGrid('getDataIDs');
            var $OperationHtml = "";
            if($Operation){
                $OperationHtml = GetButtonHtml($Operation); //组合Button的Html页面
            }
            if($data.IsEdit==true){
                //编辑的处理
                //新增的按钮（删除）
                for (var i = 1; i <= ids.length; i++) {
                    //var new_i = i;
                    var $DelID = "Del_"+$ID+"_"+i;

                    var $DelHtml = ' <a id="'+$DelID+'" data-row="'+i+'" title="删除行"><span class="fa fa-trash-o red bigger-150"></span></a> ';
                    $("#"+$TableID).jqGrid('setRowData', ids[i - 1], {operation: $OperationHtml+$DelHtml});
                    //最后给这个删除按钮添加事件
                    $("#"+$DelID).click(function(){
                        //console.debug($(this).closest("tr").attr("id"));
                        $row = $(this).closest("tr").attr("id");//获取当前行的ID号码
                        //$row = $(this).attr("data-row");
                        $("#"+$ID).LeeTable("DelRowData",{'row':$row});
                        //$LineNumber--;//行号
                        //$.fn.LeeTable.data.LineNumber = $LineNumber; //给全局行号
                    });
                }
            }else{
                //常规的处理
                //var page_other_no = other;
                //console.debug(ids.length);
                //循环所有列
                var page_other_no = $paging_no.concat($paging_other); //两个数组合并
                var new_page_no = [];
                for (var i = 1; i <= ids.length; i++) {
                    //console.debug(json_data);
                    var param_id = json_data.rows[i - 1].cell[$KeyAttrName];
                    $("#jqg_"+$TableID+"_" + i).attr("value", param_id);
                    if($NameAttrName!=""){
                        $("#jqg_"+$TableID+"_" + i).attr("data-name", json_data.rows[i - 1].cell[$NameAttrName]);
                    }
                    if($Operation){
                        //alert($OperationHtml);
                        $("#"+$TableID).jqGrid('setRowData',ids[i - 1],{operation: $OperationHtml});
                    }
                    if (page_other_no.indexOf(param_id) > -1) {
                        //page_other_no.remove(param_id);
                        page_other_no.splice($.inArray(param_id,page_other_no),1);
                        new_page_no.push(param_id);
                        $("#"+$TableID).jqGrid('setSelection', i);
                    }
                    $paging_other = page_other_no;
                    $paging_no = new_page_no;
                    //console.debug($paging_no+"-"+$paging_other);
                    meger();

                    //如果是单选执行的东西 start
                    if($MultiType==1){
                        $("#jqg_"+$TableID+"_" + i).attr("type","radio");
                        $("#jqg_"+$TableID+"_" + i).attr("name","jqg_"+$TableID);
                    }
                    //如果是单选执行的东西 end

                    //判断所有的按钮 是否包含IfButton start
                    $.each($Operation,function(IBi,IBv){
                        if(IBv.IfButton){
                            //alert(IBi);
                            //循环里面的条件
                            $.each(IBv.IfButton,function(IBi2,IBv2){
                                //console.debug(json_data.rows[i - 1].cell[IBv2.If]+"--"+IBv2.Etc);
                                //console.debug(IBv2.Etc);
                                if(json_data.rows[i - 1].cell[IBv2.If]==IBv2.Etc){
                                    //修改这个按钮的样式
                                    $ButDom = $("#"+$TableID).find("#"+i).find("[data-no="+IBi+"]");
                                    if(IBv2.Title){$ButDom.attr("title",IBv2.Title);}
                                    if(IBv2.Url){$ButDom.attr("href",IBv2.Url);}
                                    if(IBv2.Js){$ButDom.attr("onclick",IBv2.Js);}
                                    if(IBv2.Css){$ButDom.find("span").attr("class",IBv2.Css+" bigger-150");}
                                }
                            });
                        }
                    });
                    //判断所有的按钮 是否包含IfButton end

                    //处理每个单元格里面包含{xxx}的这种参数，然后替换成属性 start
                    $("#"+$TableID).find("#"+i).find("td").each(function(tdk,tdv){
                        if($(this).attr("aria-describedby")!=$TableID+'_cb'){
                            var $html = $(this).html();
                            //console.debug($html);
                            $(this).html(AttrAnalytical($html,json_data,i));
                        }
                    });
                    //处理每个单元格里面包含{xxx}的这种参数，然后替换成属性 end
                }
            }
            //每次执行一次宽度自适应
            $("#"+$TableID).jqGrid('setGridWidth', $("#"+$ID).width());
        },
        onSelectAll: function (aRowids, status) {
            //点击全选的时候
            var data = [];
            $("#"+$TableID).find("input[type='checkbox']:checked").each(function (k, v) {
                data.push($(this).val());
            })
            $paging_no=data;
            meger();
        },
        onSelectRow: function (rowid, status) {
            var data = [];
            $("#"+$TableID).find("input[type='checkbox']:checked").each(function (k, v) {
                data.push($(this).val());
            })
            $paging_no = data;
            meger();

            //如果是单选执行的东西 start
            if($MultiType==1){
                $("#"+$TableID).find("#jqg_"+$TableID+"_"+(rowid))[0].checked = true;
                $("#"+$TableID).find("tr").removeClass('ui-state-highlight');//去掉当前行的绿色
                var data = [];
                data.push($(":radio[name='jqg_"+$TableID+"']:checked").val());
                //console.debug(data);
                $paging_no = data;
                $paging_other = [];
                meger();
            }
            //如果是单选执行的东西 end
            
        },
        loadComplete: function () {
            var table = this;
            setTimeout(function () {
                updatePagerIcons(table);
            }, 0);
        }
    });

    function meger() {
        $page_end = $paging_no.concat($paging_other);
        $.fn.LeeTable.SelectedID = $page_end;
        //console.debug($paging_no);
        //console.debug($paging_other);
        //console.debug($page_end);

        //讲$page_end用逗号分割成数组
        var $page_end_arr = $page_end;
        //console.debug($page_end_arr);
        //循环$page_end_arr和$paging_attr做对比
        $.each($page_end_arr,function(end_k,end_v){
            //首先判断这个ID在$paging_attr里是否存在
            if($paging_attr.end_v){
                //存在就不需要处理了
            }else{
                //不存在就要根据json_data来获取值
                $.each(json_data.rows,function(data_k,data_v){
                    if(data_v.cell[$KeyAttrName]==end_v){
                        $paging_attr[end_v] = data_v.cell;
                    }
                });
            }
        });
        //alert($page_end_arr);
        //循环$paging_attr和$page_end做对比，找到多余的 去除掉
        $.each($paging_attr,function(attr_k,attr_v){
            if($page_end_arr.indexOf(attr_k)>=0){
                //代表存在
            }else{
                //不存在则删除这个对象
                delete $paging_attr[attr_k];
            }
        });
        //console.debug($paging_attr);
        $.fn.LeeTable.SelectedAttr = $paging_attr;//给到全局变量
    }

    function updatePagerIcons(table) {
        var replacement =
        {
            'ui-icon-seek-first': 'ace-icon fa fa-angle-double-left bigger-140',
            'ui-icon-seek-prev': 'ace-icon fa fa-angle-left bigger-140',
            'ui-icon-seek-next': 'ace-icon fa fa-angle-right bigger-140',
            'ui-icon-seek-end': 'ace-icon fa fa-angle-double-right bigger-140'
        };
        $('.ui-pg-table:not(.navtable) > tbody > tr > .ui-pg-button > .ui-icon').each(function () {
            var icon = $(this);
            var $class = $.trim(icon.attr('class').replace('ui-icon', ''));

            if ($class in replacement)
                icon.attr('class', 'ui-icon ' + replacement[$class]);
        })
    }

    /**
     * 解析{attr}各种属性的方法
     * @param content 内容
     * @param data 全部属性
     * @param row 行号
     * @returns {*} 返回解析好的结果
     * @constructor
     */
    function AttrAnalytical(content,data,row){
        var $match = content.match(/\{([^}]+)\}/g);
        //console.debug($match);
        var $NewContent = content;
        if($match){
            for (var i = 0; i < $match.length; i++) {
                var $attr = $match[i];
                $attr = $attr.replace("{","");
                $attr = $attr.replace("}","");
                $NewContent = $NewContent.replace($match[i],data.rows[row - 1].cell[$attr]);
            }
        }
        return $NewContent;
    }

    //定义成自适应宽度 start
    $("#"+$TableID).jqGrid('setGridWidth', $("#"+$ID).width());
    $(window).on('resize.jqGrid', function () {
        $("#"+$TableID).jqGrid('setGridWidth', $("#"+$ID).width());
    });
    //定义成自适应宽度 end

    /**
     * 通过json数组组成button的heml页面
     * @param $ButtonJson json数据如[{Title:'查看',Css:'fa fa-search orange'}]
     * @constructor
     */
    function GetButtonHtml($ButtonJson){
        var $ButtonHtml = "";
        //循环出按钮的html
        $.each($ButtonJson,function(i,val){
            $ButtonHtml = $ButtonHtml+' <a ';
            if(val.Url){
                if (val.Url.indexOf('"')>=0){
                    alert("Url里不能有\"双号，请改成'单号");
                    return false;
                }
                $ButtonHtml = $ButtonHtml+'href="' + val.Url + '" ';
            }
            if(val.Js){
                if (val.Js.indexOf('"')>=0){
                    alert("Js里不能有\"双号，请改成'单号");
                    return false;
                }
                $ButtonHtml = $ButtonHtml+'onclick="' + val.Js + '" ';
            }
            $ButtonHtml = $ButtonHtml+'title="'+val.Title+'" data-no="'+i+'"><span class="'+val.Css+' bigger-150"></span></a> ';
        });
        return $ButtonHtml;
    }

};


