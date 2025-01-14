<?php
require_once 'AppController.php';
require_once 'ClientsController.php';
require_once dirname(__DIR__) . '/repository/InvoiceRepository.php';
require_once dirname(__DIR__) . '/repository/ProductsRepository.php';
require_once dirname(__DIR__) . '/repository/ClientsRepository.php';
require_once dirname(__DIR__) . '/libraries/fpdf186/fpdf.php'; //TODO MOZE BELDY ROBIC
class InvoicesController extends AppController
{
    public function getInvoices()
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
        $searchByNipFlag = filter_var($_GET['searchByNipFlag'] ?? 'false', FILTER_VALIDATE_BOOLEAN);
        $invoiceRepositroy = InvoiceRepository::getInstance();
        $invoices = $invoiceRepositroy->getInvoices((int)$user_id, (int)$limit, (int)$offset, $namePrefix, $searchByNipFlag);

        if (empty($invoices)) {
            echo json_encode(["message" => "fail"]);
        } else {
            echo json_encode(["message" => "success", "invoices" => $invoices]);
        }
    }

    public function howManyInvoices()
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
        $invoiceRepostiory = InvoiceRepository::getInstance();
        $count = $invoiceRepostiory->howManyInvoices((int)$user_id, $namePrefix);

        if (is_int($count)) {
            echo json_encode(["message" => "success", "count" => $count]);
        } else {
            echo json_encode(["message" => "fail"]);
        }

    }

    public function addInvoice()
    {
        if (!$this->isPost()) {
            return $this->render('main');
        }

        $user_id = $_SESSION['id'];

        $invoice_porducts_json = $_POST['productsJsonInput'];
        $client_id = $_POST['clientInvoiceID'];
        $date = $_POST['invoice_date'];
        $clientRepository = ClientsRepository::getInstance();
        $client = $clientRepository->getClientByID($user_id, $client_id);
        $productRepository = ProductsRepository::getInstance();

        if($invoice_porducts_json === "[]"){
            echo json_encode(["message" => "fail", "error" => "empty list of products"]);
            return;
        } elseif (empty($client)) {
            echo json_encode(["message" => "fail", "error" => "cant assign client to invoice"]);
            return;
        } elseif (empty($date)){
            echo json_encode(["message" => "fail", "error" => "empty date"]);
            return;
        } else {
            $products = json_decode($invoice_porducts_json, true);
            foreach ($products as $product) {
                $product = $productRepository->getProductByID($user_id, $product['id']);
                if($product === null){
                    echo json_encode(["message" => "fail", "error" => "invalid product"]);
                    return;
                }
            }

            $productsArray = [];

            foreach ($products as $product) {
                $productsArray[$product['id']] = $product['quantity'];
            }



            $invoiceRepository = InvoiceRepository::getInstance();
            $invoiceRepository->addInvoice($user_id, $client_id, $date, $productsArray);
            return $this->render('main', ['message' => 'Invoice successfully added!']); //TODO POZAMIENIAC RENDERY NA HEADERY
        }




    }

    public function getInvoiceDetails(){

        if(!$this->isGet()) {
            return $this->render('main');
        }

        if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['id'])) {
            $this->render('main', ['message' => 'Session problem!']);
        }

        $user_id = $_SESSION['id'];
        $invoice_id = $_GET['invoice_id'];
        $invoiceRepostiory = InvoiceRepository::getInstance();

        $invoiceDetailsJSON = $invoiceRepostiory->getInvoiceDetails((int)$user_id, (int)$invoice_id);

        if(empty($invoiceDetailsJSON)){
            echo json_encode(["message" => "fail", "error" => "invoice not found"]);
        } else {
            echo json_encode(["message" => "success", "invoiceDetailsJSON" => $invoiceDetailsJSON]);
        }
    }
}


