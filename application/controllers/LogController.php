<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 04/09/2020
 * Time: 13.06
 */

class LogController extends CI_Controller
{

    public function index(){
        $this->load->model('LogModel');
        $data['data_edc'] = $this->LogModel->getAllDataLog();

        $this->load->view('features/log/index', $data);
    }



}