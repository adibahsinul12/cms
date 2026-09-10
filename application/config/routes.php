<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// API Routes
$route['api/post'] = 'api/post/index';
$route['api/post/(:num)'] = 'api/post/detail/$1';
$route['api/post/revisions/(:num)'] = 'api/post/revisions/$1';

// Admin Routes
$route['admin'] = 'admin/dashboard';
$route['admin/dashboard'] = 'admin/dashboard';
$route['admin/posts'] = 'admin/posts';
$route['admin/posts/create'] = 'admin/create_post';
$route['admin/posts/edit/(:num)'] = 'admin/edit_post/$1';
$route['admin/categories'] = 'admin/categories';
$route['admin/tags'] = 'admin/tags';

// Admin Routes
$route['admin/categories'] = 'admin/categories';
$route['admin/tags'] = 'admin/tags';
$route['admin/media'] = 'admin/media';

// Auth Routes
$route['auth/login'] = 'auth/login';
$route['auth/register'] = 'auth/register';
$route['auth/logout'] = 'auth/logout';
$route['admin/users'] = 'admin/users';