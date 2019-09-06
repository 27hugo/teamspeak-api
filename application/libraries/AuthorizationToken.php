<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/php-jwt/JWT.php';
require_once APPPATH . 'third_party/php-jwt/BeforeValidException.php';
require_once APPPATH . 'third_party/php-jwt/ExpiredException.php';
require_once APPPATH . 'third_party/php-jwt/SignatureInvalidException.php';

use \Firebase\JWT\JWT;

class AuthorizationToken {

    protected $token_key;
    protected $token_algorithm;
    protected $token_header = ['authorization','Authorization'];

    /**
     * Token Expire Time
     * ----------------------
     * ( 1 Day ) : 60 * 60 * 24 = 86400
     * ( 1 Hour ) : 60 * 60     = 3600
     */
    protected $token_expire_time = 3600; 

    public function __construct(){
        $this->CI =& get_instance();
        $this->CI->load->config('jwt');
        $this->token_key        = $this->CI->config->item('jwt_key');
        $this->token_algorithm  = $this->CI->config->item('jwt_algorithm');
    }

    public function generateToken($data){
        try {
            return JWT::encode($data, $this->token_key, $this->token_algorithm);
        }catch(Exception $e) {
            return $e->getMessage();
        }
    }

    public function validateToken(){
        
        $headers = $this->CI->input->request_headers();

        $token_data = $this->tokenIsExist($headers);
        if($token_data['status'] === TRUE){
            try{
                try {
                    $token_decode = JWT::decode($headers[$token_data['key']], $this->token_key, array($this->token_algorithm));
                }
                catch(Exception $e) {
                    return ['status' => FALSE, 'message' => $e->getMessage()];
                }

                if(!empty($token_decode) AND is_object($token_decode)){
                    // Check User ID exists
                    if(empty($token_decode->id)){
                        return ['status' => FALSE, 'message' => 'No se definió el id del token.'];

                    // Check Token Time
                    }else if(empty($token_decode->time OR !is_numeric($token_decode->time))) {
                        return ['status' => FALSE, 'message' => 'No se definió la hora del token.'];
                    
                    }else{
                        // Check Token Time Valid 
                        $time_difference = strtotime('now') - $token_decode->time;
                        if( $time_difference >= $this->token_expire_time ){
                            return ['status' => FALSE, 'message' => 'El token ha expirado.'];

                        }else{
                            // All Validation False Return Data
                            return ['status' => TRUE, 'data' => $token_decode];
                        }
                    }
                    
                }else{
                    return ['status' => FALSE, 'message' => 'Forbidden'];
                }
            }catch(Exception $e) {
                return ['status' => FALSE, 'message' => $e->getMessage()];
            }
        }else{
            // Authorization Header Not Found!
            return ['status' => FALSE, 'message' => $token_data['message'] ];
        }
    }

    public function tokenIsExist($headers){
        if(!empty($headers) AND is_array($headers)) {
            foreach ($this->token_header as $key) {
                if (array_key_exists($key, $headers) AND !empty($key))
                    return ['status' => TRUE, 'key' => $key];
            }
        }
        return ['status' => FALSE, 'message' => 'Token no encontrado'];
    }

    public function userData(){
        $headers = $this->CI->input->request_headers();
        $token_data = $this->tokenIsExist($headers);
        if($token_data['status'] === TRUE){
            try{
                try {
                    $token_decode = JWT::decode($headers[$token_data['key']], $this->token_key, array($this->token_algorithm));
                }catch(Exception $e) {
                    return ['status' => FALSE, 'message' => $e->getMessage()];
                }

                if(!empty($token_decode) AND is_object($token_decode)){
                    return $token_decode;
                }else{
                    return ['status' => FALSE, 'message' => 'Forbidden'];
                }
            }catch(Exception $e) {
                return ['status' => FALSE, 'message' => $e->getMessage()];
            }
        }else{
            // Authorization Header Not Found!
            return ['status' => FALSE, 'message' => $token_data['message'] ];
        }
    }

}
