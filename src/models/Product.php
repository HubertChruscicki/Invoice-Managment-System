<?php

class Product{
    private $id;
    private $id_category;
    private $id_company;
    private $name;
    private $price_brutto;
    private $price_netto;

    //TODO przejrzec wszystkie modele i pomyslec nad dodaniem is_deleted dodatkowo zmienic to w getproduct products w repository
    public function __construct(int $id, int $id_category, int $id_company, string $name, float $price_brutto, float $price_netto)
    {
        $this->id = $id;
        $this->id_category = $id_category;
        $this->id_company = $id_company;
        $this->name = $name;
        $this->price_brutto = $price_brutto;
        $this->price_netto = $price_netto;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getIdCategory(): int
    {
        return $this->id_category;
    }

    public function setIdCategory(int $id_category): void
    {
        $this->id_category = $id_category;
    }

    public function getIdCompany(): int
    {
        return $this->id_company;
    }

    public function setIdCompany(int $id_company): void
    {
        $this->id_company = $id_company;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPriceBrutto(): float
    {
        return $this->price_brutto;
    }

    public function setPriceBrutto(float $price_brutto): void
    {
        $this->price_brutto = $price_brutto;
    }

    public function getPriceNetto(): float
    {
        return $this->price_netto;
    }

    public function setPriceNetto(float $price_netto): void
    {
        $this->price_netto = $price_netto;
    }


}