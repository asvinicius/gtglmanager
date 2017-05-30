<?php
class WalletModel extends CI_Model{
    protected $idwallet;
    protected $reference;
    protected $collected;
    protected $premium;
    protected $accumulated;
            
    function WalletModel() {
        parent::__construct();
        $this->setIdwallet(null);
        $this->setReference(null);
        $this->setCollected(null);
        $this->setPremium(null);
        $this->setAccumulated(null);
    }
   
    public function save($data = null) {
        if ($data != null) {
            if ($this->db->insert('wallet', $data)) {
                return true;
            }
        }
    }
    
    public function update($data = null) {
        if ($data != null) {
            $this->db->where("idwallet", $data['idwallet']);
            if ($this->db->update('wallet', $data)) {
                return true;
            }
        }
    }
    
    public function listing() {
        $this->db->where("reference >", 0);
        $this->db->order_by("reference", "asc");
        return $this->db->get("wallet")->result();
    }
    
    public function search($reference) {
        $this->db->where("reference", $reference);
        return $this->db->get("wallet")->row_array();
    }
    
    
    function getIdwallet() {
        return $this->idwallet;
    }

    function getReference() {
        return $this->reference;
    }

    function getCollected() {
        return $this->collected;
    }

    function getPremium() {
        return $this->premium;
    }

    function getAccumulated() {
        return $this->accumulated;
    }

    function setIdwallet($idwallet) {
        $this->idwallet = $idwallet;
    }

    function setReference($reference) {
        $this->reference = $reference;
    }

    function setCollected($collected) {
        $this->collected = $collected;
    }

    function setPremium($premium) {
        $this->premium = $premium;
    }

    function setAccumulated($accumulated) {
        $this->accumulated = $accumulated;
    }


}