<?php

use App\Kernel;

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if (!getenv('MB_EXPOSE_DEV') && (isset($_SERVER['HTTP_CLIENT_IP'])
   || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
   || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))
)) {
   header('HTTP/1.0 403 Forbidden');
   exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}


require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel('dev', (bool) $context['APP_DEBUG']);
};
