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
$route['default_controller'] = 'auth';
$route['login'] = 'rest/index_post';
$route['dashboard_anggota/(:num)'] = 'anggota/show/detail/$1';
$route['laporan/show/detail/laporan_mingguan/(:any)/(:num)'] = 'laporan/show/detail/laporan_mingguan/$1/$2';
$route['laporan/show/detail/laporan_mingguan/(:any)'] = 'laporan/show/detail/laporan_mingguan/$1/0';
$route['laporan/show/list/my_data(:num)'] = 'laporan/show/list/my_data/$2';
$route['laporan/show/list/my_data'] = 'laporan/show/list/my_data/0';

//Yang ada New nya
$route['laporan/show/list/my(:num)'] = 'laporan/show/list/my/$2';
$route['laporan/show/list/my'] = 'laporan/show/list/my/0';
$route['laporan/show/detail/laporan/(:any)/(:num)'] = 'laporan/show/detail/laporan/$1/$2';
$route['laporan/show/detail/laporan/(:any)'] = 'laporan/show/detail/laporan/$1/0';
$route['laporan/show/list/nilai(:num)'] = 'laporan/show/list/nilai/$2';
$route['laporan/show/list/nilai'] = 'laporan/show/list/nilai/0';

//Setelah Tambah Pencapaian dan Nilai B / Atasan yang ada Rev1 nya
$route['laporan/show/list/mydata(:num)'] = 'laporan/show/list/mydata/$2';
$route['laporan/show/list/mydata'] = 'laporan/show/list/mydata/0';
$route['laporan/show/detail/laporan_detail/(:any)/(:num)'] = 'laporan/show/detail/laporan_detail/$1/$2';
$route['laporan/show/detail/laporan_detail/(:any)'] = 'laporan/show/detail/laporan_detail/$1/0';
$route['laporan/show/detail/my_detail_nilai/(:any)/(:num)'] = 'laporan/show/detail/my_detail_nilai/$1/$2';
$route['laporan/show/detail/my_detail_nilai/(:any)'] = 'laporan/show/detail/my_detail_nilai/$1/0';


//Rev2
$route['laporan/show/list/rev2(:num)'] = 'laporan/show/list/rev2/$2';
$route['laporan/show/list/rev2'] = 'laporan/show/list/rev2/0';
$route['laporan/show/detail/laporan_detail_rev2/(:any)/(:num)'] = 'laporan/show/detail/laporan_detail_rev2/$1/$2';
$route['laporan/show/detail/laporan_detail_rev2/(:any)'] = 'laporan/show/detail/laporan_detail_rev2/$1/0';
$route['laporan/show/detail/my_detail_nilai_rev2/(:any)/(:num)'] = 'laporan/show/detail/my_detail_nilai_rev2/$1/$2';
$route['laporan/show/detail/my_detail_nilai_rev2/(:any)'] = 'laporan/show/detail/my_detail_nilai_rev2/$1/0';



// $route['login_admin'] = 'login/others/Administrator';
// $route['login_kampus'] = 'login/others/Kampus';
// $route['grup/guru_mode'] = 'grup/show/guru_mode';
// $route['verif']         = 'update/verifikasi';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// WFA management routes
$route['absensi_config/manage_pengguna_wfa'] = 'absensi_config/manage_pengguna_wfa';
$route['absensi_config/update_pengguna_wfa'] = 'absensi_config/update_pengguna_wfa';
$route['absensi_config/update_wfa_config'] = 'absensi_config/update_wfa_config';

// Backward compatibility for older mixed-case endpoint links
$route['absensi_config/managePenggunaWFA'] = 'absensi_config/manage_pengguna_wfa';
$route['absensi_config/updatePenggunaWFA'] = 'absensi_config/update_pengguna_wfa';
$route['absensi_config/updateWFAConfig'] = 'absensi_config/update_wfa_config';

// Buku Tamu aliases (including typo-compatible endpoint)
$route['bukutamu'] = 'BukuTamu/index';
$route['bukutamu/event/(:any)'] = 'BukuTamu/index/$1';
$route['BukuTamu/event/(:any)'] = 'BukuTamu/index/$1';
$route['dataBukuTamu'] = 'BukuTamu/dataBukutamu';
$route['dataBukutamu'] = 'BukuTamu/dataBukutamu';
$route['BukuTamu/dataBukuTamu'] = 'BukuTamu/dataBukutamu';
$route['dataBukuttamu'] = 'BukuTamu/dataBukutamu';
$route['BukuTamu/dataBukuttamu'] = 'BukuTamu/dataBukutamu';

// Preventif Maintenance routes (CI3 naming convention: Preventif_maintenance with underscore)
$route['preventif-maintenance'] = 'Preventif_maintenance/index';
$route['preventif-maintenance/add'] = 'Preventif_maintenance/add';
$route['preventif-maintenance/edit/(:any)'] = 'Preventif_maintenance/edit/$1';
$route['preventif-maintenance/detail/(:any)'] = 'Preventif_maintenance/detail/$1';
$route['preventif-maintenance/save'] = 'Preventif_maintenance/save';
$route['preventif-maintenance/addUpdate'] = 'Preventif_maintenance/addUpdate';
$route['preventif-maintenance/updateStatus'] = 'Preventif_maintenance/updateStatus';
$route['preventif-maintenance/delete'] = 'Preventif_maintenance/delete';
$route['preventif-maintenance/export'] = 'Preventif_maintenance/export';
$route['preventif-maintenance/pagination/(:any)'] = 'Preventif_maintenance/pagination/$1';
$route['preventif-maintenance/pagination'] = 'Preventif_maintenance/pagination';
$route['preventif-maintenance/pagination_dt'] = 'Preventif_maintenance/pagination_dt';
$route['preventif-maintenance/show/(:any)'] = 'Preventif_maintenance/show/$1';
$route['preventif-maintenance/show/(:any)/(:any)'] = 'Preventif_maintenance/show/$1/$2';

// WhatsApp Convia Webhook & Session Reminder Routes
$route['webhook_convia'] = 'Webhook_convia/index';
$route['webhook_convia/(:any)'] = 'Webhook_convia/$1';
$route['reminder_wa'] = 'Reminder_wa/index';
$route['reminder_wa/(:any)'] = 'Reminder_wa/$1';

