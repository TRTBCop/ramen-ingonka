@php
    $report = $test_result['extra']['report'];

    $grades = ['A', 'B', 'C', 'D'];

    $result_icon = ['fa-circle', 'fa-triangle', 'fa-x'];

    $element_color = [
        '0' => ['type' => 1, 'color' => '#008CFF', 'class' => 'is--primary'],
        '100' => ['type' => 1, 'color' => '#008CFF', 'class' => 'is--primary'],
        '200' => ['type' => 2, 'color' => '#FF525D', 'class' => 'is--danger'],
        '600' => ['type' => 2, 'color' => '#FF525D', 'class' => 'is--danger'],
        '300' => ['type' => 3, 'color' => '#05B64D', 'class' => 'is--success'],
        '700' => ['type' => 3, 'color' => '#05B64D', 'class' => 'is--success'],
        '400' => ['type' => 4, 'color' => '#7F6CF8', 'class' => 'is--purple'],
        '800' => ['type' => 4, 'color' => '#7F6CF8', 'class' => 'is--purple'],
        '500' => ['type' => 5, 'color' => '#FF9700', 'class' => 'is--orange'],
        '900' => ['type' => 5, 'color' => '#FF9700', 'class' => 'is--orange'],
    ];

    $action_description = ['주어진 문제와 관련된 수학적 개념을 파악하고 수학적 용어, 기호, 식, 그래프 표의 의미와 관련 성질을 문제에 적용하는 능력', '수와 식을 이해하고 연산의 기본 법칙이나 성질을 적용하여 계산할 수 있는 능력입니다.', '해결 방법을 알고 있지 않은 문제 상황에서 수학의 지식과 기능을 활용하여 최적의 해결 방안을 선택하여 주어진 문제를 해결하는 능력', '두 가지 이상의 수학적 개념의 관련성을 파악하고 종합하여 문제를 해결하는 능력으로 실생활 상황에서 이를 적용하고 문제를 해결하는 능력입니다.'];

    $meta_category = ['확실해요', '정답일 것 같아요', '모르겠어요'];
    $meta_description = [['정답이라고 확신하고 풀이 답안을 전부 맞힌 문제', '정답이라고 확신했으나 풀이 답안을 70% 이상 맞힌 문제', '정답이라고 확신했으나 풀이 답안을 70% 미만 맞힌 문제'], ['정답이라는 확신은 없으나 풀이 답안을 전부 맞힌 문제', '정답이라는 확신 없이 풀이 답안을 70% 이상 맞힌 문제', '정답이라는 확신 없이 풀이 답안을 70% 미만 맞힌 문제'], ['모르는 문제라고 판단했으나 풀이 답안을 전부 맞힌 문제', '모르는 문제라고 판단했으나 풀이 답안을 70% 이상 맞힌 문제', '모르는 문제라고 판단하고 풀이 답안을 70% 미만 맞힌 문제']];

    $extend_questions = array_map('array_values', array_chunk($report['extend']['questions'], 5));
@endphp

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>리딩수학 진단평가 보고서</title>

    <script data-search-pseudo-elements src="https://kit.fontawesome.com/bd8a238770.js" crossorigin="anonymous"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;700&family=Noto+Serif+KR:wght@400;700&display=swap"
        rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>

    @vite(['resources/app/report.ts'])

    <style>
        /** 그래프에는 전부 position: relative 먹이기 */
        .graph {
            position: relative;
        }

        /* 내용 영역별 원형 그래프 */
        .element_rate_graph {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 140px;
            height: 140px;
        }

        /* 행동 영역별 원형 그래프 */
        .action_rate_graph {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 95px;
            height: 95px;
        }


        /* 메타인지 원형 그래프 */
        .meta_graph {
            position: absolute;
            left: 50%;
            top: 60%;
            transform: translate(-50%, -50%);
            width: 150px;
            height: 150px;
        }


        /* 윤경님이 행동 영역별 레이더 그래프 작업 해주기 전에 임시 스타일 작업  */
        .radar_graph {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            height: 250px;
        }
    </style>
</head>

