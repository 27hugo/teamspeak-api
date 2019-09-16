<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class Channels extends REST_Controller{

    private $authorization;

    public function __construct(){
        parent::__construct();
        $this->load->model('channels_model');
        $this->load->library('authorizationtoken');
        $this->load->library('reply');
        $this->authorization = $this->authorizationtoken->validateToken();

        if( $this->authorization['status'] == false)
            $this->response( $this->reply->error($this->authorization['message']) , REST_Controller::HTTP_OK);
       
    }

    public function index_get(){
        try{
            $channels = $this->channels_model->get();
            $this->response( array('status' => 'OK', 'data' => $channels) , REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( $this->reply->error($e->getMessage()) , REST_Controller::HTTP_OK);
        }
        
    }
    public function find_get( $channel_id ){
        //cumple con todos lo necesario para buscar un canal por "channel_id"
        try{
            if(is_null($channel_id)){
                $this->response( $this->reply->error('falta can_id') , REST_Controller::HTTP_OK);               
            }
            $channel = $this->channels_model->get( $channel_id );
            $this->response( $this->reply->ok($channel) , REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( $this->reply->error($e->getMessage()) , REST_Controller::HTTP_OK);
        }
        
    }

    public function findByCliId_get( $cli_id ){
        //cumple con todos lo necesario para buscar los canales creados por el cliente con su "cli_id"
        try{
            if(is_null($cli_id)){
                $this->response( $this->reply->error('falta cli_id') , REST_Controller::HTTP_OK);               
            }
            $channels = $this->channels_model->getByCliId( $cli_id );
            $this->response( $this->reply->ok($channels) , REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( $this->reply->error($e->getMessage()) , REST_Controller::HTTP_OK);
        }
        
    }

    public function findBetween_post(){
         //cumple con todos lo necesario para buscar los canales creados entre las fechas ingresadas
        
        $first_date = $this->post('first_date');
        $second_date = $this->post('second_date');
        if( $first_date == null){
            $this->response( $this->reply->error('falta first_date') , REST_Controller::HTTP_OK);    
        
        }else if( $second_date == null){
            $this->response( $this->reply->error('falta second_date') , REST_Controller::HTTP_OK);    
        
        }
        try{
            $channels = $this->channels_model->getChannelsBetweenMonths( $first_date, $second_date );
            $this->response( $this->reply->ok($channels) , REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( $this->reply->error($e->getMessage()) , REST_Controller::HTTP_OK);    
        }
    }

    public function updateChannelName_put(){
         //cumple con todos lo necesario para cambiar el nombre del canal segun su "can_id"
        $channel = array(
            'can_id' => $this->put('can_id'),
            'can_nombre' => $this->put('can_nombre')
        );

        if( $channel['can_id'] == null ){
            $this->response( $this->reply->error('falta can_id') , REST_Controller::HTTP_OK);
        
        }else if( $channel['can_nombre'] == null ){
            $this->response( $this->reply->error('falta can_nombre') , REST_Controller::HTTP_OK);
        
        }
        try{
            $this->load->library('teamspeak');   
            $this->channels_model->updateChannelName( $channel );
            $this->teamspeak->editChannelName( $channel['can_id'], $channel['can_nombre'] );
            $this->response( $this->reply->ok('Nombre del canal actualizado'), REST_Controller::HTTP_OK);
        }catch(Exception $e){
            log_message('error', $e->getMessage());
            $this->response( $this->reply->fatal($e->getMessage()), REST_Controller::HTTP_OK);
        }
    }

    public function updateChannelPassword_put(){
        //cumple con todos lo necesario para cambiar la contraseña del canal segun su "can_id"
        $channel = array(
            'can_id' => $this->put('can_id'),
            'can_contrasena' => $this->put('can_contrasena')
        );

        if( $channel['can_id'] == null ){
            $this->response( $this->reply->error('falta can_id') , REST_Controller::HTTP_OK);
        
        }else if( $channel['can_contrasena'] == null ){
            $this->response( $this->reply->error('falta can_contrasena') , REST_Controller::HTTP_OK);
        }
        try{
            $this->load->library('teamspeak');   
            $this->channels_model->updateChannelPassword( $channel );
            $this->teamspeak->editChannelPassword( $channel['can_id'], $channel['can_contrasena']);
            $this->response( $this->reply->ok('Contraseña del canal actualizada'), REST_Controller::HTTP_OK);
        }catch(Exception $e){
            log_message('error', $e->getMessage());
            $this->response( $this->reply->fatal($e->getMessage()), REST_Controller::HTTP_OK);
        }
    }

    public function create_post(){
        //cumple con todos lo necesario para crear un canal
        $channel = array(
            'can_id' => null,
            'can_cli_id' => $this->post('can_cli_id'),
            'can_cli_ts_id' => null,
            'can_nombre'  => $this->post('can_nombre'),
            'can_contrasena' => $this->post('can_contrasena'),
            'can_permisos' => null
        );

        if( $channel['can_cli_id'] == null ){
            $this->response( $this->reply->error('falta can_cli_id') , REST_Controller::HTTP_OK);
        
        }else if( $channel['can_nombre'] == null ){
            $this->response( $this->reply->error('falta can_nombre') , REST_Controller::HTTP_OK);
        
        }else if( $channel['can_contrasena'] == null ){
            $this->response( $this->reply->error('falta can_contrasena') , REST_Controller::HTTP_OK);
        }

        try{
            $this->load->library('teamspeak');   
            $can_id = $this->teamspeak->createChannel($this->post('can_nombre'), $this->post('can_contrasena'));
            $clientInfo = $this->teamspeak->getConnectedClientInfo();
            $channel['can_id'] = $can_id;
            $channel['can_cli_ts_id'] = $clientInfo['cli_ts_id'];
            $this->channels_model->createChannel($channel);
            $this->response( $this->reply->ok($channel) , REST_Controller::HTTP_OK );
        }catch(Exception $e){
            log_message('error', $e->getMessage());
            $this->response( $this->reply->fatal($e->getMessage()) , REST_Controller::HTTP_OK);
        }
    }

    public function delete_delete( $channel_id = null ){
        //cumple con todos lo necesario para borrar un canal por su "$channel_id"
        if( $channel_id == null){
            $this->response( $this->reply->error('falta can_id'), REST_Controller::HTTP_OK);

        }
        try{
            $this->load->library('teamspeak');   
            $this->channels_model->deleteChannel( $channel_id );
            $this->teamspeak->deleteChannel( $channel_id );
            $this->response( $this->reply->ok('El canal ha sido eliminado') , REST_Controller::HTTP_OK);
        }catch(Exception $e){   
            log_message('error', $e->getMessage());
            $this->response( $this->reply->fatal($e->getMessage()) , REST_Controller::HTTP_OK);
        }
    }
}
