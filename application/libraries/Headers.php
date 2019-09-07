<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once( APPPATH.'third_party/teamspeak3/TeamSpeak3.php' );

class Headers{

	public function __construct(){
		header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Content-Type: application/json");
        header("Access-Control-Allow-Headers: Access-Control-Allow-Origin ,Origin, Content-Type, Content-Length, Accept-Encoding, Authorization");

        if ( "OPTIONS" === $_SERVER['REQUEST_METHOD'] ) {
            die();
        }		
	}

}
