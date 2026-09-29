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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['default_controller'] = 'VCall';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['dashboard'] = 'VCall/dashboard';
$route['dashboard1'] = 'VCall/dashboard1';

// Masters
$route['teams'] = 'VCall/team';

// Analytics
$route['total-calls'] = 'VCall/tcalls';
$route['outbound-auto'] = 'VCall/outbound_auto';
$route['outbound-manual'] = 'VCall/outbound_manual';
$route['outbound-calls'] = 'VCall/outbound';
$route['inbound-calls'] = 'VCall/inbound';

// Reports
$route['agent-call-report'] = 'Rpt/agent_call_report';
$route['never-logged-in-report'] = 'Rpt/never_logged_in_report';
$route['team-call-report'] = 'Rpt/team_call_report';
$route['agent-productivity'] = 'VCall/aprod';
$route['daily-performance-report'] = 'Rpt/daily_performance_report';
$route['detailed-dpr'] = 'Rpt/detail_dpr';

// Other Pages
$route['unique-calls'] = 'VCall/unique';
$route['connected-calls'] = 'VCall/con_calls';
$route['rejected-calls'] = 'VCall/rej_calls';
$route['missed-calls'] = 'VCall/msd_calls';
$route['never-attended-calls'] = 'VCall/';
$route['not-picked-by-client'] = 'VCall/not_pick_calls';
