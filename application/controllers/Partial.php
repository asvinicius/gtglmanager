<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partial extends CI_Controller {

    public function index(){
        if($this->islogged()){
            $status = $this->getstatus();
            $delivery = $status['status_mercado'];
            $mis = array("status" => $delivery);
            $this->load->view('template/header', $mis);
            
            switch($status['status_mercado']){
                case 1:
                    $this->load->view('super/unavailable');
                    break;
                case 2:
                    $partial = $this->listpartial();
                    $month = $this->monthpartial($partial);
                    $overall = $this->overallpartial($partial);
                    
                    $msg = array("partial" => $partial, "month" => $month, "overall" => $overall);
                    
                    $this->load->view('super/partial', $msg);
                    break;
            }
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
    
    public function listpartial() {
        $this->load->model('TeamModel');
        $team = new TeamModel();
        
        $finished = $team->listing();
        $final = 0;
        foreach ($finished as $value) {
            $final = $final+1;
        }
        
        $squad = array();
        
        $c = 0;
        foreach ($finished as $value) {
            for($i = 0; $i<$final; $i++){
                if($c == $i){
                    $squad[$i] = $this->getSquad($value->slugteam);
                }
            }
            $c = $c+1;
        }
        
        $t = array();
        
        for($i = 0; $i<$final; $i++){
            $t[$i] = array(
                "nome" => $squad[$i]['time']['nome'],
                "cartoleiro" => $squad[$i]['time']['nome_cartola'],
                "parcial" => $this->getPartial($squad[$i]['atletas']));
        }
                
        for($i = 0; $i<$final-1; $i++){
            for($j = $i+1; $j<$final; $j++){
                if($t[$j]['parcial'] > $t[$i]['parcial']){
                    $aux = $t[$i];
                    $t[$i] = $t[$j];
                    $t[$j] = $aux;
                }
            }
        }
        
        $teams = array(
                "t1" => $t[0],
                "t2" => $t[1],
                "t3" => $t[2],
                "t4" => $t[3],
                "t5" => $t[4],
                "t6" => $t[5],
                "t7" => $t[6],
                "t8" => $t[7]);
        
        return $teams;
    }
    
    public function monthpartial($partial) {
        
        $json = $this->getleague();
        
        $t = array();
        
        $c = 0;        
        foreach ($json['times'] as $equipe) {
            $t[$c] = $equipe;
            $c++;
        }
        
        foreach ($partial as $team) {
            for($i = 0; $i<$c; $i++){
                if($t[$i]['nome'] == $team['nome']){
                    $t[$i]['pontos']['mes'] = $t[$i]['pontos']['mes'] + $team['parcial'];
                }
            }
        }
        
        for($i = 0; $i<$c-1; $i++){
            for($j = $i+1; $j<$c; $j++){
                if($t[$j]['pontos']['mes'] > $t[$i]['pontos']['mes']){
                    $aux = $t[$i];
                    $t[$i] = $t[$j];
                    $t[$j] = $aux;
                }
            }
        }
        
        for($i = 0; $i<$c; $i++){
            $t[$i]['pontos']['mes'] = number_format($t[$i]['pontos']['mes'], 2);
        }
        
        $month = array(
                "t1" => $t[0],
                "t2" => $t[1],
                "t3" => $t[2],
                "t4" => $t[3],
                "t5" => $t[4],
                "t6" => $t[5],
                "t7" => $t[6],
                "t8" => $t[7]);
        
        return $month;
        
    }
    
    public function overallpartial($partial) {
        
        $json = $this->getleague();
        
        $t = array();
        
        $c = 0;        
        foreach ($json['times'] as $equipe) {
            $t[$c] = $equipe;
            $c++;
        }
        
        foreach ($partial as $team) {
            for($i = 0; $i<$c; $i++){
                if($t[$i]['nome'] == $team['nome']){
                    $t[$i]['pontos']['campeonato'] = $t[$i]['pontos']['campeonato'] + $team['parcial'];
                }
            }
        }
        
        for($i = 0; $i<$c-1; $i++){
            for($j = $i+1; $j<$c; $j++){
                if($t[$j]['pontos']['campeonato'] > $t[$i]['pontos']['campeonato']){
                    $aux = $t[$i];
                    $t[$i] = $t[$j];
                    $t[$j] = $aux;
                }
            }
        }
        
        for($i = 0; $i<$c; $i++){
            $t[$i]['pontos']['campeonato'] = number_format($t[$i]['pontos']['campeonato'], 2);
        }
        
        $overall = array(
                "t1" => $t[0],
                "t2" => $t[1],
                "t3" => $t[2],
                "t4" => $t[3],
                "t5" => $t[4],
                "t6" => $t[5],
                "t7" => $t[6],
                "t8" => $t[7]);
        
        return $overall;
        
    }
    
    public function getSquad($slug) {
        
        $url = 'https://api.cartolafc.globo.com/time/slug/'.$slug;
        
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
    
    public function getPartial($atletas) {
        $url = 'https://api.cartolafc.globo.com/atletas/pontuados';
        
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
        
        $parcial = 0;
        
        foreach ($atletas as $atleta){
            foreach ($json['atletas'] as $verify) {
              if($atleta['apelido'] == $verify['apelido'] && $atleta['clube_id'] == $verify['clube_id']){
                  $parcial = $parcial + $verify['pontuacao'];
              }
            }
        }
        return $parcial; 
        
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