<template>
  <AppButton :disabled="isDisabledButton" :loading="isLoading" @click="onClickNextButton">
    {{ curriculumStore.getIsFinishedQuestion ? '다음으로' : '제출하기' }}
  </AppButton>
</template>

<script setup lang="ts">
  import { ref, computed } from 'vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import { TrainingPageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import { isEqual } from 'lodash';
  import AppButton from '@/app/components/buttons/AppButton.vue';

  const pageData = usePage<TrainingPageProps>();

  const curriculumStore = useCurriculumStoreWithOut();

  const emits = defineEmits(['last-question-action']);

  const isLoading = ref(false);

  const isDisabledButton = computed(() => {
    if (curriculumStore.getIsFinishedQuestion) {
      return !isAllChecked.value;
    } else {
      return !curriculumStore.getIsAllAnswersEntered;
    }
  });

  const isAllChecked = computed(() => {
    return isEqual(
      curriculumStore.getOmr,
      curriculumStore.stepResult?.questions
        .find((questionResult) => questionResult.question_id === curriculumStore.getCurrentQuestion?.id)
        ?.answers.map((answer) => answer.correctAnswer),
    );
  });

  async function onClickNextButton() {
    if (isLoading.value) return;
    isLoading.value = true;
    if (pageData.props.training.stage === 1) {
      if (curriculumStore.getIsFinishedQuestion) {
        curriculumStore.moveToNextQuestion(() => emits('last-question-action'));
      } else {
        await curriculumStore.submitResult(
          { answers: curriculumStore.getOmr, timer: curriculumStore.getTimer },
          {
            onSuccess: () => {
              curriculumStore.setIsFinishedQuestion(true);
            },
          },
        );
      }
    } else {
      await curriculumStore.submitResult(
        { answers: curriculumStore.getOmr, timer: curriculumStore.getTimer },
        {
          onSuccess: () => {
            curriculumStore.moveToNextQuestion(() => emits('last-question-action'));
          },
        },
      );
    }

    isLoading.value = false;
  }
</script>
