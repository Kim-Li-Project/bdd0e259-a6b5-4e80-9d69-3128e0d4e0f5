<?php

namespace App\Models;

/**
 * App\Models\Assessment
 * @property string $id
 * @property string $name
 * @property array $questions
 **/
class Assessment
{
    public string $id;
    public string $name;
    public array $questions;

    /**
     * @param array $data
     */
    public function __construct(array $data)

    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->questions = $data['questions'];
    }

}
