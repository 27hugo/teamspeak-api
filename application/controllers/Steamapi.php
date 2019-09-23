<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class Steamapi extends REST_Controller{

    private $authorization;

    public function __construct(){
        parent::__construct();
        $this->load->library('reply');  
    }

    public function getSteamProfile_get($steamid = null){
        if( $steamid == null ){
            $this->response( $this->reply->error('Debe ingresar el steamID del jugador') , REST_Controller::HTTP_OK);
        }
        if(!is_numeric($steamid)){
            $this->response( $this->reply->error('Debe ingresar un steamID válido') , REST_Controller::HTTP_OK);
        }
        
        try{
        	$url = 'http://steamcommunity.com/profiles/'.$steamid.'/?xml=1';
            $data    = file_get_contents($url);
			$request     = simplexml_load_string($data);
			$profile = array();
			if(!empty($request)) {
            	foreach ($request as $key => $value) {
					$profile[(string)$key] = (string)$value;
				}
                $this->response( array('status' => 'OK', 'data' => $profile) , REST_Controller::HTTP_OK);
            }else{
                $this->response( $this->reply->error('La solicitud no encontró ningún perfil') , REST_Controller::HTTP_OK);
            }
        }catch(Exception $e){
            $this->response( $this->reply->error($e->getMessage()) , REST_Controller::HTTP_OK);
        }
    }
}
