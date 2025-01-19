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

    public function howManyInvoices(int $user_id, string $namePrefix=null): int //TODO FLAGA $searchByNipFlag I W INNYCH KLASACH
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
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
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
                "SELECT  i.id AS invoice_id, u.id_company AS company_id, s.id AS sale_id, cl.id AS client_id,
                        i.date AS invoice_date, c.name AS company_name, c.nip AS company_nip, c.address AS company_address, c.city AS company_city, c.zip_code AS company_zip_code, c.country AS company_country,
                        cl.name AS client_name, cl.nip AS client_nip, cl.address AS client_address, cl.city AS client_city, cl.zip_code AS client_zip_code, cl.country AS client_country,
                        SUM(p.price_netto * sp.quantity) AS total_price_netto, SUM(p.price_brutto * sp.quantity) AS total_price_brutto , SUM(sp.quantity) as ammount_of_products
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
            $baseStmt .= "
                AND i.is_deleted = false
                GROUP BY 
                    i.id, u.id_company, s.id, cl.id, i.date, c.name, c.nip, c.address, c.city,
                    c.zip_code, c.country, cl.name, cl.nip, cl.address, cl.city, cl.zip_code, cl.country
                ORDER BY i.date desc
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

    public function getInvoiceDetails(int $user_id, int $invoice_id)
    {
        $db = $this->database->connect();

        try {
            $stmt = $db ->prepare("
            select s.id as sale_id, cl.name as client_name, cl.nip as client_nip, cl.address as client_address, 
                   cl.city as client_city, cl.zip_code as client_zip_code, cl.country as client_country, i.date as invoice_date
                from invoice i
                join company c on c.id = i.id_company
                join users u on u.id_company = c.id
                join clients cl on cl.id = i.id_client
                join sales s on s.id_invoice = i.id
            where u.id = ? and i.id = ?
        ");
            $stmt->execute([
                $user_id,
                $invoice_id
            ]);

            $invoiceDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $sale_id = $invoiceDetails[0]['sale_id'];

            $stmt = $db ->prepare("
                select p.name as name, c.name as category, price_brutto, vat , ROUND((vat/100 * price_brutto),2) as vat_value, price_netto, quantity
                    from sale_products sp
                    join sales s on s.id = sp.id_sale
                    join products p on p.id = sp.id_product
                    join product_categories c on c.id = p.id_category
                    where status = 'contain-invoice' and sp.id_sale = ?
        ");
            $stmt->execute([$sale_id]);

            $prodcuts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $db ->prepare("
                select c.name as company_name, c.nip as company_nip, c.address as company_address, c.city as company_city,
                    c.zip_code as company_zip_code, c.country as company_country
                    from company c
                    join users u on u.id_company = c.id
                    where u.id = ?

        ");
            $stmt->execute([$user_id]);

            $company = $stmt->fetchAll(PDO::FETCH_ASSOC);



            $invoiceDetailsJSON = json_encode(["client" => $invoiceDetails, "products" => $prodcuts, "company" => $company]);
            return json_decode($invoiceDetailsJSON);


        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new Exception("Failed to get invoice details." . $e->getMessage());
        }
    }
    public function addInvoice($user_id, $client_id, $date, $productsArray)
    {

        $db = $this->database->connect();
        $db->beginTransaction();

        try {
            $stmt = $db ->prepare("
            WITH userCompanyID AS (
                 SELECT u.id_company
                     FROM users u
                     JOIN company c ON c.id = u.id_company
            WHERE u.id = ?
            ),
             newInvoiceID AS (
                 INSERT INTO invoice (id_client, id_company, date)
                     SELECT ?, id_company, ?
                     FROM userCompanyID
                     RETURNING id
             ),
             newSalesID AS (
                 INSERT INTO sales (id_invoice, id_company, status)
                     SELECT ni.id, uc.id_company, 'contain-invoice'
                     FROM newInvoiceID ni, userCompanyID uc
                     RETURNING id
             )
            SELECT * FROM newSalesID;
        ");
            $stmt->execute([
                $user_id,
                $client_id,
                $date
            ]);

            $sales_id = $stmt->fetch(PDO::FETCH_COLUMN);


            $stmt = $db->prepare("
            INSERT INTO sale_products (id_sale, id_product, quantity)
            VALUES (?, ?, ?);
        ");

            foreach ($productsArray as $idProduct => $quantity) {
                $stmt->execute([
                    $sales_id,
                    $idProduct,
                    $quantity
                ]);
            }
            $db->commit();

        } catch (PDOException $e) {
            $db->rollBack();
            error_log($e->getMessage());
            throw new Exception("Failed to add new invoice to database." . $e->getMessage());
        }
    }

    public function deleteInvoice($user_id, $invoice_id)
    {
        $baseStmt = "
            UPDATE public.invoice
                SET is_deleted = true
                FROM invoice inv
                JOIN company c ON c.id = inv.id_company
                JOIN users u ON u.id_company = c.id
                WHERE u.id = :user_id
                AND inv.id = :invoice_id AND inv.is_deleted = false AND inv.id = invoice.id";

        $stmt = $this->database->connect()->prepare($baseStmt);

        $stmt->bindParam(":invoice_id", $invoice_id, PDO::PARAM_INT);
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