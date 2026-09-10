<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller']   = 'home_view/index';
$route['404_override']         = '';
$route['translate_uri_dashes'] = FALSE;

// ==========================================
// 1. FRONTEND ADMIN ROUTES
// ==========================================
$route['admin']                   = 'admin/dashboard';
$route['admin/dashboard']         = 'admin/dashboard';
$route['admin/posts']             = 'admin/posts';
$route['admin/posts/create']      = 'admin/create_post';
$route['admin/posts/edit/(:num)'] = 'admin/edit_post/$1';
$route['admin/categories']        = 'admin/categories';
$route['admin/tags']              = 'admin/tags';
$route['admin/media']             = 'admin/media';
$route['admin/users']             = 'admin/users';
$route['admin/settings']          = 'admin/settings';

// ==========================================
// 2. BACKEND API ROUTES (Stage 1 - INDONESIA)
// ==========================================
$route['api/post']                  = 'api/post/index';
$route['api/post/detail/(:num)']    = 'api/post/detail/$1';
$route['api/post/create']           = 'api/post/create';
$route['api/post/update/(:num)']    = 'api/post/update/$1';
$route['api/post/delete/(:num)']    = 'api/post/delete/$1';
$route['api/post/revisions/(:num)'] = 'api/post/revisions/$1';

// ==========================================
// 3. BACKEND API ROUTES (Stage 2 - JEPANG)
// ==========================================
$route['api/media']                 = 'api/media/index';
$route['api/media/upload']          = 'api/media/upload';
$route['api/media/delete/(:num)']   = 'api/media/delete/$1';

// ==========================================
// 4. BACKEND API ROUTES (Stage 3 - JERMAN)
// ==========================================
$route['api/user']                  = 'api/user/index';
$route['api/user/roles']            = 'api/user/roles';
$route['api/user/detail/(:num)']    = 'api/user/detail/$1';
$route['api/user/create']           = 'api/user/create';
$route['api/user/update/(:num)']    = 'api/user/update/$1';
$route['api/user/delete/(:num)']    = 'api/user/delete/$1';

// ==========================================
// 5. AUTH & PUBLIC ROUTES
// ==========================================
$route['login']                     = 'auth_view/login';
$route['register']                  = 'auth_view/register';
$route['home']                      = 'home_view/index';

$route['api/auth/login']            = 'api/auth/login';
$route['api/auth/register']         = 'api/auth/register';
$route['api/auth/logout']           = 'api/auth/logout';
$route['api/public/posts']          = 'api/public_posts/index';

// ==========================================
// 6. STAGE 4 INGGRIS - SETTINGS & META
// ==========================================
$route['api/settings']              = 'api/settings/index';
$route['api/post/meta']             = 'api/post_meta/save';
$route['api/post/meta/(:num)']      = 'api/post_meta/get/$1';