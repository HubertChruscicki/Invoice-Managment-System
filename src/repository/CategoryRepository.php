<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/Category.php';

class CategoryRepository extends Repository
{
    protected static $instance = null;

    protected function  __construct(){
        parent::__construct();
    }

    public static function getInstance(): Repository
    {
        if(!isset(self::$instance))
        {
            self::$instance = new CategoryRepository();
        }
        return self::$instance;
    }

    public function howManyCategories(int $user_id, string $namePrefix = null): int
    {
        $baseStmt = "SELECT COUNT(*)
                     FROM product_categories pc
                         JOIN company_categories cc ON pc.id = cc.id_category
                         JOIN company c ON c.id = cc.id_company
                         JOIN users u ON u.id_company = c.id
                         WHERE u.id = :user_id AND pc.is_deleted = false";

        if($namePrefix !== null){
            $baseStmt .= " AND LOWER(pc.name) ~ :namePrefix;";
        }

        $stmt = $this->database->connect()->prepare($baseStmt);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        if($namePrefix !== null){
            $prefixRegex = '^' . $namePrefix;
            $stmt->bindParam(":namePrefix", $prefixRegex, PDO::PARAM_STR);
        }
        $stmt->execute();

        $count = $stmt->fetchColumn();
        return (int)$count;
    }
    public function getCategory(string $category_name): ?Category
    {
        $stmt = $this->database->connect()->prepare("SELECT * FROM public.product_categories WHERE name = :name and is_deleted = false");
        $stmt->bindParam(":name", $category_name, PDO::PARAM_STR);
        $stmt->execute();

        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($category == false) {
            return null;
        }

        return new Category(
            $category['id'],
            $category['name'],
            $category['vat']
        );
    }

    public function getCategoryByID(int $ID): ?Category
    {
        $stmt = $this->database->connect()->prepare("SELECT * FROM public.product_categories WHERE id = :id"); //and is_deleted = false
        $stmt->bindParam(":id", $ID, PDO::PARAM_INT);
        $stmt->execute();

        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($category == false) {
            return null;
        }

        return new Category(
            $category['id'],
            $category['name'],
            $category['vat']
        );
    }

    public function getCategories(int $user_id, int $limit, int $offset, string $namePrefix = null): array
    {

        $baseStmt = "
                SELECT pc.id, pc.name, pc.vat, COUNT(p.id) AS ammountProducts
                    FROM product_categories pc
                    LEFT JOIN public.products p ON pc.id = p.id_category AND p.is_deleted = false
                    JOIN company_categories cc on cc.id_category = pc.id
                    JOIN users u on u.id_company = cc.id_company
                    WHERE pc.is_deleted = false AND u.id = :user_id";

        if($namePrefix !== null){
            $baseStmt .= " AND LOWER(pc.name) ~ :namePrefix";
        }
        $baseStmt .= " GROUP BY pc.id, pc.name, pc.vat
                       ORDER BY pc.name
                       LIMIT :limit OFFSET :offset";

        $stmt = $this->database->connect()->prepare($baseStmt);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if($namePrefix !== null){
            $prefixRegex = '^' . $namePrefix;
            $stmt->bindParam(":namePrefix", $prefixRegex, PDO::PARAM_STR);
        }
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function addCategory(int $user_id, string $category_name, float $vat): void
    {
        $stmt = $this->database->connect()->prepare(
            'WITH userCompanyID AS (
                SELECT u.id_company
                FROM users u
                JOIN company c ON c.id = u.id_company
                WHERE u.id = ?
            ),
            newCategoryID AS (
                INSERT INTO product_categories (name, vat)
                VALUES (?, ?)
                RETURNING id
            )
            INSERT INTO company_categories (id_company, id_category)
            SELECT uc.id_company, nc.id
            FROM userCompanyID uc, newCategoryID nc;'
           );
        $stmt->execute([
            $user_id,
            $category_name,
            $vat
        ]);
    }

    public function deleteCategory(int $user_id, string $category_name): bool
    {
        $baseStmt = "
                WITH updated_category AS (
                    UPDATE public.product_categories pc
                    SET is_deleted = true
                    FROM company_categories cc
                    JOIN company c ON c.id = cc.id_company
                    JOIN users u ON u.id_company = c.id
                    WHERE pc.is_deleted = false
                    AND u.id = :user_id
                    AND LOWER(pc.name) = :category_name
                    RETURNING pc.id
        )
        UPDATE public.products
        SET is_deleted = true
        FROM updated_category
        WHERE products.id_category = updated_category.id;
        ";

        $stmt = $this->database->connect()->prepare($baseStmt);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        $stmt->bindParam(":category_name", $category_name, PDO::PARAM_STR);
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
