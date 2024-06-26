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
$route['cart'] = 'Cart/cartDetails';
$route['register'] = 'Login/register';
$route['login'] = 'Login/register';
$route['otp'] = 'Login/otp';
$route['addtocart'] = 'Cart/addtocart';
$route['wallet'] = 'Checkout/wallet';
$route['removeCartitem'] = 'Cart/removeCartitem';
$route['updateCart'] = 'Cart/updateCart';
$route['couponList'] = 'Cart/couponList';
$route['registerSave'] = 'Login/registerSave';
$route['verifyotp'] = 'Login/verifyotp';
$route['loginsave'] = 'Login/loginsave';
$route['specialoffers'] = 'Home/specialoffers';
$route['deliverypolicy'] = 'Home/deliverypolicy';
$route['disclaimer'] = 'Home/disclaimer';
$route['rasapaka'] = 'Home/rasapaka';
$route['dealoftheday'] = 'Home/dealoftheday';
$route['orderpayment'] = 'Checkout/paymentPayload';
$route['ordereasebuzz'] = 'Checkout/ordereasebuzz';
$route['orderguestpayment'] = 'Guest/paymentPayload';
$route['about'] = 'Home/about';
$route['webhooks'] = 'Home/webhooks';
$route['terms'] = 'Home/terms';
$route['privacy'] = 'Home/privacy';
$route['contact'] = 'Home/contact'; 
$route['v1/register'] = 'v1/App/register';
$route['v1/verifyotp'] = 'v1/App/verifyotp';
$route['v1/login'] = 'v1/App/login';
$route['v1/homepage'] = 'v1/App/homepage';
$route['v1/category'] = 'v1/App/category_list';
$route['v1/productlist'] = 'v1/App/productlist';
$route['v1/productdetails'] = 'v1/App/productdetails';
$route['v1/writereviews'] = 'v1/App/writereviews';
$route['v1/wishlist'] = 'v1/App/wishlist';
$route['v1/removeWishlist'] = 'v1/App/removeWishlist';
$route['v1/state'] = 'v1/App/state';
$route['v1/city'] = 'v1/App/city';
$route['v1/area'] = 'v1/App/area';
$route['v1/orders'] = 'v1/App/orders';
$route['v1/wallet'] = 'v1/App/wallet';
$route['v1/orderdetails'] = 'v1/App/orderdetails';
$route['v1/cancelorder'] = 'v1/App/cancelorder';
$route['v1/myaccount'] = 'v1/App/myaccount';
$route['v1/updateprofile'] = 'v1/App/updateprofile';
$route['v1/searchSuggestion'] = 'v1/App/searchSuggestion';
$route['v1/searchresults'] = 'v1/App/searchresults';
$route['v1/sort'] = 'v1/App/sort';
$route['v1/pincodecheck'] = 'v1/App/pincodecheck';
$route['v1/coupons'] = 'v1/App/couponList';
$route['v1/confirmOrders'] = 'v1/App/confirmOrders';
$route['v1/easebuzzResponse'] = 'v1/App/easebuzzResponse';
$route['v1/addwishlist'] = 'v1/App/addWishlist';
$route['v1/resendotp'] = 'v1/App/resendotp';
$route['v1/search'] = 'v1/App/search';
$route['v1/filters'] = 'v1/App/filters';
$route['v1/success'] = 'v1/App/success';
$route['v1/failures'] = 'v1/App/failures';
$route['v1/qrcode'] = 'v1/App/qrcode';
$route['v1/couponcode'] = 'v1/App/couponcode';
$route['v1/guestorder'] = 'v1/App/guestorder';
$route['v1/atomResponse'] = 'v1/App/atomResponse';
$route['v1/guesteasebuzzResponse'] = 'v1/App/guesteasebuzzResponse';
/******* Version 1 ************/
$route['delivery/v1/deliverylogin'] = 'v1/App/deliverylogin';
$route['delivery/v1/deliveryverifyotp'] = 'v1/App/deliveryverifyotp';
$route['delivery/v1/deliverytodayorders'] = 'v1/App/deliverytodayorders';
$route['delivery/v1/deliverypendingorders'] = 'v1/App/deliverypendingorders';
$route['delivery/v1/deliverychangeorderstatus'] = 'v1/App/deliverychangeorderstatus';
$route['delivery/v1/deliveryorderdetails'] = 'v1/App/deliveryOrderdetails';
$route['delivery/v1/profile'] = 'v1/App/deliveryprofile';
$route['delivery/v1/deliveredorder'] = 'v1/App/deliveredorder';
$route['delivery/v1/orderscount'] = 'v1/App/orderscount';
$route['delivery/v1/spotredemption'] = 'v1/App/spotredemption';
$route['delivery/v1/notification'] = 'v1/App/franchisenotificationlogs';
/******* Version 2 ************/
$route['delivery/v2/deliverylogin'] = 'v2/App/deliverylogin';
$route['delivery/v2/deliveryverifyotp'] = 'v2/App/deliveryverifyotp';
$route['delivery/v2/deliverytodayorders'] = 'v2/App/deliverytodayorders';
$route['delivery/v2/deliverypendingorders'] = 'v2/App/deliverypendingorders';
$route['delivery/v2/deliverychangeorderstatus'] = 'v2/App/deliverychangeorderstatus';
$route['delivery/v2/deliveryorderdetails'] = 'v2/App/deliveryOrderdetails';
$route['delivery/v2/profile'] = 'v2/App/deliveryprofile';
$route['delivery/v2/deliveredorder'] = 'v2/App/deliveredorder';
$route['delivery/v2/orderscount'] = 'v2/App/orderscount';
$route['delivery/v2/spotredemption'] = 'v2/App/spotredemption';
$route['delivery/v2/notification'] = 'v2/App/franchisenotificationlogs';
/*********** Offers ************/
$route['offers/v1/pincodes'] = 'v1/App/specialoffers';
$route['offers/v1/getproductlist'] = 'v1/App/getproductlist';
$route['offers/v1/getspincodes'] = 'v1/App/getspincodes';
$route['offers/v1/specialproductdetails'] = 'v1/App/specialproductdetails';
// End of api 
$route['resendOtp'] = 'Login/resendOtp';
$route['searchresults'] = 'Home/searchResults';
$route['paymentResponse'] = 'Checkout/paymentResponse';
$route['products/(:any)'] = 'Products/index/$1';
$route['order-view/(:any)/(:any)'] = 'My_account/order_view/$1/$2';
$route['my-account'] = 'My_account/index';
$route['rasapakaproview/(:any)'] = 'Products/rasapakaproview/$1';
// $route['(:any)/(:any)'] = 'Products/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
