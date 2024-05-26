<?php

namespace App\Services;

use App\Models\Curriculum;
use App\Models\Student;
use App\Models\TrainingResult;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Integer;

class HistoryService
{
    private Student $student;
    private Collection $trainingResult;
    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    /**
     * 학습 기록 가져오기
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getItems(string $startDate, string $endDate): array
    {
        $studentCurricula = $this->student->curricula;
        $trainingResults = $this->student->training_results;

        $fourWeeks = [];
        $startWeek = 0;
        $endWeek = 6;

        while ($endWeek <= 28) {
            // 최근 4주간 성취도
            $weeklyBetweenDate = [
                now()->subDays($endWeek)->startOfDay(),
                now()->subDays($startWeek)->endOfDay(),
            ];

            $completedStudentCurricula = $studentCurricula->whereBetween('completed_at', $weeklyBetweenDate);
            $completedTrainingResults = $trainingResults->whereBetween('completed_at', $weeklyBetweenDate);

            $weekData = $this->weeklyAchievement($completedStudentCurricula, $completedTrainingResults); // 주간 성취도
            $fourWeeks[] = $weekData;

            $startWeek = $endWeek + 1;
            $endWeek = $endWeek + 7;
        }

        {
            // 학습 내용
            $selectedBetweenDate = [
                now()->createFromFormat('Y-m-d', $endDate)->startOfDay(),
                now()->createFromFormat('Y-m-d', $startDate)->endOfDay(),
            ];

            $curriculumIds = [];
            $tempResults = $trainingResults->whereBetween('updated_at', $selectedBetweenDate);
            $tempResults->each(function (TrainingResult $result) use (&$curriculumIds) {
                $curriculumIds[] = $result->curriculum_id;
            });
            $selectedStudentCurricula = $studentCurricula->whereIn('id', $curriculumIds);
            $selectedTrainingResults = $trainingResults->sortBy('stage');

            $histories = $this->trainingDetail($selectedStudentCurricula, $selectedTrainingResults); // 학습 내용 데이터
        }

        return [
            'week' => $fourWeeks[0], // 이번 주 성취도
            'four_weeks' => $fourWeeks, // 최근 4주간 성취도
            'trainings' => $histories, // 학습 내용
        ];
    }

    /**
     * 정답률 계산
     *
     * @param int $correct
     * @param int $questions
     * @return int|null
     */
    private function getRate(int $correct, int $questions): int|null
    {
        return $questions ? round($correct / $questions * 100) : null;
    }

    /**
     * 주간 성취도
     *
     * @param Collection $studentCurricula
     * @param Collection $trainingResults
     * @return array|null
     */
    private function weeklyAchievement(Collection $studentCurricula, Collection $trainingResults): array|null
    {
        $totalQuestions = $trainingResults->sum('total_questions');
        $totalCorrect = $trainingResults->sum('total_correct');
        $rate = $this->getRate($totalCorrect, $totalQuestions);

        if ($rate) {
            $data = [
                'total_subsection_completed' => $studentCurricula->count(),
                'total_training_completed' => $trainingResults->count(),
                'total_questions' => $totalQuestions,
                'total_correct' => $totalCorrect,
                'rate' => $rate.'%',
            ];
        } else {
            $data = null;
        }

        return $data;
    }

    /**
     * 학습 내용
     *
     * @param Collection $studentCurricula
     * @param Collection $trainingResults
     * @return array
     */
    private function trainingDetail(Collection $studentCurricula, Collection $trainingResults): array
    {
        $stageCode = dbcode('trainings.stage'); // stage 코드값 가져오기
        $arrData = [];

        $studentCurricula->each(function (Curriculum $curriculum) use ($trainingResults, $stageCode, &$arrData) {
            $curriculum->load(['ancestors' => function ($query) {
                $query->hasParent();
            }]);
            $subjects = Arr::pluck($curriculum->ancestors->toArray(), 'name');
            if (empty($subjects[2])) {
                $subjects[2] = null;
            }
            $subjects[3] = $curriculum->name;

            $results = $trainingResults->where('curriculum_id', $curriculum->id)->toArray();
            $stageData = [[], [], []]; // 학습 내역이 담길 빈 배열
            $completedCurriculum = !!$curriculum->pivot->completed_at; // 소단원 완료 여부
            $totalQuestions = 0;
            $totalCorrect = 0;

            foreach ($stageData as $trainingKey => $training) {
                $result = $results[$trainingKey] ?? [
                    'stage' => $trainingKey + 1,
                    'total_questions' => 0,
                    'total_correct' => 0,
                    'completed_at' => null,
                ]; // 학습 데이터가 있으면 그대로 할당, 없으면 빈 데이터 할당
                $type = 0; // 0:학습 전, 1:학습 중, 2:학습완료

                if (isset($result['id'])) {
                    $type = $result['completed_at'] ? 2 : 1;
                }

                if ($completedCurriculum) {
                    // 완료된 경우만 전체 문제개수, 정답개수 저장
                    $totalQuestions += $result['total_questions'];
                    $totalCorrect += $result['total_correct'];
                }

                $stageData[$trainingKey] = [
                    'type' => $type,
                    'stage_name' => $stageCode[$result['stage']],
                    'completed_at' => $result['completed_at'],
                    'total' => [
                        'questions' => $totalQuestions ?? null,
                        'rate' => $this->getRate($result['total_correct'], $result['total_questions']),
                    ],
                ];
            }

            $arrData[] = [
                'subjects' => $subjects,
                'total' => [
                    'questions' => $totalQuestions, // 전체 문제개수
                    'correct' => $totalCorrect, // 전체 정답개수
                    'rate' => $this->getRate($totalCorrect, $totalQuestions), // 정답률
                ],
                'stages' => $stageData,
            ];
        });

        return $arrData;
    }
}