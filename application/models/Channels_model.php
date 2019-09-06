<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Channels_model extends CI_Model{
    
    public function __construct(){
        parent::__construct();
    }
    
    public function get($can_id = null){
        if (!is_null($can_id)) {
            $result = $this->db->where('can_id', $can_id)->get('canales');
            if ($result->num_rows() === 1) {
                return $result->row_object();
            }
            throw new Exception('El canal indicado no existe');
        }
    
        $result = $this->db->get('canales');
        if ($result->num_rows() > 0) {
            return $result->result_object();
        }
        throw new Exception('No se han creado canales aun');
    }
    
    public function createChannel( $channel ){
        $this->db->insert('canales', $channel );
        if($this->db->affected_rows() > 0)
            return true;
        throw new Exception('Ocurrió un error al crear canal');
    }

    public function getByCliId($cli_id){
        $result = $this->db->where('can_cli_id', $cli_id)->get('canales');
        if ($result->num_rows() > 0) {
            return $result->result_object();
        }
        throw new Exception('El cliente no registra ningun canal');
    }

    public function getChannelsBetweenMonths( $first_date, $second_date ){
        $this->db->where('can_creacion >=', $first_date);
        $this->db->where('can_creacion <=', $second_date);
        $result = $this->db->get('canales');
        if ($result->num_rows() > 0) {
            return $result->result_object();
        }
        throw new Exception('No existen canales en las fechas indicadas');    
    }

    public function updateChannelName( $channel ){  
        $this->db->set('can_nombre', $channel['can_nombre']);
        $this->db->where('can_id', $channel['can_id']);
        $this->db->update('canales');
        if( $this->db->affected_rows() === 1 )
            return $this->db->affected_rows();
        else
            throw new Exception('Ocurrió un error al actualizar el canal');
    }

    public function updateChannelPassword( $channel ){  
        $this->db->set('can_contrasena', $channel['can_contrasena']);
        $this->db->where('can_id', $channel['can_id']);
        $this->db->update('canales');
        if( $this->db->affected_rows() === 1 )
            return $this->db->affected_rows();
        else
            throw new Exception('Ocurrió un error al actualizar el canal');
    }

    public function deleteChannel( $channel_id){
        $this->db->where('can_id', $channel_id);
        $this->db->delete('canales');
        if( $this->db->affected_rows() === 1 )
            return $this->db->affected_rows();
        else
            throw new Exception('Ocurrió un error al eliminar el canal');
    }
}
