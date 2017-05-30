<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monthly extends CI_Controller {

    public function index(){
        if($this->islogged()){
            $this->load->model('RankingModel');
            $ranking = new RankingModel();
            
            $delivery = $ranking->listing(1);
            $msg = array("ranking" => $delivery);
            
            $this->load->view('template/header');
            $this->load->view('super/monthly', $msg);
        }
    }
    
    public function islogged() {
        if ($this->session->userdata('logged') === TRUE){
            return true;
        }
        else {
            redirect(base_url('login'));
        }
    }
}