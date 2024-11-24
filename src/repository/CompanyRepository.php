<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';

class CompanyRepository extends Repository
{
    protected static $instance = null;

    protected function  __construct(){
        parent::__construct();
    }

    public static function getInstance(): Repository
    {
        if(!isset(self::$instance))
        {
            self::$instance = new CompanyRepository();
        }
        return self::$instance;
    }

    public function getCompany(int $id_company): ?Company
    {
        $stmt = $this->database->connect()->prepare("SELECT * FROM public.company WHERE id = :id_company");
        $stmt->bindParam(":id_company", $id_company, PDO::PARAM_STR);
        $stmt->execute();

        $company = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($company == false) {
            return null; //exception TODO
        }

        return new Company(
            $company['id'],
            $company['name'],
            $company['nip'],
            $company['address'],
            $company['city'],
            $company['zip_code'],
            $company['country']
        );
    }
    public function doesCompanyExist(string $name, string $nip, $address, string $city): bool
    {
        $stmt = $this->database->connect()->prepare('
        SELECT COUNT(*) 
        FROM public.company 
        WHERE LOWER(name) = LOWER(?) OR 
              LOWER(nip) = LOWER(?) OR 
              (LOWER(address) = LOWER(?) AND LOWER(city) = LOWER(?))
    ');
        $stmt->execute([$name, $nip, $address, $city]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    public function addCompany(string $company_name, string $nip, string $address, string $city, string $zip_code, string $country): void
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO public.company (name, nip, address, city, zip_code, country)
            VALUES (?, ?, ?, ?, ?, ?)
            ');
        $stmt->execute([
            $company_name,
            $nip,
            $address,
            $city,
            $zip_code,
            $country
        ]);
    }
}
