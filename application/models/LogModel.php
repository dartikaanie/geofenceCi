<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 04/09/2020
 * Time: 13.10
 */

class LogModel extends CI_Model {

    public function getAllDataLog()
    {
        $this->db->select('log_potition_edc.* , edc.*');
        $this->db->from('log_potition_edc');
        $this->db->join('edc', 'edc.id = log_potition_edc.edc_id', 'inner join');
        $query = $this->db->get();
        return $query->result();
    }

    public function addData($edc_id,$lat,$lng){
        $datetime =  date("Y-m-d H:m:s");
        $data = array(
            "edc_id"=>$edc_id,
            "lat"=>$lat,
            "lng"=>$lng,
            "datetime" => $datetime
        );
        $this->db->insert("log_potition_edc", $data);
    }




}