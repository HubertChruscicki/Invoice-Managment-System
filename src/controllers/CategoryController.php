<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/CategoryRepository.php';
class CategoryController extends AppController
{
//TODO JAK W REGISTER TAK ADD ITEMU
//TODO LADOWANIE ITEMOW
    public function getCategories()
    {
        if (!$this->isGet()) {
            return $this->render('main');
        }

        error_log('GET parameters: ' . print_r($_GET, true));

        $limit = $_GET['limit'] ?? 10;
        $offset = $_GET['offset'] ?? 0;

        $categoryRepository = CategoryRepository::getInstance();
        $categories=$categoryRepository->getCategoires((int)$limit, (int)$offset);

        if(empty($categories)){
            echo json_encode(["message" => "fail"]);
        }
        else {
            echo json_encode(["message" => "success", "categories" => $categories]);
        }
    }



}