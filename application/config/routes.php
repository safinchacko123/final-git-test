<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
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
  |	http://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There area two reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router what URI segments to use if those provided
  | in the URL cannot be matched to a valid route.
  |
 */


//$route['default_controller'] = "index/home";
/*For front-end by Lynn */
$route['default_controller'] = "index";
$route['location/(:any)'] = "index/location/$1";
$route['category'] = "index/category";

$route['seller'] = "index/seller";
$route['seller/(:any)'] = "index/seller/$1";
$route['area'] = "index/area";
$route['area/(:any)'] = "index/area/$1";
$route['detail'] = "index/detail";
$route['detail/(:any)'] = "index/detail/$1";
$route['checkout'] = "index/checkout";
$route['order'] = "index/order";
$route['login'] = "index/login";
$route['login/(:any)'] = "index/login/$i";
$route['register'] = "index/register";
$route['logout/(:any)'] = "index/logout/$i";
$route['logout'] = "index/logout";
$route['forgot'] = "index/forgot";
$route['resetPassword/(:any)'] = "index/resetPassword/$i";
$route['resetPassword'] = "index/resetPassword";
$route['myAccount'] = "index/myAccount";
$route['search/(:any)'] = "search/index/$i";
$route['invoice'] = "index/invoice";
$route['invoice/(:any)'] = "index/invoice/$1";

$route['aboutus'] = "index/aboutus";
$route['tc'] = "index/tc";
$route['policy'] = "index/policy";
$route['returnpolicy'] = "index/returnpolicy";
$route['faq'] = "index/faq";
$route['contactus'] = "index/contactus";
$route['help'] = "index/help";
$route['press'] = "index/press";
$route['privacy'] = "index/privacy";
$route['term'] = 'index/term';
$route['how_it_work'] = 'index/how_it_work';



/*For front-end by Lynn */

//this for the admininstration console
$route['admin'] = 'admin/dashboard';
$route['admin/media/(:any)'] = 'admin/media/$1';

$route['admin'] = 'admin/dashboard';
$route['admin/media/(:any)'] = 'admin/media/$1';

$route['products'] = 'admin/products';
$route['products/form'] = 'admin/products/form';
$route['products/form/(:any)'] = 'admin/products/form/$1';
$route['products/index/(:any)'] = 'admin/products/index/$1';
$route['products/review/(:any)'] = 'admin/products/review/$1';
$route['products/delete/(:any)'] = 'admin/products/delete/$1';

$route['orders'] = 'admin/orders';
$route['orders/index/(:any)'] = 'admin/orders/index/$1';