<?php
class TeamModel extends CI_Model{
    protected $idteam;
    protected $namecoach;
    protected $nameteam;
    protected $slugteam;
    
    function TeamModel() {
        parent::__construct();
        $this->setIdteam(null);
        $this->setNamecoach(null);
        $this->setNameteam(null);
        $this->setSlugteam(null);
    }
    
    public function save($data = null) {
        if ($data != null) {
            if ($this->db->insert('team', $data)) {
                return true;
            }
        }
    }
    
    public function update($data = null) {
        if ($data != null) {
            $this->db->where("idteam", $data['idteam']);
            if ($this->db->update('team', $data)) {
                return true;
            }
        }
    }
    
    public function delete($idteam) {
        if ($idteam != null) {
            $this->db->where("idteam", $idteam);
            if ($this->db->delete("team")) {
                return true;
            }
        }
    }
    
    public function listing() {
        $this->db->select('*');
        $this->db->order_by("namecoach", "asc");
        return $this->db->get("team")->result();
    }
    
    public function search($idteam) {
        $this->db->where("idteam", $idteam);
        return $this->db->get("team")->row_array();
    }
    
    
    function getIdteam() {
        return $this->idteam;
    }

    function getNamecoach() {
        return $this->namecoach;
    }

    function getNameteam() {
        return $this->nameteam;
    }

    function getSlugteam() {
        return $this->slugteam;
    }

    function setIdteam($idteam) {
        $this->idteam = $idteam;
    }

    function setNamecoach($namecoach) {
        $this->namecoach = $namecoach;
    }

    function setNameteam($nameteam) {
        $this->nameteam = $nameteam;
    }

    function setSlugteam($slugteam) {
        $this->slugteam = $slugteam;
    }
}