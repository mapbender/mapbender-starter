<?php

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

$kernelClass = $_ENV['KERNEL_CLASS'];
if (!$kernelClass) {
    throw new Exception("KERNEL_CLASS environment variable not found. Please add the FQCN of your Kernel class to your .env file");
}
if (!class_exists($kernelClass)) {
    throw new Exception("KERNEL_CLASS environment variable does not refer to an existing class. Check the spelling and make sure composer autoload is setup correctly.");
}

return function (array $context) use ($kernelClass) {
    return new $kernelClass($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
};
