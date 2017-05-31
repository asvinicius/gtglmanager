<?php
class DetailModel extends CI_Model{
    protected $iddetail;
    protected $month;
    protected $champion;
    protected $worse;
    
    function DetailModel() {
        parent::__construct();
        $this->setIddetail(null);
        $this->setMonth(null);
        $this->setChampion(null);
        $this->setWorse(null);
    }
   
    public function save($data = null) {
        if ($data != null) {
            if ($this->db->insert('detail', $data)) {
                return true;
            }
        }
    }
    
    public function listing() {
        $this->db->select('*');
        $this->db->order_by("month", "asc");
        return $this->db->get("detail")->result();
    }
    
    function getIddetail() {
        return $this->iddetail;
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

    function setIddetail($iddetail) {
        $this->iddetail = $iddetail;
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