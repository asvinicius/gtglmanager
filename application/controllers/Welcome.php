<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index(){
        if($this->islogged()){
            
            if($this->uptodate()){
                $json = $this->getstatus();
                $delivery = $json['status_mercado'];
                $msg = array("status" => $delivery);
                
                $this->load->view('template/header');
                $this->load->view('super/home', $msg);
            }
        }
    }
    
    public function uptodate() {
        $this->load->model('MarketstatusModel');
        $status = new MarketstatusModel();
        
        $mktstat = $status->search();
        $json = $this->getstatus();
        
        if($json['rodada_atual'] == $mktstat['currentround']){
            if($json['status_mercado'] == $mktstat['marketstatus']){
                return true;
            }else{
                return $this->updatedatabase();
            }
        }else{
            return $this->updatedatabase();
        }
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
    
    public function updatedatabase() {
        $league = $this->getleague();
        
        $this->checknewteam($league);
        $this->checkoverall($league);
        $this->checkbank($league);
        $this->checkinfo($league);
        
        if($this->setstatus($league)){
            return true;
        }
    }
    
    public function checknewteam($league) {
        $this->load->model('TeamModel');
        $team = new TeamModel();
        
        foreach ($league['times'] as $leagueteam) {
            $aux = $team->search($leagueteam['time_id']);
            
            if(!$aux){
                $teamdata['idteam'] = $leagueteam['time_id'];
                $teamdata['namecoach'] = $leagueteam['nome_cartola'];
                $teamdata['nameteam'] = $leagueteam['nome'];
                $teamdata['slugteam'] = $leagueteam['slug'];

                if($team->save($teamdata)){
                }
            }
        }
    }
    
    public function checkoverall($league) {
        $this->load->model('RankingModel');
        $ranking = new RankingModel();
        
        foreach ($league['times'] as $leagueteam) {
            $aux = $ranking->search($leagueteam['time_id'], 0);
            
            if($aux){
                $rankingdata['idranking'] = $aux['idranking'];
                $rankingdata['team'] = $aux['team'];
                $rankingdata['rating'] = $leagueteam['pontos']['campeonato'];
                $rankingdata['patrimony'] = $leagueteam['patrimonio'];
                $rankingdata['type'] = $aux['type'];

                if($ranking->update($rankingdata)){
                }
            }else{
                $rankingdata['idranking'] = null;
                $rankingdata['team'] = $leagueteam['time_id'];
                $rankingdata['rating'] = $leagueteam['pontos']['campeonato'];
                $rankingdata['patrimony'] = $leagueteam['patrimonio'];
                $rankingdata['type'] = 0;

                if($ranking->save($rankingdata)){
                }
            }
            
            
            $aux = $ranking->search($leagueteam['time_id'], 1);
            
            if($aux){
                $rankingdata['idranking'] = $aux['idranking'];
                $rankingdata['team'] = $aux['team'];
                $rankingdata['rating'] = $leagueteam['pontos']['mes'];
                $rankingdata['patrimony'] = $leagueteam['patrimonio'];
                $rankingdata['type'] = $aux['type'];

                if($ranking->update($rankingdata)){
                }
            }else{
                $rankingdata['idranking'] = null;
                $rankingdata['team'] = $leagueteam['time_id'];
                $rankingdata['rating'] = $leagueteam['pontos']['mes'];
                $rankingdata['patrimony'] = $leagueteam['patrimonio'];
                $rankingdata['type'] = 1;

                if($ranking->save($rankingdata)){
                }
            }
        }
    }
    
    public function checkbank($league) {
        $this->load->model('MarketstatusModel');
        $mktstatus = new MarketstatusModel();
        $this->load->model('WalletModel');
        $wallet = new WalletModel();
        
        $current = $mktstatus->search();
        
        if($league['ranking']['mes'] != $current['currentmonth']){
            $verify = $wallet->search($current['currentmonth']);

            if($verify['premium'] == 0){

                $total = $wallet->search(0);

                $totaldata['idwallet'] = $total['idwallet'];
                $totaldata['reference'] = $total['reference'];
                $totaldata['collected'] = $total['collected'];
                $totaldata['premium'] = $total['premium']+105;
                $totaldata['accumulated'] = $total['accumulated']-105;

                if($wallet->update($totaldata)){
                    $finished = $wallet->search($current['currentmonth']);

                    $finisheddata['idwallet'] = $finished['idwallet'];
                    $finisheddata['reference'] = $finished['reference'];
                    $finisheddata['collected'] = $finished['collected'];
                    $finisheddata['premium'] = $finished['premium']+105;
                    $finisheddata['accumulated'] = $finished['accumulated']-105;

                    if($wallet->update($finisheddata)){
                    }
                }
            }
        }
    }
    
    public function checkinfo($league) {
        $this->load->model('RankingModel');
        $this->load->model('DetailModel');
        $this->load->model('TeamModel');
        
        $ranking = new RankingModel();
        $detail = new DetailModel();
        $team = new TeamModel();
        
        if($league['ranking']['mes'] != $current['currentmonth']){
            $finished = $ranking->listing(1);
            
            $detaildata['iddetail'] = null;
            $detaildata['month'] = $current['currentmonth'];
            
            $cont = 1;
            
            foreach ($finished as $value) {
                switch ($cont) {
                    case 1:
                        $obj = $team->search($value['team']);
                        $detaildata['champion'] = $obj['namecoach'];
                        break;
                    case 7:
                        $obj = $team->search($value['team']);
                        $detaildata['worse'] = $obj['namecoach'];
                        break;
                }
                $cont++;
            }
        }
    }
    
    public function setstatus($league) {
        $this->load->model('MarketstatusModel');
        $status = new MarketstatusModel();
        
        $json = $this->getstatus();
        
        $aux = null;
        foreach ($league['times'] as $value) {
            $aux = $value['ranking']['mes'];
        }
        
        $mktstat = $status->search();
        
        $mktsdata['idmktstatus'] = $mktstat['idmktstatus'];
        $mktsdata['currentmonth'] = $aux;
        $mktsdata['currentround'] = $json['rodada_atual'];
        $mktsdata['marketstatus'] = $json['status_mercado'];
        
        if($status->update($mktsdata)){
            return true;
        }
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
        
        return $json;
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