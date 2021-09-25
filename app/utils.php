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
