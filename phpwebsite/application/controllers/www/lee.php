<?php

class Lee extends WWW_controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('www/lead_model', 'lead');
        $this->load->model('www/objects_model', 'objects');
        $this->load->model('admin/obj_model', 'obj');
        $this->load->model('admin/format_model', 'format');
        $this->load->model('admin/attr_model', 'attr');
    }
    public function lee_export(){
        $data = "";
        $this->load->view('www/lee/export',$data);
     }
}

?>