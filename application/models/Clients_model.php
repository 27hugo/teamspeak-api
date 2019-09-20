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
        return $result->result_object();
    }
    
    public function updateClient( $client ){  
        $this->db->set('cli_nombre', $client['cli_nombre'] );
        $this->db->set('cli_alias', $client['cli_alias'] );
        $this->db->set('cli_region', $client['cli_region'] );
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
    public function connection_get($client_id){
        $this->db->where('his_log_cli_id', $client_id);
        $this->db->order_by('his_log_ultima_conexion', 'DESC');
        $this->db->limit(5);

        $result = $this->db->get('historial_login');
        return $result->result_object();
    }
    public function connectionbetween($client){
        $this->db->where('his_log_cli_id', $client['cli_id']);
        $this->db->where('his_log_ultima_conexion >=', $client['first_date']);
		$this->db->where('his_log_ultima_conexion <=', $client['second_date']);
        $this->db->order_by('his_log_ultima_conexion', 'DESC');
        $this->db->limit(5);

        $result = $this->db->get('historial_login');
        return $result->result_object();
    }
     public function connectionpermonth($year, $month ){
     	$this->db->select('count(his_id) as total_conexiones' );
	    $this->db->where('year(his_log_ultima_conexion)', date($year));
	   	$this->db->where('month(his_log_ultima_conexion)', date($month));
	    
	    $result = $this->db->get('historial_login');
	    return $result->result_object();
    }
    public function total_clients(){
    	$this->db->select('count(cli_id) as total_clientes');
    	$this->db->from('clientes');
	    $result = $this->db->get();
	    return $result->result_object();
    }

    
}





