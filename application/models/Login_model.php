<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model{
    
    public function __construct(){
        parent::__construct();
        date_default_timezone_set('America/Santiago');
    }
    
    public function validateClient( $client ){
        $this->db->where('log_correo', $client['log_correo']);
        $this->db->where('log_contrasena', $client['log_contrasena']);
        $result = $this->db->get('login');
        if( $result->num_rows() === 1){
            $this->db->set('log_ultima_conexion', date('Y-m-d H:i:s'));
            $this->db->set('log_conexion_ip', $client['log_conexion_ip']);
            $this->db->where('log_cli_id', $result->row()->log_cli_id);
            $this->db->update('login');
            if( $this->db->affected_rows() === 0 ){
                throw new Exception('No se ha podido recuperar los datos de conexión');
            }
            return $result->row();
        }
        throw new Exception('Usuario y/o contraseña no válidos');
    }
    
    public function registerClient( $client, $login ){
        $this->db->trans_start();

        $this->db->insert('clientes', $client );
        $cli_id = $this->db->insert_id();

        $this->db->set('cli_uid', md5($cli_id));
        $this->db->where('cli_id', $cli_id);
        $this->db->update('clientes');

        $login['log_cli_id'] = $cli_id;
        $this->db->insert('login', $login);
    
        if($this->db->trans_complete())
            return true;
        throw new Exception('Ocrrió un error al registrar cliente');
    }

    public function updatePassword( $client ){
        $this->db->set('log_contrasena', $client['log_contrasena']);
        $this->db->where('log_cli_id', $client['log_cli_id']);
        $this->db->update('login');
        if( $this->db->affected_rows() != 1)
            throw new Exception('Ocurrió un error al actualizar la contraseña'); 
    }

}
