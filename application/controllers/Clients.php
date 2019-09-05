<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';


class Clients extends REST_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('clients_model');
        $this->load->library('teamspeak');   
        date_default_timezone_set('America/Santiago');
    }

    public function index_get(){
        try{
            $clients = $this->clients_model->get();
            $this->response( array('data' => $clients) , REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( array('error' => $e->getMessage()) , REST_Controller::HTTP_NOT_FOUND);
        }
        
    }

    public function online_get(){
        try{
            $clients = $this->teamspeak->getClients();
            $this->response( array('data' => $clients) , REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( array('error' => $e->getMessage()) , REST_Controller::HTTP_NOT_FOUND);
        }
    }
    public function find_get( $client_id ){
        try{
            if(is_null($client_id)){
                $this->response( array('error' => 'falta cli_id') , REST_Controller::HTTP_BAD_REQUEST);               
            }
            
            $client = $this->clients_model->get( $client_id );
            $this->response( array('data' => $client) , REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( array('error' => $e->getMessage()) , REST_Controller::HTTP_NOT_FOUND);
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
            $this->response( array('error' => 'falta cli_id') , REST_Controller::HTTP_BAD_REQUEST);
        
        }else if( $client['cli_nombre'] == null ){
            $this->response( array('error' => 'falta cli_nombre') , REST_Controller::HTTP_BAD_REQUEST);
        
        }else if( $client['cli_pais'] == null ){
            $this->response( array('error' => 'falta cli_pais') , REST_Controller::HTTP_BAD_REQUEST);
        
        }else if( $client['cli_ciudad'] == null ){
            $this->response( array('error' => 'falta cli_ciudad') , REST_Controller::HTTP_BAD_REQUEST);
        
        }else if( $client['cli_nacimiento'] == null ){
            $this->response( array('error' => 'falta cli_nacimiento') , REST_Controller::HTTP_BAD_REQUEST);
        
        }
        try{
            $this->clients_model->updateClient( $client );
            $this->response( array('data' => 'El cliente ha sido actualizado'), REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( array('error' => $e->getMessage()), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function register_post(){
        $client = array(
            'cli_nombre' => $this->post('cli_nombre'),
            'cli_alias'  => $this->post('cli_alias'),
            'cli_pais' => $this->post('cli_pais'),
            'cli_ciudad' => $this->post('cli_ciudad'),
            'cli_nacimiento' => $this->post('cli_nacimiento'),
            'cli_creacion' => date('Y-m-d G:i:s')
        );
        $login = array(
            'log_correo' => $this->post('log_correo'),
            'log_contrasena' => $this->post('log_contrasena')
        );

        if( $client['cli_nombre'] == null ){
            $this->response( array('error' => 'falta cli_nombre') , REST_Controller::HTTP_BAD_REQUEST);
        
        }else if( $client['cli_pais'] == null ){
            $this->response( array('error' => 'falta cli_pais') , REST_Controller::HTTP_BAD_REQUEST);
        
        }else if( $client['cli_ciudad'] == null ){
            $this->response( array('error' => 'falta cli_ciudad') , REST_Controller::HTTP_BAD_REQUEST);
        
        }else if( $client['cli_nacimiento'] == null ){
            $this->response( array('error' => 'falta cli_nacimiento') , REST_Controller::HTTP_BAD_REQUEST);
        
        }else if( $login['log_correo'] == null ){
            $this->response( array('error' => 'falta log_correo') , REST_Controller::HTTP_BAD_REQUEST);
        
        }else if( $login['log_contrasena'] == null ){
            $this->response( array('error' => 'falta log_contrasena') , REST_Controller::HTTP_BAD_REQUEST);
        }

        try{
            $this->clients_model->registerClient( $client, $login );
            $this->response( array('data' => 'Registro completado con éxito') , REST_Controller::HTTP_OK );
        }catch(Exception $e){
            $this->response( array('error' => $e->getMessage()) , REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete_delete( $client_id ){
        try{
            $this->clients_model->deleteClient( $client_id );
            $this->response( array('data' => 'El cliente ha sido eliminado') , REST_Controller::HTTP_OK);    
        }catch(Exception $e){   
            $this->response( array('error' => $e->getMessage()) , REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
