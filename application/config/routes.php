<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller']   = 'home_view/index';
$route['404_override']         = '';
$route['translate_uri_dashes'] = FALSE;

// ==========================================
// FRONTEND ADMIN ROUTES
// ==========================================
$route['admin']                  = 'admin/dashboard';
$route['admin/dashboard']        = 'admin/dashboard';
$route['admin/posts']            = 'admin/posts';
$route['admin/posts/create']     = 'admin/create_post';
$route['admin/posts/edit/(:num)'] = 'admin/edit_post/$1';
$route['admin/categories']       = 'admin/categories';
$route['admin/tags']             = 'admin/tags';
$route['admin/media']            = 'admin/media';
$route['admin/users']            = 'admin/users';
$route['admin/settings']         = 'admin/settings';
$route['admin/comments']         = 'admin/comments';
$route['admin/logs']             = 'admin/logs';
$route['admin/seo']              = 'admin/seo';

// ==========================================
// STAGE 1 - INDONESIA (Posts, Category, Tag)
// ==========================================
$route['api/post']                  = 'api/post/index';
$route['api/post/detail/(:num)']    = 'api/post/detail/$1';
$route['api/post/create']           = 'api/post/create';
$route['api/post/update/(:num)']    = 'api/post/update/$1';
$route['api/post/delete/(:num)']    = 'api/post/delete/$1';
$route['api/post/revisions/(:num)'] = 'api/post/revisions/$1';

$route['api/category']               = 'api/category/index';
$route['api/category/create']        = 'api/category/create';
$route['api/category/update/(:num)'] = 'api/category/update/$1';
$route['api/category/delete/(:num)'] = 'api/category/delete/$1';
$route['api/category/(:num)']        = 'api/category/detail/$1';

$route['api/tag']                   = 'api/tag/index';
$route['api/tag/create']            = 'api/tag/create';
$route['api/tag/update/(:num)']     = 'api/tag/update/$1';
$route['api/tag/delete/(:num)']     = 'api/tag/delete/$1';
$route['api/tag/(:num)']            = 'api/tag/detail/$1';

// ==========================================
// STAGE 2 - JEPANG (Media)
// ==========================================
$route['api/media']                  = 'api/media/index';
$route['api/media/show/(:num)']      = 'api/media/detail/$1';
$route['api/media/(:num)']           = 'api/media/detail/$1';
$route['api/media/upload']           = 'api/media/upload';
$route['api/media/update/(:num)']    = 'api/media/update/$1';
$route['api/media/delete/(:num)']    = 'api/media/delete/$1';

// ==========================================
// STAGE 3 - JERMAN (Auth & User)
// ==========================================
$route['api/auth/login']             = 'api/auth/login';
$route['api/auth/register']          = 'api/auth/register';
$route['api/auth/logout']            = 'api/auth/logout';

$route['api/user']                   = 'api/user/index';
$route['api/user/roles']             = 'api/user/roles';
$route['api/user/detail/(:num)']     = 'api/user/detail/$1';
$route['api/user/create']            = 'api/user/create';
$route['api/user/update/(:num)']     = 'api/user/update/$1';
$route['api/user/delete/(:num)']     = 'api/user/delete/$1';

// ==========================================
// STAGE 4 - INGGRIS (Settings, SEO, Post Meta)
// ==========================================
$route['api/settings']               = 'api/settings/index';
$route['api/seo']                    = 'api/seo/index';
$route['api/post/meta']              = 'api/post_meta/index';
$route['api/post/meta/(:num)']       = 'api/post_meta/detail/$1';

// ==========================================
// STAGE 5 - AMERIKA (Comment, Log)
// ==========================================
$route['api/comments']               = 'api/comment/index';
$route['api/comments/add']           = 'api/comment/add';
$route['api/comments/(:num)']        = 'api/comment/update/$1';
$route['api/comments/delete/(:num)'] = 'api/comment/delete/$1';

$route['api/logs']                   = 'api/log/index';

// ==========================================
// AUTH VIEW & FRONTEND
// ==========================================
$route['login']                      = 'auth_view/login';
$route['register']                   = 'auth_view/register';
$route['home']                       = 'home_view/index';
$route['api/public/posts']           = 'api/public_posts/index';