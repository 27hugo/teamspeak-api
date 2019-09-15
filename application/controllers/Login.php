<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';


class Login extends REST_Controller{

    private $connection_client_ip;
    
    public function __construct(){
        

        parent::__construct();
        $this->connection_client_ip = $_SERVER['REMOTE_ADDR'];
        $this->load->model('login_model');
        $this->load->library('authorizationtoken');
        $this->load->library('encryption');
        $this->load->library('reply');
        $this->encryption->initialize( array(
            'driver' => 'openssl',
            'cipher' => 'aes-256',
            'mode'   => 'ecb'
        ));
    }
    
    public function index_post(){
        
        $client = array(
            'log_correo' => $this->post('log_correo'),
            'log_contrasena' => $this->encryption->encrypt($this->post('log_contrasena')),
            'log_conexion_ip' => $this->connection_client_ip
        );
        if( $client['log_correo'] == null){
            $this->response( $this->reply->error('falta log_correo'), REST_Controller::HTTP_OK );

        }else if( $client['log_contrasena'] == null){
            $this->response( $this->reply->error('falta log_contrasena') , REST_Controller::HTTP_OK );
        
        }
        try{
            $client = $this->login_model->validateClient( $client );
            
            $tokenpayload['id'] = md5($client->log_cli_id);
            $tokenpayload['email'] = $client->log_correo; 
            $tokenpayload['time'] = time();
            $token = $this->authorizationtoken->generateToken( $tokenpayload );
            $this->response( $this->reply->ok( $token ), REST_Controller::HTTP_OK);
        }catch(Exception $e){
            $this->response( $this->reply->error( $e->getMessage() ), REST_Controller::HTTP_OK);
        }
    }

    public function changePassword_put(){
        
        $client = array(
            'log_cli_id' => $this->put('log_cli_id'),
            'log_contrasena' => $this->encryption->encrypt($this->put('log_contrasena'))
        );
        if( $client['log_cli_id'] == null ){
            $this->response( $this->reply->error('falta log_cli_id') , REST_Controller::HTTP_OK);
        
        }else if( $client['log_contrasena'] == null ){
            $this->response( $this->reply->error('falta log_contrasena'), REST_Controller::HTTP_OK);
        }
        
        try{
            $this->login_model->updatePassword( $client );
            $this->response( $this->reply->ok('Contraseña actualizada con éxito') , REST_Controller::HTTP_OK);
        }catch(Exception $e){
            log_message('error', $e->getMessage());
            $this->response( $this->reply->fatal($e->getMessage()) , REST_Controller::HTTP_OK);
        }
    }

    public function register_post(){

        $client = array(
            'cli_nombre' => $this->post('cli_nombre'),
            'cli_alias'  => $this->post('cli_alias'),
            'cli_region' => $this->post('cli_region'),
            'cli_ciudad' => $this->post('cli_ciudad'),
            'cli_nacimiento' => $this->post('cli_nacimiento')
        );
        $login = array(
            'log_correo' => $this->post('log_correo'),
            'log_contrasena' => $this->encryption->encrypt($this->post('log_contrasena')),
            'log_conexion_ip' => $this->connection_client_ip
        );

        if( $client['cli_nombre'] == null ){
            $this->response( $this->reply->error('falta cli_nombre') , REST_Controller::HTTP_OK);
        
        }else if( $client['cli_region'] == null ){
            $this->response( $this->reply->error('falta cli_region') , REST_Controller::HTTP_OK);
        
        }else if( $client['cli_ciudad'] == null ){
            $this->response( $this->reply->error('falta cli_ciudad') , REST_Controller::HTTP_OK);
        
        }else if( $client['cli_nacimiento'] == null ){
            $this->response( $this->reply->error('falta cli_nacimiento') , REST_Controller::HTTP_OK);
        
        }else if( $login['log_correo'] == null ){
            $this->response( $this->reply->error('falta log_correo') , REST_Controller::HTTP_OK);
        
        }else if( $this->post('log_contrasena') == null ){
            $this->response( $this->reply->error('falta log_contrasena') , REST_Controller::HTTP_OK);
        }

        try{
            $this->login_model->registerClient( $client, $login );
            $this->response( $this->reply->ok('Registro completado con éxito') , REST_Controller::HTTP_OK );
        }catch(Exception $e){
            log_message('error', $e->getMessage());
            $this->response( $this->reply->fatal($e->getMessage()) , REST_Controller::HTTP_OK);
        }
    }

}
