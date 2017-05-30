<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Overall extends CI_Controller {

    public function index(){
        if($this->islogged()){
            $this->load->model('RankingModel');
            $ranking = new RankingModel();
            
            $delivery = $ranking->listing(0);
            $msg = array("ranking" => $delivery);
            
            $this->load->view('template/header');
            $this->load->view('super/overall', $msg);
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