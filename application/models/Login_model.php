<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model{
    
    public function __construct(){
        parent::__construct();
        date_default_timezone_set('America/Santiago');
    }
    /*
    | Método para iniciar sesion que valida el usuario ingresado, este método
    | retorna un mensaje en caso de que la contraseña, o el nombre de usuario
    | no sean válidos. En caso de algun error interno retorna un mensaje de 
    | error
    */
    public function validateClient( $client ){
        $this->db->where('log_correo', $client['log_correo']);
        $result = $this->db->get('login');
        /* Comprobar si el usuario ingresado existe */
        if( $result->num_rows() === 0 ){
            throw new Exception('El usuario ingresado no existe');
        }
        /* Comprobar si la contraseña coincide con el usuario ingresado */
        $this->db->where('log_correo', $client['log_correo']);
        $this->db->where('log_contrasena', $client['log_contrasena']);
        $result = $this->db->get('login');
        if( $result->num_rows() === 1){
            /* Setear datos de conexión del cliente para cada login realizado */
            $this->db->set('log_ultima_conexion', date('Y-m-d H:i:s'));
            $this->db->set('log_conexion_ip', $client['log_conexion_ip']);
            $this->db->where('log_cli_id', $result->row()->log_cli_id);
            $this->db->update('login');
            if( $this->db->affected_rows() === 0 ){
                throw new Exception('No se han podido recuperar los datos de conexión');
            }
            return $result->row();
        }
        throw new Exception('La contraseña ingresada no es válida');
    }
    /*
    | Aqui se registran los datos del cliente a través de una transacción, esta
    | es necesaria para insertar en las dos tablas, login y clientes, en donde
    | se separa la información personal del cliente y en la otra donde se encuentra
    | lo relacionado con la sesión. Recibe los parametros con informacion del cliente
    | como array, y también los datos de login como array
    */
    public function registerClient( $client, $login ){
        $this->db->trans_start();
        
        $client['cli_creacion'] = date('Y-m-d H:i:s');
        $this->db->insert('clientes', $client );
        $cli_id = $this->db->insert_id();

        /* Se define el unique_id, que sería el mismo cli_id solo que cifrado para
        uso público y no entregar información directa de los id. */
        $this->db->set('cli_uid', md5($cli_id));
        $this->db->where('cli_id', $cli_id);
        $this->db->update('clientes');

        $login['log_cli_id'] = $cli_id;
        $this->db->insert('login', $login);
        
        $this->db->trans_complete();
        if( $this->db->trans_status() ){
            return true;
        }
        throw new Exception('Ocurrió un error al registrar cliente');
    }
    /*
    | Método para actualizar la contraseña de un determinado usuario, se reicbe
    | solo el id del cliente y la contraseña que será reemplazada.
    */
    public function updatePassword( $client ){
        $this->db->set('log_contrasena', $client['log_contrasena']);
        $this->db->where('log_cli_id', $client['log_cli_id']);
        $this->db->update('login');
        if( $this->db->affected_rows() != 1)
            throw new Exception('Ocurrió un error al actualizar la contraseña del cliente ID '.$client['log_cli_id']); 
    }

}
