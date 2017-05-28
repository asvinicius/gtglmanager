<?php
class TeamModel extends CI_Model{
    protected $idteam;
    protected $coach;
    protected $name;
    
    function TeamModel() {
        parent::__construct();
        $this->setIdteam(null);
        $this->setCoach(null);
        $this->setName(null);
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
        $this->db->order_by("coach", "asc");
        return $this->db->get("team")->result();
    }
    
    public function search($idteam) {
        $this->db->where("idteam", $idteam);
        return $this->db->get("team")->row_array();
    }
    
    function getIdteam() {
        return $this->idteam;
    }

    function getCoach() {
        return $this->coach;
    }

    function getName() {
        return $this->name;
    }

    function setIdteam($idteam) {
        $this->idteam = $idteam;
    }

    function setCoach($coach) {
        $this->coach = $coach;
    }

    function setName($name) {
        $this->name = $name;
    }


}