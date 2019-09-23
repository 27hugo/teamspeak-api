<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reply{

    public function __construct(){
        
    }

    public function ok($data){
        return [
            'status'=> 'OK',
            'data' => $data
        ];
    }
    public function error($err){
        return [
            'status' => 'ERROR',
            'error' => $err
        ];
    }
    public function fatal($err){
        return [
            'status' => 'FATAL',
            'error' => $err
        ];
    }

}