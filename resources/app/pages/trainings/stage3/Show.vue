<template>
  <AppLearningLayout>
    <Training3Step2Exam v-if="isStep2" :key="currentQuestion?.id" />
    <DefaultExam v-else :questions="pageData.props.contents.questions" :is-inquiry-toggle="true" />
    <template v-if="!isStep2" #buttons>
      <DefaultExamFooterButtons @last-question-action="stageFinish" />
    </template>
  </AppLearningLayout>
</template>

<script setup lang="ts">
  import { computed, onUnmounted } from 'vue';
  import { Question } from '@/app/api/model/question';
  import AppLearningLayout from '@/app/layouts/AppLearningLayout.vue';
  import { TrainingContents, TrainingPageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import DefaultExam from '@/app/components/question/DefaultExam.vue';
  import Training3Step2Exam from '@/app/pages/trainings/stage3/components/Stage3Step2Exam.vue';
  import DefaultExamFooterButtons from '@/app/components/buttons/DefaultExamFooterButtons.vue';
  import { goStepResultPage } from '@/app/core/helpers/routerHelper';

  const pageData = usePage<TrainingPageProps<TrainingContents>>();

  const curriculumStore = useCurriculumStoreWithOut();

  const training = computed(() => pageData.props.training);

  const trainingResult = computed(() => pageData.props.training_result);

  const stepResult = computed(() => pageData.props.step_result);

  const isStep2 = computed(() => Number(pageData.props.step) === 1);

  const currentQuestion = computed(() => curriculumStore.getCurrentQuestion);
  curriculumStore.currentQuestion = null;

  const questions = pageData.props.contents.questions.map(
    (_question) => pageData.props.questions.find((value) => value.id === _question.id) as Question,
  );

  curriculumStore.initTraining(questions, stepResult.value);

  function stageFinish() {
    if (pageData.props.is_preview) {
      window.close();
      return;
    }

    if (trainingResult.value && stepResult.value) {
      goStepResultPage(training.value, trainingResult.value, stepResult.value);
    }
  }

  onUnmounted(() => {
    curriculumStore.stopTimer();
  });
</script>
