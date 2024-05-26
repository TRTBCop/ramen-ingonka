<?php

namespace App\Services;

use App\Http\Resources\ListCollection;
use App\Models\Student;
use App\Models\TrainingResult;
use Illuminate\Support\Collection;
use App\Services\CurriculumService;

class IncorrectNoteService
{
    private Student $student;

    /**
     * 학생의 훈련 결과
     * @var Collection|TrainingResult[]
     */
    public Collection $trainingResults;


    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    /**
     * 페이지 네이션된 오답 노트 목록
     */
    public function getPaginatedFilteredTrainingResults()
    {
        request()->filters = request()->filters ?? [];
        $filters = request()->filters;
        // 완료한 훈련만 노출
        $filters['is_completed'] = true;


        // stage 필터가 없으면 유형, 서술형만 노출 -- 개념훈련은 오답노트가 없음
        if (!isset(request()->filters['stage']) || request()->filters['stage'] == 0) {
            $filters['stage'] = [2, 3];
        }

        // 학년 학기 커리큘럼 필터가 없으면 현재 설정된 학년 학기가 기본 값
        if (!isset(request()->filters['parent_curriculum_id'])) {
            $filters['parent_curriculum_id'] = CurriculumService::getRootCategoryId($this->student->grade, $this->student->term);
        }

        request()->filters = $filters;

        $query = TrainingResult::listFilter();
        $collection = $query->paginate(10);
        return ListCollection::collection($collection);
    }

    /**
     * 오답노트 학년 학기 필터 옵션
     */
    public function getFilterOptionsByGradeTerm()
    {
        $gradeTermOptions = [];

        $gradeTerms = config('dailykor.training.curricula_id_by_grade_term');
        $gradeTitleConfig = config('dailykor.dbcode.students.grade');

        foreach ($gradeTerms as $grade => $terms) {
            foreach ($terms as $term => $curriculumId) {
                $gradeTermOptions[$curriculumId] = $gradeTitleConfig[$grade].'-'.$term;
            }
        }

        return $gradeTermOptions;
    }

    /**
     * 오답노트 훈련 유형 필터 옵션
     */
    public function getFilterOptionsByStage()
    {
        return [0 => '훈련 전체', 2 => '유형 훈련', 3 => '서술형 훈련'];
    }

    /**
     * 학습 결과에서 틀린
     */
    public function getIncorrectQuestionByTrainingResult(TrainingResult $trainingResult)
    {
        $trainingResult->load('steps.questions.question');

        return array_map(function ($stepResult) {
            $filtered = array_filter($stepResult['questions'], function ($questionResult) {
                return $questionResult['correct_percent'] !== 100;
            });

            return array_map(function ($questionResult) {
                $questionResult['question'] = QuestionService::questionToMathmlToImage($questionResult['question']);

                return $questionResult;
            }, $filtered);
        }, $trainingResult->steps->toArray());
    }
}
