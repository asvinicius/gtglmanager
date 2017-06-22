<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

    public function index(){
        if($this->islogged()){       
            $this->load->model('DetailModel');
            $detail = new DetailModel();
            
            $delivery = $detail->listing();
            $msg = array("detail" => $delivery, "status" => $this->showpart());
            
            $this->load->view('template/header', $msg);
            $this->load->view('super/report', $msg);
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
    
    public function closemonth() {
        if($this->islogged()){       
            $this->load->model('StatusoffModel');
            $this->load->model('RankingModel');
            
            $statusoff = new StatusoffModel();
            $ranking = new RankingModel();
            
            $monthstatus = $statusoff->search();            
            $monthranking = $ranking->listing(1);
            
            $msg = array("month" => $monthstatus, "ranking" => $monthranking);
            
            $this->load->view('template/header');
            $this->load->view('super/closemonth', $msg);
        }
    }
    
    public function conclude($reference) {
        if($this->islogged()){
            $this->checkbank($reference);
            $this->checkinfo();
            
            if($this->checkstatus()){
                redirect(base_url('report'));
            }
        }
    }
    
    public function checkbank($reference) {
        $this->load->model('WalletModel');
        $this->load->model('PaymentModel');
        $this->load->model('RankingModel');
        
        $wallet = new WalletModel();
        $payment = new PaymentModel();
        $ranking = new RankingModel();
        
        $total = $wallet->search(0);
        
        $totaldata['idwallet'] = $total['idwallet'];
        $totaldata['reference'] = $total['reference'];
        $totaldata['collected'] = $total['collected'];
        $totaldata['premium'] = $total['premium']+120;
        $totaldata['accumulated'] = $total['accumulated']-120;
        
        if($wallet->update($totaldata)){
            $current = $wallet->search($reference);
            
            $currentdata['idwallet'] = $current['idwallet'];
            $currentdata['reference'] = $current['reference'];
            $currentdata['collected'] = $current['collected'];
            $currentdata['premium'] = $current['premium']+120;
            $currentdata['accumulated'] = $current['accumulated']-120;
            
            if($wallet->update($currentdata)){
                $finished = $ranking->listing(1);
                $final = 0;
                foreach ($finished as $value) {
                    $final = $final+1;
                }
                for($i = 1; $i<=$final; $i++){
                    
                    $value = $payment->searchpayment($i);
                    
                    $paymentdata['idpayment'] = $value['idpayment'];
                    $paymentdata['team'] = $value['team'];
                    $paymentdata['amount'] = $value['amount']+1;

                    if($payment->update($paymentdata)){
                    }
                }
            }
        }
    }
    
    public function checkinfo() {
        $this->load->model('StatusoffModel');
        $this->load->model('RankingModel');
        $this->load->model('DetailModel');
        $this->load->model('CurrentModel');
        $this->load->model('TeamModel');

        $statusoff = new StatusoffModel();
        $ranking = new RankingModel();
        $detail = new DetailModel();
        $current = new CurrentModel();
        $team = new TeamModel();
        
        $finished = $ranking->listing(1);
        $monthinfo = $statusoff->search();
        $currentinfo = $current->search();

        $detaildata['iddetail'] = null;
        $detaildata['month'] = $monthinfo['monthoff'];
        $detaildata['champion'] = null;
        $detaildata['worse'] = null;
        
        $currentdata['idcurrent'] =  $currentinfo['idcurrent'];
        $currentdata['month'] = $monthinfo['monthoff'];
        $currentdata['champion'] = null;
        $currentdata['worse'] = null;
        
        $final = 0;
        foreach ($finished as $value) {
            $final = $final+1;
        }
        
        $cont = 1;
        foreach ($finished as $value) {
            switch ($cont) {
                case 1:
                    $obj = $team->search($value->team);
                    $detaildata['champion'] = $obj['namecoach'];
                    $currentdata['champion'] = $obj['namecoach'];
                    break;
                case $final:
                    $obj = $team->search($value->team);
                    $detaildata['worse'] = $obj['namecoach'];
                    $currentdata['worse'] = $obj['namecoach'];
                    break;
            }
            $cont++;
        }
        $detail->save($detaildata);
        $current->update($currentdata);
    }
    
    public function checkstatus() {
        $this->load->model('StatusoffModel');

        $statusoff = new StatusoffModel();
        
        $off = $statusoff->search();
        
        $offdata['idstatusoff'] = $off['idstatusoff'];
        $offdata['monthoff'] = $off['monthoff']+1;
        
        if($statusoff->update($offdata)){
            return true;
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