<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 04/09/2020
 * Time: 13.05
 */

class MerchantController extends CI_Controller
{
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('MerchantModel');
    }

    public function index(){

        $data['data_edc'] = $this->MerchantModel->getAllDataMerchant();

        $this->load->view('features/merchant/index', $data);
    }


    public function show($id){

        $data['data_merchant'] = $this->MerchantModel->getSingleData($id);
        $this->load->view('features/merchant/show', $data);
    }

    public function getEdc($id){
        $dataEdc = $this->MerchantModel->getEdc($id);
        echo json_encode($dataEdc);
    }

    public function create(){
        $this->load->view('features/merchant/form_add');
    }

    function store(){
        $name = $this->input->post("merchant_name");
        $lat = $this->input->post("lat");
        $lng = $this->input->post("lng");
        $radius = $this->input->post("radius");
        $this->MerchantModel->createData($name, $lat, $lng, $radius);

        redirect('merchant');
    }

}