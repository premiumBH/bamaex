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
$route['default_controller'] = 'backend/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

$route[''] = 'backend/login';
$route['logout'] = 'user/logout';
$route['setting'] = 'user/settings';
$route['account'] = 'user/account';

$route['client/add-client'] = 'clientmanagement/add-client';
$route['client/order-list'] = 'clientmanagement/order-list';
$route['client/under-clients'] = 'clientmanagement/under-clients';
$route['client/contact'] = 'clientmanagement/contact';
$route['client/prospect'] = 'clientmanagement/prospect';
$route['client/account'] = 'clientmanagement/account';
$route['client/suspended'] = 'clientmanagement/suspended';
$route['client/blacklisted'] = 'clientmanagement/blacklisted';

$route['order/single-consignment'] = 'ordermanagement/single-consignment';
$route['order/multiple-consignment'] = 'ordermanagement/multiple-consignment';
$route['order/pickup-request'] = 'ordermanagement/pickup-request';
$route['order/track-order'] = 'ordermanagement/track-order';
$route['order/order-status'] = 'ordermanagement/order-status';
$route['order/order-list'] = 'ordermanagement/order-list';

$route['address/pickup-address'] = 'addressbook/pickup-address';
$route['address/delivery-address'] = 'addressbook/delivery-address';
$route['address/assign-pickup'] = 'addressbook/assign-pickup';
$route['address/due-deliveries'] = 'addressbook/due-deliveries';

$route['user/add-staff'] = 'usermanagement/add-staff';
$route['user/staff'] = 'usermanagement/staff';
$route['user/sub-user'] = 'usermanagement/sub-user';



$route['pages'] = 'AccessControl/pages';
$route['page/create'] = 'AccessControl/create-page';
$route['user-type'] = 'AccessControl/user-types';

