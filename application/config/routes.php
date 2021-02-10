<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['sitemap_index\.xml'] = 'sitemap/index';
$route['sitemap_flights\.xml'] = 'sitemap/flights';
$route['destinations/(:any)'] = "destinations/route/$1";
$route['about_us'] = "home/about_us";
$route['contact_us'] = "home/contact_us";

$route['packages/(:any)'] = "packages/list/$1";
$route['packages/(:any)/details/(:num)'] = "packages/details/$1/$2";

$route['admin/login'] = "admin/auth/login";
$route['admin/logout'] = "admin/auth/logout";
$route['admin/change_password'] = "admin/auth/change_password";
$route['admin/products/edit_product/(:num)/variation/(:num)'] = "admin/products/edit_product_variation/$1/$2";

$route['member/login'] = "member/auth/login";
$route['member/logout'] = "member/auth/logout";
$route['member/change_password'] = "member/auth/change_password";
$route['member/edit_member/(:num)'] = "member/auth/edit_member/$1";

$route['user/login'] = "user/auth/login";
$route['user/logout'] = "user/auth/logout";
$route['user/change_password'] = "user/auth/change_password";
$route['user/edit_user/(:num)'] = "user/auth/edit_user/$1";

$route['vendor/login'] = "vendor/auth/login";
$route['vendor/logout'] = "vendor/auth/logout";
$route['vendor/change_password'] = "vendor/auth/change_password";
$route['vendor/edit_user/(:num)'] = "vendor/auth/edit_user/$1";
$route['vendor/products/edit_product/(:num)/variation/(:num)'] = "vendor/products/edit_product_variation/$1/$2";

$route['shipper/login'] = "shipper/auth/login";
$route['shipper/logout'] = "shipper/auth/logout";
$route['shipper/change_password'] = "shipper/auth/change_password";
$route['shipper/edit_user/(:num)'] = "shipper/auth/edit_user/$1";

$route['admin/packages/(:any)'] = "admin/packages/package_type/$1";
$route['admin/packages/(:any)/create'] = "admin/packages/create/$1";
$route['admin/packages/(:any)/(:num)/edit'] = 'admin/packages/edit/$1/$2';
$route['admin/packages/(:any)/(:num)'] = 'admin/packages/details/$1/$2';
$route['admin/packages/(:any)/(:num)/details'] = 'admin/packages/details/$1/$2';
$route['admin/packages/(:any)/(:num)/description'] = 'admin/packages/description/$1/$2';
$route['admin/packages/(:any)/(:num)/photo_gallery'] = 'admin/packages/photo_gallery/$1/$2';
$route['admin/packages/(:any)/(:num)/features'] = 'admin/packages/features/$1/$2';
$route['admin/packages/(:any)/(:num)/amenities'] = 'admin/packages/amenities/$1/$2';
$route['admin/packages/(:any)/(:num)/itinerary'] = 'admin/packages/itinerary/$1/$2';
$route['admin/packages/(:any)/(:num)/location'] = 'admin/packages/location/$1/$2';
$route['admin/packages/(:any)/(:num)/faq'] = 'admin/packages/faq/$1/$2';
$route['admin/packages/(:any)/(:num)/terms_conditions '] = 'admin/packages/terms_conditions/$1/$2';