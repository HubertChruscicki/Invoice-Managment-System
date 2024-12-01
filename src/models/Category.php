<?php

class Category{
    private $id;
    private $name;
    private $vat;

    function __construct(int $id, string $name, int $vat)
    {
        $this->id = $id;
        $this->name = $name;
        $this->vat = $vat;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getVat(): int
    {
        return $this->vat;
    }

    public function setVat(int $vat): void
    {
        $this->vat = $vat;
    }


}