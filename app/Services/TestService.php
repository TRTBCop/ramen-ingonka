<?php

namespace App\Services;

use App\Enums\CurriculumElementEnum;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Database\Eloquent\Collection;

class TestService
{
    public const TIME_LIMIT = 60 * 40; // 40분
    protected CurriculumService $curriculumService;

    public function __construct(CurriculumService $curriculumService)
    {
        $this->curriculumService = $curriculumService;
    }

    /**
     * 해당 문제가 추가문제인지 여부 반환하는 메서드
     */
    public function getIsExtend(Test $test, Question $question)
    {
        $foundItem = null;
        foreach ($test->contents['questions'] as $key => $item) {
            if ($item['id'] == $question->id) {
                $foundItem = $item;
                break;
            }
        }

        return (isset($foundItem) && $foundItem['is_extend']);
    }

    /**
     * 기본 문제
     * @param Collection $questions
     * @return Collection
     */
    public function getBaiscQuestions(Test $test): Collection
    {
        $questions = $test->questions()->with('curriculum')->whereNotNull('published_at')->get();

        return $questions->filter(function (Question $question) use ($test) {
            return !self::getIsExtend($test, $question);
        });
    }

    /**
     * @param Collection $questions
     * @param array $extra
     * @return Collection
     */
    public function getExtendQuestions(Test $test, array $extra): Collection
    {
        $questions = $test->questions()->with('curriculum')->whereNotNull('published_at')->get();

        $extra = $this->getExtra($extra);

        // 완료한 문제
        $colCompletedQuestion = collect($extra['questions']);

        // 기본문제중 틀린문제
        $wrongQuestions = $colCompletedQuestion->filter(function ($question) {
            return !$question['is_extend'] && $question['question_count'] > $question['correct_count'];
        });

        // 틀린 문제의 내용영역을 가저온다
        $wrongElement = [];
        foreach ($wrongQuestions as $item) {
            $wrongElement[$item['element']] = true;
        }

        // 틀린문제의 내용역역과 같은 확장문제를 모두 가저온다
        return $questions->filter(function (Question $question) use ($wrongElement, $test) {
            return self::getIsExtend($test, $question) && in_array($question->curriculum->element->value, array_keys($wrongElement));
        });
    }

    public function getPoint(array $extra): float
    {
        $colExtra = collect($extra['questions'] ?? []);
        // 기본문제
        $basicQuestion = $colExtra->filter(function ($question) {
            return !$question['is_extend'];
        });

        return round($basicQuestion->sum('correct_count') / $basicQuestion->sum('question_count') * 100);
    }

    /**
     * @param array $extra
     * @return array
     */
    public function getExtra(array $extra): array
    {
        if (!$extra) {
            $extra = [
                'questions' => []
            ];
        }
        return $extra;
    }

