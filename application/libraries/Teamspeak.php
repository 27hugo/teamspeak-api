<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once( APPPATH.'third_party/teamspeak3/TeamSpeak3.php' );

class Teamspeak{

    private $host = 'owc.cl';
    private $username = 'fer';
    private $password = 'KJMipyCz';
    private $port = '9987';
    private $queryport = '10011';
    
    public function __construct(){
        $uri = 'serverquery://'.$this->username.':'.$this->password.'@'.$this->host.':'.$this->queryport.'/?server_port='.$this->port;	
        $this->factory = TeamSpeak3::factory($uri);
    }

    public function getClients(){
        $tsClients = $this->factory->clientList();
        $clients = [];
        $client = [];
        foreach($tsClients as $c){
            $client['cli_id'] = $c['client_database_id'];
            $client['cli_nickname'] = $c['client_nickname'];
            $client['cli_ip'] = $c['connection_client_ip'];
            $client['cli_platform'] = $c['client_platform'];
            $client['cli_version'] = $c['client_version'];
            array_push( $clients , $client );
        }
        return $clients;
    }
    
    public function getChannels(){
        $tsChannels = $this->factory->channelList();
        $channels = [];
        $channel = [];
        foreach($tsChannels as $c){
            $channel['can_id'] = $c['cid'];
            $channel['can_contrasena'] = $c['channel_name'];
            array_push( $channels , $channel );
        }
        return $channels;
    }

    public function createChannel($channel_name, $channel_password){
        $channel_id = $this->factory->channelCreate(array(
            "channel_name" => $channel_name,
            "channel_password" => $channel_password,
            "channel_flag_permanent" => TRUE
        ));
        return $channel_id;
    }
    public function editChannelName($channel_id, $channel_name){
        $properties["cid"] = $channel_id;
        $properties["channel_name"] = $channel_name;
        $this->factory->execute("channeledit", $properties);
        $this->factory->resetNodeInfo();
    }
    public function editChannelPassword($channel_id, $channel_password){
        $properties['cid'] = $channel_id;
        $properties['channel_password'] = $channel_password;
        $this->factory->execute("channeledit", $properties);
        $this->factory->resetNodeInfo();
    }
    public function deleteChannel($channel_id){
        $this->factory->channelDelete($channel_id);
    }
    public function getConnectedClientInfo(){
        //IP produccion
        //$connection_client_ip = $_SERVER['REMOTE_ADDR'];
		
		//IP localhost / desarrollo
		$connection_client_ip = file_get_contents("http://ipecho.net/plain");
		$client = [];
        $clients = $this->factory->clientList();
        foreach ($clients as $cli) {
            if($connection_client_ip == $cli['connection_client_ip']){
                $client['cli_ts_id'] = $cli['client_database_id'];
                $client['cli_ts_nickname'] = $cli['client_nickname'];
                $client['cli_ts_ip'] =  $connection_client_ip;
            }
        }
        var_dump($client);
        return $client;
    }
}

