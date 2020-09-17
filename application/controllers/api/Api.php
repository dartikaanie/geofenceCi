<?php
require (APPPATH.'libraries/RestController.php');
require (APPPATH.'libraries/JWT.php');

use \Firebase\JWT\JWT;
use \Firebase\JWT\SignatureInvalidException;



class Api extends \ciGeofenceTestV3\RestServer\RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('LogModel');
        $this->load->model('EdcModel');
    }


    private function getPublicKey(){
        $pub_key = openssl_pkey_get_public(file_get_contents('assets/my_ca.cer'));
        $keyData = openssl_pkey_get_details($pub_key);
//        file_put_contents('./key.pub', $keyData['key']);
        return $keyData['key'];
    }

    public function api_post(){
        $insert = false;
        $ex=null;
        $edc = null;
        $jwt = $this->input->get_request_header('token');
        try {
            $decode = JWT::decode($jwt, $this->getPublicKey(), array('HS256'));

        } catch (Exception $ex){
            $ex="token failed";
        }catch(SignatureInvalidException $e ) {
            $ex = "token failed";
        }

        if($decode){
            $edc = $this->EdcModel->getSingleData($decode->sn, $decode->dm);
        }
        if($edc != null){
            $eLat = $this->post("lat");
            $eLng = $this->post("lng");

            if($edc != null){
                $mLat = $edc[0]->lat;
                $mLng = $edc[0]->lng;
                $distance = $this->distanceBetween($eLat, $eLng, $mLat, $mLng);
                $radius = 100;

                if($distance > $radius){
                    try{
                        $this->LogModel->addData($edc[0]->id,$eLat,$eLng );
                        $insert = true;
                    }catch (Exception $e){
                        $ex = $e;
                    }
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

    //get last log

    public function getLastLog_get(){



        $sn = $this->get("sn");
        $dm = $this->get("dm");

        try{
            $data = $this->LogModel->getLastLog($sn, $dm);
        }catch (Exception $ex){
            return $ex;
        }

        if($data){
            $tokenData = array();
            $tokenData['sn'] = $sn;
            $tokenData['dm'] = $dm;
            $response['token'] = JWT::encode($tokenData, $this->getPublicKey());

            $response['status']=200;
            $response['error']=false;
            $response['data']= $data;
            $response['message']='Success';

        }else{
            $response['status']=502;
            $response['error']=true;
            $response['token'] = "failed";
            $response['message']='Failed';

        }
        $this->response($response);

    }





    function setupTcpStreamServer($pem_file, $pem_passphrase, $ip, $port) {

//setup and listen to a tcp IP/port, returning the socket stream
        //create a stream context for our SSL settings
        $context = stream_context_create();

        //Setup the SSL Options
        stream_context_set_option($context, 'ssl', 'local_cert', $pem_file);  // Our SSL Cert in PEM format
        stream_context_set_option($context, 'ssl', 'passphrase', $pem_passphrase); // Private key Password
        stream_context_set_option($context, 'ssl', 'allow_self_signed', true);
        stream_context_set_option($context, 'ssl', 'verify_peer', false);
//
        //create a stream socket on IP:Port

        $socket = stream_socket_server("{$ip}:{$port}", $errno, $errstr, 30);
        stream_socket_enable_crypto($socket, false);

        return $socket;

    }

    function decrypt($ivHashCiphertext, $password) {
        $method = "AES-256-CBC";
        $iv = substr($ivHashCiphertext, 0, 16);
        $hash = substr($ivHashCiphertext, 16, 32);
        $ciphertext = substr($ivHashCiphertext, 48);
        $key = hash('sha256', $password, true);

        if (!hash_equals(hash_hmac('sha256', $ciphertext . $iv, $key, true), $hash)) return null;

        return openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);
    }

}