    /**
     * 리포트 생성
     *
     * @param Collection $questions
     * @param array $extra
     * @param array $option
     * @return array
     */
    public function setReport(Test $test, array $extra, array $option): array
    {
        $questions = $test->questions()->with('curriculum')->whereNotNull('published_at')->get();

        $name = $option['name'] ?? '';
        $testTitle = $option['test_title'] ?? '';
        $seconds = $extra['timer'] ?? 0; // 기본문제 평가시간
        $minutes = intdiv($seconds, 60);
        $remainingSeconds = $seconds % 60;
        $extraQuestions = collect($extra['questions']);
        $extraQuestionKeys = $extraQuestions->keys();

        // 단원별 평가
        $curriculumRate = [];

        $arrCurriculumId = $questions->pluck('curriculum.id')->toArray();
        $curricula = $arrCurriculumId ? $this->curriculumService->getChapters($arrCurriculumId) : null;

        $actionRate = []; // 행동영역
        $elementQuestion = [];
        $basicQuestions = [];
        $extendQuestions = [];
        $metaCognition = [
            'result' => [
                0 => [ // 알아요
                    0 => [
                        'question_count' => 0,
                        'correct_question_count' => 0,
                        'allotment' => 5.56,
                        'point' => 0,
                    ],
                    1 => [
                        'question_count' => 0,
                        'correct_question_count' => 0,
                        'allotment' => 0,
                        'point' => 0,
                    ],
                    2 => [
                        'question_count' => 0,
                        'correct_question_count' => 0,
                        'allotment' => 0,
                        'point' => 0,
                    ],
                ],
                1 => [ // 잘모르겠어요
                    0 => [
                        'question_count' => 0,
                        'correct_question_count' => 0,
                        'allotment' => 1.39,
                        'point' => 0,
                    ],
                    1 => [
                        'question_count' => 0,
                        'correct_question_count' => 0,
                        'allotment' => 2.78,
                        'point' => 0,
                    ],
                    2 => [
                        'question_count' => 0,
                        'correct_question_count' => 0,
                        'allotment' => 0,
                        'point' => 0,
                    ],
                ],
                2 => [ // 몰라요
                    0 => [
                        'question_count' => 0,
                        'correct_question_count' => 0,
                        'allotment' => 0,
                        'point' => 0,
                    ],
                    1 => [
                        'question_count' => 0,
                        'correct_question_count' => 0,
                        'allotment' => 0,
                        'point' => 0,
                    ],
                    2 => [
                        'question_count' => 0,
                        'correct_question_count' => 0,
                        'allotment' => 5.56,
                        'point' => 0,
                    ],
                ],
            ]
        ];
        foreach ($questions->whereIn('id', $extraQuestionKeys) as $question) {
            if (empty($extraQuestions[$question->id])) {
                continue;
            }

            $extraQuestion = $extraQuestions[$question->id];

            $point = (!empty($extraQuestion['question_count']) ? (int)round($extraQuestion['correct_count'] / $extraQuestion['question_count'] * 100) : 0);
            $resultIcon = $point == 100 ? 0 : ($point >= 70 ? 1 : 2);

            // 추가문제
            if (self::getIsExtend($test, $question)) {
                $extendQuestions[] = [
                    'point' => $point,
                    'result' => $resultIcon,
                    'element' => $question->curriculum->element,
                    'txt_element' => $question->curriculum->txt_element,
                ];
                continue;
            }


            // 메타인지
            // 정답예측도 0 확실해요, 정답일거같아요 1, 모르겠어요 2
            // 결과 0 동그라미, 1 세모, 2 엑스
            // $metaCognition[정답예측도][결과]
            $extraQuestionMetaCognition = $extraQuestion['meta_cognition'] ?? 0;

            $metaCognition['result'][$extraQuestionMetaCognition][$resultIcon]['question_count']++;
            if ($resultIcon === 0) {
                $metaCognition['result'][$extraQuestionMetaCognition][$resultIcon]['correct_question_count']++;
            }
            $metaCognition['result'][$extraQuestionMetaCognition][$resultIcon]['point'] = min($metaCognition['result'][$extraQuestionMetaCognition][$resultIcon]['question_count'] * $metaCognition['result'][$extraQuestionMetaCognition][$resultIcon]['allotment'], 100);

            // 대단원별
            $chapterId = $curricula->where('id', $question->curriculum->id)->first()->id ?? 0;
            $chapter = $curricula->where('id', $question->curriculum->id)->first()->name ?? '';
            if (empty($curriculumRate[$chapterId])) {
                $curriculumRate[$chapterId] = [
                    'element' => 0,
                    'txt_element' => '',
                    'question_count' => 0,
                    'correct_count' => 0
                ];
            }

            $curriculumRate[$chapterId]['name'] = $chapter;
            $curriculumRate[$chapterId]['element'] = $question->curriculum->element->value;
            $curriculumRate[$chapterId]['txt_element'] = $question->curriculum->txt_element;
            $curriculumRate[$chapterId]['question_count'] += $extraQuestion['question_count'] ?? 0;
            $curriculumRate[$chapterId]['correct_count'] += $extraQuestion['correct_count'] ?? 0;
            $curriculumRate[$chapterId]['rate'] = $point;

            // txt_action 추가
            $extraQuestion['answers'] = array_map(function ($item) {
                $item['txt_action'] = config('dailykor.test.questions.answers.action')[$item['action']] ?? '';
                return $item;
            }, $extraQuestion['answers'] ?? []);

            // 내용영역별
            $element = $question->curriculum->element->value;
            $elementQuestion[$element][] = $extraQuestion;
            $basicQuestions[] = [
                'curriculum_name' => $question->curriculum->name,
                'result' => $resultIcon,
                'rate' => $point,
                'txt_level' => $question->txt_level,
                'element' => $question->curriculum->element,
                'txt_element' => $question->curriculum->txt_element,
                'chapter' => $chapter,
                'answers' => $extraQuestion['answers'],
            ];

            if (empty($extraQuestion['answers']) || !is_array($extraQuestion['answers'])) {
                continue;
            }

            foreach ($extraQuestion['answers'] as $answers) {
                if (empty($actionRate[$answers['action']])) {
                    $actionRate[$answers['action']] = [
                        'txt_action' => config('dailykor.test.questions.answers.action')[$answers['action']] ?? '',
                        'question_count' => 0,
                        'correct_count' => 0,
                        'rate' => 0
                    ];
                }

                $actionRate[$answers['action']]['question_count']++;
                $actionRate[$answers['action']]['correct_count'] += $answers['correct'];
                $actionRate[$answers['action']]['rate'] = (int)round($actionRate[$answers['action']]['correct_count'] / $actionRate[$answers['action']]['question_count'] * 100);
            }
        } // end foreach Question

        $metaCognition['point'] = round(collect($metaCognition['result'])->flatten(1)->pluck('point')->sum());
        $metaCognition['area'] = $this->getArea($metaCognition['point']).$this->getArea($extra['point']);
        $metaCognition['area_review'] = sprintf($this->getAreaReview($metaCognition['area']), $name, $extra['point'], $metaCognition['point']);

        $extendPoint = count($extendQuestions) ? array_sum(array_column($extendQuestions, 'point')) / count($extendQuestions) : 0;

        $elementRate = []; // 내용영역별
        foreach ($elementQuestion as $element => $items) {
            $colItems = collect($items);
            $elementRate[$element]['name'] = CurriculumElementEnum::options()[$element] ?? '';
            $elementRate[$element]['question_count'] = $colItems->sum('question_count');
            $elementRate[$element]['correct_count'] = $colItems->sum('correct_count');
            $elementRate[$element]['rate'] = ($elementRate[$element]['question_count']) ? (int)round($elementRate[$element]['correct_count'] / $elementRate[$element]['question_count'] * 100) : 0;
        }

        $level = $this->getLevel($extra['point']);

        // 총평
        $review = $this->getReview($curriculumRate, [
            'name' => $name,
            'level' => $level,
            'point' => $extra['point'],
            'seconds' => $seconds,
            'test_title' => $testTitle
        ]);

        return [
            'test_title' => $testTitle, // 타이틀 (초등 x학년 x학기)
            'name' => $name, // 학생명
            'test_minute_second' => $minutes.'분 '.$remainingSeconds.'초', // 평가시간
            'point' => $extra['point'], // 점수
            'level' => $level, // 나의위치
            'question_count' => $extraQuestions->sum('question_count'), // 문제수
            'correct_count' => $extraQuestions->sum('correct_count'), // 정답수
            'curriculum_rate' => $curriculumRate, // 단원별
            'element_rate' => $elementRate, // 내용영역별
            'action_rate' => $actionRate, // 행동영역별
            'review' => $review, // 총평
            'basic_questions' => $basicQuestions, // 기본문제
            'extend' => [
                'point' => $extendPoint, // 개념확인성취도(확장 문제 총점)
                'questions' => $extendQuestions, // 확장문제
            ],
            'meta_cognition' => $metaCognition, // 메타인지
        ];
    }

