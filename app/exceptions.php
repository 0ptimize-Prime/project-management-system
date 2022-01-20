<?php

error_reporting(E_ALL);

function handleException($e)
{
    error_log($e);
    http_response_code(500);
    $displayErrors = ini_get('display_errors');
    if (filter_var($displayErrors, FILTER_VALIDATE_BOOLEAN) || $displayErrors == "stdout") {
        echo $e;
    } else {
        ob_end_clean();
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
