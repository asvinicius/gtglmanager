<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

    public function index(){
        if($this->islogged()){       
            $this->load->model('DetailModel');
            $detail = new DetailModel();
            
            $delivery = $detail->listing();
            $msg = array("detail" => $delivery);
            
            $this->load->view('template/header');
            $this->load->view('super/report', $msg);
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