<body class="A4">

    <!-- [D] 인쇄하기 버튼-->
    <div class="only_screen">
        <button class="btn" onclick="window.print()">인쇄하기</button>
    </div>

    <div class="count">
        <section class="page cover">
            <p class="head">
                <span>리딩수학으로 1등 수학 시작하기!</span>
                <span>https://www.readingmath.co.kr/</span>
            </p>
            <h1 class="cover__title">
                <strong class="username">{{ $report['name'] }}</strong> 님의<br>
                <em class="is--brand">
                    <strong>수리문해력 종합진단평가</strong><br>보고서
                </em>
            </h1>
            <dl class="userinfo">
                <dt>학년</dt>
                <dd>{{ $report['test_title'] }}</dd>
            </dl>
            <dl class="userinfo">
                <dt>평가일</dt>
                <dd>{{ $test_result['completed_at']->format('Y-m-d') }}</dd>
            </dl>

            <div class="b_logo">

            </div>

            <div class="b_logo">
                <img class="lg__logo" src="{{ Vite::asset('resources/assets/img/math/logo_math_lg.png') }}"
                    alt="리딩수학">
                <img src="{{ Vite::asset('resources/assets/img/math/logo_vertical.svg') }}" alt="플러스마이너스곱하기나누기이미지">
            </div>
        </section>

        <!-- 수리문해력 종합 성취도 / 단원별 평가 결과 -->
        <section class="page">
            <dl class="userinfo userinfo--row mb-1">
                <dt class="blind">학생이름</dt>
                <dd class="username">{{ $report['name'] }}</dd>
                <dt>응시 학년</dt>
                <dd>{{ $report['test_title'] }}</dd>
                <dt>평가일</dt>
                <dd>{{ $test_result['completed_at']->format('Y.m.d') }}</dd>
                <dt>평가 시간</dt>
                <dd>{{ $report['test_minute_second'] }}<small>(제한시간 40분)</small></dd>
            </dl>

            <h2 class="page__title">수리문해력 종합 성취도</h2>
            <div class="box row mb-1">

                <div class="summary w50">
                    <dl class="column">
                        <dt>나의 점수</dt>
                        <dd class="is--brand"><strong>{{ $report['point'] }}</strong>점</dd>
                    </dl>
                    <dl class="column">
                        <dt>나의 위치</dt>
                        <dd class="is--brand"><strong>{{ $report['level'] }}</strong>레벨</dd>
                    </dl>
                    <dl class="column column--full">
                        <dt>풀이 답안 정답수</dt>
                        <dd><em class="is--brand"><strong>{{ $report['correct_count'] }}</strong>문제</em><span>/
                                {{ $report['question_count'] }}문제</span></dd>
                    </dl>
                </div>

                <ol class="score-levels w50">
                    <li class="on">
                        <i class="level0{{ $report['level'] }}">{{ $report['level'] }}</i>
                    </li>
                </ol>
            </div>

            <!-- [D]
            type1: blue
            type2: red
            type3: green
            type4: purple
            type5: orange
            -->
            <h2 class="page__title">단원별 평가 결과</h2>
            <div class="box mb-1">
                <ul class="gauges">
                    @foreach ($report['curriculum_rate'] as $curriculum)
                        <li>
                            <p>
                                <small
                                    class="badge--type{{ $element_color[$curriculum['element']]['type'] }}">{{ $curriculum['txt_element'] }}</small>
                                {{ $curriculum['name'] }}
                            </p>
                            <div class="gauge gauge--type{{ $element_color[$curriculum['element']]['type'] }}">
                                <span class="gauge__label">{{ $curriculum['rate'] }}%</span>
                                <progress class="gauge__value" max="100" value="{{ $curriculum['rate'] }}">
                                    {{ $curriculum['rate'] }}%
                                </progress>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="box--result">
                {!! $report['review'] !!}
            </div>
        </section>

        <!-- 내용 영역별 평가 결과 -->
        <section class="page">
            <h2 class="page__title">내용 영역별 평가 결과</h2>
            <div class="box mb-1">
                <ul class="graph_wrap">
                    @foreach ($report['element_rate'] as $key => $element)
                        <li>
                            <div class="graph">
                                <span>{{ $element['name'] }}</span>
                                <div class="element_rate_graph"></div>
                            </div>
                            <div class="graph__help">
                                <dl>
                                    <dt class="">정답</dt>
                                    <dd class="{{ $element_color[$key]['class'] }}">
                                        {{ $element['correct_count'] }}</dd>
                                </dl>
                                <span>/</span>
                                <dl>
                                    <dt class="">문제</dt>
                                    <dd class="is--gray">{{ $element['question_count'] }}</dd>
                                </dl>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <table class="table txt--center">
                <colgroup>
                    <col style="width: 10%">
                    <col style="width: 15%">
                    <col style="width: 45%">
                    <col style="width: 10%">
                    <col style="width: 10%">
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">번호</th>
                        <th scope="col">내용영역</th>
                        <th scope="col">단원</th>
                        <th scope="col">결과</th>
                        <th scope="col">난이도</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report['basic_questions'] as $question)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><small
                                    class="badge--type{{ $element_color[$question['element']]['type'] }}">{{ $question['txt_element'] }}</small>
                            </td>
                            <td class="txt">{{ $question['chapter'] }}</td>
                            <td>
                                <i class="far {{ $result_icon[$question['result']] }}"></i>
                            </td>
                            <td>{{ $question['txt_level'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="tip">
                <dl>
                    <dt><i class="far fa-circle">&#x20DD;</i></dt>
                    <dd>풀이 과정 전체 정답 /</dd>
                    <dt><i class="far fa-triangle">&#x20E4;</i></dt>
                    <dd>풀이 과정 70% 이상 정답 /</dd>
                    <dt><i class="far fa-x">&#x2715;</i></dt>
                    <dd>풀이 과정 70% 미만 정답</dd>
                </dl>
            </div>
        </section>

        <!-- 행동 영역별 평가 결과 -->
        <section class="page">
            <h2 class="page__title">행동 영역별 평가 결과 <small>- 전체</small></h2>

            <div class="box mb-1 p-1">
                <div class="graph__desc">
                    <div class="graph__box">
                        <strong class="title">{{ $report['action_rate'][1]['txt_action'] }}력<span
                                class="badge--white"><em>{{ $report['action_rate'][1]['rate'] }}%</em></span></strong>
                        <strong class="title">{{ $report['action_rate'][4]['txt_action'] }}력<span
                                class="badge--white"><em>{{ $report['action_rate'][4]['rate'] }}%</em></span></strong>
                        <strong class="title">{{ $report['action_rate'][3]['txt_action'] }}력<span
                                class="badge--white"><em>{{ $report['action_rate'][3]['rate'] }}%</em></span></strong>
                        <strong class="title">{{ $report['action_rate'][2]['txt_action'] }}력<span
                                class="badge--white"><em>{{ $report['action_rate'][2]['rate'] }}%</em></span></strong>
                        <div class="graph">
                            <div class="radar_graph"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="columns mb-1">
                @foreach (array_values($report['action_rate']) as $action)
                    <div class="column">
                        <div class="donut">
                            <div class="action_rate_graph"></div>
                        </div>
                        <div class="description">
                            <strong class="title">{{ $action['txt_action'] }}력 <span
                                    class="badge--white"><em>{{ $action['correct_count'] }}</em>/{{ $action['question_count'] }}</span></strong>
                            <p>
                                {{ $action_description[$loop->index] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="titles">
                <h2 class="page__title">행동 영역별 평가 결과 <small>- 문항별</small></h2>
                <div class="help">
                    <span><i class="on"></i>오답</span>
                    <span><i class="bg--gray"></i>정답</span>
                </div>
            </div>


            @foreach ($report['basic_questions'] as $question)
                <dl class="question_items">
                    <dt class="num">{{ $loop->iteration }}번</dt>
                    @foreach ($question['answers'] as $answer)
                        <dd class="{{ $answer['correct'] ? '' : 'wrong' }}">
                            <em>{{ $loop->iteration }}</em>
                            <span>{{ $answer['txt_action'] }}</span>
                        </dd>
                    @endforeach
                </dl>
                @if ($loop->index === 4)
                    <hr class="hr">
                @endif
            @endforeach
        </section>

        <!-- 메타인지 신뢰도 평가 결과 / 추가 문제 평가 결과 -->
        <section class="page">
            <h2 class="page__title">메타인지 신뢰도 평가 결과</h2>
            <div class="row mb-1">
                <div class="box w40 graph__meta">
                    <div class="graph">
                        <span>메타인지 신뢰도</span>
                        <div class="meta_graph meta_meta_graph"> </div>
                    </div>
                    <div class="graph">
                        <span>종합 성취도</span>
                        <div class="meta_graph meta_total_graph"> </div>
                    </div>
                </div>
                <div class="meta">
                    <div>
                        <div class="score--vertical">
                            <span><small>100</small></span>
                            <span><small>~99</small></span>
                            <span><small>~79</small></span>
                            <span><small>~59</small></span>
                        </div>
                        <div class="w100">
                            <ul class="score">
                                @foreach ($grades as $meta_grade)
                                    @foreach (array_reverse($grades) as $total_grade)
                                        <li
                                            class="{{ $report['meta_cognition']['area'] === $meta_grade . $total_grade ? 'on' : '' }}">
                                            {{ $meta_grade . $total_grade }}
                                        </li>
                                    @endforeach
                                @endforeach

                            </ul>
                            <div class="score--horizontal">
                                <span>~59</span>
                                <span>~79</span>
                                <span>~99</span>
                                <span>100</span>
                            </div>
                        </div>
                    </div>
                    <div class="help">
                        <span><i class="bg--gray"></i>메타인지 신뢰도 점수(세로)</span>
                        <span><i class=""></i>종합 성취도 점수(가로)</span>
                        <span><i class="on"></i>나의 위치</span>
                    </div>
                </div>

            </div>

            <table class="table txt--center table__meta mb-1">
                <colgroup>
                    <col style="width:96px">
                    <col style="width:96px">
                    <col style="width:96px">
                    <col style="width:60%">
                </colgroup>
                <thead>
                    <tr>
                        <th>정답예측도</th>
                        <th>결과</th>
                        <th>해당문제수</th>
                        <th>설명</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report['meta_cognition']['result'] as $row_data)
                        @foreach ($row_data as $data)
                            <tr>
                                @if ($loop->first)
                                    <td rowspan="3">
                                        {{ $meta_category[$loop->parent->index] }}
                                    </td>
                                @endif
                                <td><i class="far {{ $result_icon[$loop->index] }}"></i></td>
                                <td>{{ $data['question_count'] }} </td>
                                <td class="txt">
                                    {{ $meta_description[$loop->parent->index][$loop->index] }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            <div class="box--result mb-1">
                {!! $report['meta_cognition']['area_review'] !!}
            </div>

            @if (!empty($extend_questions))
                <h2 class="page__title">추가 문제 평가 결과</h2>
                <div class="row">
                    <div class="box w30 graph__meta">
                        <div class="graph">
                            <span>개념 확인 성취도</span>
                            <div class="meta_graph extend_questions_graph"> </div>
                        </div>
                    </div>
                    <div class="w70 table__add">
                        @foreach ($extend_questions as $questions)
                            <table class="table mb-1">
                                <colgroup>
                                    <col style="width: 73px">
                                    <col style="width: 84px">
                                    <col style="width: 84px">
                                    <col style="width: 84px">
                                    <col style="width: 84px">
                                    <col style="width: 84px">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>번호</th>
                                        @foreach ($questions as $data)
                                            <th>{{ $loop->iteration }} </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>결과</th>
                                        @foreach ($questions as $data)
                                            <td><i class="far {{ $result_icon[$data['result']] }}"></i></td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th>내용 영역</th>
                                        @foreach ($questions as $data)
                                            <td>
                                                <small
                                                    class="badge--type{{ $element_color[$data['element']]['type'] }}">
                                                    {{ $data['txt_element'] }}</small>
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>

        <!-- 내용영역별/행동영역별 평가 설명 -->
        <section class=" page">
            <h2 class="page__title">내용 영역별 평가 설명</h2>
            <table class="table mb-1">
                <colgroup>
                    <col style="width: 15%">
                    <col style="width: 15%">
                    <col style="width: 70%">
                </colgroup>
                <tbody>
                    <tr>
                        <th rowspan="5">초등</th>
                        <th>수와 연산</th>
                        <td class="txt">
                            수는 사물의 개수와 양을 나타내기 위해 발생하며 자연수, 분수, 소수가 사용됩니다. 자연수에 대한 사칙계산이
                            정의되고, 이는 분수와 소수의 사칙계산을 할 수
                            있습니다.
                        </td>
                    </tr>
                    <tr>
                        <th>도형</th>
                        <td class="txt">주변의 모양을 여러 가지 평면도형 및 입체도형으로 범주화되고, 각각의 도형과 입체도형이 갖는
                            고유한 성질을 알 수 있습니다.</td>
                    </tr>
                    <tr>
                        <th>측정</th>
                        <td class="txt">생활 주변에는 시간, 길이, 들이, 무게, 각도, 넓이, 부피 등 다양한 속성이 존재하며
                            단위를 이용하여 양을 수치화할 수 있습니다. 어림을
                            통해 양을 단순화하여 표현할 수 있습니다.</td>
                    </tr>
                    <tr>
                        <th>규칙성</th>
                        <td class="txt">생활 주변의 여러 현상을 탐구할 수 있습니다.
                        </td>
                    </tr>
                    <tr>
                        <th>자료와 가능성</th>
                        <td class="txt">자료의 수집, 분류, 정리, 해석을 통해 통계의 기초를 이해할 수 있습니다. 가능성을
                            수치화하는 경험을 통해 확률의 기초를 이해할 수 있습니다.
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table  mb-1">
                <colgroup>
                    <col style="width: 15%">
                    <col style="width: 15%">
                    <col style="width: 70%">
                </colgroup>
                <tbody>
                    <tr>
                        <th rowspan="5">중등</th>
                        <th>수와 연산</th>
                        <td class="txt">
                            수는 방정식의 해의 존재를 보장하기 위해 정수, 유리수, 실수 등으로 확장되고, 각각의 수체계에서 사칙계산이
                            정의되고 연산의 성질이 일관되게 성립한다. 수는 수학에서
                            다루는 가장 기본적인 개념으로, 실생활뿐 아니라 타 교과나 수학의 다른 영역을 학습하는 데 필수적이다.
                        </td>
                    </tr>
                    <tr>
                        <th>문자와 식</th>
                        <td class="txt">
                            문자를 통해 수량 사이의 관계를 일반화함으로써 산술에서 대수로 이행하며, 수에 대한 사칙연산과 소인수분해는
                            다항식으로 확장되어 적용된다. 또한 방정식과 부등식은 양
                            사이의 관계를 나타내며, 적절한 절차를 따라 이를 만족시키는 해를 구할 수 있다.
                        </td>
                    </tr>
                    <tr>
                        <th>함수</th>
                        <td class="txt">
                            변화하는 양 사이의 관계를 나타내는 함수는 대응과 종속의 의미를 포함하며, 그래프는 함수를 시각적으로 표현하는
                            도구이다. 함수는 다양한 변화 현상 속의 수학적 관계를
                            이해하고 표현함으로써 여러 가지 문제를 해결하는 데 도움이 된다.
                        </td>
                    </tr>
                    <tr>
                        <th>기하</th>
                        <td class="txt">
                            주변의 형태는 여러 가지 평면도형이나 입체도형으로 범주화 되고, 각각의 평면도형이나 입체도형은 고유한 성질을
                            갖습니다. 도형의 성질을 정당화하는 과정에서 요구되는
                            연역적 추론은 수학적 소양을 기르는 데 도움이 됩니다.
                        </td>
                    </tr>
                    <tr>
                        <th>확률과 통계
                        </th>
                        <td class="txt">
                            사건이 일어날 가능성을 수치화한 확률, 그리고 자료를 수집, 정리, 해석하는 통계는 현대 정보화 사회의 불확실성을
                            이해하는 중요한 도구입니다. 다양한 자료를 수집,
                            정리, 해석하고, 확률을 이해함으로써, 미래를 예측하고 합리적인 의사 결정을 하는 민주 시민으로서의 기본 소양을
                            기를 수 있습니다.
                        </td>
                    </tr>
                </tbody>
            </table>

            <h2 class="page__title">행동 영역별 평가 설명</h2>
            <table class="table">
                <colgroup>
                    <col style="width: 20%">
                    <col style="width: 70%">
                </colgroup>
                <tbody>
                    <tr>
                        <th>문해력</th>
                        <td class="txt">주어진 문제와 관련된 수학적 개념을 파악하고 수학적 용어, 기호, 식, 그래프, 표의 의미과
                            관련성질을 문제에 적용하는 능력</td>
                    </tr>
                    <tr>
                        <th>계산력</th>
                        <td class="txt">수와 식을 이해하고 연산의 기본 법칙이나 성질을 적용하여 계산할 수 있는 능력</td>
                    </tr>
                    <tr>
                        <th>추론력</th>
                        <td class="txt">해결 방법을 알고 있지 않은 문제 상황에서 수학의 지식과 기능을 활용하여 전략을 탐색하고
                            해결의 핵심 원리를 발견하며 최적의 해결 방안을
                            선택하여 주어진 문제를 해결하는 능력</td>
                    </tr>
                    <tr>
                        <th>문제 해결력</th>
                        <td class="txt">두 가지 이상의 수학적 개념, 원리, 법칙의 관련성을 파악하고 종합하여 문제를 해결하는
                            능력으로 실생활 상황에서 관련된 수학적 개념, 원리,
                            법칙 등을 파악하고 이를 적용하고 문제를 해결하는 능력</td>
                    </tr>
                </tbody>
            </table>

        </section>
    </div>
</body>
<script type="text/javascript">
    function setElementGaugeGraph(rate, color, element) {
        echarts.init(element).setOption({
            series: [{
                type: 'gauge',
                startAngle: 235,
                endAngle: -55,
                pointer: {
                    show: false,
                },
                axisLine: {
                    lineStyle: {
                        width: 11,
                        color: [
                            [rate * 0.01, color],
                            [1, '#DBDBDB'],
                        ]
                    },
                },
                animation: false,
                axisTick: {
                    show: false
                },
                splitLine: {
                    distance: -11,
                    length: 11,
                    lineStyle: {
                        color: '#fff',
                        width: 2,
                    }
                },
                axisLabel: {
                    show: false,
                },
                detail: {
                    width: '60%',
                    offsetCenter: [0, '85%'],
                    fontSize: 16,
                    fontWeight: 'bold',
                    formatter: '{value}%',
                    color: color
                },
                data: [{
                    value: rate
                }]
            }, ]
        });
    }

    function setDefaultGaugeGraph(rate, element, fontSize = 14) {
        echarts.init(element).setOption({
            series: [{
                type: 'gauge',
                progress: {
                    show: true,
                    color: '#7F93DA',
                    width: 5
                },
                startAngle: 270,
                endAngle: -90,
                pointer: {
                    show: false,
                },
                axisLine: {
                    lineStyle: {
                        width: 5,
                        color: [
                            [1, '#DBDBDB'],
                        ]
                    },
                },
                animation: false,
                axisTick: {
                    show: false
                },
                splitLine: {
                    show: false
                },
                axisLabel: {
                    show: false,
                },
                detail: {
                    offsetCenter: [0, '5%'],
                    fontSize,
                    fontWeight: 'bold',
                    formatter: '{value}%',
                    color: '#7F93DA'
                },
                data: [{
                    value: rate
                }]
            }, ]
        });
    }

    /**
     * data - 점수 배열 [문해력, 계산력, 추론력, 문제 해결력] 순서
     */
    function setRadarGraph(data, element) {
        echarts.init(element).setOption({
            radar: [{
                indicator: [{
                        max: 100
                    },
                    {
                        max: 100
                    },
                    {
                        max: 100
                    },
                    {
                        max: 100
                    },
                ],
                shape: 'circle',
                splitNumber: 4,
                axisName: {
                    color: '#333'
                },
                splitLine: {
                    lineStyle: {
                        width: 1,
                        color: '#BACAE5'
                    }
                }
            }, ],
            series: [{
                type: 'radar',
                data: [{
                    value: data,
                    areaStyle: {
                        color: '#7F93DA'
                    },
                }, ]
            }, ]
        });
    }

    function initGraphs(report) {
        // 내용 영역별 그래프	
        const element_rate = Object.values(report.element_rate);
        const element_color = @json($element_color);
        document.querySelectorAll('.element_rate_graph').forEach((element, i) => {
            setElementGaugeGraph(element_rate[i].rate, element_color[Object.keys(report.element_rate)[i]].color,
                element);
        });

        // 행동별 그래프 - 0점이여도 약간 티를 내기 위해 max를 이용해 최솟값 3점으로 조정
        setRadarGraph(Object.values(report.action_rate).map((data) => Math.max(data.rate, 3)), document.querySelector(
            '.radar_graph'));

        const action_rate = Object.values(report.action_rate);
        document.querySelectorAll('.action_rate_graph').forEach((element, i) => {
            setDefaultGaugeGraph(action_rate[i].rate, element);
        });

        // 메타인지 신뢰도 그래프
        const meta_meta_graph = document.querySelector('.meta_meta_graph');
        if (meta_meta_graph) {
            setDefaultGaugeGraph(report.meta_cognition.point, meta_meta_graph, 16);
        }

        // 메타인지 종합 성취도 그래프
        const meta_total_graph = document.querySelector('.meta_total_graph');
        if (meta_meta_graph) {
            setDefaultGaugeGraph(report.point, meta_total_graph, 16);
        }

        // 추가 문제 그래프
        const extend_questions_graph = document.querySelector('.extend_questions_graph');
        if (extend_questions_graph) {
            setDefaultGaugeGraph(report.extend.point, extend_questions_graph, 16);
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        const report = @json($report);
        console.log(report);

        initGraphs(report);
    });
</script>

</html>
