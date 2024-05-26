<template>
  <h2>{{ test.title }} <br />수리문해력 종합진단평가 결과</h2>
  <div class="result__box">
    <ul class="">
      <li>
        <font-awesome-icon icon="fa-regular fa-ballot-check"></font-awesome-icon>
        <div>
          <p class="is--gray"
            >문항 정답 수<span>{{ data.groupCorrectCount }}개</span></p
          >
          <p
            >총 문항 수<span>{{ data.groupQuestionCount }}개</span></p
          >
        </div>
      </li>
      <li>
        <font-awesome-icon icon="fa-regular fa-ballot-check"></font-awesome-icon>
        <div>
          <p class="is--gray"
            >풀이 답안 정답 수<span>{{ data.correctCount }}개</span></p
          >
          <p
            >총 풀이 답안 수<span>{{ data.questionCount }}개</span></p
          >
        </div>
      </li>
      <li>
        <font-awesome-icon icon="fa-regular fa-timer"></font-awesome-icon>
        <div>
          <p class="is--gray"
            >소요 시간<span>{{ data.testMinuteSecond }}</span></p
          >
          <p>기준 시간<span>40분 00초</span></p>
        </div>
      </li>
      <li>
        <font-awesome-icon icon="fa-regular fa-user"></font-awesome-icon>
        <div>
          <p class="is--gray"
            >나의 점수<span>{{ data.point }}점</span></p
          >
          <p class="is--gray"
            >나의 레벨<span>{{ data.level }}레벨</span></p
          >
        </div>
      </li>
    </ul>
  </div>
  <p>
    보다 자세한 내용은<br />
    진단평가 보고서를 통해 확인하세요.
  </p>
  <button class="btn--brand" @click="openTestReport">보고서 보기</button>
</template>

<script setup lang="ts">
  import { computed, PropType } from 'vue';
  import { Test } from '@/app/api/model/tests';
  import { values } from 'lodash';

  const props = defineProps({
    test: {
      type: Object as PropType<Test>,
      default: null,
    },
  });

  const data = computed(() => {
    const testResult = props.test.result;
    const questions = testResult.extra.questions;

    const point = testResult.point;

    const level = testResult.extra.report.level;

    const groupQuestionCount = values(questions).filter((quesiton) => !quesiton.is_extend).length;
    const groupCorrectCount = values(questions).filter(
      (quesiton) => !quesiton.is_extend && quesiton.question_count === quesiton.correct_count,
    ).length;

    const correctCount = testResult.extra.report.correct_count;
    const questionCount = testResult.extra.report.question_count;

    const testMinuteSecond = testResult.extra.report.test_minute_second;

    return {
      point,
      level,
      groupQuestionCount,
      groupCorrectCount,
      correctCount,
      questionCount,
      testMinuteSecond,
    };
  });

  function openTestReport() {
    window.open(route('app.tests.reports.show', props.test.result?.uuid));
  }
</script>
