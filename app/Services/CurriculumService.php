<?php

namespace App\Services;

use App\Enums\CurriculumElementEnum;
use App\Models\Curriculum;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CurriculumService
{
    /**
     */
    public function __construct()
    {
    }

    /**
     * 학년학기를 입력받아 부모 curriculum.id를 찾는다
     *
     * @param int $grade
     * @param int $term
     * @return int
     */
    public static function getRootCategoryId(int $grade, int $term): int
    {
        return config('dailykor.training.curricula_id_by_grade_term')[$grade][$term] ?? 0;
    }

    /**
     * 저학년(초등) 유무
     * @param int $grade
     * @return bool
     */
    public static function isLower(int $grade): bool
    {
        return in_array($grade, config('dailykor.training.grade_group.lower'));
    }

    /**
     *  커리큘럼 자식노드의 마지막 노드 리턴 (속성값을 찾기 위해)
     */
    public static function getLastNode(Curriculum $curriculum)
    {
        $lastNode = $curriculum;
        foreach ($curriculum->children as $childCurriculum) {
            if ($childCurriculum->children) {
                $lastNode = self::getLastNode($childCurriculum);
            }
        }

        return $lastNode;
    }

    /**
     * 컬리큘럼이 마지막 노드인지 여부 반환
     */
    public static function getIsLastNode(Curriculum $curriculum)
    {
        return count($curriculum->children) === 0;
    }

    /**
     * @param array $arrCurriculumId
     * @return Collection
     */
    public function getChapters(array $arrCurriculumId): Collection
    {
        return Curriculum::with(['ancestors' => function ($query) {
            $query->hasParent();
        }])->whereIn('id', $arrCurriculumId)->get();
    }


    public static function getCurriculumIdToName(): array
    {
        $curricula = Curriculum::with(['trainings', 'ancestors' => function ($query) {
            $query->hasParent();
        }])->listFilter()->whereIsLeaf()->get();

        $curriculumIdToName = [];
        foreach ($curricula as $curriculum) {
            $label = $curriculum->ancestors->count() ? implode(' > ', $curriculum->ancestors->pluck('name')->toArray()).' > ' : '';
            $label .= $curriculum->name;
            $curriculumIdToName[$curriculum->id] = [
                'name' => $label,
                'txt_element' => $curriculum->txt_element
            ];
        }

        return $curriculumIdToName;
    }

    /**
     * 해당 컬리 큘럼에서 진행한 모든 학습 결과 반환
     */
    public static function getUserTrainingResultsByCurriculum(Student $student, int $curriculumId)
    {
        $trainingResults = collect();

        $getSubCurriculumTrainingResults = function ($curriculumId) use (&$getSubCurriculumTrainingResults, $student, &$trainingResults) {
            $curriculum = Curriculum::find($curriculumId);

            if ($curriculum) {
                $currentTrainingResults = $curriculum->training_results()->with('training')->where('student_id', $student->id)->get();

                foreach ($curriculum->children as $childCurriculum) {
                    $getSubCurriculumTrainingResults($childCurriculum->id);
                }

                $trainingResults = $trainingResults->merge($currentTrainingResults);
            }
        };

        $getSubCurriculumTrainingResults($curriculumId);

        return $trainingResults;
    }

    /**
     * 해당 단원에서 아직 완료하지 않은 훈련을 찾아서 반환하는 메서드
     */
    public static function getFirstIncompleteStage(array $trainingResults, Curriculum $curriculum)
    {
        $stages = [
            1 => false,
            2 => false,
            3 => false,
        ];

        $filteredArray = array_filter($trainingResults, function ($trainingResult) use ($curriculum) {
            return $trainingResult['curriculum_id'] == $curriculum->id && isset($trainingResult['completed_at']);
        });

        foreach ($filteredArray as $trainingResult) {
            $stages[$trainingResult['training']['stage']] = true;
        }

        $allCompleted = array_reduce($stages, function ($carry, $item) {
            return $carry && $item;
        }, true);

        if ($allCompleted) {
            return 0;
        } else {
            $firstFalseKey = null;

            foreach ($stages as $key => $value) {
                if (!$value) {
                    $firstFalseKey = $key;
                    break;
                }
            }

            return $firstFalseKey;
        }
    }
}
