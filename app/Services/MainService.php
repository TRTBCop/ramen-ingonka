<?php

namespace App\Services;

use App\Enums\CurriculumElementEnum;
use App\Models\Curriculum;
use App\Models\Student;
use App\Models\Training;
use Illuminate\Support\Collection;

class MainService
{
    private Student $student;

    /**
     * 학년 학기 대단원 커리큘럼 트리구조
     * 마지막 노드일 경우 학습과 완료한 학습 기록 load 되어있음.
     * @var Collection|Curriculum[]
     */
    private Collection $currentGradeTermCurriclumsByTree;

    public function __construct(Student $student)
    {
        $this->student = $student;

        // 학생의 학년학기의 root 커리큘럼 id를 찾는다
        $gradeTermCurriculumId = CurriculumService::getRootCategoryId($student->grade, $student->term);

        // 해당 학년 학기의 대단원을 불러오고 하위 노드들의 학습, 완료한 학습
        $this->currentGradeTermCurriclumsByTree = Curriculum::with(['trainings.curriculum', 'trainings.results' => function ($query) {
            $query->where('student_id', $this->student->id)->whereNotNull('completed_at');
        }])->defaultOrder()
        ->descendantsOf($gradeTermCurriculumId)->toTree();
    }



    public function getcurriculaMap()
    {
        $configCurriculaDdByGradeTerm = config('dailykor.training.curricula_id_by_grade_term');
        $curricula = Curriculum::with(['trainings.curriculum', 'trainings.results' => function ($query) {
            $query->where('student_id', $this->student->id)->whereNotNull('completed_at');
        }])->defaultOrder()
            ->descendantsOf(Curriculum::MATH_ROOT_ID)->toTree();

        $curriculaMap = [
            'chapter' => [],
            'element' => [],
        ];

        $curriculaElement = [];
        $curricula->each(function ($curriculum) use (&$curriculaElement) {
            foreach ($curriculum->children as $children) {
                $lastNode = CurriculumService::getLastNode($children);
                if ($lastNode->element->value) {
                    $curriculaElement[$lastNode->element->value][$curriculum->id][] = $children;
                }
            }
        });

        // 단원별 [학년][학기] = $curricula
        // 속성별[학년][학기][속성]= $curricula
        foreach ($configCurriculaDdByGradeTerm as $grade => $value) {
            $curriculumElementEnum = CurriculumService::isLower($grade) ? CurriculumElementEnum::lower() : CurriculumElementEnum::upper();

            foreach ($value as $term => $curriculumId) {
                $curriculumByGradeTerm = $curricula->find($curriculumId);
                $curriculaMap['chapter'][$grade][$term] = ['curriculums' => $curriculumByGradeTerm->children, 'progress' => $this->getCurriculumsProgress($curriculumByGradeTerm)];

                foreach ($curriculumElementEnum as $element) {
                    if (!isset($curriculaMap['element'][$element->value]['progress'])) {
                        $curriculaMap['element'][$element->value]['progress'] = 0;
                    }

                    if (!isset($curriculaMap['element'][$element->value]['chapter'])) {
                        $curriculaMap['element'][$element->value]['chapter'] = [];
                    }

                    if (!isset($curriculaMap['element'][$element->value]['chapter'][$grade])) {
                        $curriculaMap['element'][$element->value]['chapter'][$grade] = [];
                    }


                    if (isset($curriculaElement[$element->value][$curriculumId])) {
                        $curriculum = $curriculaElement[$element->value][$curriculumId];
                        $curriculaMap['element'][$element->value]['chapter'][$grade][$term] = ['curriculums' => $curriculum, 'progress' => $this->getCurriculumsProgress($curriculumByGradeTerm)];
                        $chapter = $curriculaMap['element'][$element->value]['chapter'];
                        $count = 0;
                        $sum = 0;
                        foreach ($chapter as $gradeTerms) {
                            foreach ($gradeTerms as $item) {
                                $count++;
                                $sum += $item['progress'];
                            }
                        }
                        if ($sum) {
                            $curriculaMap['element'][$element->value]['progress'] = round($sum / $count);
                        }
                    }
                }
            }
        }
        return $curriculaMap;
    }


    /**
     * 학생의 설정된 학년 학기에서 진행해야 할 다음 훈련을 반환
     */
    public function getNextTraining(): Training | null
    {
        $nextTraining = null;

        foreach ($this->currentGradeTermCurriclumsByTree as $curriculum) {
            $nextTraining = $this->findNotCompleteTrainingByCurriculum($curriculum);

            if (isset($nextTraining)) {
                break;
            }
        }

        return $nextTraining;
    }

    /**
     * 학갱의 설정된 학년/학기의 대단원별 진행률을 반환하는 메서드
     */
    public function getCurriculumsProgressByGradeTerm(): array
    {
        // 6단원 고정
        $progress = array_fill(0, 6, 100);

        foreach ($this->currentGradeTermCurriclumsByTree as $key => $curriculum) {
            $progress[$key] = $this->getCurriculumsProgress($curriculum);
        }

        return $progress;
    }


    /**
     * 컬리큘럼에서 완료하지 않은 학습을 찾아 반환 함.
     * 마지막 노드가 아닐 경우 재귀.
     */
    public function findNotCompleteTrainingByCurriculum(Curriculum $curriculum): Training | null
    {
        $notCompleteTraining = null;
        // 마지막 노드일 경우
        if (CurriculumService::getIsLastNode($curriculum)) {
            foreach ($curriculum->trainings as $training) {
                if (count($training->results) === 0) {
                    $notCompleteTraining = $training;
                    break;
                }
            }

            // 무료체험 유저는 첫번째 학습만 가능하게
            if ($this->student->isFree() && !isset($notCompleteTraining)) {
                $notCompleteTraining = $curriculum->trainings->where('stage', 3)->first();
            }
        } else {
            foreach ($curriculum->children as $childrenCurriculum) {
                $notCompleteTraining = $this->findNotCompleteTrainingByCurriculum($childrenCurriculum);
                if (isset($notCompleteTraining)) {
                    break;
                }
            }
        }

        return $notCompleteTraining;
    }


    /**
     * 특정 컬리큘럼의 진행률을 반환함.
     * 마지막 노드가 아닐 경우 재귀.
     */
    public function getCurriculumsProgress(Curriculum $curriculum): int
    {
        $progress = 0;
        if (CurriculumService::getIsLastNode($curriculum)) {
            $isCompletedCurriculum = $curriculum->trainings->reduce(function ($carry, $training) {
                // 현재 요소가 조건을 충족하는지 확인하고 결과 반환
                return $carry && !$training->results->isEmpty();
            }, true);

            if ($isCompletedCurriculum) {
                $progress = 100;
            }
        } else {
            $sumProgress = 0;
            foreach ($curriculum->children as $childrenCurriculum) {
                $sumProgress += $this->getCurriculumsProgress($childrenCurriculum);
            }
            if ($sumProgress) {
                $progress = round($sumProgress / count($curriculum->children));
            }
        }

        return $progress;
    }
}
