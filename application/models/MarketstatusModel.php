<?php
class MarketstatusModel extends CI_Model{
    protected $idmktstatus;
    protected $currentround;
    protected $marketstatus;
    
    function MarketstatusModel() {
        parent::__construct();
        $this->setIdmktstatus(null);
        $this->setCurrentround(null);
        $this->setMarketstatus(null);
    }
    
    public function update($data = null) {
        if ($data != null) {
            $this->db->where("idmktstatus", $data['idmktstatus']);
            if ($this->db->update('mktstatus', $data)) {
                return true;
            }
        }
    }
    
    public function search() {
        $this->db->where("idmktstatus", 1);
        return $this->db->get("mktstatus")->row_array();
    }    
    
    function getIdmktstatus() {
        return $this->idmktstatus;
    }

    function getCurrentround() {
        return $this->currentround;
    }

    function getMarketstatus() {
        return $this->marketstatus;
    }

    function setIdmktstatus($idmktstatus) {
        $this->idmktstatus = $idmktstatus;
    }

    function setCurrentround($currentround) {
        $this->currentround = $currentround;
    }

    function setMarketstatus($marketstatus) {
        $this->marketstatus = $marketstatus;
    }



}