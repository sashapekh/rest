<?php

namespace Sashapekh\SimpleRest\Models;

class User
{
    public int $id;
    public string $name;
    public int $age;
    public string $address;
    public string $email;

    public function __construct(int $id, string $name, string $email, int $age, string $address)
    {
        $this->id = $id;
        $this->name = $name;
        $this->age = $age;
        $this->address = $address;
        $this->email = $email;
    }

    public function toArray(): array
    {
        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'age'     => $this->age,
            'address' => $this->address,
            'email'   => $this->email
        ];
    }
}