
    public function del(){
       $this->{#obj_name#}->del($_GET['{#obj_name#}_id']);
	   success('www/{#obj_name#}','删除成功');
    }