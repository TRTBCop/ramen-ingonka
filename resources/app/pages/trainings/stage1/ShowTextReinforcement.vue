<template>
  <AppLearningLayout>
    <DefaultExam
      :questions="reinforcements.questions"
      :is-show-question-result="true"
      :is-show-questiont-label="false"
    />
    <template #buttons>
      <DefaultExamFooterButtons @last-question-action="lastQuestionNextAction" />
    </template>
  </AppLearningLayout>
</template>

<script setup lang="ts">
  import { computed, onUnmounted } from 'vue';
  import { TrainingContents, TrainingPageProps } from '@/app/types/pageData';
  import { router, usePage } from '@inertiajs/vue3';
  import DefaultExam from '@/app/components/question/DefaultExam.vue';
  import AppLearningLayout from '@/app/layouts/AppLearningLayout.vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import { Question } from '@/app/api/model/question';
  import DefaultExamFooterButtons from '@/app/components/buttons/DefaultExamFooterButtons.vue';

  const pageData = usePage<TrainingPageProps<TrainingContents>>();

  const curriculumStore = useCurriculumStoreWithOut();

  const reinforcements = computed(() => pageData.props.contents);

  const stepResult = computed(() => pageData.props.step_result);

  function setContents() {
    const questions = pageData.props.contents.questions.map(
      (_question) => pageData.props.questions.find((value) => value.id === _question.id) as Question,
    );

    curriculumStore.initTraining(questions, stepResult.value);
  }

  function lastQuestionNextAction() {
    if (pageData.props.is_preview) {
      window.close();
      return;
    }

    router.get(
      route('app.trainings.stage1.texts.review.show', {
        training: pageData.props.training,
        trainingConceptText: pageData.props.training_concept_text_id as number,
      }),
    );
  }

  setContents();

  onUnmounted(() => {
    curriculumStore.stopTimer();
  });
</script>
