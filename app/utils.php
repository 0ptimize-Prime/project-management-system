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

class FlashMessage
{
    private FlashMessageType $type;
    private string $name, $msg;

    private function __construct(string $name, string $msg, FlashMessageType $type)
    {
        $this->type = $type;
        $this->name = $name;
        $this->msg = $msg;
    }

    public static function create_flash_message(string $name, string $message, FlashMessageType $type): void
    {
        $new_msg = new FlashMessage($name, $message, $type);
        if (isset($_SESSION[FLASH][$name])) {
            unset($_SESSION[FLASH][$name]);
        }

        $_SESSION[FLASH][$name] = serialize($new_msg);
    }

    public function get_message(): string
    {
        return $this->msg;
    }

    public function get_flash_message_type(): FlashMessageType
    {
        return $this->type;
    }

    public static function display_flash_message(string $name): void
    {
        if (isset($_SESSION[FLASH][$name])) {
            $msg_obj = unserialize($_SESSION[FLASH][$name]);
            $msg_obj->get_flash_message_type()->display($msg_obj->get_message());
            unset($_SESSION[FLASH][$name]);
        }
    }
}

interface FlashMessageType
{
    public function display(string $message);
}

class SuccessFlashMessage implements FlashMessageType
{
    public function display(string $message): void
    {
        $output = "<div class=\"alert alert-success flash-message\" style='top: 55px' role=\"alert\">%s</div>";
        echo sprintf($output, htmlspecialchars($message));
    }
}

class ErrorFlashMessage implements FlashMessageType
{
    public function display(string $message): void
    {
        $output = "<div class=\"alert alert-danger flash-message\" style='top: 55px' role=\"alert\">%s</div>";
        echo sprintf($output, htmlspecialchars($message));
    }
}