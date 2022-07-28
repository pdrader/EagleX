<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('SigninController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

//Common
$routes->get('/', 'SigninController::index');
$routes->get('/signin', 'SigninController::index');
$routes->post('/loginAuth', 'SigninController::loginAuth');
$routes->get('/logout', 'SigninController::logout');


//Admin
$routes->get('/admin/payment', 'AdminController::payment',['filter' => 'AuthGuardAdmin']);
$routes->post('/admin/payment-upload', 'AdminController::paymentUpload',['filter' => 'AuthGuardAdmin']);

$routes->get('/admin/statement-list', 'AdminController::statementList',['filter' => 'AuthGuardAdmin']);
$routes->get('/admin/statement/delete/(:num)/(:num)/(:num)', 'AdminController::deleterunreport/$1/$2/$3',['filter' => 'AuthGuardAdmin']);

$routes->get('admin/run/(:num)/(:num)/(:num)', 'AdminController::runreport/$1/$2/$3',['filter' => 'AuthGuardAdmin']);
$routes->get('admin/pro/delete/(:num)', 'AdminController::prodelete/$1',['filter' => 'AuthGuardAdmin']);

$routes->post('/admin/runreport-update', 'AdminController::runreportupdate',['filter' => 'AuthGuardAdmin']);
$routes->get('admin/view/(:num)/(:num)/(:num)', 'AdminController::viewreport/$1/$2/$3',['filter' => 'AuthGuardAdmin']);


//Driver
$routes->get('driver/report/(:num)', 'DriverController::viewreport/$1',['filter' => 'authGuard']);
$routes->get('driver/statement-list', 'DriverController::statementList',['filter' => 'authGuard']);
 
$routes->get('driver/report/send/(:num)/(:num)/(:num)', 'DriverController::sendreport/$1/$2/$3',['filter' => 'authGuard']);



/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
