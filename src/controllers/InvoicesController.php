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

    public function generateInvoicePDF()
    {
        if(!$this->isGet()) {
            return $this->render('main');
        }

        if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['id'])) {
            $url = '/';
            header("Location: $url");
            exit;
        }

        $user_id = $_SESSION['id'];
        $invoice_id = $_GET['invoice_id'];
        $invoiceRepostiory = InvoiceRepository::getInstance();

        $invoiceDetailsJSON = json_decode(json_encode($invoiceRepostiory->getInvoiceDetails((int)$user_id, (int)$invoice_id)));

        if(empty($invoiceDetailsJSON)){
            $this->render('main', ['message' => 'generating invoice pdf failed!']);
            return;
        }


        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', '', 12);

        $pdf->Cell(180, 10, 'Date: ' .  $invoiceDetailsJSON->client[0]->invoice_date, 0, 1, 'R');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 16);

        $pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');
        $pdf->Ln(10);


        $pdf->SetFont('Arial', 'B', 14);

        $pdf->Cell(90, 10, 'Client Details:', 0, 0, 'L');
        $pdf->Cell(90, 10, 'Seller Details:', 0, 1, 'R');


        $pdf->SetFont('Arial', '', 12);

        $pdf->Cell(90, 10, 'Name: ' . $invoiceDetailsJSON->client[0]->client_name, 0, 0, 'L');
        $pdf->Cell(90, 10, 'Name: ' . $invoiceDetailsJSON->company[0]->company_name, 0, 1, 'R');

        $pdf->Cell(90, 10, 'NIP: ' . $invoiceDetailsJSON->client[0]->client_nip, 0, 0, 'L');
        $pdf->Cell(90, 10, 'NIP: ' . $invoiceDetailsJSON->company[0]->company_nip, 0, 1, 'R');

        $pdf->Cell(90, 10, 'Address: ' . $invoiceDetailsJSON->client[0]->client_address, 0, 0, 'L');
        $pdf->Cell(90, 10, 'Address: ' . $invoiceDetailsJSON->company[0]->company_address, 0, 1, 'R');

        $pdf->Cell(90, 10, 'City: ' . $invoiceDetailsJSON->client[0]->client_city, 0, 0, 'L');
        $pdf->Cell(90, 10, 'City: ' . $invoiceDetailsJSON->company[0]->company_city, 0, 1, 'R');

        $pdf->Cell(90, 10, 'Zip Code: ' . $invoiceDetailsJSON->client[0]->client_zip_code, 0, 0, 'L');
        $pdf->Cell(90, 10, 'Zip Code: ' . $invoiceDetailsJSON->company[0]->company_zip_code, 0, 1, 'R');

        $pdf->Cell(90, 10, 'Country: ' . $invoiceDetailsJSON->client[0]->client_country, 0, 0, 'L');
        $pdf->Cell(90, 10, 'Country: ' . $invoiceDetailsJSON->company[0]->company_country, 0, 1, 'R');

        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Products:', 0, 1);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(70, 10, 'Name', 1);
        $pdf->Cell(25, 10, 'Vat', 1);
        $pdf->Cell(35, 10, 'Price brutto', 1);
        $pdf->Cell(35, 10, 'Prcie netto', 1);
        $pdf->Cell(25, 10, 'Quantity', 1);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);
        $productsAmmount = 0;
        $totalBrutto = 0;
        $totalNetto = 0;


        foreach ($invoiceDetailsJSON->products as $product) {
            $pdf->Cell(70, 10, $product->name, 1, 0);
            $pdf->Cell(25, 10, $product->vat, 1, 0);
            $pdf->Cell(35, 10, $product->price_brutto, 1, 0);
            $totalBrutto += (int)$product->price_brutto;
            $pdf->Cell(35, 10, $product->price_netto, 1, 0);
            $totalNetto += (int)$product->price_netto;
            $pdf->Cell(25, 10, $product->quantity, 1, 0);
            $productsAmmount += (int)$product->quantity;
            $pdf->Ln();
        }

        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $this->addRowToPDF($pdf, 'Ammount of products:', $productsAmmount);
        $this->addRowToPDF($pdf, 'Total Amount (Brutto):', number_format($totalBrutto, 2) . ' $');
        $this->addRowToPDF($pdf, 'Total Amount (Netto):', number_format($totalNetto, 2) . ' $');

        $pdf->Output('I', 'Invoice_invoice_number.pdf');
        exit();
    }

    private function addRowToPDF($pdf, $label, $value) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, $label, 0, 0);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $value, 0, 1);
    }
}


