<?php

require APPPATH . 'libraries\RestController.php';


class Api extends \ciGeofenceTestV3\RestServer\RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('LogModel');
        $this->load->model('EdcModel');
    }

    public function api_post(){
        $edc_id = $this->post("id");
        $eLat = $this->post("lat");
        $eLng = $this->post("lng");

        $insert = false;
        $ex=null;
        $edc = $this->EdcModel->getSingleData($edc_id);
        if($edc != null){
            $mLat = $edc[0]->lat;
            $mLng = $edc[0]->lng;
            $distance = $this->distanceBetween($eLat, $eLng, $mLat, $mLng);


            $radius = 100;

            if($distance > $radius){
                try{
                    $this->LogModel->addData($edc_id,$eLng,$eLat);
                    $insert = true;
                }catch (Exception $ex){
                    print_r($ex);
                }
            }
        }


        if($insert){
            $response['status']=200;
            $response['error']=false;
            $response['distance']= $distance;
            $response['message']='Data Log ditambahkan.';
            $this->response($response);
        }else{
            $response['status']=502;
            $response['error']=true;
            if($ex !=null){
                $response['error_message']=$ex;
            }
            $response['message']='Data Log gagal ditambahkan.';
            $this->response($response);
        }
    }

    function distanceBetween($lat1, $lon1, $lat2, $lon2) {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lon1 *= $pi80;
        $lat2 *= $pi80;
        $lon2 *= $pi80;
        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $m = $r * $c *1000;
        return $m;
    }
}