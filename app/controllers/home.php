<?php

require_once __DIR__ . "/../utils.php";
require_once __DIR__ . "/../models/UserManager.php";
require_once __DIR__ . "/../models/NotificationManager.php";
require_once __DIR__ . "/../models/FileManager.php";

class Home extends Controller
{
    public function dashboard()
    {
        $this->checkAuth("home/dashboard", function () {
            $notificationManager = NotificationManager::getInstance();
            return [
                'user' => $_SESSION["user"],
                'tasks' => [],
                'notifications' => $notificationManager->getNotifications($_SESSION["user"]["username"])
            ];
        });
    }

    public function profile(string $arg = '')
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $this->checkAuth("home/profile", function () {
                return [
                    'user' => $_SESSION["user"]
                ];
            });
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->checkAuth("home/profile", function () {
            });
            $userManager = UserManager::getInstance();
            $fileManager = FileManager::getInstance();
            $is_profile_updated = false;
            switch ($arg) {
                case "name":
                    if (!isset($_POST["name"])) {
                        FlashMessage::create_flash_message(
                            "update-profile",
                            "Invalid request to update display name.",
                            new ErrorFlashMessage()
                        );
                        header("Location: " . BASE_URL . "home/profile");
                        die;
                    }

                    if (!$this->validate_display_name($_POST["name"])) {
                        header("Location: " . BASE_URL . "home/profile");
                        die;
                    }
                    $result = $userManager->updateUser($_SESSION["user"]["username"], $_POST["name"], $_SESSION["user"]["userType"]);
                    if ($result) {
                        FlashMessage::create_flash_message(
                            "update-profile",
                            "Updated display name successfully.",
                            new SuccessFlashMessage()
                        );
                        $is_profile_updated = true;
                    } else {
                        FlashMessage::create_flash_message(
                            "update-profile",
                            "Update failed.",
                            new ErrorFlashMessage()
                        );
                    }
                    break;
                case "password":
                    if (!isset(
                        $_POST["old_password"],
                        $_POST["new_password"]
                    )) {
                        FlashMessage::create_flash_message(
                            "update-profile",
                            "Invalid request to update password.",
                            new ErrorFlashMessage()
                        );
                        header("Location: " . BASE_URL . "home/profile");
                        die;
                    }

                    if ($userManager->checkCredentials($_SESSION["user"]["username"], $_POST["old_password"])) {
                        $userManager->updatePassword($_SESSION["user"]["username"], $_POST["new_password"]);
                        FlashMessage::create_flash_message(
                            "update-profile",
                            "Password changed successfully.",
                            new SuccessFlashMessage()
                        );
                        $is_profile_updated = true;
                    } else {
                        FlashMessage::create_flash_message(
                            "update-profile",
                            "Current password is incorrect.",
                            new ErrorFlashMessage()
                        );
                    }
                    break;
                case "profile-picture":
                    $user = $_SESSION["user"];
                    if (
                        isset($_POST["remove_profile_picture"]) &&
                        $_POST["remove_profile_picture"] !== "" &&
                        !empty($user["profile_picture"])) {
                        $fileManager->deleteFile($user["profile_picture"]);
                        if (file_exists(__DIR__ . '/../../public/uploads/' . $user["profile_picture"])) {
                            unlink(__DIR__ . '/../../public/uploads/' . $user["profile_picture"]);
                        }
                        $result = $userManager->updateUser($user["username"], $user["name"], $user["userType"], null);
                        if ($result) {
                            FlashMessage::create_flash_message(
                                "update-profile",
                                "Successfully removed profile picture.",
                                new SuccessFlashMessage()
                            );
                            $is_profile_updated = true;
                        } else {
                            FlashMessage::create_flash_message(
                                "update-profile",
                                "Something went wrong.",
                                new ErrorFlashMessage()
                            );
                            header("Location: " . BASE_URL . "home/profile");
                            die;
                        }
                    }

                    if ($_FILES["profile_picture"]["tmp_name"]) {
                        $file = $_FILES["profile_picture"]["tmp_name"];
                        $file_loc = $_FILES["profile_picture"]["tmp_name"];
                        $folder = __DIR__ . '/../../public/uploads/';
                        $profile_picture = $fileManager->addFile($user["username"], $file);
                        if ($profile_picture)
                            move_uploaded_file($file_loc, $folder . $profile_picture);
                        else {
                            FlashMessage::create_flash_message(
                                "update-profile",
                                "Something went wrong.",
                                new ErrorFlashMessage()
                            );
                            header("Location: " . BASE_URL . "home/profile");
                            die;
                        }

                        $result = $userManager->updateUser($user["username"], $user["name"], $user["userType"], $profile_picture);
                        if ($result) {
                            FlashMessage::create_flash_message(
                                "update-profile",
                                "Successfully updated profile picture.",
                                new SuccessFlashMessage()
                            );
                            $is_profile_updated = true;
                        } else {
                            FlashMessage::create_flash_message(
                                "update-profile",
                                "Something went wrong.",
                                new ErrorFlashMessage()
                            );
                        }
                    }
                    break;
                default:
                    break;
            }
            header("Location: " . BASE_URL . "home/profile");
            if ($is_profile_updated)
                $_SESSION["user"] = $userManager->getUserDetails($_SESSION["user"]["username"]);
        }
    }

    private function validate_display_name(string $name): bool
    {
        if (!ctype_alpha(str_replace(" ", "", $name))) {
            FlashMessage::create_flash_message(
                "update-profile",
                "The name should be alphabetic.",
                new ErrorFlashMessage()
            );
            return false;
        }
        return true;
    }
}
