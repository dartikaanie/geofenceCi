
<?php
class EdcModel extends CI_Model {

    public function getAllDataEdc()
    {
        $query = $this->db->get('edc');
        return $query->result();
    }

    public function getSingleData($sn, $dm)
    {
        $this->db->select('merchant.* , edc.*');
        $this->db->from('edc');
        $this->db->join('merchant', 'edc.merchant_id = merchant.merchant_id', 'inner join');
        $this->db->where(['edc.serial_number' => $sn, 'edc.device_model' => $dm]);
        $query = $this->db->get();
        return $query->result();
    }


}
?>