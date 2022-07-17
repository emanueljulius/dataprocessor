<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['upload'] = 'welcome/index';
$route['do-upload'] = 'welcome/doUpload';
$route['view'] = 'welcome/view';
$route['report'] = 'welcome/report';
$route['get-country-data'] = 'welcome/getCountryData';
$route['get-first-country-code'] = 'welcome/getFirstCountryCode';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;