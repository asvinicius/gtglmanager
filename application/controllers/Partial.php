<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partial extends CI_Controller {

    public function index(){
        if($this->islogged()){
            $this->load->view('template/header');
            $status = $this->getstatus();
            
            switch($status['status_mercado']){
                case 1:
                    $this->load->view('super/unavailable');
                    break;
                case 2:
                    $this->load->view('super/partial');
                    break;
            }
        }
    }
    
    public function general(){
        if($this->islogged()){
            $this->load->view('template/header');
            $this->load->view('super/general');
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
    
    public function monthpartial($partial) {
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
        
        $t = array();
        
        $c = 0;        
        foreach ($json['times'] as $equipe) {
            $t[$c] = $equipe;
            $c++;
        }
        
        foreach ($partial as $team) {
            for($i = 0; $i<7; $i++){
                if($t[$i]['nome'] == $team['nome']){
                    $t[$i]['pontos']['mes'] = $t[$i]['pontos']['mes'] + $team['parcial'];
                }
            }
        }
        
        for($i = 0; $i<6; $i++){
            for($j = $i+1; $j<7; $j++){
                if($t[$j]['pontos']['mes'] > $t[$i]['pontos']['mes']){
                    $aux = $t[$i];
                    $t[$i] = $t[$j];
                    $t[$j] = $aux;
                }
            }
        }
        
        for($i = 0; $i<7; $i++){
            $t[$i]['pontos']['mes'] = number_format($t[$i]['pontos']['mes'], 2);
        }
        
        $ranking = array(
                "t1" => $t[0],
                "t2" => $t[1],
                "t3" => $t[2],
                "t4" => $t[3],
                "t5" => $t[4],
                "t6" => $t[5],
                "t7" => $t[6]);
        
        return $ranking;
        
    }
    
    public function listpartial() {
        
        $seco = $this->getSquad("chola-mais-ac");
        $galego = $this->getSquad("estacao-united-clube");
        $josemar = $this->getSquad("jacuipense-fc");
        $pereira = $this->getSquad("voe-bem");
        $thiaguinho = $this->getSquad("thiko-ac");
        $vinicius = $this->getSquad("dexolas-dexolas");
        $wellington = $this->getSquad("basel-fc");
       
        $cholamais = array(
                    "nome" => $seco['time']['nome'],
                    "cartoleiro" => $seco['time']['nome_cartola'],
                    "parcial" => $this->getPartial($seco['atletas']));
        $estacaoutd = array(
                    "nome" => $galego['time']['nome'],
                    "cartoleiro" => $galego['time']['nome_cartola'],
                    "parcial" => $this->getPartial($galego['atletas']));
        $jacuipense = array(
                    "nome" => $josemar['time']['nome'],
                    "cartoleiro" => $josemar['time']['nome_cartola'],
                    "parcial" => $this->getPartial($josemar['atletas']));
        $voebem = array(
                    "nome" => $pereira['time']['nome'],
                    "cartoleiro" => $pereira['time']['nome_cartola'],
                    "parcial" => $this->getPartial($pereira['atletas']));
        $thikoac = array(
                    "nome" => $thiaguinho['time']['nome'],
                    "cartoleiro" => $thiaguinho['time']['nome_cartola'],
                    "parcial" => $this->getPartial($thiaguinho['atletas']));
        $dexolas = array(
                    "nome" => $vinicius['time']['nome'],
                    "cartoleiro" => $vinicius['time']['nome_cartola'],
                    "parcial" => $this->getPartial($vinicius['atletas']));
        $baselfc = array(
                    "nome" => $wellington['time']['nome'],
                    "cartoleiro" => $wellington['time']['nome_cartola'],
                    "parcial" => $this->getPartial($wellington['atletas']));
                
        $t = array();
        $t[0] = $cholamais;
        $t[1] = $estacaoutd;
        $t[2] = $jacuipense;
        $t[3] = $voebem;
        $t[4] = $thikoac;
        $t[5] = $dexolas;
        $t[6] = $baselfc;
        
        for($i = 0; $i<6; $i++){
            for($j = $i+1; $j<7; $j++){
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
                "t7" => $t[6]);
        
        return $teams;
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
    
    
    public function islogged() {
        if ($this->session->userdata('logged') === TRUE){
            return true;
        }
        else {
            redirect(base_url('login'));
        }
    }
}