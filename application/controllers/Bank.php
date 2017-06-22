<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends CI_Controller {

    public function index(){
        if($this->islogged()){
            $this->load->model('PaymentModel');
            $payment = new PaymentModel();
            $this->load->model('MarketstatusModel');
            $mktstatus = new MarketstatusModel();
            $this->load->model('WalletModel');
            $wallet = new WalletModel();
            
            $delivery = $payment->listing();
            $accumulated = $wallet->search(0);
            $acc = $accumulated['accumulated'];
            $listwallet = $wallet->listing();
            $current = $mktstatus->search();
            
            $msg = array("individual" => $delivery, 
                         "accumulated" => $acc, 
                         "wallet" => $listwallet, 
                         "current" => $current,
                         "status" => $this->showpart());
            
            $this->load->view('template/header', $msg);
            $this->load->view('super/bank', $msg);
        }
    }
    
    public function showpart() {
        $json = $this->getstatus();
        return $json['status_mercado'];
    }
    public function getstatus() {
        
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
        
        return $json;
    }
    
    function addquota() {
        if($this->islogged()){
            $this->load->model('TeamModel');
            $team = new TeamModel();
            $delivery = $team->listing();
            $msg = array("teams" => $delivery);
            $this->load->view('template/header');
            $this->load->view('super/addquota', $msg);
        }
    }
    
    public function savequota() {
        $this->load->model('PaymentModel');
        $payment = new PaymentModel();
        
        $vteam = $this->input->get('team');
        
        $aux = $payment->search($vteam);
        
        if($aux){            
            if($this->renew($aux)){                
                redirect(base_url('bank'));
            }
        }else{            
            if($this->newpayment($vteam)){
                redirect(base_url('bank'));
            }    
        }
    }
    
    public function newpayment($vteam) {
        $this->load->model('PaymentModel');
        $payment = new PaymentModel();
        
        $paymentdata['idpayment'] = null;
        $paymentdata['team'] = $vteam;
        $paymentdata['amount'] = 0;    

        if($payment->save($paymentdata)){               
            return $this->updatewallet();
        }
    }
    
    public function renew($aux) {
        $this->load->model('PaymentModel');
        $payment = new PaymentModel();
        
        $paymentdata['idpayment'] = $aux['idpayment'];
        $paymentdata['team'] = $aux['team'];
        $paymentdata['amount'] = $aux['amount']-1;
        
        if($payment->update($paymentdata)){              
            return $this->updatewallet();
        }
    }
    
    public function updatewallet() {
        $this->load->model('StatusoffModel');
        $this->load->model('WalletModel');
            
        $statusoff = new StatusoffModel();
        $wallet = new WalletModel();
        
        $current = $statusoff->search();
        
        $total = $wallet->search(0);
        
        $totaldata['idwallet'] = $total['idwallet'];
        $totaldata['reference'] = $total['reference'];
        $totaldata['collected'] = $total['collected']+20;
        $totaldata['premium'] = $total['premium'];
        $totaldata['accumulated'] = $total['accumulated']+20;
        
        if($wallet->update($totaldata)){
            $here = $wallet->search($current['monthoff']);
            
            if($here){
                $heredata['idwallet'] = $here['idwallet'];
                $heredata['reference'] = $here['reference'];
                $heredata['collected'] = $here['collected']+20;
                $heredata['premium'] = $here['premium'];
                $heredata['accumulated'] = $here['accumulated']+20;
                
                if($wallet->update($heredata)){
                    return true;
                }
            }else{
                $heredata['idwallet'] = null;
                $heredata['reference'] = $current['monthoff'];
                $heredata['collected'] = 20;
                $heredata['premium'] = 0;
                $heredata['accumulated'] = 20;
                
                if($wallet->save($heredata)){
                    return true;
                }
            }
        }
    }
    
    public function cancel() {
        redirect(base_url('bank'));            
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