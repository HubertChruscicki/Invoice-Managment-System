<?php
require_once 'AppController.php';
require_once dirname(__DIR__) . '/repository/ClientsRepository.php';

class ClientsController extends AppController
{
    public function addClient()
    {
        if (!$this->isPost()) {
            return $this->render('main');
        }

        if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['id'])) {
            $this->render('main', ['message' => 'Session problem!']);
            return;
        }
        $user_id = $_SESSION['id'];



        $client_name = $_POST['clientName'];
        $nip = $_POST['nip'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $zip_code = $_POST['zipCode'];
        $country = $_POST['country'];

        if ($client_name === '' || $nip === '' || $address === '' || $city === '' || $zip_code === '' || $country === '') {
            $this->render('main', ['message' => 'Please fill in all fields!']);
            return;
        }
        $clientRepository = ClientsRepository::getInstance();
        $client = $clientRepository->getClient((int)$user_id, $client_name, $nip);

        if($client) {
            $this->render('main', ['message' => 'Such client already exists!']);
            return;
        }

        $clientRepository->addClient((int)$user_id, $client_name, $nip, $address, $city, $zip_code, $country);
        return $this->render('main', ['message' => 'Client successfully added!']);
    }

    public function getSessionId(){
        if(isset($_SESSION['id'])){
            echo $_SESSION['id'];
        }
        else{
            echo "sraka";
        }
    }
    public function howManyClients()
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

        $clientRepository = ClientsRepository::getInstance();
        $count = $clientRepository->howManyClients((int)$user_id, $namePrefix);

        if (is_int($count)) {
            echo json_encode(["message" => "success", "count" => $count]);
        } else {
            echo json_encode(["message" => "fail"]);
        }

    }

    public function getClients()
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
        $namePrefix = $_GET['namePrefix'] ?? null;

        $clientRepository = ClientsRepository::getInstance();
        $clients = $clientRepository->getClients((int)$user_id, (int)$limit, (int)$offset, $namePrefix);

        if (empty($clients)) {
            echo json_encode(["message" => "fail"]);
        }
        else{
            echo json_encode(["message" => "success", "clients" => $clients]);
        }
    }

    public function deleteClient()
    {
        if (!$this->isPost()) {
            return $this->render('main');
        }
        if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['id'])) {
            $this->render('main', ['message' => 'Session problem!']);
            return;
        }
        $user_id = $_SESSION['id'];

        $clientName = json_decode(file_get_contents('php://input'), true)['clientName'] ?? null;

        if($clientName) {
            $clientRepository = ClientsRepository::getInstance();
            $deleteSuccess = $clientRepository->deleteClient((int)$user_id, $clientName);
            if($deleteSuccess) {
                echo json_encode(["message" => "success"]);
            }
            else {
                echo json_encode(["message" => "fail", "error" => "cant delete client"]);
            }
        }
        else {
            echo json_encode(["message" => "fail", "error" => "Client name problem"]);
        }
    }

}

