<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clients_model extends CI_Model{
    
    public function __construct(){
        parent::__construct();
        date_default_timezone_set('America/Santiago');
    }
    
    public function get($client_id = null){
        if (!is_null($client_id)) {
            $result = $this->db->where('cli_id', $client_id)->get('clientes');
            if ($result->num_rows() === 1) {
                return $result->row_object();
            }
            throw new Exception('El cliente indicado no existe');
        }
    
        $result = $this->db->get('clientes');
        if ($result->num_rows() > 0) {
            return $result->result_object();
        }
        throw new Exception('No hay clientes registrados');
    }
    
    public function updateClient( $client ){  
        $this->db->set('cli_nombre', $client['cli_nombre'] );
        $this->db->set('cli_alias', $client['cli_alias'] );
        $this->db->set('cli_pais', $client['cli_pais'] );
        $this->db->set('cli_ciudad', $client['cli_ciudad'] );
        $this->db->set('cli_nacimiento', $client['cli_nacimiento'] ); 
        $this->db->where('cli_id', $client['cli_id']);
        $this->db->update('clientes');
        if( $this->db->affected_rows() === 1 )
            return $this->db->affected_rows();
        else
            throw new Exception('Ocurrió un error al actualizar cliente ID '.$client['cli_id']);
    }

    
    public function deleteClient( $client_id){
        $this->db->where('cli_id', $client_id);
        $this->db->delete('clientes');
        if( $this->db->affected_rows() === 1 )
            return $this->db->affected_rows();
        else
            throw new Exception('Ocurrió un error al eliminar cliente ID '.$client['cli_id']);
    }
}
