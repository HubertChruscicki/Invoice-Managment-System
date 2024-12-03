<?php
session_start();
require_once 'AppController.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../repository/CategoryRepository.php';
class SecurityController extends AppController
{

    public function login()
    {
        $userRepository = UserRepository::getInstance();
        if (!$this->isPost()) {
            return $this->render('login');
        }
        $email = $_POST['email'];
        $password = $_POST['pass'];
        $user = $userRepository->getUser($email);

        if(!$user){
            return $this->render('login', ['messages' => ['User not found!']]);
        }

        if(password_verify($password, $user->getPassword())){
            $_SESSION['emial'] = $user->getEmail();
            $_SESSION['id'] = $user->getId();
            $this->render('main');
        }
        else{
            return $this->render('login', ['messages' => ['Wrong password!']]);
        }
    }

    public function register()
    {
        if(!$this->isPost()){
            return $this->render('register');
        }

        $email = $_POST['email'];
        $company_name = $_POST['company_name'];
        $nip = $_POST['nip'];
        $address = $_POST['adress'];
        $city = $_POST['city'];
        $zip = $_POST['zip_code'];
        $country = $_POST['country'];
        $password = $_POST['pass'];

        if ($email === '' || $company_name === '' || $nip === '' || $address === '' || $city === '' || $zip === '' || $country === '' || $password === '' ) {
            $this->render('register', ['messages' => ['You have to fill all fields!']]);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->render('register', ['messages' => ['Email is not valid!']]);
            return;
        }
        if (strlen($password) < 8) {
            $this->render('register', ['messages' => ['Password is too short!']]);
            return;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $this->render('register', ['messages' => ['Password must contain at least one capital letter!']]);
            return;
        }
        if (!preg_match('/[0-9]/', $password)) {
            $this->render('register', ['messages' => ['Password must contain at least one number!']]);
            return;
        }

        $email = strtolower($email);
        $userRepository = UserRepository::getInstance();
        $companyRepository = CompanyRepository::getInstance();

        $user = $userRepository->getUser($email);
        if($user){
            $this->render('register', ['messages' => ['User with this email already exist!']]);
            return;
        }

        $comapny = $companyRepository->doesCompanyExist($company_name,  $nip, $address, $city);
        if($comapny){
            echo "sigma";
            $this->render('register', ['messages' => ['Company details belong to other one!']]);
            return;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        $userRepository->addAdminUser($email, $company_name, $nip, $address, $city, $zip, $country, $hash);

        return $this->render('login', ['messages' => ['Successfully registered!']]);
    }

    public function logout(){
        session_unset();
        session_destroy();
        $this->render('login');
    }
}