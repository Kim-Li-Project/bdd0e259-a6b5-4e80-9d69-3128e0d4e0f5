<?php

namespace Service;

use App\Models\Student;
use App\Service\ReportService;
use Tests\TestCase;

class ReportServiceTest extends TestCase
{
    public function test_empty_responses_return_not_found_assessments_message()
    {
        $students = collect(json_decode(file_get_contents(base_path('\storage\app\public\students.json')), true))
            ->map(fn($data) => new Student($data));

        $service = new ReportService(collect(), collect(), collect());

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Matching response not found');

        $student = $students->firstWhere('id', 'student1');
        $report = $service->generateReport($student, "Diagnostic");
    }

    public function test_invalid_report_type_return_error_message()
    {
        $students = collect(json_decode(file_get_contents(base_path('\storage\app\public\students.json')), true))
            ->map(fn($data) => new Student($data));
        $responsesData = collect(json_decode(file_get_contents(base_path('\storage\app\public\student-responses.json')), true));
        $questionsData = collect(json_decode(file_get_contents(base_path('\storage\app\public\questions.json')), true));
        $assessmentsData = collect(json_decode(file_get_contents(base_path('\storage\app\public\assessments.json')), true));

        $service = new ReportService($responsesData, $questionsData, $assessmentsData);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid report type');

        $student = $students->firstWhere('id', 'student1');
        $report = $service->generateReport($student, "Invalid");
    }

}
