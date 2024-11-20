<?php

namespace App\Models;

/**
 * App\Models\Question
 * @property string $id
 * @property string $stem
 * @property string $type
 * @property string $strand
 * @property array $config
 **/
class Question
{
    public string $id;
    public string $stem;
    public string $type;
    public string $strand;
    public array $config;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->stem = $data['stem'];
        $this->type = $data['type'];
        $this->strand = $data['strand'];
        $this->config = $data['config'];
    }

}
