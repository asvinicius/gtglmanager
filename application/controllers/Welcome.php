<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index(){
        if($this->islogged()){
            
            if($this->uptodate()){
                $this->load->model('CurrentModel');
                $current = new CurrentModel();
                
                $currentinfo = $current->search();
                
                $delivery = $this->showpart();
                
                $msg = array("status" => $delivery, "current" => $currentinfo);
                
                $this->load->view('template/header', $msg);
                $this->load->view('super/home', $msg);
            }
        }
    }
    
    public function uptodate() {
        $this->load->model('MarketstatusModel');
        $status = new MarketstatusModel();
        
        $mktstat = $status->search();
        $json = $this->getstatus();
        
        if($json['status_mercado'] == 4){
            return true;
        }else{
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
    
    public function updatedatabase() {
        $league = $this->getleague();
        
        $this->checknewteam($league);
        $this->checkoverall($league);
        $this->checkmonth($league);
        
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
        }
    }
    
    public function checkmonth($league) {
        $this->load->model('RankingModel');
        $ranking = new RankingModel();
        
        foreach ($league['times'] as $leagueteam) {
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
    
    public function setstatus($league) {
        $this->load->model('MarketstatusModel');
        $status = new MarketstatusModel();
        
        $json = $this->getstatus();
        
        $aux = null;
        $c = 0;
        foreach ($league['times'] as $value) {
            if($c==0){
                $aux = $value['ranking']['mes'];
            }
            $c++;
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