    /**
     * 메타인지 신뢰도
     *
     * @param int $point
     * @return string
     */
    private function getArea(int $point): string
    {
        return match (true) {
            $point <= 59 => 'D',
            $point <= 79 => 'B',
            $point <= 99 => 'C',
            default => 'A'
        };
    }


    /**
     * 메타인지 신뢰도 리뷰
     *
     * @param string $area
     * @return string
     */
    private function getAreaReview(string $area): string
    {
        return match ($area) {
            'AA' => '%s 학생의 종합 성취도는 %.1f%%이며, 메타인지 신뢰도도 %.1f%%으로 AA구간에 해당합니다.
이 구간의 학생들은 또래에 비해 우수한 지적 수준에, 지식에 대한 확신감까지 갖고 있기 때문에 학습적으로 자신감이 넘칩니다. 자만하지 않고 해결과정에서 실수하는 부분이 없도록 주의할 필요가 있습니다.',
            'AB' => '%s 학생의 종합 성취도는 %.1f%%이며, 메타인지 신뢰도는 %.1f%%으로 AB구간에 해당합니다.
이 구간의 학생들은 자신의 학습 상태에 대한 인지가 정확하여 아는 것과 모르는 것에 대한 구별이 명확합니다. 헷갈리는 내용이더라도 모른다고 엄격하게 분류하기 때문에 완전한 이해를 지향한다고 볼 수 있습니다.',
            'AC' => '%s 학생의 종합 성취도는 %.1f%%이며, 메타인지 신뢰도는 %.1f%%으로 AC구간에 해당합니다.
이 구간의 학생들은 자신의 학습 상태에 대한 인지가 정확하여 아는 것과 모르는 것에 대한 구별이 명확합니다. 헷갈리는 내용이더라도 모른다고 엄격하게 분류하기 때문에 완전한 이해를 지향한다고 볼 수 있습니다.',
            'AD' => '%s 학생의 종합 성취도는 %.1f%%이며, 메타인지 신뢰도는 %.1f%%으로 AD구간에 해당합니다. 
이 구간의 학생들은 자신의 학습 상태에 대한 인지가 정확하여 아는 것과 모르는 것에 대한 구별이 명확합니다. 헷갈리는 내용이더라도 모른다고 엄격하게 분류하기 때문에 완전한 이해를 지향한다고 볼 수 있습니다.',
            'BA' => '%s 학생의 종합 성취도는 %.1f%%이며, 메타인지 신뢰도는 %.1f%%으로 BA구간에 해당합니다.
이 구간의 학생들은 문제 상황을 수학적으로 이해하고 해결하는 수리문해력을 갖추고 있습니다. 하지만 학습 상태에 대한 점검에는 일부 오류가 있다는 점을 간과해서는 안 됩니다.',
            'BB' => '%s 학생의 종합 성취도는 %.1f%%이며, 메타인지 신뢰도는 %.1f%%으로 BB구간에 해당합니다. 
이 구간의 학생들은 학습 상태에 대한 점검의 정확도가 양호한 편입니다. 이 구간의 학생들은 학습할 동기와 목표만 설정할 수 있다면 가시적인 성과를 낼 잠재력을 가지고 있습니다.',
            'BC' => '%s 학생의 종합 성취도는 %.1f%%이며, 메타인지 신뢰도는 %.1f%%으로 BC구간에 해당합니다. 
이 구간의 학생들은 학습 상태에 대한 점검의 정확도가 양호한 편입니다. 이 구간의 학생들은 학습할 동기와 목표만 설정할 수 있다면 가시적인 성과를 낼 잠재력을 가지고 있습니다. ',
            'BD' => '%s 학생의 종합 성취도는 %.1f%%이며, 메타인지 신뢰도는 %.1f%%으로 BD구간에 해당합니다.
이 구간의 학생들은 학습 상태에 대한 점검의 정확도가 양호한 편입니다. 이 구간의 학생들은 학습할 동기와 목표만 설정할 수 있다면 가시적인 성과를 낼 잠재력을 가지고 있습니다.',
            'CA' => '%s 학생의 종합 성취도는 %.1f%%, 메타인지 신뢰도는 %.1f%%으로 CA구간에 해당합니다.
이 구간의 학생들은 문제 상황을 수학적으로 이해하고 해결하는 수리문해력을 갖추고 있습니다. 정답이라는 확신 없이 정답을 고른 문항 또는 정답이라는 확신을 가지고 오답을 고른 문항이 다수 있습니다.',
            'CB' => '%s 학생의 종합 성취도는 %.1f%%이며, 메타인지 신뢰도는 %.1f%%으로 CB구간에 해당합니다.
이 구간의 학생들은 학습 상태에 대한 점검의 정확도가 낮은 편입니다. 정답이라는 확신 없이 정답을 고른 문항 또는 정답이라는 확신을 가지고 오답을 고른 문항이 다수 있습니다. ',
            'CC' => '%s 학생의 종합 성취도는 %.1f%%이며, 메타인지 신뢰도는 %.1f%%으로 CC구간에 해당합니다.
이 구간의 학생들은 학습 상태에 대한 점검의 정확도가 낮은 편입니다. 정답이라는 확신 없이 정답을 고른 문항 또는 정답이라는 확신을 가지고 오답을 고른 문항이 다수 있습니다. ',
            'CD' => '%s 학생의 종합 성취도는 %.1f%%이며, 메타인지 신뢰도는 %.1f%%으로 CD구간에 해당합니다.
이 구간의 학생들은 학습 상태에 대한 점검의 정확도가 낮은 편입니다. 정답이라는 확신 없이 정답을 고른 문항 또는 정답이라는 확신을 가지고 오답을 고른 문항이 다수 있습니다. ',
            'DA' => '%s 학생의 종합 성취도는 %.1f%%이며, 메타인지 신뢰도는 %.1f%%으로 DA구간에 해당합니다.
이 구간의 학생들은 문제 상황을 수학적으로 이해하고 해결하는 수리문해력을 갖추고 있습니다. 하지만 정답이라고 확신하지 못하고 정답을 맞힌 문항이 상당히 많습니다. 학습 상태가 불완전할 가능성도 있으며 자신감이 부족하여 자신의 학습 상태를 긍정적으로 평가하지 못할 가능성도 있습니다.',
            'DB' => '%s 학생의 종합 성취도는 %.1f%%이며, 메타인지 신뢰도는 %.1f%%으로 DB구간에 해당합니다.
이 구간의 학생들은 자신의 학습 상태를 정확하게 평가하고 점검하는 것에 익숙하지 않습니다. 학습 상태의 인지에 오류가 있을 가능성이 높습니다.',
            'DC' => '%s 학생의 종합 성취도는 %.1f%%이며, 메타인지 신뢰도는 %.1f%%으로 DC구간에 해당합니다.
이 구간의 학생들은 자신의 학습 상태를 정확하게 평가하고 점검하는 것에 익숙하지 않습니다. 학습 상태의 인지에 오류가 있을 가능성이 높습니다.',
            'DD' => '%s 학생의 종합 성취도는 %.1f%%이며, 메타인지 신뢰도는 %.1f%%으로 DD구간에 해당합니다. 
이 구간의 학생들은 자신의 학습 상태를 정확하게 평가하고 점검하는 것에 익숙하지 않습니다. 학습 상태의 인지에 오류가 있을 가능성이 높습니다.',
            default => '',
        };
    }

