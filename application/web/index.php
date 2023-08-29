<?php

use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/config/bootstrap.php';

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}

$kernelClass = $_ENV['KERNEL_CLASS'];
if (!$kernelClass) {
    throw new Exception("KERNEL_CLASS environment variable not found. Please add the FQCN of your Kernel class to your .env file");
}
if (!class_exists($kernelClass)) {
    throw new Exception("KERNEL_CLASS environment variable does not refer to an existing class. Check the spelling and make sure composer autoload is setup correctly.");
}

$kernel = new $kernelClass($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
