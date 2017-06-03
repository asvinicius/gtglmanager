<?php
class StatusoffModel extends CI_Model{
    protected $idstatusoff;
    protected $monthoff;
    
    function StatusoffModel() {
        parent::__construct();
        $this->setIdstatusoff(null);
        $this->setMonthoff(null);
    }
    
    public function update($data = null) {
        if ($data != null) {
            $this->db->where("idstatusoff", $data['idstatusoff']);
            if ($this->db->update('statusoff', $data)) {
                return true;
            }
        }
    }
    
    public function search() {
        $this->db->where("idstatusoff", 1);
        return $this->db->get("statusoff")->row_array();
    }    
    
    function getIdstatusoff() {
        return $this->idstatusoff;
    }

    function getMonthoff() {
        return $this->monthoff;
    }

    function setIdstatusoff($idstatusoff) {
        $this->idstatusoff = $idstatusoff;
    }

    function setMonthoff($monthoff) {
        $this->monthoff = $monthoff;
    }


}