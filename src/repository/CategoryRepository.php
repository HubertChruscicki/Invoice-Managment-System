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

    public function howManyCategories(string $namePrefix = null): int
    {
        $baseStmt = "SELECT count(*) FROM product_categories";

        if($namePrefix !== null){
            $baseStmt .= " WHERE LOWER(name) ~ :namePrefix AND is_deleted = false;";
        }
        else{
            $baseStmt .= " WHERE is_deleted = false;";
        }
        $stmt = $this->database->connect()->prepare($baseStmt);
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
            return null; //exception TODO
        }

        return new Category(
            $category['id'],
            $category['name'],
            $category['vat']
        );
    }

    public function getCategoires(int $limit, int $offset, string $namePrefix = null): array
    {

        $baseStmt = "
            SELECT pc.id, pc.name, pc.vat, COUNT(p.id) AS ammountProducts
            FROM public.product_categories pc LEFT JOIN public.products p ON pc.id = p.id_category AND p.is_deleted = false";

        if($namePrefix !== null){
            $baseStmt .= " WHERE LOWER(pc.name) ~ :namePrefix AND pc.is_deleted = false";
        }
        else{
            $baseStmt .= " WHERE pc.is_deleted = false";
        }
        $baseStmt .= " GROUP BY pc.id, pc.name, pc.vat
                       ORDER BY pc.name
                       LIMIT :limit OFFSET :offset";

        $stmt = $this->database->connect()->prepare($baseStmt);

        if($namePrefix !== null){
            $prefixRegex = '^' . $namePrefix;
            $stmt->bindParam(":namePrefix", $prefixRegex, PDO::PARAM_STR);
        }
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function addCategory(string $category_name, float $vat): void
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO public.product_categories (name, vat)
            VALUES (?, ?)
            ');
        $stmt->execute([
            $category_name,
            $vat
        ]);
    }

    public function deleteCategory(string $category_name): bool
    {
        $baseStmt = "
        WITH updated_category AS (
            UPDATE public.product_categories 
            SET is_deleted = true 
            WHERE LOWER(name) = :category_name AND is_deleted = false
            RETURNING id
        )
        UPDATE public.products
        SET is_deleted = true 
        FROM updated_category
        WHERE products.id_category = updated_category.id;";

        $stmt = $this->database->connect()->prepare($baseStmt);

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
