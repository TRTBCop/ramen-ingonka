<template>
  <div v-if="question" class="note__box">
    <div class="note__question_area">
      <h3>{{ stepName }} / {{ questionIndex + 1 }}번</h3>
      <h4 class="note__question" v-html="question.inquiry"></h4>
      <ul v-if="isChoiceType" class="note__quiz">
        <li v-for="(choice, i) in answer.choices" :key="i">
          <strong class="note__quiz__number"></strong><span v-html="choice"></span>
        </li>
      </ul>
      <div v-if="question.options" class="question__box question__box--white">
        <span class="badge--lightgray"> <i class="fa-regular fa-list-ul"></i>보기</span>
        <p v-html="question.options"></p>
      </div>
    </div>
    <div class="note__expxplain_area">
      <h3>풀이</h3>
      <div v-show="isShowCorrect">
        <span class="badge--lightgray"> <i class="fa-regular fa-key-skeleton"></i>정답 </span>
        <div class="note__quiz">
          <!-- 선지형일 경우 -->
          <template v-if="isChoiceType">
            <template v-for="(value, i) in answer.answer" :key="i">
              <strong class="note__quiz__number">{{ value }}</strong>
              <span v-html="answer.choices[Number(value) - 1]"></span>
            </template>
          </template>
          <template v-else>
            <template v-for="(value, i) in answer.answer" :key="i">
              <strong v-if="answer.choice_symbol" class="note__quiz__number">{{
                String.fromCharCode(12896 + i)
              }}</strong>
              <span v-html="value"></span>
            </template>
          </template>
        </div>
        <template v-if="question.explanation">
          <span class="badge--lightgray"><i class="fa-regular fa-key-skeleton"></i>오답 해설</span>
          <div class="note__quiz">
            <p v-html="question.explanation"></p>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { computed, PropType } from 'vue';
  import { QuestionResult } from '@/app/api/model/questionResult';
  import { QuestionAnswerEnum } from '@/app/api/model/question';
  import { getStepName } from '@/app/core/helpers/trainingHelper';

  const props = defineProps({
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

  const question = computed(() => props.questionResult.question);

  const answer = computed(() => question.value.answers[0] || []);

  const isChoiceType = computed(() => answer.value.type === QuestionAnswerEnum.Choice);

  const stepName = computed(() => getStepName(2, props.stepKey));
</script>
