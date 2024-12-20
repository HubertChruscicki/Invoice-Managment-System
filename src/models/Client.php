<?php

class Client
{
    private $id;
    private $name;
    private $nip;
    private $address;
    private $city;
    private $zip_code;
    private $country;

    function __construct($id, $name, $address, $city, $zip_code, $country){
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->city = $city;
        $this->zip_code = $zip_code;
        $this->country = $country;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getNip()
    {
        return $this->nip;
    }

    public function setNip($nip): void
    {
        $this->nip = $nip;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address): void
    {
        $this->address = $address;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city): void
    {
        $this->city = $city;
    }

    public function getZipCode()
    {
        return $this->zip_code;
    }


    public function setZipCode($zip_code): void
    {
        $this->zip_code = $zip_code;
    }

    public function getCountry()
    {
        return $this->country;
    }


    public function setCountry($country): void
    {
        $this->country = $country;
    }


}