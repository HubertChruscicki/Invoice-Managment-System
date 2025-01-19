<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/CategoryRepository.php';

class CategoryController extends AppController
{

    public function addCategory()
    {

        if (!$this->isPost()) {
            return $this->render('main');
        }
        $user_id = $_SESSION['id'];

        $category_name = $_POST['categoryName'];
        $vat_value = $_POST['vatValue'];

        if ($category_name === '' || $vat_value === '') {
            $this->render('main', ['messages' => ['You have to fill all fields!']]);
            return;
        }
        if ((int)$vat_value < 0) {
            $this->render('main', ['messages' => ['Vat cannot be negative']]);
            return;
        }
        if ((float)$vat_value === 0.0) {
            $this->render('main', ['messages' => ['Vat cannot be 0']]);
            return;
        }

        $categoryRepository = CategoryRepository::getInstance();
        $category = $categoryRepository->getCategory((int)$user_id, $category_name);

        if ($category) {
            $this->render('main', ['messages' => ['Category with such name exist!']]);
            return;
        }
        $categoryRepository->addCategory((int)$user_id, $category_name, $vat_value);

        $url = '/';
        header("Location: $url");
        exit;
    }


    public function getCategories()
    {
        if (!$this->isGet()) {
            return $this->render('main');
        }

        if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['id'])) {
            $this->render('main', ['message' => 'Session problem!']);
            return;
        }

        $user_id = $_SESSION['id'];

        $limit = $_GET['limit'] ?? 10;
        $offset = $_GET['offset'] ?? 0;
        $namePrefix = $_GET['namePrefix'] ?? null;

        $categoryRepository = CategoryRepository::getInstance();
        $categories = $categoryRepository->getCategories((int)$user_id, (int)$limit, (int)$offset, $namePrefix);

        if (empty($categories)) {
            echo json_encode(["message" => "success", "categories" => []]);
        } else {
            echo json_encode(["message" => "success", "categories" => $categories]);
        }

    }

    public function howManyCategories()
    {

        if (!$this->isGet()) {
            return $this->render('main');

        }
        if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['id'])) {
            $this->render('main', ['message' => 'Session problem!']);
            return;
        }
        $user_id = $_SESSION['id'];

        $namePrefix = $_GET['namePrefix'] ?? null;

        $categoryRepository = CategoryRepository::getInstance();
        $count = $categoryRepository->howManyCategories((int)$user_id, $namePrefix);

        if (is_int($count)) {
            echo json_encode(["message" => "success", "count" => $count]);
        } else {
            echo json_encode(["message" => "fail"]);
        }
    }

    public function deleteCategory()
    {
        if (!$this->isPost()) {
            return $this->render('main');
        }
        if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['id'])) {
            $this->render('main', ['message' => 'Session problem!']);
            return;
        }
        $user_id = $_SESSION['id'];

        $categoryName = json_decode(file_get_contents('php://input'), true)['categoryName'] ?? null;


        if ($categoryName) {
            $categoryRepository = CategoryRepository::getInstance();
            $deleteSuccess = $categoryRepository->deleteCategory((int)$user_id, $categoryName);
            if ($deleteSuccess) {
                echo json_encode(["message" => "success"]);
            } else {
                echo json_encode(["message" => "fail", "error" => "cant delete category"]);
            }
        } else {
            echo json_encode(["message" => "fail", "error" => "Category name problem"]);
        }

    }
}