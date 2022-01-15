<?php

error_reporting(E_ALL);

function handleException($e)
{
    error_log($e);
    http_response_code(500);
    if (filter_var(ini_get('display_errors'), FILTER_VALIDATE_BOOLEAN)) {
        echo $e;
    } else {
        include __DIR__ . "/views/500.php";
    }
}

set_exception_handler('handleException');

set_error_handler(function ($level, $message, $file = '', $line = 0) {
    throw new ErrorException($message, 0, $level, $file, $line);
});

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null) {
        $e = new ErrorException(
            $error['message'], 0, $error['type'], $error['file'], $error['line']
        );
        handleException($e);
    }
});
