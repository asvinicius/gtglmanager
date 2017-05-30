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
            
            $msg = array("individual" => $delivery, "accumulated" => $acc, "wallet" => $listwallet, "current" => $current);
            
            $this->load->view('template/header');
            $this->load->view('super/bank', $msg);
        }
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
        $this->load->model('MarketstatusModel');
        $mktstatus = new MarketstatusModel();
        $this->load->model('WalletModel');
        $wallet = new WalletModel();
        
        $current = $mktstatus->search();
        
        $total = $wallet->search(0);
        
        $totaldata['idwallet'] = $total['idwallet'];
        $totaldata['reference'] = $total['reference'];
        $totaldata['collected'] = $total['collected']+20;
        $totaldata['premium'] = $total['premium'];
        $totaldata['accumulated'] = $total['accumulated']+20;
        
        if($wallet->update($totaldata)){
            $here = $wallet->search($current['currentmonth']);
            
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
                $heredata['reference'] = $current['currentmonth'];
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