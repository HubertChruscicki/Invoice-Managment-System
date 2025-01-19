<?php

require_once 'Repository.php';
require_once dirname(__DIR__).'/models/Product.php';
class ProductsRepository extends Repository
{
    protected static $instance = null;

    protected function _construct()
    {
        parent::_construct();
    }

    public static function getInstance(): Repository
    {
        if (!isset(self::$instance)) {
            self::$instance = new ProductsRepository();
        }
        return self::$instance;
    }

    public function getProduct(int $user_id, string $product_name)
    {
        $stmt =
            "select p.id, p.id_category, p.id_company, p.name, p.price_brutto, p.price_netto from products p
        join company c on c.id = p.id_company
        join users u on u.id_company = c.id
        where is_deleted = false and u.id = :user_id
        and LOWER(p.name) ~ :namePrefix"
        ;
        $prefixRegex = '^'.$product_name.'$';
        $stmt = $this->database->connect()->prepare($stmt);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':namePrefix', $prefixRegex, PDO::PARAM_STR);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if($product == false){
            return null;
        }

        return new Product(
            $product['id'],
            $product['id_category'],
            $product['id_company'],
            $product['name'],
            $product['price_brutto'],
            $product['price_netto']
        );

    }

    public function getProductByID(int $user_id, int $product_id)
    {
        $stmt =
            "select p.id, p.id_category, p.id_company, p.name, p.price_brutto, p.price_netto from products p
        join company c on c.id = p.id_company
        join users u on u.id_company = c.id
        where is_deleted = false and u.id = :user_id and p.id = :product_id"
        ;
        $stmt = $this->database->connect()->prepare($stmt);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if($product == false){
            return null;
        }

        return new Product(
            $product['id'],
            $product['id_category'],
            $product['id_company'],
            $product['name'],
            $product['price_brutto'],
            $product['price_netto']
        );

    }

    public function addProduct(int $user_id, int $id_category, string $name, float $price_brutto, float $price_netto)
    {
        try{
            $stmt =
        "WITH userCompanyID as(
                   SELECT u.id_company
                   from users u
                   join company c on c.id = u.id_company
                   where u.id = ?
                  
            )
            INSERT INTO products (id_category, id_company, name, price_brutto, price_netto)
            SELECT ?, id_company, ?, ?, ?
            FROM userCompanyID"
            ;
            $stmt = $this->database->connect()->prepare($stmt);
            $stmt->execute([
                $user_id,
                $id_category,
                $name,
                $price_brutto,
                $price_netto
            ]);

        }
        catch(PDOException $e){
            error_log($e->getMessage());
            throw new Exception("Failed to add new product to database.");
        }
    }


    public function howManyProducts(int $user_id, string $namePrefix=null): int
    {
        $baseStmt =
            "SELECT COUNT(*)
                    from products p
                    join company c on c.id = p.id_company
                    join users u on u.id_company = c.id
                    where u.id = :user_id and is_deleted = false";
        if($namePrefix !== null){
            $baseStmt .= " and LOWER(p.name) ~ :namePrefix";
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


    public function getProducts(int $user_id, int $limit, int $offset, string $namePrefix=null, bool $searchByCategoryFlag = false)
    {
        try {
            $baseStmt =
                "select p.id, p.id_category, p.id_company, p.name, pc.name as category_name, pc.vat, ROUND((pc.vat/100 * p.price_brutto),2)  as vat_value, p.price_brutto, p.price_netto, p.is_deleted from products p
            join company c on c.id = p.id_company
            join users u on u.id_company = c.id
            join product_categories pc on pc.id = p.id_category                                                                                  
            where p.is_deleted = false and u.id = :user_id"
            ;
            if($namePrefix !== null) {
                if($searchByCategoryFlag === true){
                    $baseStmt .= " AND LOWER(pc.name) ~ :namePrefix ";
                }
                else{
                    $baseStmt .= " AND LOWER(p.name) ~ :namePrefix ";
                }
            }
            $baseStmt .= " ORDER BY p.name
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

    public function deleteProduct(int $user_id, string $product_name): bool
    {
        $baseStmt = "
            UPDATE public.products p
            SET is_deleted = true
            FROM company c
            JOIN users u ON u.id_company = c.id
            WHERE
            c.id = p.id_company
            AND LOWER(p.name) = :product_name
            AND u.id = :user_id
            AND p.is_deleted = false;";

        $stmt = $this->database->connect()->prepare($baseStmt);

        $stmt->bindParam(":product_name", $product_name, PDO::PARAM_STR);
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