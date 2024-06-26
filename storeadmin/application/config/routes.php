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
$route['default_controller'] = 'Home';
$route['ads'] = 'Master/ads';
$route['adsadd'] = 'Master/adsadd';
$route['category'] = 'Master/category';
$route['categoryadd'] = 'Master/categoryadd';
$route['subcategory'] = 'Master/subcategory';
$route['subcategoryadd'] = 'Master/subcategoryadd';
$route['subsubcategory'] = 'Master/subsubcategory';
$route['subsubcategoryadd'] = 'Master/subsubcategoryadd';
$route['brand'] = 'Master/brand';
$route['brandadd'] = 'Master/brandadd';
$route['sizes'] = 'Master/sizes';
$route['sizesadd'] = 'Master/sizeadd';
$route['subscribe'] = 'Master/subscribe';
$route['colors'] = 'Master/colors';
$route['colorsadd'] = 'Master/colorsadd';
$route['states'] = 'Master/states';
$route['statesadd'] = 'Master/stateadd';
$route['cities'] = 'Master/city';
$route['citiesadd'] = 'Master/cityadd';
$route['area'] = 'Master/area';
$route['areaadd'] = 'Master/areaadd';
$route['banners'] = 'Master/sliders';
$route['bannersadd'] = 'Master/slideradd';
$route['orders'] = 'Master/orders';
$route['referralpoints'] = 'Master/referralpoints';
$route['guestorders'] = 'Master/guestorders';
$route['reviews'] = 'Master/reviews';
$route['coupons'] = 'Master/coupons';
$route['couponsadd'] = 'Master/couponsadd';
$route['franchises'] = 'Master/franchises';
$route['franchiseadd'] = 'Master/franchisesadd';
$route['editfranchise/(:any)'] = 'Master/editfranchise/$1';
$route['products'] = 'Master/products';
$route['productsadd'] = 'Master/productsadd';
$route['inventory'] = 'Master/inventory';
$route['faq'] = 'Master/faq';
$route['faqadd'] = 'Master/faqadd';
$route['qrcode'] = 'Master/qrcode';
$route['testimonials'] = 'Master/testimonials';
$route['testimonialsadd'] = 'Master/testimonialsadd';
$route['orderdetails/(:any)'] = 'Master/orderdetails/$1';
$route['guestorderdetails/(:any)'] = 'Master/guestorderdetails/$1';
$route['aboutus'] = 'Master/about';
$route['privacy'] = 'Master/privacy';
$route['pincodes'] = 'Master/pincodes';
$route['pincodeadd'] = 'Master/pincodeadd';
$route['pinnotification'] = 'Master/pinnotification';
$route['terms'] = 'Master/terms';
$route['brochure'] = 'Master/brochure';
$route['cancellationpolicy'] = 'Master/cancellationpolicy';
$route['refundpolicy'] = 'Master/returnpolicy';
$route['contactus'] = 'Master/contactus';
$route['walletsetting'] = 'Master/walletsetting';
$route['newsletter'] = 'Master/newsletter';
$route['contest'] = 'Master/contest';
$route['sociallinks'] = 'Master/sociallinks';
$route['shippingpolicy'] = 'Master/shippingpolicy';
$route['topsellingproducts'] = 'Master/topsellingproducts';
$route['users'] = 'Master/users';
$route['orders'] = 'Master/orders';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
