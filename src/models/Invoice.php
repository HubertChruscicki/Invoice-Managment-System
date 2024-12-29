<?php

class Invoice
{
    private $id;
    private $id_client;
    private $id_company;
    private $date;

    public function __construct(int $id, int $id_client, int $id_company, string $date)
    {
    $this->id = $id;
    $this->id_client = $id_client;
    $this->id_company = $id_company;
    $this->date = $date;
    }

    public function getIdCompany(): int
    {
        return $this->id_company;
    }

    public function setIdCompany(int $id_company): void
    {
        $this->id_company = $id_company;
    }

    public function getIdClient(): int
    {
        return $this->id_client;
    }

    public function setIdClient(int $id_client): void
    {
        $this->id_client = $id_client;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }



}

