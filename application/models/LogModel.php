<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 04/09/2020
 * Time: 13.10
 */

class LogModel extends CI_Model {



    public function getAllDataLog($limit, $start)
    {
        $this->db->select('*');
        $this->db->from('log_potition_edc');
        $this->db->join('edc', 'edc.id = log_potition_edc.edc_id');
        $this->db->order_by("log_potition_edc.datetime", "DESC");
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result();
    }

    public function addData($edc_id,$lat,$lng){
        $data = array(
            "edc_id"=>$edc_id,
            "lat"=>$lat,
            "lng"=>$lng
        );
        $this->db->insert("log_potition_edc", $data);
    }

    public function get_count() {
        return $this->db->count_all("log_potition_edc");
    }

    public function getLastLog($sn, $dm){
        $this->db->select('*');
        $this->db->from('log_potition_edc');
        $this->db->join('edc', 'edc.id = log_potition_edc.edc_id');
        $this->db->where('serial_number', $sn);
        $this->db->where('device_model',$dm);
        $this->db->order_by("log_potition_edc.datetime", "DESC");
        $this->db->limit(1);
        $query = $this->db->get();

        return $query->result();
    }




}