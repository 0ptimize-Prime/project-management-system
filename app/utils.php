<?php

function includeWithVariables($filePath, $variables = [])
{
    if (file_exists($filePath)) {
        extract($variables);
        include $filePath;
    }
}


function convertDateToTimestamp(string $date): int
{
    $dateObj = DateTime::createFromFormat("Y-m-d", $date);
    return $dateObj->getTimestamp();
}

const FLASH = "FLASH";
const FLASH_SUCCESS = "success";
const FLASH_ERROR = "danger";

function create_flash_message(string $name, string $message, string $type) : void {
    if (isset($_SESSION[FLASH][$name])) {
        unset($_SESSION[FLASH][$name]);
    }

    $_SESSION[FLASH][$name] = [
        "message" => $message,
        "type" => $type
    ];
}

function display_flash_message(string $name) : void {
    if (!isset($_SESSION[FLASH][$name])) {
        return;
    }

    $message_arr = $_SESSION[FLASH][$name];
    unset($_SESSION[FLASH][$name]);
    $output = "<div class=\"alert alert-%s\" role=\"alert\">%s</div>";
    echo sprintf($output, $message_arr["type"], $message_arr["message"]);
}
