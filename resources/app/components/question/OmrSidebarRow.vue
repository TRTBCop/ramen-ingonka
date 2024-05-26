<template>
  <div
    class="toggle_area"
    :class="{
      toggle_area__shut: false,
    }"
  >
    <!-- 문제 풀이가 없는 경우 -->
    <div
      v-if="isSingleQuestion"
      class="answers"
      :class="{
        'answers--input': !isChoiceType,
      }"
    >
      <!-- 선지형일 경우 -->
      <AnswerChoices v-if="isChoiceType" :answer-row="answerRow" />
      <!-- 입력형일 경우 -->
      <template v-else>
        <template v-for="(_, answerCol) in answers" :key="answerCol">
          <!-- 분수형인 경우 -->
          <OmrCol
            v-if="getBlankCount(answerCol) > 1"
            :answer-row="answerRow"
            :answer-col="answerCol"
            :is-sidebar="true"
          />
          <!-- 일반형인 경우 -->
          <OmrSidebarCol v-else :answer-row="answerRow" :answer-col="answerCol" />
        </template>
      </template>
    </div>
    <!-- 문제 풀이가 있는 경우 -->
    <template v-else>
      <div class="toggle__head">
        <h3 class="">{{ answerRow + 1 }}번</h3>
      </div>

      <div class="answers answers--input">
        <!-- 선지형일 경우 -->
        <OmrSidebarCol v-if="isChoiceType" :answer-row="answerRow" :answer-col="null" />
        <!-- 입력형일 경우 -->
        <template v-else>
          <template v-for="(_, answerCol) in answers" :key="answerCol">
            <!-- 분수형인 경우 -->
            <OmrCol
              v-if="getBlankCount(answerCol) > 1"
              :answer-row="answerRow"
              :answer-col="answerCol"
              :is-sidebar="true"
            />
            <!-- 일반형인 경우 -->
            <OmrSidebarCol v-else :answer-row="answerRow" :answer-col="answerCol" />
          </template>
        </template>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
  import { computed, PropType } from 'vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import { isArray, isNil } from 'lodash';
  import OmrSidebarCol from '@/app/components/question/OmrSidebarCol.vue';
  import { QuestionAnswerEnum } from '@/app/api/model/question';
  import OmrCol from '@/app/components/question/OmrCol.vue';
  import AnswerChoices from './AnswerChoices.vue';

  const props = defineProps({
    answerRow: {
      type: Number as PropType<number>,
      default: 0,
    },
  });

  const curriculumStore = useCurriculumStoreWithOut();

  const isSingleQuestion = computed(() => curriculumStore.getIsSingleQuestion);

  const isChoiceType = computed(() => {
    const question = curriculumStore.getCurrentQuestion;

    if (isNil(question)) return false;

    return question.answers[props.answerRow].type === QuestionAnswerEnum.Choice;
  });
  const answers = computed(() => {
    const question = curriculumStore.getCurrentQuestion;

    if (isNil(question)) return [];

    return question.answers[props.answerRow].answer;
  });

  function getBlankCount(answerCol: number) {
    const currentQuesion = curriculumStore.getCurrentQuestion;

    if (!currentQuesion) return 1;

    if (currentQuesion.answers[props.answerRow].type === QuestionAnswerEnum.Choice) return 1;

    if (isNil(answerCol)) return 1;

    const blankAnswer = currentQuesion.answers[props.answerRow].answer[answerCol];

    if (isArray(blankAnswer)) {
      return blankAnswer.length;
    }

    return 1;
  }
</script>
../api/model/question
