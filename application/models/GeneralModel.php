<?php
class GeneralModel extends CI_Model{
    protected $idgeneral;
    
    function GeneralModel() {
        parent::__construct();
        $this->setIdgeneral(null);
    }
}