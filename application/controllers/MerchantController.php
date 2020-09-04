<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 04/09/2020
 * Time: 13.05
 */

class MerchantController extends CI_Controller
{

    public function index(){
        $this->load->model('MerchantModel');
        $data['data_edc'] = $this->MerchantModel->getAllDataMerchant();

        $this->load->view('features/merchant/index', $data);
    }

}