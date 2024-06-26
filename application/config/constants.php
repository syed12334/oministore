<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
define('CUSTOMER_SESSION','SWARNAGOWRI');
define('SP_URL', 'https://api.sparkpost.com/api/v1/transmissions');
// define('SP_KEY', 'c68a4378ef99e56fcfef4eeadac77a4a78828e8d');
define('SP_KEY', '9649668de7edf0e0f6bba10148e10c3585dc89f7');
// define('SP_EMAIL', 'noreply@swarnagowri.com');
define('SP_EMAIL', 'noreply@swarnagowri.in');

define('SP_NAME', 'SWARNAGOWRI');
define('SPR_NAME', 'SWARNAGOWRI');



// define('MERCHANT_KEY','2PBP7IABZ2');
// define('SALT','DAH88E3UWQ');
// define('ENV','test');

define('MERCHANT_KEY','L0PB7AWQSA');
define('SALT','5GV5H2P1IA');
define('ENV','prod');

/******** TEST ATOM CREDENTAILS *********/
 // define('LOGIN','317156');
 // define('Password','Test@123');
 // define('ProductId','NSE');
 // define('TransactionCurrency','INR');
 // define('ReqHashKey','KEY123657234');
 // define('ResHashKey','KEYRESP123657234');
 // define('MODE','uat');
 // define('RequestEncypritonKey','A4476C2062FFA58980DC8F79EB6A799E');
 // define('Salt','A4476C2062FFA58980DC8F79EB6A799E');
 // define('ResponseDecryptionKey','75AEF0FA1B94B3C10D4F5B268F757F11');
 // define('ResponseSalt','75AEF0FA1B94B3C10D4F5B268F757F11');
 // define('PaymentUrl','https://paynetzuat.atomtech.in/ots/aipay/auth');
 // define('isLive',false);

 /******** LIVE ATOM CREDENTAILS *********/
 define('LOGIN','544782');
 define('Password','38e681ae');
 define('ProductId','INNOVATIVE');
 define('TransactionCurrency','INR');
 define('ReqHashKey','45cde6658e12b068d0');
 define('ResHashKey','1f89e4ead35b2904c3');
 define('MODE','uat');
 define('RequestEncypritonKey','D0A6B36F4BF48D5834B89C3434BBD6DC');
 define('Salt','D0A6B36F4BF48D5834B89C3434BBD6DC');
 define('ResponseDecryptionKey','FCA4A5C07CD4164EE26813C151C3640A');
 define('ResponseSalt','FCA4A5C07CD4164EE26813C151C3640A');
 define('PaymentUrl','https://payment1.atomtech.in/ots/aipay/auth');
 define('isLive',false);