<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
|-------------------------------------------------------
| Server Hostname
|-------------------------------------------------------
|
| Set the teamspeak host server to connect and
| get clients and channels information. This
| API only works if this parameters are configurated.
| Isn't needed the IP, also works with domains
|
| Example: $config['host'] = "example.com";
|
*/
$config['host'] = '';

/*
|-------------------------------------------------------
| Server Admin User  
|-------------------------------------------------------
|
| Set the user name provide to access a server admin 
| queries. It is required to access server information
| and use the API for create, delete or update channels
|
| Example: $config['username'] = 'userdemo';
|
*/
$config['username'] = '';

/*
|-------------------------------------------------------
| Server Admin Password 
|-------------------------------------------------------
|
| Set the user password provide to access a server admin 
| queries. It is required to access server information
| and use the API for create, delete or update channels
|
| Example: $config['password'] = 'userpassword';
|
*/
$config['password'] = '';

/*
|-------------------------------------------------------
| Connection Port  
|-------------------------------------------------------
|
| Set the server connection port (default: 9987)
|
| Example: $config['port'] = '9987';
|
*/
$config['port'] = "9987";

/*
|-------------------------------------------------------
| Server Query Port  
|-------------------------------------------------------
| Set the server query connection port (default: 10011)
|
| Example $config['queryport'] = '10011';
|
*/
$config['queryport'] = "10011";