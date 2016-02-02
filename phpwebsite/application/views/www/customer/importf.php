<style>
    .must {
        border: 1px solid red;
    }
</style>
<pre>1.在系统字段中，选择与CRM系统匹配的数据列；
2.在可以根据取值查找已有记录的字段(如客户编号)上勾选“主键”；
3.对引用字段，选择可以在系统中按名称查找对应记录的字段。</pre>
<form name="importRelation" id="importRelation">
    <input type="hidden" name="file_url" value="<?php echo $file_url ?>"/>

    <h3>系统属性</h3>
    <table class="table table-striped table-bordered" id="">
        <tbody>
        <tr>
            <th>CRM属性</th>
            <th>导入属性</th>
            <th>主键</th>
            <th>CRM属性</th>
            <th>导入属性</th>
            <th>主键</th>
        </tr>
        <?php
        //上传文件的标题可以让用户选择默认value是null 字符串
        $default_option = '<option value="null"></option>';
        foreach ($csv as $k => $v) {
            $default_option .= '<option value="' . $k . '">' . $v . '</option>';
        }
        //显示系统系统属性
        foreach ($lead['system'] as $key => $value) {
            $num = $key + 1;
            if ($num == 1) {
                echo "<tr>";
            }
            //判断在哪些情况下显示主键的的checkbox的显示
            $dis = "";
            if ($value["ATTR_TYPE"] == 4) {
                $dis = "none";
            }
            //判断如果上传的标题和数据库标题一样 直接显示 不一样显示select 让用户直接选择
            $option = '<option value="null"></option>';
            foreach ($csv as $k => $v) {
                if ($value['LABEL'] == $v) {
                    $option .= '<option value="' . $k . '"  selected = "selected">' . $v . '</option>';
                } else {
                    $option .= '<option value="' . $k . '">' . $v . '</option>';
                }
            }
            $class = "col-sm-12";
            $title = "";
            if ($value["IS_MUST"] == 1) {
                $class = "col-sm-12  must";
                $title = "必须选择";
            }
            echo '<td>' . $value['LABEL'] . '</td>
                  <td>
                      <select id="suffix" class="' . $class . '" name="' . $value['ATTR_NAME'] . '"  title="' . $title . '">
                        ' . $option . '
                      </select>
                  </td>
                  <td>
                      <input style="display:' . $dis . '" name="' . $value['ATTR_NAME'] . 'checkBox" type="checkbox"/>
                  </td>';

            if ($num == count($lead['system'])) {
                echo "</tr>";
                break;
            }
            if ($num % 2 == 0) {
                echo "</tr><tr>";
            }

        }
        ?>
        <tbody>
    </table>

    <h3>引用属性</h3>
    <table class="table table-striped table-bordered">
        <tbody>
        <tr>
            <th>CRM属性</th>
            <th>导入属性</th>
            <th>主键</th>
            <th>CRM属性</th>
            <th>导入属性</th>
            <th>主键</th>
        </tr>
        <?php
        //显示引用属性
        foreach ($lead['ref'] as $key => $value) {
            $option2 = "";
            foreach ($value as $k => $v) {
                $option2 .= '<option value="' . $v["ATTR_NAME"] . '">' . $key . ":" . $v["LABEL"] . '</option>';
            }
            $select_arr[$key] = $option2;
        }
        //p($select_arr);die;
        $num = 1;
        foreach ($lead['ref'] as $key => $value) {
            if ($num == 1) {
                echo "<tr>";
            }

            $option2 = '<option value="null"></option>';
            $class = "col-sm-12 refValue";
            $title = "";
            $select_index = "null";
            if ($value["IS_MUST"] == 1) {
                $class = "col-sm-12 refValue must";
                $title = "必须选择";
            }
            foreach ($csv as $k => $v) {
                if ($key == $v) {
                    $option2 .= '<option value="' . $k . '"  selected = "selected">' . $v . '</option>';
                    $select_index = $k;
                } else {
                    $option2 .= '<option value="' . $k . '">' . $v . '</option>';
                }
            }
            echo '<td>
                     <select class="col-sm-12 refName">
                        ' . $select_arr[$key] . '
                     </select>
                   </td>
                      <input type="hidden" name="' . $value[0]['ATTR_NAME'] . '" value="'.$select_index.'">
                    <td>
                       <select class="' . $class . '" title="' . $title . '">
                         ' . $option2 . '
                       </select>
                    </td>
                    <td>
                        <input type="checkbox" name="' . $value[0]['ATTR_NAME'] . 'checkBox"/>
                    </td>';

            if ($num == count($lead['ref'])) {
                echo "</tr>";
                break;
            }
            if ($num % 2 == 0) {
                echo "</tr><tr>";
            }
            $num++;
        }
        ?>
        <tbody>
    </table>
</form>
<script type="text/javascript">
    $(function () {
        $(".refName").change(function () {
            var hidden = $(this).parent().next();
            $(hidden).attr("name", $(this).val())
            var check = $(this).parent().next().next().next().children();
            $(check).attr("name", $(this).val() + "checkBox")

        });
        $(".refValue").change(function () {
            var hidden = $(this).parent().prev();
            $(hidden).val($(this).val());
        })

    })

</script>
