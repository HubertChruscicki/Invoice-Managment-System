<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/CategoryRepository.php';
class CategoryController extends AppController
{

    public function addCategory(){ //TODO NAPRAWIC KOMUNIKATY

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
        $category=$categoryRepository->getCategory($category_name);

        if($category){
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
        $categories=$categoryRepository->getCategoires((int)$limit, (int)$offset, $namePrefix);

        if(empty($categories)){
            echo json_encode(["message" => "fail"]);
        }
        else {
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

}