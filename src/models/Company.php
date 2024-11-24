<?php

class Company
{
    private $id;
    private $name;
    private $nip;
    private $address;
    private $city;
    private $zip_code;
    private $country;

    function __construct(int $id, string $name, string $nip, string $addres, string $city, string $zip_code, string $country)
    {
        $this->id = $id;
        $this->name = $name;
        $this->nip = $nip;
        $this->address = $addres;
        $this->city = $city;
        $this->zip_code = $zip_code;
        $this->country = $country;
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

    public function getNip(): string
    {
        return $this->nip;
    }

    public function setNip(string $nip): void
    {
        $this->nip = $nip;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getZipCode(): string
    {
        return $this->zip_code;
    }

    public function setZipCode(string $zip_code): void
    {
        $this->zip_code = $zip_code;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }


}
