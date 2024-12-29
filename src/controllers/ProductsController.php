<?php

require_once 'AppController.php';
require_once 'CategoryController.php';
require_once dirname(__DIR__) . '/repository/CategoryRepository.php';
require_once dirname(__DIR__) . '/repository/ProductsRepository.php';

class ProductsController extends AppController
{
    public function addProduct()
    {

        if (!$this->isPost()) {
            return $this->render('main');
        }

        if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['id'])) {
            $this->render('main', ['message' => 'Session problem!']);
            return;
        }
        $user_id = $_SESSION['id'];
        $id_category = (int)$_POST['productCategoryID']; //category by name
        $name = $_POST['name'];
        $price_brutto = (float)$_POST['price_brutto'];
        $price_netto = null;


        try {
            $category = CategoryRepository::getInstance()->getCategoryById($id_category);
            $vatPercent = $category->getVat();
            $vatCalculate = 1 + $vatPercent / 100;
            $price_netto = round($price_brutto / $vatCalculate);

        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception("Failed to calculate netto price.");
        }


        if ($id_category === '' || $name === '' || $price_brutto === '') {
            $this->render('main', ['message' => 'Please fill in all fields!']);
            return;
        }

        if ((int)$price_brutto < 0) {
            $this->render('main', ['messages' => ['Brutto price cannot be negative!']]);
            return;
        }
        if ((float)$price_brutto === 0.0) {
            $this->render('main', ['messages' => ['Brutto price cannot be 0']]);
            return;
        }

        $productsRepository = ProductsRepository::getInstance();
        $product = $productsRepository->getProduct((int)$user_id, $name);

        if ($product) {
            $this->render('main', ['message' => 'Such product already exists!']);
            return;
        }

        $productsRepository->addProduct((int)$user_id, $id_category, $name, $price_brutto, $price_netto);
        return $this->render('main', ['message' => 'Product successfully added!']);
    }

    public function howManyProducts()
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

        $productRepositroy = ProductsRepository::getInstance();
        $count = $productRepositroy->howManyProducts((int)$user_id, $namePrefix);

        if (is_int($count)) {
            echo json_encode(["message" => "success", "count" => $count]);
        } else {
            echo json_encode(["message" => "fail"]);
        }

    }

    public function getProducts()
    {
        if (!$this->isGet()) {
            return $this->render('main');
        }

        if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['id'])) {
            $this->render('main', ['message' => 'Session problem!']);
        }

        $user_id = $_SESSION['id'];

        $limit = $_GET['limit'] ?? 10;
        $offset = $_GET['offset'] ?? 0;
        $namePrefix = $_GET['namePrefix'] ?? null;
        $searchByCategoryFlag = filter_var($_GET['searchByCategoryFlag'] ?? 'false', FILTER_VALIDATE_BOOLEAN);
        $productRepository = ProductsRepository::getInstance();
        $products = $productRepository->getProducts((int)$user_id, (int)$limit, (int)$offset, $namePrefix, $searchByCategoryFlag);

        if (empty($products)) {
            echo json_encode(["message" => "fail"]);
        } else {
            echo json_encode(["message" => "success", "products" => $products]);
        }
    }

    public function deleteProduct()
    {
        if (!$this->isPost()) {
            return $this->render('main');
        }
        if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['id'])) {
            $this->render('main', ['message' => 'Session problem!']);
            return;
        }
        $user_id = $_SESSION['id'];
        $productName = json_decode(file_get_contents('php://input'), true)['productName'] ?? null;
        if($productName) {
            $productsRepository = ProductsRepository::getInstance();
            $deleteSuccess = $productsRepository->deleteProduct((int)$user_id, $productName);
            if($deleteSuccess) {
                echo json_encode(["message" => "success"]);
            }
            else {
                echo json_encode(["message" => "fail", "error" => "cant delete product"]);
            }
        }
        else {
            echo json_encode(["message" => "fail", "error" => "Product name problem"]);
        }
    }

}