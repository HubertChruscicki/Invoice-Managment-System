<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';

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

    public function howManyCategories(): int
    {
        $stmt=$this->database->connect()->prepare("SELECT count(*) FROM product_categories");
        $stmt->execute();
        $vile = $stmt->fetchColumn();
        return (int)$vile;
    }
    public function getCategory(string $category_name): ?Category
    {
        $stmt = $this->database->connect()->prepare("SELECT * FROM public.product_categories WHERE name = :name");
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
}
