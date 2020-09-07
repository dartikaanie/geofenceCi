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
        $this->db->select('*');
        $this->db->from('edc');
        $this->db->join('log_potition_edc', 'edc.id = 	log_potition_edc.edc_id');
        $this->db->order_by('datetime', 'DESC');
        $this->db->group_by('edc.id');
        $this->db->where(['merchant_id' => $id]);

        $query = $this->db->get();
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