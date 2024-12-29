<?php
require_once 'AppController.php';
require_once dirname(__DIR__) . '/repository/InvoiceRepository.php';
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
}