<?php

namespace App\Service;

use App\Models\Assessment;
use App\Models\Question;
use App\Models\Student;
use App\Models\StudentResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;

class ReportService
{
    protected Collection $studentResponses;
    protected Collection $questions;
    protected Collection $assessments;
    protected int $totalQuestions;

    /**
     * @param Collection $studentResponses
     * @param Collection $questions
     * @param Collection $assessments
     */
    public function __construct(Collection $studentResponses, Collection $questions, Collection $assessments)
    {
        $this->studentResponses = collect($studentResponses)->map(function ($studentResponse) {
            return new StudentResponse($studentResponse);
        });
        $this->questions = collect($questions)->map(function ($question) {
            return new Question($question);
        });
        $this->assessments = collect($assessments)->map(function ($assessment) {
            return new Assessment($assessment);
        });
        $this->totalQuestions = count($this->questions);
    }

    /**
     * @param Student $student
     * @param string $reportType
     * @return array|string
     * @throws Exception
     */
    public function generateReport(Student $student, string $reportType)
    {

        $studentResponsesCollection = $this->studentResponses->where(function ($response) use ($student) {
            return $response->student['id'] === $student->id;
        });

        if (!$studentResponsesCollection)
            throw new Exception("Matching response not found");

        return match ($reportType) {
            "Diagnostic" => $this->diagnosticReport($student, $studentResponsesCollection),
            "Progress" => $this->progressReport($student, $studentResponsesCollection),
            "Feedback" => $this->feedbackReport($student, $studentResponsesCollection),
            default => throw new Exception("Invalid report type"),
        };
    }

    /**
     * @param Student $student
     * @param $studentResponse
     * @return array
     * @throws Exception
     */
    private function diagnosticReport(Student $student, $studentResponse)
    {

        $latestResponse = $studentResponse->sortByDesc('completed')->first();

        if (!$latestResponse)
            throw new Exception("Matching response not found");

        $assessment = $this->assessments->firstWhere('id', $latestResponse->assessmentId);

        // Build the report as an array
        $report = [];

        $report[] = "{$student->getFullName()} recently completed {$assessment->name} assessment on "
            . Carbon::createFromFormat('d/m/Y H:i:s', $latestResponse->completed)->format('jS F Y h:i A');
        $report[] = "He got {$latestResponse->results['rawScore']} questions right out of {$this->totalQuestions}. Details by strand given below:";
        $report[] = ''; // Blank line for readability

        $strandScores = [];
        foreach ($latestResponse->responses as $response) {
            $question = $this->questions->firstWhere('id', $response['questionId']);
            if ($question) {
                $strand = $question->strand;
                $isCorrect = $response['response'] === $question->config['key'];
                if (!isset($strandScores[$strand])) {
                    $strandScores[$strand] = ['correct' => 0, 'total' => 0];
                }
                $strandScores[$strand]['total']++;
                if ($isCorrect) {
                    $strandScores[$strand]['correct']++;
                }
            }
        }

        foreach ($strandScores as $strand => $score) {
            $report[] = "{$strand}: {$score['correct']} out of {$score['total']} correct";
        }

        return $report;

    }

    /**
     * @param $student
     * @param $studentResponses
     * @return array|string
     */
    private function progressReport($student, $studentResponses)
    {
        $completedStudentResponses = $studentResponses->sortBy('completed')->whereNotNull('completed');

        $first = $completedStudentResponses->first();
        $last = $completedStudentResponses->last();

        // Calculate score improvement
        $scoreDiff = $last->results['rawScore'] - $first->results['rawScore'];

        // Build the report as an array
        $report = [];
        $report[] = "{$student->getFullName()} has completed Numeracy assessment {$completedStudentResponses->count()} times in total. Date and raw score given below:";
        $report[] = ""; // Blank line for readability

        foreach ($completedStudentResponses as $completedStudentResponse) {
            $report[] = "Date: " . Carbon::createFromFormat('d/m/Y H:i:s', $completedStudentResponse->assigned)->format('jS F Y') . ", Raw Score: {$completedStudentResponse->results['rawScore']} out of {$this->totalQuestions}";
        }

        $report[] = "";
        $report[] = "{$student->getFullName()} got {$scoreDiff} more correct in the recent completed assessment than the oldest";

        return $report;
    }

    /**
     * @param $student
     * @param $studentResponses
     * @return array|string
     *
     */
    private function feedbackReport($student, $studentResponses)
    {
        $latestResponse = $studentResponses->sortByDesc('completed')->first();

        if (!$latestResponse) {
            return "{$student['firstName']} {$student['lastName']} has no completed assessments.";
        }

        $report = [];
        $report[] = "{$student->getFullName()} recently completed Numeracy assessment on " . Carbon::createFromFormat('d/m/Y H:i:s', $latestResponse->completed)->format('jS F Y h:i A');
        $report[] = "He got {$latestResponse->results['rawScore']} questions right out of {$this->totalQuestions}. Feedback for wrong answers given below";
        $report[] = "";
        foreach ($latestResponse->responses as $response) {
            $question = $this->questions->firstWhere('id', $response['questionId']);
            if ($question && $response['response'] !== $question->config['key']) {
                $wrongOption = collect($question->config['options'])->firstWhere('id', $response['response']);
                $correctOption = collect($question->config['options'])->firstWhere('id', $question->config['key']);

                $report[] = "Question: {$question->stem}";
                $report[] = "Your answer: {$wrongOption['label']} with value {$wrongOption['value']}";
                $report[] = "Right answer: {$correctOption['label']} with value {$correctOption['value']}";
                $report[] = "Hint: {$question->config['hint']}";
                $report[] = "";
            }
        }

        return $report;
    }

}
