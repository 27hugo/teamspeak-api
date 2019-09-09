<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Channels_model extends CI_Model{
    
    public function __construct(){
        parent::__construct();
        date_default_timezone_set('America/Santiago');
    }
    
    public function get($can_id = null){
        if (!is_null($can_id)) {
            $result = $this->db->where('can_id', $can_id)->get('canales');
            if ($result->num_rows() === 1) {
                return $result->row_object();
            }else{
                throw new Exception('El canal indicado no existe');
            }
        }
    
        $result = $this->db->get('canales');
        if ($result->num_rows() > 0) {
            return $result->result_object();
        }else{
            throw new Exception('No se han creado canales aun');
        }
    }
    
    public function createChannel( $channel ){
        $channel['can_creacion'] = date('Y-m-d H:i:s');           
        $this->db->insert('canales', $channel );
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            throw new Exception('Ocurrió un error al crear canal '.$channel['can_nombre']);
        }
    }

    public function getByCliId($cli_id){
        $result = $this->db->where('can_cli_id', $cli_id)->get('canales');
        if ($result->num_rows() > 0) {
            return $result->result_object();
        }else{
            throw new Exception('El cliente no registra ningun canal');
        }
    }

    public function getChannelsBetweenMonths( $first_date, $second_date ){
        $this->db->where('can_creacion >=', $first_date);
        $this->db->where('can_creacion <=', $second_date);
        $result = $this->db->get('canales');
        if ($result->num_rows() > 0) {
            return $result->result_object();
        }else{
            throw new Exception('No existen canales en las fechas indicadas');    
        }
    }

    public function updateChannelName( $channel ){  
        $this->db->set('can_nombre', $channel['can_nombre']);
        $this->db->where('can_id', $channel['can_id']);
        $this->db->update('canales');
        if( $this->db->affected_rows() === 1 ){
            return $this->db->affected_rows();
        }else{
            throw new Exception('Ocurrió un error al actualizar el nombre del canal ID '.$channel['can_id']);
        }
    }

    public function updateChannelPassword( $channel ){  
        $this->db->set('can_contrasena', $channel['can_contrasena']);
        $this->db->where('can_id', $channel['can_id']);
        $this->db->update('canales');
        if( $this->db->affected_rows() === 1 ){
            return $this->db->affected_rows();
        }else{
            throw new Exception('Ocurrió un error al actualizar la contraseña del canal ID '.$channel['can_id']);
        }
    }

    public function deleteChannel( $channel_id){
        $this->db->where('can_id', $channel_id);
        $this->db->delete('canales');
        if( $this->db->affected_rows() === 1 ){
            return $this->db->affected_rows();
        }else{
            throw new Exception('Ocurrió un error al eliminar el canal ID '.$channel['can_id']);
        }
    }
}
