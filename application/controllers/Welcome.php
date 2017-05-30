<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index(){
        if($this->islogged()){
            
            if($this->needsupdating()){
                $this->updatedatabase();
            }else{
                $this->load->view('template/header');
                $this->load->view('super/home');
            }            
        }
    }
    
    public function updatedatabase() {
        $this->checknewteam();
        $this->checkoverall();
        $this->checkbank();
        $this->checkinfo();
        
        if($this->setstatus()){
            redirect(base_url('welcome'));
        }
    }
    
    public function checknewteam() {
        $this->load->model('TeamModel');
        $team = new TeamModel();
        
        $league = $this->getleague();
        
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
    
    public function checkoverall() {
        $this->load->model('RankingModel');
        $ranking = new RankingModel();
        
        $league = $this->getleague();
        
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
    
    public function checkbank() {
        $this->load->model('MarketstatusModel');
        $mktstatus = new MarketstatusModel();
        $this->load->model('WalletModel');
        $wallet = new WalletModel();
        
        $current = $mktstatus->search();
        
        $verify = $wallet->search($current['currentmonth']-1);
        
        if($verify['premium'] == 0){
        
            $total = $wallet->search(0);

            $totaldata['idwallet'] = $total['idwallet'];
            $totaldata['reference'] = $total['reference'];
            $totaldata['collected'] = $total['collected'];
            $totaldata['premium'] = $total['premium']+105;
            $totaldata['accumulated'] = $total['accumulated']-105;

            if($wallet->update($totaldata)){
                $finished = $wallet->search($current['currentmonth']-1);

                if($finisheddata){
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
    
    public function checkinfo() {
        $this->load->model('MarketstatusModel');
        $mktstatus = new MarketstatusModel();
        $this->load->model('CurrentModel');
        $current = new CurrentModel();
        
        $status = $mktstatus->search();
        
        if($status['currentmonth']>1){
            $info = $current->search($status['currentmonth']-1);
            if(!$info){
                
            }
        }else{
            
        }
    }
    
    public function setstatus() {
        
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
        
        $mktsdata['idmktstatus'] = $mktstat['idmktstatus'];
        $mktsdata['currentround'] = $json['rodada_atual'];
        $mktsdata['marketstatus'] = $json['status_mercado'];
        
        if($status->update($mktsdata)){
            return true;
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