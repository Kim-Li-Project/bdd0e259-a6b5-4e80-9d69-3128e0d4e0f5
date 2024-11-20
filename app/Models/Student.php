<?php

namespace App\Models;

/**
 * App\Models\Student
 * @property string $id
 * @property string $firstName
 * @property string $lastName
 * @property string $yearLevel
 **/
class Student
{
    public string $id;
    public string $firstName;
    public string $lastName;
    public string $yearLevel;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->firstName = $data['firstName'];
        $this->lastName = $data['lastName'];
        $this->yearLevel = $data['yearLevel'];
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

}
