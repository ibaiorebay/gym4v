<?php

namespace App\Models;

class MonitorDTO
{
    public int $id;
    public string $name;
    public string $email;
    public string $phone;
    public string $photo;

    public function __construct($id, $name, $email, $phone, $photo)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->photo = $photo;
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): void
    {
        $this->photo = $photo;
    }

}
