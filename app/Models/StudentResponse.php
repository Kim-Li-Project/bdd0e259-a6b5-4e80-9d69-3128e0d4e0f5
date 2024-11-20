<?php

namespace App\Models;

/**
 * App\Models\StudentResponse
 * @property string $id
 * @property string $assessmentId
 * @property string $assigned
 * @property string $started
 * @property ?string $completed
 * @property array $student
 * @property array $responses
 **/
class StudentResponse
{
    public string $id;
    public string $assessmentId;

    public string $assigned;
    public string $started;
    public ?string $completed;
    public array $student;
    public array $responses;
    public array $results;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->assessmentId = $data['assessmentId'];
        $this->assigned = $data['assigned'];
        $this->started = $data['started'];
        $this->completed = $data['completed'] ?? null;
        $this->student = $data['student'];
        $this->responses = $data['responses'];
        $this->results = $data['results'];
    }
}
