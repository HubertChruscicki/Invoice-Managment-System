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

    public function findUserInfo(int $id)
    {
        $stmt = $this->database->connect()->prepare("SELECT u.id, u.id_user_role, u.id_company, c.name as company_name, u.name, u.surname, u.email, ur.role_name as role 
                                                                FROM public.users u
                                                                JOIN user_role ur ON ur.id = u.id_user_role
                                                                JOIN company c on c.id = u.id_company
                                                                WHERE u.id = :id"
        );


        $stmt->bindParam(":id", $id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
        throw new Exception("Admin user adding error: " . $e->getMessage());
        }
    }

    public function addUserToCompany(int $creator_user_id, string $name, string $surname, string $email, string $role, string $password): void
    {

        $stmt = $this->database->connect()->prepare("
           Select id from user_role
                where role_name = :role_name"
        );
//        $stmt->bindParam(":role_name", $role, PDO::PARAM_STR);
//        $stmt->execute();
//        $role_id = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $this->database->connect()->prepare("
           select id_company from users
                where id = :user_id"
        );
        $stmt->bindParam(":user_id", $creator_user_id, PDO::PARAM_INT);
        $stmt->execute();
        $company_id = $stmt->fetch(PDO::FETCH_ASSOC);

//        echo $role_id['id'];
        $this->addUser((int)$company_id, (int)$role ,$email, $password, $name, $surname);


    }





    public function getUsers(int $user_id, int $limit, int $offset, string $searchPrefix = null, $searchByEmailFlag)
    {
        try {
            $baseStmt = //TODO tu bedzie mozna wydupic niektore selecty
                "select u.id, ur.role_name , u.name, u.surname, u.email 
                    from users u
                    join user_role ur on ur.id = u.id_user_role
                    where u.id_user_role != 1
                    and u.id_company = (select id_company from users where id = :user_id)";
            if($searchPrefix !== null) {
                if($searchByEmailFlag === true){
                    $baseStmt .= " AND LOWER(u.email) ~ :searchPrefix ";
                }
                else{
                    $baseStmt .= " AND LOWER(u.surname) ~ :searchPrefix ";
                }
            }
            $baseStmt .= "
                LIMIT :limit 
                offset :offset";
            $stmt = $this->database->connect()->prepare($baseStmt);
            if ($searchPrefix !== null) {
                $prefixRegex = '^' . $searchPrefix;
                $stmt->bindParam(":searchPrefix", $prefixRegex, PDO::PARAM_STR);
            }
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e){
            error_log("Database error: " . $e->getMessage());
            return 0;
        }
    }




    public function howManyUsers(int $user_id, string $searchPrefix = null): int
    {
        $baseStmt =
            "SELECT COUNT(*)
                FROM users
                WHERE id_user_role != 1 
                and id_company = (select id_company from users where id = :user_id)";
        if($searchPrefix !== null){
            $baseStmt .= " and LOWER(surname) ~ :searchPrefix";
        }
        try{
            $stmt = $this->database->connect()->prepare($baseStmt);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            if($searchPrefix !== null){
                $searchPrefix = trim($searchPrefix);
                $prefixRegex = '^'.$searchPrefix;
                $stmt->bindParam(':searchPrefix', $prefixRegex, PDO::PARAM_STR);
            }
            $stmt->execute();
            $count = $stmt->fetchColumn();
            return (int)$count;
        }
        catch(PDOException $e){
            error_log($e->getMessage());
            return 0;
        }
    }


    public function deleteUser($user_id, $user_to_delete_id)
    {
        $baseStmt = "
            DELETE FROM users
                WHERE id_company = (select id_company from users where id=:user_id)
                AND id_user_role != 1 and id = :user_to_delete_id
        ";

        $stmt = $this->database->connect()->prepare($baseStmt);

        $stmt->bindParam(":user_to_delete_id", $user_to_delete_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

}

