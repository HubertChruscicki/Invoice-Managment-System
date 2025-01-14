<?php

require_once 'Repository.php';
require_once dirname(__FILE__).'/../models/Client.php';

class ClientsRepository extends Repository
{
    protected static $instance = null;

    protected function _construct()
    {
        parent::_construct();
    }

    public static function getInstance(): Repository
    {
        if (!isset(self::$instance)) {
            self::$instance = new ClientsRepository();
        }
        return self::$instance;
    }

    public function getClient(int $user_id, string $client_name, string $nip): ?Client
    {
        $stmt = $this->database->connect()->prepare
        ("select cl.id, cl.name, cl.nip, cl.address, cl.city, cl.zip_code, cl.country
                    from clients cl
                    join company_clients ccl on cl.id = ccl.id_client
                    join company c on c.id = ccl.id_company
                    join users u on u.id_company = c.id
                    where is_deleted = false and u.id = :user_id
                        and (
                        LOWER(cl.name) ~ :namePrefix OR LOWER(cl.nip) ~ :nipPrefix
                        )
         ");
        $prefixRegex = '^' . $client_name . '$';
        $nipRegex = '^' . $nip . '$';
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":namePrefix", $prefixRegex, PDO::PARAM_STR);
        $stmt->bindParam(":nipPrefix", $nipRegex, PDO::PARAM_STR);
        $stmt->execute();
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($client == false) {
            return null; //exception TODO
        }

        return new Client(
            $client['id'],
            $client['name'],
            $client['address'],
            $client['city'],
            $client['zip_code'],
            $client['country']
        );
    }

    public function getClientByID(int $user_id, int $client_id): ?Client
    {
        $stmt = $this->database->connect()->prepare
        ("select cl.id, cl.name, cl.nip, cl.address, cl.city, cl.zip_code, cl.country
                    from clients cl
                    join company_clients ccl on cl.id = ccl.id_client
                    join company c on c.id = ccl.id_company
                    join users u on u.id_company = c.id
                    where is_deleted = false and u.id = :user_id
                    and cl.id = :client_id
      ");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":client_id", $client_id, PDO::PARAM_INT);
        $stmt->execute();
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($client == false) {
            return null; //exception TODO
        }

        return new Client(
            $client['id'],
            $client['name'],
            $client['address'],
            $client['city'],
            $client['zip_code'],
            $client['country']
        );
    }

    public function addClient(int $user_id, string $name, string $nip, string $address, string $city, string $zip_code, string $country)
    {
        try {
            $stmt = $this->database->connect()->prepare(
                "WITH userCompanyID AS (
                SELECT u.id_company
                FROM users u
                JOIN company c ON c.id = u.id_company
                WHERE u.id = ?
            ),
            newClientID AS (
                INSERT INTO clients (name, nip, address, city, zip_code, country)
                VALUES (?, ?, ?, ?, ?, ?)
                RETURNING id
            )
            INSERT INTO company_clients (id_company, id_client)
            SELECT uc.id_company, nc.id
            FROM userCompanyID uc, newClientID nc;"
            );

            $stmt->execute([
                $user_id,
                $name,
                $nip,
                $address,
                $city,
                $zip_code,
                $country
            ]);
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            throw new Exception("Failed to add new client to database.");
        }
    }
    public function howManyClients(int $user_id,string $namePrefix = null): int
    {

        $baseStmt = "SELECT COUNT(*) 
                 FROM clients cl
                 JOIN company_clients ccl ON cl.id = ccl.id_client
                 JOIN company c ON c.id = ccl.id_company
                 JOIN users u ON u.id_company = c.id
                 WHERE u.id = :user_id AND is_deleted = false";

        if ($namePrefix !== null) {
            $baseStmt .= " AND LOWER(cl.name) ~ :namePrefix";
        }

        try {
            $stmt = $this->database->connect()->prepare($baseStmt);

            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            if ($namePrefix !== null) {
                $prefixRegex = '^' . $namePrefix;
                $stmt->bindParam(":namePrefix", $prefixRegex, PDO::PARAM_STR);
            }

            $stmt->execute();
            $count = $stmt->fetchColumn();

            return (int)$count;

        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return 0;
        }
    }

    public function getClients(int $user_id, int $limit, int $offset, string $namePrefix = null, $searchByNipFlag)
    {
        try{
            $baseStmt = "select cl.id,cl.name, cl.nip, cl.address, cl.city, cl.zip_code, cl.country, cl.is_deleted from clients cl
                  join company_clients ccl on cl.id = ccl.id_client
                  join company c on c.id = ccl.id_company
                  join users u on u.id_company = c.id
                  where is_deleted = false and u.id = :user_id";
            if($namePrefix !== null) {
                if($searchByNipFlag === true){
                    $baseStmt .= " AND LOWER(cl.nip) ~ :namePrefix ";
                }
                else{
                    $baseStmt .= " AND LOWER(cl.name) ~ :namePrefix ";
                }

            }
            $baseStmt .= " ORDER BY cl.name
                           LIMIT :limit 
                           offset :offset";
            $stmt = $this->database->connect()->prepare($baseStmt);
            if ($namePrefix !== null) {
                $prefixRegex = '^' . $namePrefix;
                $stmt->bindParam(":namePrefix", $prefixRegex, PDO::PARAM_STR);
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

    public function deleteClient(int $user_id, string $client_name): bool
    {
        $baseStmt = "
                    UPDATE public.clients cl
                    SET is_deleted = true
                    FROM company_clients ccl
                        JOIN company c ON c.id = ccl.id_company
                        JOIN users u ON u.id_company = c.id
                    WHERE cl.id = ccl.id_client
                    AND LOWER(cl.name) = :client_name
                    AND u.id = :user_id
                    AND cl.is_deleted = false;";

        $stmt = $this->database->connect()->prepare($baseStmt);

        $stmt->bindParam(":client_name", $client_name, PDO::PARAM_STR);
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