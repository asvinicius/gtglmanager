<?php
class RankingModel extends CI_Model{
    protected $idranking;
    protected $team;
    protected $rating;
    protected $patrimony;
    protected $type;
    
    function RankingModel() {
        parent::__construct();
        $this->setIdranking(null);
        $this->setTeam(null);
        $this->setRating(null);
        $this->setPatrimony(null);
        $this->setType(null);
    }
   
    public function save($data = null) {
        if ($data != null) {
            if ($this->db->insert('ranking', $data)) {
                return true;
            }
        }
    }
    
    public function update($data = null) {
        if ($data != null) {
            $this->db->where("idranking", $data['idranking']);
            if ($this->db->update('ranking', $data)) {
                return true;
            }
        }
    }
    
    public function delete($idranking) {
        if ($idranking != null) {
            $this->db->where("idranking", $idranking);
            if ($this->db->delete("ranking")) {
                return true;
            }
        }
    }
    
    public function listing($type) {
        $this->db->where("type", $type);
        $this->db->order_by("rating", "desc");
        $this->db->join('team', 'team.idteam=team', 'inner');
        return $this->db->get("ranking")->result();
    }
    
    public function search($idteam, $type) {
        $this->db->where("team", $idteam);
        $this->db->where("type", $type);
        return $this->db->get("ranking")->row_array();
    }
    
    function getIdranking() {
        return $this->idranking;
    }

    function getTeam() {
        return $this->team;
    }

    function getRating() {
        return $this->rating;
    }

    function getPatrimony() {
        return $this->patrimony;
    }

    function getType() {
        return $this->type;
    }

    function setIdranking($idranking) {
        $this->idranking = $idranking;
    }

    function setTeam($team) {
        $this->team = $team;
    }

    function setRating($rating) {
        $this->rating = $rating;
    }

    function setPatrimony($patrimony) {
        $this->patrimony = $patrimony;
    }

    function setType($type) {
        $this->type = $type;
    }
}