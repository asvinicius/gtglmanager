<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function index(){
        if ($this->session->userdata('logged') === TRUE) {
            redirect(base_url('welcome'));
        } else {
            $this->load->view('login');
        }
    }
    
    public function signin() {
        
        header('Content-type: application/json');
        
        $email = $this->input->get("email");
        $password = $this->input->get("password");
        $serviceId = 4728;
        
        $url = 'https://login.globo.com/api/authentication';
        
        $jsonAuth = array(
          'captcha' => '',
          'payload' => array(
            'email' => $email,
            'password' => $password,
            'serviceId' => $serviceId
          )
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($jsonAuth));
        $result = curl_exec($ch);
        
        if ($result === FALSE) {
          die(curl_error($ch));
        }
        curl_close($ch);
        
        $parseJson = json_decode($result, TRUE);
        
        
        if($parseJson['id'] == "Authenticated"){            
            $session = array(
                'glbId' => $parseJson['glbId'],
                'logged' => TRUE
            );
            $this->session->set_userdata($session);
            redirect(base_url('login'));
        }else{            
            redirect(base_url('login'));
        }      
        
    }
    
    public function signout() {
        
        $url = 'https://login.globo.com/logout';
        
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
        
        $this->session->sess_destroy();
        redirect(base_url());
    }
}