<?php
require_once 'Repository.php';
require_once dirname(__DIR__).'/models/Invoice.php';

class InvoiceRepository extends Repository
{
    protected static $instance = null;

    protected function _construct()
    {
        parent::_construct();
    }

    public static function getInstance(): Repository
    {
        if (!isset(self::$instance)) {
            self::$instance = new InvoiceRepository();
        }
        return self::$instance;
    }

    public function howManyInvoices(int $user_id, string $namePrefix=null): int
    {
        $baseStmt =
            "SELECT COUNT(*)
                from invoice i
                join company c on c.id = i.id_company
                join users u on u.id_company = c.id
                join sales s on s.id_invoice = i.id
                join clients cl on cl.id = i.id_client
                where u.id = :user_id and s.status='contain-invoice'";
        if($namePrefix !== null){
            $baseStmt .= " and LOWER(cl.name) ~ :namePrefix";
        }
        try{
            $stmt = $this->database->connect()->prepare($baseStmt);
            $stmt->bindParam('user_id', $user_id, PDO::PARAM_INT);
            if($namePrefix !== null){
                $prefixRegex = '^'.$namePrefix;
                $stmt->bindParam(':namePrefix', $prefixRegex, PDO::PARAM_STR);
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
    public function getInvoices(int $user_id, int $limit, int $offset, string $namePrefix=null, $searchByNipFlag)
    {
        try {
            $baseStmt = //TODO tu bedzie mozna wydupic niektore selecty
                "SELECT i.id AS invoice_id, u.id_company AS company_id, s.id AS sale_id, cl.id AS client_id,
                        i.date AS invoice_date, c.name AS company_name, c.nip AS company_nip, c.address AS company_address, c.city AS company_city, c.zip_code AS company_zip_code, c.country AS company_country,
                        cl.name AS client_name, cl.nip AS client_nip, cl.address AS client_address, cl.city AS client_city, cl.zip_code AS client_zip_code, cl.country AS client_country,
                        SUM(p.price_netto * sp.quantity) AS total_price_netto, SUM(p.price_brutto * sp.quantity) AS total_price_brutto
                        FROM users u
                                JOIN company c ON c.id = u.id_company
                                JOIN sales s ON s.id_company = c.id
                                JOIN invoice i ON i.id = s.id_invoice
                                JOIN clients cl ON cl.id = i.id_client
                                JOIN sale_products sp ON sp.id_sale = s.id
                                JOIN products p ON p.id = sp.id_product
                        WHERE
                            s.status = 'contain-invoice'
                            and u.id = :user_id";
            if($namePrefix !== null) {
                if($searchByNipFlag === true){
                    $baseStmt .= " AND LOWER(cl.nip) ~ :namePrefix ";
                }
                else{
                $baseStmt .= " AND LOWER(cl.name) ~ :namePrefix ";
                }
            }
            $baseStmt .=
                "GROUP BY 
                    i.id, u.id_company, s.id, cl.id, i.date, c.name, c.nip, c.address, c.city,
                    c.zip_code, c.country, cl.name, cl.nip, cl.address, cl.city, cl.zip_code, cl.country
                ORDER BY i.date
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




}