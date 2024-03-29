<?php

function includeWithVariables($filePath, $variables = [])
{
    if (file_exists($filePath)) {
        extract($variables);
        include $filePath;
    }
}

function showNavbar(array $data, bool $isLoggedIn = true)
{
    includeWithVariables(
        __DIR__ . "/views/templates/navbar.php",
        array("isLoggedIn" => $isLoggedIn, "user" => $data["user"] ?? null, "notifications" => $data["notifications"] ?? [])
    );
}

function showSidebar(array $data)
{
    includeWithVariables(__DIR__ . "/views/templates/sidebar.php", array("userType" => $data["user"]["userType"]));
}

function statusBadgeColor(string $status): string
{
    return match ($status) {
        "ASSIGNED" => "secondary",
        "PENDING" => "info",
        "COMPLETE" => "success",
        default => "primary",
    };
}

function safeJsonEncode($obj): string
{
    $encoded = json_encode($obj, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS);
    return $encoded ?: 'null';
}

function convertDateToTimestamp(string $date): int
{
    $dateObj = DateTime::createFromFormat("Y-m-d", $date);
    return $dateObj->getTimestamp();
}

const FLASH = "FLASH";

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
        $output = "<div class=\"alert alert-success flash-message mx-4\" role=\"alert\">%s</div>";
        echo sprintf($output, htmlspecialchars($message));
    }
}

class ErrorFlashMessage implements FlashMessageType
{
    public function display(string $message): void
    {
        $output = "<div class=\"alert alert-danger flash-message mx-4\" role=\"alert\">%s</div>";
        echo sprintf($output, htmlspecialchars($message));
    }
}
