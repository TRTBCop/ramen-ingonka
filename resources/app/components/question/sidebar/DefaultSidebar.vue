<template>
  <div v-if="currentQuestion" class="frame col--sm">
    <div class="frame__head title_area">
      <h3 class="title">{{ helpMessage }}</h3>
    </div>
    <div class="frame__body">
      <OmrSidebarRow v-for="(_, row) in currentQuestion.answers" :key="row" :answer-row="row" />
    </div>
  </div>
</template>

<script setup lang="ts">
  import { computed } from 'vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import OmrSidebarRow from '@/app/components/question/OmrSidebarRow.vue';
  import { isEqual } from 'lodash';

  const curriculumStore = useCurriculumStoreWithOut();

  const currentQuestion = computed(() => curriculumStore.getCurrentQuestion);

  const isFinishedQuestion = computed(() => curriculumStore.isFinishedQuestion);

  const isAllChecked = computed(() => {
    return isEqual(
      curriculumStore.getOmr,
      curriculumStore.stepResult?.questions
        .find((questionResult) => questionResult.question_id === curriculumStore.getCurrentQuestion?.id)
        ?.answers.map((answer) => answer.correctAnswer),
    );
  });

  const helpMessage = computed(() => {
    if (isFinishedQuestion.value && !isAllChecked.value) {
      return '정답 확인';
    } else {
      return '답안';
    }
  });
</script>
