<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class Clients extends REST_Controller{

    private $authorization;

    public function __construct(){
        parent::__construct();
        $this->load->model('clients_model');
        $this->load->library('teamspeak');   
        $this->load->library('authorizationtoken');
        $this->authorization = $this->authorizationtoken->validateToken();

        if( $this->authorization['status'] == false)
            $this->response( array('status' => 'ERROR', 'error' => $this->authorization['message']) , REST_Controller::HTTP_OK); 

    }

    public function index_get(){
        try{
            $clients = $this->clients_model->get();
            $this->response( array('status' => 'OK', 'data' => $clients) , REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( array('status' => 'ERROR', 'error' => $e->getMessage()) , REST_Controller::HTTP_OK);
        }
        
    }

    public function online_get(){
        try{
            $clients = $this->teamspeak->getClients();
            $this->response( array('status' => 'OK', 'data' => $clients) , REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( array('status' => 'ERROR', 'error' => $e->getMessage()) , REST_Controller::HTTP_OK);
        }
    }

    public function find_get( $client_id ){
        try{
            if(is_null($client_id)){
                $this->response( array('status' => 'ERROR', 'error' => 'falta cli_id') , REST_Controller::HTTP_OK);               
            }
            
            $client = $this->clients_model->get( $client_id );
            $this->response( array('status' => 'OK', 'data' => $client) , REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( array('status' => 'ERROR', 'error' => $e->getMessage()) , REST_Controller::HTTP_OK);
        }
        
    }

    public function update_put(){
        $client = array(
            'cli_id' => $this->put('cli_id'),
            'cli_nombre' => $this->put('cli_nombre'),
            'cli_alias' => $this->put('cli_alias'),
            'cli_pais' => $this->put('cli_pais'),
            'cli_ciudad' => $this->put('cli_ciudad'),
            'cli_nacimiento' => $this->put('cli_nacimiento')    
        );

        if( $client['cli_id'] == null ){
            $this->response( array('status' => 'ERROR', 'error' => 'falta cli_id') , REST_Controller::HTTP_OK);
        
        }else if( $client['cli_nombre'] == null ){
            $this->response( array('status' => 'ERROR', 'error' => 'falta cli_nombre') , REST_Controller::HTTP_OK);
        
        }else if( $client['cli_pais'] == null ){
            $this->response( array('status' => 'ERROR', 'error' => 'falta cli_pais') , REST_Controller::HTTP_OK);
        
        }else if( $client['cli_ciudad'] == null ){
            $this->response( array('status' => 'ERROR', 'error' => 'falta cli_ciudad') , REST_Controller::HTTP_OK);
        
        }else if( $client['cli_nacimiento'] == null ){
            $this->response( array('status' => 'ERROR', 'error' => 'falta cli_nacimiento') , REST_Controller::HTTP_OK);
        
        }
        try{
            $this->clients_model->updateClient( $client );
            $this->response( array('status' => 'OK', 'data' => 'El cliente ha sido actualizado'), REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( array('status' => 'ERROR', 'error' => $e->getMessage()), REST_Controller::HTTP_OK);
        }
    }

    public function delete_delete( $client_id ){
        try{
            $this->clients_model->deleteClient( $client_id );
            $this->response( array('status' => 'OK', 'data' => 'El cliente ha sido eliminado') , REST_Controller::HTTP_OK);    
        }catch(Exception $e){   
            $this->response( array('status' => 'ERROR', 'error' => $e->getMessage()) , REST_Controller::HTTP_OK);
        }
    }
}
