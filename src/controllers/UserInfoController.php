<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';
class UserInfoController extends AppController
{
    public function findUserInfo()
    {
        if (!isset($_SESSION['id'])) {
            echo json_encode(["message" => "ID session doesnt exist"]);
            return;
        }
        $id = $_SESSION['id'];
        $userRepository = UserRepository::getInstance();
        $user = $userRepository->findUserInfo($id);

        if(empty($user)){
            echo json_encode(["message" => "fail"]);
        }
        else {
            echo json_encode(["message" => "success", "user" => $user]);
        }
        // Dla debugowania:
//        error_log("Returned data: " . json_encode(["message" => "success", "user" => $user]));  // Sprawdzamy, co dokładnie zwrócono
    }
}