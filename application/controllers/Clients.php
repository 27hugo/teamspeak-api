<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class Clients extends REST_Controller{

    private $authorization;

    public function __construct(){
        parent::__construct();
        $this->load->model('clients_model');
        $this->load->library('AuthorizationToken');
        $this->load->library('Reply');
        $this->authorization = $this->authorizationtoken->validateToken();

        if( $this->authorization['status'] == false)
            $this->response( $this->reply->fatal($this->authorization['message']) , REST_Controller::HTTP_OK); 

    }

    public function index_get(){
        try{
            $clients = $this->clients_model->get();
            $this->response( $this->reply->ok($clients) , REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( $this->reply->error($e->getMessage()) , REST_Controller::HTTP_OK);
        }
        
    }

    public function online_get(){
        //cumple con todos lo necesario para ver los clientes en linea
        try{
            $this->load->library('teamspeak');   
            $clients = $this->teamspeak->getClients();
            $this->response( $this->reply->ok($clients) , REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( $this->reply->error($e->getMessage()) , REST_Controller::HTTP_OK);
        }
    }
     public function countOnline_get(){
        //cumple con todos lo necesario para ver los clientes en linea
        try{
            $this->load->library('teamspeak');   
            $clients = $this->teamspeak->getClients();

            $this->response( $this->reply->ok(count($clients)-1) , REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( $this->reply->error($e->getMessage()) , REST_Controller::HTTP_OK);
        }
    }

    public function find_get( $client_id ){
        //cumple con todos lo necesario para buscar los datos del cliente segun su "cli_id"
        try{
            if(is_null($client_id)){
                $this->response( $this->reply->error('falta cli_id') , REST_Controller::HTTP_OK);               
            }
            
            $client = $this->clients_model->get( $client_id );
            $this->response( $this->reply->ok($client) , REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( $this->reply->error($e->getMessage()) , REST_Controller::HTTP_OK);
        }
        
    }

    public function update_put(){
        //cumple con todos lo necesario para cambiar los datos del cliente segun su "cli_id"
        $client = array(
            'cli_id' => $this->put('cli_id'),
            'cli_nombre' => $this->put('cli_nombre'),
            'cli_alias' => $this->put('cli_alias'),
            'cli_region' => $this->put('cli_region'),
            'cli_ciudad' => $this->put('cli_ciudad'),
            'cli_nacimiento' => $this->put('cli_nacimiento')    
        );

        if( $client['cli_id'] == null ){
            $this->response( $this->reply->error('falta cli_id') , REST_Controller::HTTP_OK);
        
        }else if( $client['cli_nombre'] == null ){
            $this->response( $this->reply->error('falta cli_nombre') , REST_Controller::HTTP_OK);
        
        }else if( $client['cli_region'] == null ){
            $this->response( $this->reply->error('falta cli_region') , REST_Controller::HTTP_OK);
        
        }else if( $client['cli_ciudad'] == null ){
            $this->response( $this->reply->error('falta cli_ciudad') , REST_Controller::HTTP_OK);
        
        }else if( $client['cli_nacimiento'] == null ){
            $this->response( $this->reply->error('falta cli_nacimiento') , REST_Controller::HTTP_OK);
        
        }
        try{
            $this->clients_model->updateClient( $client );
            $this->response( $this->reply->ok('El cliente ha sido actualizado'), REST_Controller::HTTP_OK);
        }catch(Exception $e){
            log_message('error', $e->getMessage());
            $this->response( $this->reply->fatal($e->getMessage()), REST_Controller::HTTP_OK);
        }
    }

    public function delete_delete( $client_id ){
        //cumple con todos lo necesario para borrar un cliente segun su "cli_id"
        try{
            $this->clients_model->deleteClient( $client_id );
            $this->response( $this->reply->ok('El cliente ha sido eliminado') , REST_Controller::HTTP_OK);    
        }catch(Exception $e){   
            log_message('error', $e->getMessage());
            $this->response( $this->reply->fatal($e->getMessage()) , REST_Controller::HTTP_OK);
        }
    }
     public function lastConnections_get( $client_id =null ){
        //cumple con todos lo necesario para buscar los datos del cliente segun su "cli_id"
         try{
            if(is_null($client_id)){
                $this->response( $this->reply->error('falta cli_id') , REST_Controller::HTTP_OK);               
            }
            
            $client = $this->clients_model->lastConnections( $client_id );
            $this->response( $this->reply->ok($client) , REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( $this->reply->error($e->getMessage()) , REST_Controller::HTTP_OK);
        }
        
    }
    public function connectionsBetween_post(){
        //cumple con todos lo necesario para buscar los datos del cliente segun su "cli_id"
          $client = array(
            'cli_id' => $this->post('cli_id'),
            'first_date' => $this->post('first_date'),
            'second_date' => $this->post('second_date')  
        );
          if( $client['cli_id'] == null ){
            $this->response( $this->reply->error('falta cli_id') , REST_Controller::HTTP_OK);
        
        }else if( $client['first_date'] == null ){
            $this->response( $this->reply->error('falta first_date') , REST_Controller::HTTP_OK);
        
        }else if( $client['second_date'] == null ){
            $this->response( $this->reply->error('falta second_date') , REST_Controller::HTTP_OK);
        
        }
         try{
            $result = $this->clients_model->connectionsBetween( $client );
            $this->response( $this->reply->ok($result), REST_Controller::HTTP_OK);
        }catch(Exception $e){
            log_message('error', $e->getMessage());
            $this->response( $this->reply->fatal($e->getMessage()), REST_Controller::HTTP_OK);
        }   
    }

    public function connectionsPerMonth_get($year, $month){ 
          //cumple con todos lo necesario para buscar los datos del cliente segun su "cli_id"
        try{	
         	 if(is_null($year)){
                $this->response( $this->reply->error('falta año') , REST_Controller::HTTP_OK);               
            }
            if(is_null($month)){
                $this->response( $this->reply->error('falta mes') , REST_Controller::HTTP_OK);               
            }
            $result = $this->clients_model->connectionsPerMonth( $year, $month);
            $this->response( $this->reply->ok($result) , REST_Controller::HTTP_OK);

        }catch(Exception $e){

            $this->response( $this->reply->error($e->getMessage()) , REST_Controller::HTTP_OK);
        }
        
    }
    public function totalClients_get(){

    	  try{          
            $client = $this->clients_model->totalClients();
            $this->response( $this->reply->ok($client) , REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( $this->reply->error($e->getMessage()) , REST_Controller::HTTP_OK);
        }

    }
    
        
    
}
