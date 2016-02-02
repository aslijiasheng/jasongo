<!-- /section:basics/sidebar -->
<div class="main-content">
    <!-- #section:basics/content.breadcrumbs -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="<?php echo base_url(); ?>index.php/www/">首页</a>
            </li>

            <li class="active"><?php echo $obj[1]['LABEL'] ?>列表</li>
        </ul><!-- /.breadcrumb -->



        <!-- /section:basics/content.searchbox -->
    </div>

    <!-- /section:basics/content.breadcrumbs -->
    <div class="page-content">
        <!-- /section:settings.box -->
        <div class="page-header">
            <h1>
                <?php echo $obj[1]['LABEL'] ?>列表
                <small>
                    <?php echo $obj[0]['LABEL'] ?> List
                </small>
            </h1>
        </div><!-- /.page-header -->

        <!--lee 这里是中间内容部分 start-->
						<div class="row">
							<div class="col-xs-12">

								<table id="grid-table"></table>

								<div id="grid-pager"></div>

								<script type="text/javascript">
									var $path_base = ".";//in Ace demo this will be used for editurl parameter
								</script>

								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
        <!--lee 这里是中间内容部分 end-->

    </div><!-- /.page-content -->
</div><!-- /.main-content -->

<script type="text/javascript">
$(document).ready(function(){
    $.post("<?php echo base_url(); ?>index.php/www/objects/list_object_json",{},function(data){
        var ret = eval("("+data+")");
        jqgridInit(ret);
    })
})
function jqgridInit(grid_data){
    var grid_selector = "#grid-table";
    var pager_selector = "#grid-pager";
    $(window).on('resize.jqGrid', function () {
        $(grid_selector).jqGrid( 'setGridWidth', $(".page-content").width() );
    })
        var parent_column = $(grid_selector).closest('[class*="col-"]');
        $(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
            if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
                setTimeout(function() {
                    $(grid_selector).jqGrid( 'setGridWidth', parent_column.width() );
                }, 0);
            }
        })
        jQuery(grid_selector).jqGrid({
            subGrid : false,
                subGridOptions : {
                plusicon : "ace-icon fa fa-plus center bigger-110 blue",
                    minusicon  : "ace-icon fa fa-minus center bigger-110 blue",
                    openicon : "ace-icon fa fa-chevron-right center orange"
            },
            data: grid_data,
            datatype: "local",
            height: 250,
            colNames:[' ', '标识','品类','系列', '序号', '货号','第二项目号', '品名','规格','预计上市时间', '单位', '小盒','中盒', '件','全国批发部面价元/（支/盒/个）','东北批发部面价元/（支/盒/个）', '建议零售价', '全国开单扣（除东北、新疆）','东北开单扣','新疆开单扣','中类','品牌','JDE属性','备注'],
            colModel:[
            {name:'myac',index:'', width:80, fixed:true, sortable:false, resize:false,
            formatter:'actions', 
            formatoptions:{ 
            keys:true,
                delOptions:{recreateForm: true, beforeShowForm:beforeDeleteCallback},
            }
            },
            {name:'id',index:'id', width:60, sorttype:"int", editable: false},
            {name:'cate',index:'cate',width:90, editable:true, sorttype:"date"},
            {name:'series',index:'series', width:150,editable: true},
            {name:'number',index:'number', width:70, editable: true},
            {name:'id1',index:'id1', width:90, editable: true},
            {name:'id2',index:'id2', width:150, sortable:false,editable: true,edittype:"textarea"},
            {name:'name',index:'name', width:60, sorttype:"int", editable: true},
            {name:'standard',index:'standard',width:90, editable:true, sorttype:"date"},
            {name:'market_date',index:'market_date', width:150,editable: true,unformat: pickDate},
            {name:'unit',index:'unit', width:70, editable: true},
            {name:'small_box',index:'small_box', width:90, editable: true},
            {name:'normal_box',index:'normal_box', width:150, sortable:false,editable: true,edittype:"textarea", editoptions:{rows:"2",cols:"10"}},
            {name:'piece',index:'piece',width:90, editable:true, sorttype:"date"},
            {name:'price',index:'price', width:150,editable: true,editoptions:{size:"20",maxlength:"30"}},
            {name:'price2',index:'price2', width:70, editable: true},
            {name:'price_tmp',index:'price_tmp', width:90, editable: true},
            {name:'order_kou',index:'order_kou', width:150, sortable:false,editable: true,edittype:"textarea", editoptions:{rows:"2",cols:"10"}},
            {name:'order_kou2',index:'order_kou2', worder_kou2th:60, sorttype:"int", editable: true},
            {name:'order_kou3',index:'order_kou3',width:90, editable:true},
            {name:'bigcate',index:'bigcate', width:150,editable: true},
            {name:'brand',index:'brand', width:150, sortable:false,editable: true,edittype:"textarea", editoptions:{rows:"2",cols:"10"}},
            {name:'jde_attr',index:'jde_attr', width:70, editable: true},
            {name:'zhengce',index:'zhengce', width:90, editable: true},
        ], 

        viewrecords : true,
        rowNum:10,
        rowList:[10,20,30],
        pager : pager_selector,
        altRows: true,

        multiselect: true,
        multiboxonly: true,

        loadComplete : function() {
            var table = this;
            setTimeout(function(){
                styleCheckbox(table);
                updateActionIcons(table);
                updatePagerIcons(table);
                enableTooltips(table);
            }, 0);
        },

        editurl: "<?php echo base_url(); ?>index.php/www/objects/oper",
        caption: "订单基础信息列表"

            });
        $(window).triggerHandler('resize.jqGrid');
        function aceSwitch( cellvalue, options, cell ) {
            setTimeout(function(){
                $(cell) .find('input[type=checkbox]')
                    .addClass('ace ace-switch ace-switch-5')
                    .after('<span class="lbl"></span>');
            }, 0);
        }
        function pickDate( cellvalue, options, cell ) {
            setTimeout(function(){
                $(cell) .find('input[type=text]')
                    .datepicker({format:'yyyy-mm-dd' , autoclose:true}); 
            }, 0);
        }


        jQuery(grid_selector).jqGrid('navGrid',pager_selector,
        { 	
            edit: true,
                editicon : 'ace-icon fa fa-pencil blue',
                // add: true,
                // addicon : 'ace-icon fa fa-plus-circle purple',
                // del: true,
                // delicon : 'ace-icon fa fa-trash-o red',
                search: true,
                searchicon : 'ace-icon fa fa-search orange',
                // refresh: true,
                // refreshicon : 'ace-icon fa fa-refresh green',
                // view: true,
                // viewicon : 'ace-icon fa fa-search-plus grey',
        },
        {
            recreateForm: true,
                beforeShowForm : function(e) {
                    var form = $(e[0]);
                    form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                        style_edit_form(form);
                }
        },
                {
                    closeAfterAdd: true,
                        recreateForm: true,
                        viewPagerButtons: false,
                        beforeShowForm : function(e) {
                            var form = $(e[0]);
                            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar')
                                .wrapInner('<div class="widget-header" />')
                                style_edit_form(form);
                        }
                },
                        {
                            recreateForm: true,
                                beforeShowForm : function(e) {
                                    var form = $(e[0]);
                                    if(form.data('styled')) return false;

                                    form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                                        style_delete_form(form);

                                    form.data('styled', true);
                                },
                                onClick : function(e) {
                                }
                        },
                                {
                                    recreateForm: true,
                                        afterShowSearch: function(e){
                                            var form = $(e[0]);
                                            form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
                                                style_search_form(form);
                                        },
                                        afterRedraw: function(){
                                            style_search_filters($(this));
                                        }
                                    ,
                                        multipleSearch: true,
                                },
                                        {
                                            recreateForm: true,
                                                beforeShowForm: function(e){
                                                    var form = $(e[0]);
                                                    form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
                                                }
                                        }
        )



            function style_edit_form(form) {
                form.find('input[name=sdate]').datepicker({format:'yyyy-mm-dd' , autoclose:true})

                    form.find('input[name=stock]').addClass('ace ace-switch ace-switch-5').after('<span class="lbl"></span>');
                var buttons = form.next().find('.EditButton .fm-button');
                buttons.addClass('btn btn-sm').find('[class*="-icon"]').hide();//ui-icon, s-icon
                buttons.eq(0).addClass('btn-primary').prepend('<i class="ace-icon fa fa-check"></i>');
                buttons.eq(1).prepend('<i class="ace-icon fa fa-times"></i>')

                    buttons = form.next().find('.navButton a');
                buttons.find('.ui-icon').hide();
                buttons.eq(0).append('<i class="ace-icon fa fa-chevron-left"></i>');
                buttons.eq(1).append('<i class="ace-icon fa fa-chevron-right"></i>');		
            }

        function style_delete_form(form) {
            var buttons = form.next().find('.EditButton .fm-button');
            buttons.addClass('btn btn-sm btn-white btn-round').find('[class*="-icon"]').hide();//ui-icon, s-icon
            buttons.eq(0).addClass('btn-danger').prepend('<i class="ace-icon fa fa-trash-o"></i>');
            buttons.eq(1).addClass('btn-default').prepend('<i class="ace-icon fa fa-times"></i>')
        }

        function style_search_filters(form) {
            form.find('.delete-rule').val('X');
            form.find('.add-rule').addClass('btn btn-xs btn-primary');
            form.find('.add-group').addClass('btn btn-xs btn-success');
            form.find('.delete-group').addClass('btn btn-xs btn-danger');
        }
        function style_search_form(form) {
            var dialog = form.closest('.ui-jqdialog');
            var buttons = dialog.find('.EditTable')
                buttons.find('.EditButton a[id*="_reset"]').addClass('btn btn-sm btn-info').find('.ui-icon').attr('class', 'ace-icon fa fa-retweet');
            buttons.find('.EditButton a[id*="_query"]').addClass('btn btn-sm btn-inverse').find('.ui-icon').attr('class', 'ace-icon fa fa-comment-o');
            buttons.find('.EditButton a[id*="_search"]').addClass('btn btn-sm btn-purple').find('.ui-icon').attr('class', 'ace-icon fa fa-search');
        }

        function beforeDeleteCallback(e) {
            var form = $(e[0]);
            console.debug(form);
            if(form.data('styled')) return false;

            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                    style_delete_form(form);

                    form.data('styled', true);
        }

        function beforeEditCallback(e) {
            var form = $(e[0]);
            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
                style_edit_form(form);
        }



        function styleCheckbox(table) {
        }


        function updateActionIcons(table) {
        }

        function updatePagerIcons(table) {
            var replacement = 
        {
            'ui-icon-seek-first' : 'ace-icon fa fa-angle-double-left bigger-140',
                'ui-icon-seek-prev' : 'ace-icon fa fa-angle-left bigger-140',
                'ui-icon-seek-next' : 'ace-icon fa fa-angle-right bigger-140',
                'ui-icon-seek-end' : 'ace-icon fa fa-angle-double-right bigger-140'
        };
            $('.ui-pg-table:not(.navtable) > tbody > tr > .ui-pg-button > .ui-icon').each(function(){
                var icon = $(this);
                var $class = $.trim(icon.attr('class').replace('ui-icon', ''));

                if($class in replacement) icon.attr('class', 'ui-icon '+replacement[$class]);
            })
        }

        function enableTooltips(table) {
            $('.navtable .ui-pg-button').tooltip({container:'body'});
            $(table).find('.ui-pg-div').tooltip({container:'body'});
        }
        $(document).one('ajaxloadstart.page', function(e) {
            $(grid_selector).jqGrid('GridUnload');
            $('.ui-jqdialog').remove();
        });
}
		</script>
