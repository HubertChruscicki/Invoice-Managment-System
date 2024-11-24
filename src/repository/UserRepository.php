<?php

require_once 'Repository.php';
require_once 'CompanyRepository.php';
require_once __DIR__.'/../models/User.php';
class UserRepository extends Repository
{
    protected static $instance = null;
    protected function  __construct(){
        parent::__construct();
    }

    public static function getInstance() : Repository{
        if(!isset(self::$instance))
        {
            self::$instance = new UserRepository();
        }
        return self::$instance;
    }

    public function getUser(string $email): ?User
    {

        $stmt = $this->database->connect()->prepare("SELECT * FROM public.users WHERE email = :email");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user == false) {
            return null; //exception TODO
        }
        return new User(
            $user['id'],
            $user['id_user_role'],
            $user['id_company'],
            $user['email'],
            $user['password'],
            $user['name'],
            $user['surname']
        );
    }


    public function addUser(int $id_company, int $id_user_role, string $email, string $pass, string $name, string $surname): void
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO public.users (id_company, id_user_role, name, surname, email, password) VALUES (?, ?, ?, ?, ?, ?)
        ');

        $stmt->execute([
            $id_company,
            $id_user_role,
            $name,
            $surname,
            $email,
            $pass
        ]);
    }

    public function addAdminUser(string $email, string $company_name, string $nip, string $address, string $city, string $zip_code, string $country, string $pass)
    {
        try {
            $company = CompanyRepository::getInstance();
            $company->addCompany($company_name, $nip, $address, $city, $zip_code, $country);
        } catch (Exception $e) {
            throw new Exception("Company adding error: " . $e->getMessage());
        }
        $stmt = $this->database->connect()->prepare('
        SELECT id FROM public.company WHERE nip = ?
        ');

        $stmt->execute([$nip]);
        $companyId = $stmt->fetchColumn();

        if (!$companyId) {
            throw new Exception("Nie udało się znaleźć firmy w bazie danych po jej dodaniu.");
        }

        $adminRoleId = 1;

        try {
        $this->addUser($companyId, $adminRoleId, $email, $pass, $company_name.'@admin', $company_name.'@admin');
        } catch (Exception $e) {
        throw new Exception("Amin user adding error: " . $e->getMessage());
        }
    }

    public function assignCompanyToUser(int $id_company, int $id_user): void
    {
        //TODO MOZE
    }

}

