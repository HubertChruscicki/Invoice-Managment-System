<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/CategoryRepository.php';
//TODO ZROBICP OWIAZANIE TABELI COMPANY Z CATEGORIES
class CategoryController extends AppController
{

    public function addCategory()
    { //TODO NAPRAWIC KOMUNIKATY

        if (!$this->isPost()) {
            return $this->render('main');
        }

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
        $category = $categoryRepository->getCategory($category_name);

        if ($category) {
            $this->render('main', ['messages' => ['Category with such name exist!']]);
            return;
        }
        $categoryRepository->addCategory($category_name, $vat_value);

        return $this->render('main', ['messages' => ['Category successfully added!']]);
    }


    public function getCategories()
    {

        if (!$this->isGet()) {
            return $this->render('main');
        }


        $limit = $_GET['limit'] ?? 10;
        $offset = $_GET['offset'] ?? 0;
        $namePrefix = $_GET['namePrefix'] ?? null;

        $categoryRepository = CategoryRepository::getInstance();
        $categories = $categoryRepository->getCategoires((int)$limit, (int)$offset, $namePrefix);

        if (empty($categories)) {
            echo json_encode(["message" => "success", "categories" => []]); //TODO ZMIENIC FAIL OBSLUGE
//            echo json_encode(["message" => "fail"]);
        } else {
            echo json_encode(["message" => "success", "categories" => $categories]);
        }

    }

    public function howManyCategories()
    {

        if (!$this->isGet()) {
            return $this->render('main');
        }

        $namePrefix = $_GET['namePrefix'] ?? null;

        $categoryRepository = CategoryRepository::getInstance();
        $count = $categoryRepository->howManyCategories($namePrefix);

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

        $categoryName = json_decode(file_get_contents('php://input'), true)['categoryName'] ?? null;


        if ($categoryName) {
            $categoryRepository = CategoryRepository::getInstance();
            $deleteSuccess = $categoryRepository->deleteCategory($categoryName);
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