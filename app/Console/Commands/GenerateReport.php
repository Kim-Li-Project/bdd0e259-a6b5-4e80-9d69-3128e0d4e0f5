<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Service\ReportService;
use Illuminate\Console\Command;

class GenerateReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate report for students';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $studentsData = collect(json_decode(file_get_contents(base_path('\storage\app\public\students.json')), true));
        $responsesData = collect(json_decode(file_get_contents(base_path('\storage\app\public\student-responses.json')), true));
        $questionsData = collect(json_decode(file_get_contents(base_path('\storage\app\public\questions.json')), true));
        $assessmentsData = collect(json_decode(file_get_contents(base_path('\storage\app\public\assessments.json')), true));

        $service = new ReportService($responsesData, $questionsData, $assessmentsData);

        $studentCollection = collect($studentsData)->map(function ($student) {
            return new Student($student);
        });

        while (true) {
            $studentId = $this->ask('Please enter the Student ID (leave blank to exit)');
            if (empty($studentId)) {
                $this->info('Exiting the report generator. Goodbye!');
                break;
            }

            try {
                $student = $studentCollection->firstWhere('id', $studentId);
                if (!$student)
                    throw new \Exception("Student not found");

                $reportType = $this->choice(
                    'Which report would you like to generate?',
                    ['1' => 'Diagnostic', '2' => 'Progress', '3' => 'Feedback'],
                    '1'
                );

                $report = $service->generateReport($student, $reportType);
                foreach ($report as $line) {
                    $this->line($line); // Print each line in the console
                }
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }

            $this->info("\n");
        }
    }
}
