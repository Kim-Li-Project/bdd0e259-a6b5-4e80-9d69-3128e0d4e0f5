<?php

namespace Command;

use Tests\TestCase;

class GenerateReportTest extends TestCase
{
    public function test_generate_diagnostic_report()
    {
        $this->artisan('app:generate-report')
            ->expectsQuestion('Please enter the Student ID (leave blank to exit)', 'student1')
            ->expectsChoice(
                'Which report would you like to generate?',
                'Diagnostic',
                ['Diagnostic', 'Progress', 'Feedback']
            )
            ->expectsOutput('Tony Stark recently completed Numeracy assessment on 16th December 2021 10:46 AM')
            ->expectsOutput('He got 15 questions right out of 16. Details by strand given below:')
            ->expectsOutput('Number and Algebra: 5 out of 5 correct')
            ->expectsOutput('Measurement and Geometry: 7 out of 7 correct')
            ->expectsOutput('Statistics and Probability: 3 out of 4 correct')
            ->expectsQuestion('Please enter the Student ID (leave blank to exit)', '')
            ->expectsOutput('Exiting the report generator. Goodbye!')
            ->assertExitCode(0);
    }

    public function test_cli_exit_on_empty_student_id()
    {
        $this->artisan('app:generate-report')
            ->expectsQuestion('Please enter the Student ID (leave blank to exit)', '') // Exit input
            ->expectsOutput('Exiting the report generator. Goodbye!')
            ->assertExitCode(0);
    }
}
