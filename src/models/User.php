<?php

class User{
    private $id;
    private $id_user_role;
    private $email;
    private $password;
    private $name;
    private $surname;

    function __construct(int $id, int $id_role, string $email, string $password, string $name, string $surname)
    {
        $this->id = $id;
        $this->id_user_role = $id_role;
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->surname = $surname;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getIdRole(): int
    {
        return $this->id_user_role;
    }
    public function setIdRole(int $id_role)
    {
        $this->id_role = $id_role;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function setPassword(string $password)
    {
        $this->password = $password;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function getSurname(): string
    {
        return $this->surname;
    }
    public function setSurname(string $surname)
    {
        $this->surname = $surname;
    }



}