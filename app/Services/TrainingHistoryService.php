<?php

namespace App\Services;

use App\Models\Student;
use App\Models\TrainingResult;
use Carbon\Carbon;
use DateInterval;
use Illuminate\Support\Collection;

class TrainingHistoryService
{
    /**
     * 학생의 훈련 결과
     * @var Collection|TrainingResult[]
     */
    public Collection $trainingResults;

    /**
     * 조회 선택 날짜
     */
    public Carbon $startDate;


    /**
     * 조회 선택 날짜
     */
    public Carbon $endDate;


    public function __construct(Student $student, string $date = null)
    {
        if (isset($date)) {
            $this->endDate = Carbon::createFromFormat('Y-m-d', $date);
        } else {
            $this->endDate = Carbon::now();
        }

        $this->startDate = $this->endDate->copy()->sub(new DateInterval('P6D'));

        $this->trainingResults = $student->training_results->load(['training', 'curriculum.ancestors']);
    }


    /**
     * 4주간의 성취도
     */
    public function getAchievementOver4Weeks()
    {
        $achievementOver4Weeks = [];

        $endDate = $this->endDate->copy();
        $startDate = $this->endDate->copy()->sub(new DateInterval('P28D'));

        // 4주간 완료한 학습 기록
        $fourWeekAgoTrainingResults = $this->trainingResults->filter(function ($result) use ($startDate, $endDate) {
            $completedDate = Carbon::parse($result->completed_at)->startOfDay();
            $startDateWithoutTime = Carbon::parse($startDate)->startOfDay();
            $endDateWithoutTime = Carbon::parse($endDate)->endOfDay();

            return isset($result->completed_at) && $completedDate->gte($startDateWithoutTime) && $completedDate->lte($endDateWithoutTime);
        });

        $weeklyGroupedData = array_fill(0, 4, []);

        foreach ($fourWeekAgoTrainingResults as $result) {
            $resultDate = Carbon::parse($result->completed_at);
            $weekDifference = $resultDate->diffInWeeks($endDate);

            $weeklyGroupedData[$weekDifference][] = $result;
        }

        $achievementOver4Weeks = array_map(function (array $trainingResults) {
            $curriculumCount = 0; // 완료 소단원수
            $trainingCount = count($trainingResults); // 완료 훈련수
            $correctAnswers = 0; // 총 정답수
            $totalAnswers = 0; // 총 문제수

            $sumTrainingScore = 0; // 훈련 점수 합산 (점수 계산에 사용)
            $sumTrainingCorrectPercent = 0; // 훈련 정답룰 합산 (정답률 계산에 사용)
            $totalTimer = 0;

            // 커리큘럼 완료 여부를 추적하는 변수
            $curriculumCompletion = [];

            foreach ($trainingResults as $trainingResult) {
                $curriculumId = $trainingResult->curriculum_id;
                // 최초 학습 일경우
                if ($trainingResult->round === 0) {
                    $curriculumCompletion[$curriculumId][] = $trainingResult;
                    // 완료 훈련이 3개이면 소단원 완료
                    $isCurriculumComplete = count($curriculumCompletion[$curriculumId]) === 3;
                    if ($isCurriculumComplete) {
                        $curriculumCount++;
                    }
                }

                $sumTrainingScore += $trainingResult->score;
                $sumTrainingCorrectPercent += $trainingResult->correct_percent;
                $correctAnswers += $trainingResult->correct_answers;
                $totalAnswers += $trainingResult->total_answers;

                $totalTimer += $trainingResult->timer;
            }

            $score = 0;
            if ($sumTrainingScore > 0) {
                $score = round($sumTrainingScore / $trainingCount);
            }
            $correctPercent = 0;
            if ($sumTrainingCorrectPercent > 0) {
                $correctPercent = round($sumTrainingCorrectPercent / $trainingCount);
            }

            return [
                'curriculum_count' => $curriculumCount,
                'training_count' => $trainingCount,
                'score' =>  $score,
                'correct_percent' => $correctPercent,
                'correct_answers' => $correctAnswers,
                'total_answers' => $totalAnswers,
                'total_timer_minutes' => floor($totalTimer / 60)
            ];
        }, $weeklyGroupedData);

        return $achievementOver4Weeks;
    }

    /**
     * 1주 동안의 학습 기록
     */
    public function getThisWeekTrainingResults()
    {
        $thisWeekTrainingResults = $this->trainingResults->filter(function ($result) {
            $completedDate = Carbon::parse($result->completed_at)->startOfDay();
            $startDateWithoutTime = Carbon::parse($this->startDate)->startOfDay();
            $endDateWithoutTime = Carbon::parse($this->endDate)->endOfDay();

            return isset($result->completed_at) && $completedDate->gte($startDateWithoutTime) && $completedDate->lte($endDateWithoutTime);
        });

        return $thisWeekTrainingResults;
    }

    /**
     * 학습 내용별 학습 기록
     */
    public function getTrainingResultsByCurriculum()
    {
        $thisWeekTrainingResults = $this->getThisWeekTrainingResults();

        $trainingResultsByCurriculum = [];

        // 학습을 진행한 소단원의 id 목록
        $uniqueCurriculumIds = $thisWeekTrainingResults->pluck('curriculum_id')->unique()->values()->all();
        sort($uniqueCurriculumIds);

        foreach ($uniqueCurriculumIds as $curriculumId) {
            // 해당 curriculum에 해당하는 학습 결과 필터링
            $filteredResults = $this->trainingResults->filter(function ($result) use ($curriculumId) {
                return $result->curriculum->id === $curriculumId;
            });

            $trainingResultsByCurriculum[$curriculumId] = $filteredResults->sortBy('round')->groupBy('round')->map(function ($group) {
                return $group->sortBy('training.stage')->groupBy('training.stage')->map->first();
            })->toArray();
        }

        return $trainingResultsByCurriculum;
    }

    /**
     * 학습 일시별 학습 기록
     */
    public function getTrainingResultsByDate()
    {
        $thisWeekTrainingResults = $this->getThisWeekTrainingResults();

        $trainingResultsByDate = collect($thisWeekTrainingResults)->sortByDesc('completed_at')->groupBy(function ($result) {
            return $result->completed_at->format('Y.m.d');
        });

        return $trainingResultsByDate;
    }
}
