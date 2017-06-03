<?php
class CurrentModel extends CI_Model{
    protected $idcurrent;
    protected $month;
    protected $champion;
    protected $worse;
            
    function CurrentModel() {
        parent::__construct();
        $this->setIdcurrent(null);
        $this->setMonth(null);
        $this->setChampion(null);
        $this->setWorse(null);
    }
    
    public function update($data = null) {
        if ($data != null) {
            $this->db->where("idcurrent", $data['idcurrent']);
            if ($this->db->update('current', $data)) {
                return true;
            }
        }
    }
    
    public function search() {
        $this->db->where("idcurrent", 1);
        return $this->db->get("current")->row_array();
    }
    
    
    function getIdcurrent() {
        return $this->idcurrent;
    }

    function getMonth() {
        return $this->month;
    }

    function getChampion() {
        return $this->champion;
    }

    function getWorse() {
        return $this->worse;
    }

    function setIdcurrent($idcurrent) {
        $this->idcurrent = $idcurrent;
    }

    function setMonth($month) {
        $this->month = $month;
    }

    function setChampion($champion) {
        $this->champion = $champion;
    }

    function setWorse($worse) {
        $this->worse = $worse;
    }


}