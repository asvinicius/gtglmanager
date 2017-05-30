<?php
class PaymentModel extends CI_Model{
    protected $idpayment;
    protected $team;
    protected $amount;
    
    function PaymentModel() {
        parent::__construct();
        $this->setIdpayment(null);
        $this->setTeam(null);
        $this->setAmount(null);
    }
   
    public function save($data = null) {
        if ($data != null) {
            if ($this->db->insert('payment', $data)) {
                return true;
            }
        }
    }
    
    public function update($data = null) {
        if ($data != null) {
            $this->db->where("idpayment", $data['idpayment']);
            if ($this->db->update('payment', $data)) {
                return true;
            }
        }
    }
    
    public function delete($idpayment) {
        if ($idpayment != null) {
            $this->db->where("idpayment", $idpayment);
            if ($this->db->delete("payment")) {
                return true;
            }
        }
    }
    
    public function listing() {
        $this->db->join('team', 'team.idteam=team', 'inner');
        $this->db->order_by("namecoach", "desc");
        return $this->db->get("payment")->result();
    }
    
    public function search($idteam) {
        $this->db->where("team", $idteam);
        return $this->db->get("payment")->row_array();
    }
    
    function getIdpayment() {
        return $this->idpayment;
    }

    function getTeam() {
        return $this->team;
    }

    function getAmount() {
        return $this->amount;
    }

    function setIdpayment($idpayment) {
        $this->idpayment = $idpayment;
    }

    function setTeam($team) {
        $this->team = $team;
    }

    function setAmount($amount) {
        $this->amount = $amount;
    }


}