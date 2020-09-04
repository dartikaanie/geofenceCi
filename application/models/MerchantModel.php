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
        $query = $this->db->get('merchant')->where(['merchant_id' => $id ]);
        return $query->result();
    }

}