<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| CORS HEADERS (Untuk koneksi Frontend - Backend API)
| -------------------------------------------------------------------------
*/
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// API Routes - Stage 1 (INDONESIA)
$route['api/post']['get']                  = 'api/post/index';
$route['api/post/detail/(:num)']['get']    = 'api/post/detail/$1';
$route['api/post/create']['post']          = 'api/post/create';
$route['api/post/update/(:num)']['put']    = 'api/post/update/$1';
$route['api/post/update/(:num)']['post']   = 'api/post/update/$1';
$route['api/post/delete/(:num)']['delete'] = 'api/post/delete/$1';
$route['api/post/revisions/(:num)']['get'] = 'api/post/revisions/$1';