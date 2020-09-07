
<?php
class EdcModel extends CI_Model {

    public function getAllDataEdc()
    {
        $query = $this->db->get('edc');
        return $query->result();
    }

    public function getSingleData($id)
    {
        $this->db->select('merchant.* , edc.*');
        $this->db->from('edc');
        $this->db->join('merchant', 'edc.merchant_id = merchant.merchant_id', 'inner join');
        $this->db->where(['edc.id' => $id]);
        $query = $this->db->get();
        return $query->result();
    }


}
?>