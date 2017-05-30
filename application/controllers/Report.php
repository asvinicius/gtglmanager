<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

    public function index(){
        if($this->islogged()){            
            $this->load->view('template/header');
            $this->load->view('super/report');
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