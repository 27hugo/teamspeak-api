<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'empresas';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;



//$route['cities']['get'] = 'cities/index';
//$route['cities/(:num)']['get'] = 'cities/find/$1';
//$route['cities']['post'] = 'cities/index';
//$route['cities/(:num)']['put'] = 'cities/index/$1';
//$route['cities/(:num)']['delete'] = 'cities/index/$1';


/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
//$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
//$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8
