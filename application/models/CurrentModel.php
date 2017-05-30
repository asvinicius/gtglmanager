<?php
class CurrentModel extends CI_Model{
    protected $idcurrent;
    protected $month;
    protected $champion;
    protected $worse;
            
    function CurrentModel() {
        parent::__construct();
        $this->setIdcurrent(null);
        $this->setIdmont(null);
        $this->setIdchampion(null);
        $this->setIdworse(null);
    }
    
    public function save($data = null) {
        if ($data != null) {
            if ($this->db->insert('current', $data)) {
                return true;
            }
        }
    }
    
    public function listing() {
        $this->db->select('*');
        $this->db->order_by("month", "asc");
        return $this->db->get("current")->result();
    }
    
    public function search($month) {
        $this->db->where("month", $month);
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