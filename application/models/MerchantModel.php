<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 04/09/2020
 * Time: 13.10
 */

class MerchantModel extends CI_Model {

    public function getAllDataMerchant()
    {
        $query = $this->db->get('merchant');
        return $query->result();
    }

    public function getSingleData($id)
    {
        $this->db->select('merchant.*');
        $this->db->from('merchant');
        $this->db->where(['merchant_id' => $id]);
        $query = $this->db->get();
        return $query->result();
    }

    public function getEdc($id)
    {
//        $this->db->select('*');
//        $this->db->from('edc');
//        $this->db->join('log_potition_edc', 'edc.id = 	log_potition_edc.edc_id');
//        $this->db->where('edc.id in', '(Select max(id) From log_potition_edc Group By edc_id where merchant_id = '.$id.')', false);
//
//        $this->db->where(['merchant_id' => $id]);
        $sql = "Select* from edc join log_potition_edc on edc.id = log_potition_edc.edc_id where log_potition_edc.id in (Select max(id) From log_potition_edc where edc.merchant_id =". $id ." Group By edc_id)";
        $query=$this->db->query($sql);
        return $query->result();
    }

    public function createData($name, $lat, $lng, $radius)
    {
        $data = array(
            'merchant_name' => $name,
            'lat' => $lat,
            'lng' => $lng,
            'radius' => $radius
        );
        $this->db->insert("merchant", $data);

    }

}