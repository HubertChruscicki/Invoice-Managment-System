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
    }

    public function getUsers()
    {
        if(!$this->isGet()) {
            return $this->render('main');
        }

        if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['id'])) {
            $this->render('main', ['message' => 'Session problem!']);
        }

        $user_id = $_SESSION['id'];

        $limit = $_GET['limit'] ?? 10;
        $offset = $_GET['offset'] ?? 0;
        $searchPrefix = $_GET['searchPrefix'] ?? null;
        $searchByEmailFlag = filter_var($_GET['searchByEmailFlag'] ?? 'false', FILTER_VALIDATE_BOOLEAN);

        $userRepository = UserRepository::getInstance();
        $users = $userRepository->getUsers((int)$user_id, (int)$limit, (int)$offset, $searchPrefix, $searchByEmailFlag);

        if(empty($users)){
            echo json_encode(["message" => "fail"]);
            return;

        } else {
            echo json_encode(["message" => "success", "users" => $users]);
        }
    }

    public function howManyUsers()
    {
        if (!$this->isGet()) {
            return $this->render('main');
        }

        if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['id'])) {
            $this->render('main', ['message' => 'Session problem!']);
            return;
        }
        $user_id = $_SESSION['id'];
        $searchPrefix = $_GET['searchPrefix'] ?? null;

        $userRepository = UserRepository::getInstance();
        $count = $userRepository->howManyUsers((int)$user_id, $searchPrefix);

        if (is_int($count)) {
            echo json_encode(["message" => "success", "count" => $count]);
        } else {
            echo json_encode(["message" => "fail"]);
        }
    }


    public function deleteUser()
    {
        if (!$this->isPost()) {
            return $this->render('main');
        }
        if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['id'])) {
            $this->render('main', ['message' => 'Session problem!']);
            return;
        }
        $user_id = $_SESSION['id'];
        $user_to_delete_id = json_decode(file_get_contents('php://input'), true)['user_id'] ?? null;

        if($user_to_delete_id === $user_id) {
            $this->render('main', ['message' => 'Cannot delete yourself!']);
            return;
        }

        if($user_id) {
            $userRepository = UserRepository::getInstance();
            $deleteSuccess = $userRepository->deleteUser((int)$user_id, (int)$user_to_delete_id);
            if($deleteSuccess) {
                echo json_encode(["message" => "success"]);
            }
            else {
                echo json_encode(["message" => "fail", "error" => "cant delete user"]);
            }
        }
        else {
            echo json_encode(["message" => "fail", "error" => "User deleting problem"]);
        }

    }

}