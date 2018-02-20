<?php

require_once __DIR__ . '/autoload.php';

if(version_compare(PHP_VERSION, '5.4.0') >= 0) {
    // Command that starts the built-in web server
    $command = sprintf(
        'app/console server:run %s:%d',
        TEST_WEB_SERVER_HOST,
        TEST_WEB_SERVER_PORT
    );

    $proc = new \Symfony\Component\Process\Process($command);
    $proc->start();

    $pid = $proc->getPid();
    if (!$pid) {
        echo "Failed to start web server on " . TEST_WEB_SERVER_HOST . ":" . TEST_WEBSERVER_PORT;
    } else {
        echo sprintf(
            '%s - Web server started on %s:%d',
            date('r'),
            TEST_WEB_SERVER_HOST,
            TEST_WEB_SERVER_PORT
        ) . PHP_EOL;

        sleep(5);

        // Kill the web server when the process ends
        register_shutdown_function(function() use ($pid) {
            echo sprintf('%s - Killing process with ID %d', date('r'), $pid) . PHP_EOL;
            exec('kill ' . $pid);
        });
    }
}
