<template>
  <template v-if="currentQuestion">
    <div class="note__box">
      <div class="note__question_area">
        <h3>{{ stepName }} / {{ Number(questionIndex) + 1 }}번</h3>
        <h4 class="note__question" v-html="curriculumStore.getInquiry(currentQuestion.inquiry)"></h4>
      </div>
      <div class="note__expxplain_area">
        <h3>풀이 체크</h3>
        <ul class="note__quiz lst__check">
          <li v-for="(text, i) in checkMessages" :key="i">
            <strong class="note__quiz__number"></strong>
            <span>{{ text }}</span>
          </li>
        </ul>

        <h3>틀린 이유 찾기</h3>
        <ul class="note__quiz lst__result">
          <li v-for="(text, i) in inCorrectReason" :key="i">
            <strong class="note__quiz__number"></strong>
            <span>{{ text }}</span>
          </li>
        </ul>
      </div>
    </div>
    <div v-if="isShowCorrect" class="note__box note__box__result">
      <h3>풀이</h3>
      <p v-html="question"></p>
    </div>
  </template>
</template>

<script setup lang="ts">
  import { computed, PropType } from 'vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import { Training } from '@/app/api/model/training';
  import { getStepName } from '@/app/core/helpers/trainingHelper';
  import { QuestionResult } from '@/app/api/model/questionResult';

  const props = defineProps({
    training: {
      type: Object as PropType<Training>,
      default: null,
    },
    stepKey: {
      type: Number as PropType<number>,
      default: 0,
    },
    questionIndex: {
      type: Number as PropType<number>,
      default: 0,
    },
    questionResult: {
      type: Object as PropType<QuestionResult>,
      default: null,
    },
    isShowCorrect: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });

  const curriculumStore = useCurriculumStoreWithOut();

  /** 풀이 체크 */
  const checkMessages = [
    '구해야 하는 것에 동그라미 표시를 해보세요.',
    '필요한 정보에 밑줄을 쳐보세요.',
    '알아야 하는 개념이나 식을 간단히 정리해 보세요.',
  ];

  /** 틀린 이유 */
  const inCorrectReason = [
    '개념을 까먹었어요.',
    '계산 실수했어요.',
    '다른 것을 구했어요.',
    '문제를 이해 못 했어요.',
    '답을 잘 못 입력했어요.',
  ];

  const currentQuestion = computed(() => props.questionResult.question);

  /**
   * 문제 풀이
   */
  const question = computed(() => curriculumStore.getFilledCorrectQuestion(currentQuestion.value));

  const stepName = computed(() => getStepName(3, props.stepKey));
</script>
