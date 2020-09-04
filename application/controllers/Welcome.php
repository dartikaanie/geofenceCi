<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index(){
        $this->load->model('EdcModel');
        $data['data_edc'] = $this->EdcModel->getAllDataEdc();

        $this->load->view('edc/index', $data);
    }
}