    /**
     * 진단평가 레벨 찾기
     *
     * @param int $point
     * @return int
     */
    private function getLevel(int $point): int
    {
        return match (true) {
            $point <= 50 => 1,
            $point <= 70 => 2,
            $point <= 85 => 3,
            $point <= 95 => 4,
            default => 5, // 96~100
        };
    }

    /**
     * 총평
     * @param array $curriculumRate
     * @param array $args
     * @return string
     */
    private function getReview(array $curriculumRate, array $args): string
    {
        $testTitle = $args['test_title'] ?? '';
        $level = $args['level'] ?? 5;
        $name = $args['name'] ?? '(학생명)';
        $seconds = $args['seconds'] ?? 0;
        $point = $args['point'] ?? 0;

        // 고정
        $review = $name.' 학생의 '.$testTitle.' 수리문해력 진단평가 결과 100점 만점 중 '.$point.'점으로 전체 5레벨 중 '.$level.'레벨에 위치해 있습니다. 해당 레벨의 학생들은 ';

        $review .= match ($level) {
            1 => '개념을 한 번 더 학습하는 것이 필요합니다. 학습 시 개념을 꼼꼼히 읽고 제대로 이해한 후 다음 학습으로 넘어가는 습관을 만들어 보세요. 또한 연산의 기본 법칙을 확인하고, 연산 훈련을 하는 것이 필요합니다.',
            2 => '문제에 대한 이해도를 높이기 위한 노력이 필요합니다. 문장을 읽고 난 후 이해한 내용을 다른 사람에게 설명하는 연습을 해보세요. 설명하기 어려운 내용을 스스로 탐색하고 학습해 보는 과정이 필요합니다. 또한, 문장 속에서 수학적인 상황을 읽고, 이를 수학적인 식으로 변환하여 생각하는 습관을 길러 보세요.',
            3 => '어떤 방법으로 문제를 해결해야 하는지에 대한 어려움이 있는 편입니다. 주어진 조건과 구하는 것이 무엇인지 문제를 풀기 전에 먼저 생각해 보세요. 문제에서 주어진 조건을 바탕으로 어떤 수학적 지식을 이용하여 생각해 보는 힘을 길러 보세요.',
            4 => '문제상황을 해결하기 위해 어떤 계획을 수립해야 하는지 알고 있습니다. 문제 해결 과정에서 답을 구하는 단계까지 집중력을 잃지 않도록 노력하세요.',
            5 => '문제를 해결하기 위한 계획을 잘 세우고 문해력도 우수한 편입니다. 구한 답이 문제 조건과 맞는지 확인해 보는 것도 좋은 습관입니다.',
        };

        $lackingCurriculum = [];
        foreach ($curriculumRate as $curriculum) {
            if ($curriculum['rate'] <= 70) { // 부족한 단원 70% 기준
                $lackingCurriculum[] = $curriculum['name'];
            }
        }

        // 부족한단원이 있는경우
        $review .= '<br><br>';
        if ($lackingCurriculum) {
            $review .= '단원별로 보았을 때 '.implode(',', $lackingCurriculum).' 단원이 부족한 편입니다. 수학은 각 단계가 서로 연결되어 있기 때문에 기초가 탄탄하지 않으면 다음 단계에서 어려움을 겪을 수 있습니다. 부족한 단원을 다시 한번 복습한 후 새로운 학습을 진행해 보세요.';
        } else {
            $review .= '단원별로 보았을 때 특별히 부족한 부분은 없지만 새로운 학습을 하면서 어려움이 있다면 같은 계통의 이전 단계의 학습을 복습해 보세요.';
        }


        // 평가시간에 따른 총평
        $review .= '<br><br>';
        if ($seconds > self::TIME_LIMIT) {
            $review .= $name.' 학생은 진단평가 진행 시 제한 시간을 초과하였습니다. 시간을 적절히 계획하여 제한된 시간 내에 문제를 풀 수 있도록 노력 바랍니다';
        } else {
            $review .= $name.' 학생은 진단평가 진행 시 제한 시간 내에 완료하였습니다. 학습을 할 때에도 시간을 적절히 계획하여 문제를 풀 수 있도록 노력 바랍니다.';
        }

        return $review;
    }
}
