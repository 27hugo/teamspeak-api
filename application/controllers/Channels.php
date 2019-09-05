<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';


class Channels extends REST_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('channels_model');
        $this->load->library('teamspeak');   
        date_default_timezone_set('America/Santiago');
    }

    public function index_get(){
        try{
            $channels = $this->channels_model->get();
            if(!is_null($channels))
                $this->response( array('data' => $channels) , REST_Controller::HTTP_OK);
            else
                $this->response( array('error' => 'No se han encontrado canales') , REST_Controller::HTTP_NO_CONTENT);
        }catch(Exception $e){
            $this->response( array('error' => $e->getMessage()) , REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }
    public function find_get( $channel_id ){
        try{
            if(is_null($channel_id)){
                $this->response( array('error' => 'falta can_id') , REST_Controller::HTTP_BAD_REQUEST);               
            }
            $channel = $this->channels_model->get( $channel_id );
            if(!is_null($channel))
                $this->response( array('data' => $channel) , REST_Controller::HTTP_OK);
            else
                $this->response( array('error' => 'No se ha encontrado el canal '.$channel_id) , REST_Controller::HTTP_NOT_FOUND);
        }catch(Exception $e){
            $this->response( array('error' => $e->getMessage()) , REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

    public function findByCliId_get( $cli_id ){
        try{
            if(is_null($cli_id)){
                $this->response( array('error' => 'falta cli_id') , REST_Controller::HTTP_BAD_REQUEST);               
            }
            $channels = $this->channels_model->getByCliId( $cli_id );
            if(!is_null($channels))
                $this->response( array('data' => $channels) , REST_Controller::HTTP_OK);
            else
                $this->response( array('error' => 'Aun no tienes canales') , REST_Controller::HTTP_NOT_FOUND);
        }catch(Exception $e){
            $this->response( array('error' => $e->getMessage()) , REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

    public function findBetween_post(){
        
        $first_date = $this->post('first_date');
        $second_date = $this->post('second_date');
        if(is_null($first_date))
            $this->response( array('error' => 'falta first_date') , REST_Controller::HTTP_BAD_REQUEST);    
        if(is_null($second_date))
            $this->response( array('error' => 'falta second_date') , REST_Controller::HTTP_BAD_REQUEST);    
        
        try{
            $channels = $this->channels_model->getChannelsBetweenMonths( $first_date, $second_date );
            if(!is_null($channels))
                $this->response( array('data' => $channels) , REST_Controller::HTTP_OK);
            else
                $this->response( array('error' => 'No existen canales en esta fecha') , REST_Controller::HTTP_NOT_FOUND);
        }catch(Exception $e){
            $this->response( array('error' => $e->getMessage()) , REST_Controller::HTTP_INTERNAL_SERVER_ERROR);    
        }
    }

    public function updateChannelName_put(){
        $channel = array(
            'can_id' => $this->put('can_id'),
            'can_nombre' => $this->put('can_nombre')
        );

        if( $channel['can_id'] == null ){
            $this->response( array('error' => 'falta can_id') , REST_Controller::HTTP_BAD_REQUEST);
        
        }else if( $channel['can_nombre'] == null ){
            $this->response( array('error' => 'falta can_nombre') , REST_Controller::HTTP_BAD_REQUEST);
        
        }
        try{
            $this->channels_model->updateChannelName( $channel );
            $this->teamspeak->editChannelName( $channel['can_id'], $channel['can_nombre'] );
            $this->response( array('data' => 'El canal ha sido actualizado'), REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( array('error' => $e->getMessage()), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateChannelPassword_put(){
        $channel = array(
            'can_id' => $this->put('can_id'),
            'can_contrasena' => $this->put('can_contrasena')
        );

        if( $channel['can_id'] == null ){
            $this->response( array('error' => 'falta can_id') , REST_Controller::HTTP_BAD_REQUEST);
        
        }else if( $channel['can_contrasena'] == null ){
            $this->response( array('error' => 'falta can_contrasena') , REST_Controller::HTTP_BAD_REQUEST);
        }
        try{
            $this->channels_model->updateChannelPassword( $channel );
            $this->teamspeak->editChannelPassword( $channel['can_id'], $channel['can_contrasena']);
            $this->response( array('data' => 'El canal ha sido actualizado'), REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( array('error' => $e->getMessage()), REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create_post(){
        $channel = array(
            'can_id' => null,
            'can_cli_id' => $this->post('can_cli_id'),
            'can_cli_ts_id' => null,
            'can_nombre'  => $this->post('can_nombre'),
            'can_contrasena' => $this->post('can_contrasena'),
            'can_creacion' => date('Y-m-d G:i:s'),
            'can_permisos' => null
        );

        if( $channel['can_cli_id'] == null ){
            $this->response( array('error' => 'falta can_cli_id') , REST_Controller::HTTP_BAD_REQUEST);
        
        }else if( $channel['can_nombre'] == null ){
            $this->response( array('error' => 'falta can_nombre') , REST_Controller::HTTP_BAD_REQUEST);
        
        }else if( $channel['can_contrasena'] == null ){
            $this->response( array('error' => 'falta can_contrasena') , REST_Controller::HTTP_BAD_REQUEST);
        }

        try{
            $can_id = $this->teamspeak->createChannel($this->post('can_nombre'), $this->post('can_contrasena'));
            $clientInfo = $this->teamspeak->getConnectedClientInfo();
            $channel['can_id'] = $can_id;
            $channel['can_cli_ts_id'] = $clientInfo['cli_ts_id'];
            if( $this->channels_model->createChannel($channel) ){
                $this->response( array('data' => $channel) , REST_Controller::HTTP_OK );
            }
            $this->response( array('error' => 'No se pudo crear el canal') , REST_Controller::HTTP_BAD_REQUEST );
        }catch(Exception $e){
            $this->response( array('error' => $e->getMessage()) , REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete_delete( $channel_id ){
        try{
            $this->channels_model->deleteChannel( $channel_id );
            $this->teamspeak->deleteChannel( $channel_id );
            $this->response( array('data' => 'El canal ha sido eliminado') , REST_Controller::HTTP_OK);
        }catch(Exception $e){   
            $this->response( array('error' => $e->getMessage()) , REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
