<?php
class User extends WWW_controller {

    public function modify_password(){
    	$data="";
    	$this->load->view('www/login/modify_password',$data);
    }
    public function update_password(){
    	$this->load->model('www/user_model',$user);
    	$oldpass = $_POST['User.LoginPassword'];
    	$newpass = $_POST['User.NewPassword'];
        p($newpass);

    	$rnewpass = $_POST['User.RepeatPassword'];
    	p($rnewpass);
        $LoginName = $this->session->userdata('login_name');
        $user_id = $this->session->userdata('user_id');
    	$OrgID = $this->session->userdata('org_id');

    	$user_data = $this->user->LoginUserData2($LoginName,$OrgID);

    	if($this->user->checkPassword($oldpass, $user_data['LOGIN_PASSWORD'])==true){
            if($newpass == $rnewpass){
                //$this->user->update_password($OrgID, $user_id, $login_password);
                $res['res'] = 'suc';
                $res['msg'] = ' 密码修改成功！';
            }else{
                $res['res'] = 'fail';
                $res['msg'] = '重复密码不一致！';
            }
        }else{
            $res['res'] = 'fail';
            $res['msg'] = '原密码错误！';
        }

        echo json_encode($res);
    }
}
?>
    