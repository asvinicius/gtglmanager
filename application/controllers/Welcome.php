<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index(){
        if($this->islogged()){
            
            if($this->needsupdating()){
                echo "Precisa atualizar";
            }else{
                echo "Não precisa atualizar";
            }
            
            return false;
            
            $this->load->view('template/header');
            $this->load->view('super/home');
        }
        
    }
    
    public function needsupdating() {
        
        $this->load->model('MarketstatusModel');
        $status = new MarketstatusModel();
        
        $url = 'https://api.cartolafc.globo.com/mercado/status';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER ,[
          'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
          'Content-Type: application/json',
        ]);
        $result = curl_exec($ch);
        
        if ($result === FALSE) {
            die(curl_error($ch));
        }
        
        curl_close($ch);
        
        $json = json_decode($result, true);
        
        $mktstat = $status->search();
        
        if($mktstat['currentround'] == $json['rodada_atual']){
            if($mktstat['marketstatus'] == $json['status_mercado']){
                return false;
            }else{
                return true;
            }
            
        }else{
            return true;
        }
    }
    
    public function updateinfo() {
        $league = $this->getleague();
    }
    
    public function getleague() {
        
        $url = 'https://api.cartolafc.globo.com/auth/liga/gt-grades-league-2017';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER ,[
          'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
          'Content-Type: application/json',
          'X-GLB-Token: '.$this->session->userdata('glbId'),
        ]);
        $result = curl_exec($ch);
        
        if ($result === FALSE) {
            die(curl_error($ch));
        }
        
        curl_close($ch);
        
        $json = json_decode($result, true);
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