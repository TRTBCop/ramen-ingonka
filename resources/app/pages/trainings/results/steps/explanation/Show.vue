<template>
  <AppLearningLayout>
    <Training3Step2Exam v-if="isStage3Step2" :key="currentQuestion?.id" :is-review="true" />

    <DefaultExam
      v-else
      :questions="curriculumStore.getQuestions"
      :is-show-question-result="true"
      :is-inquiry-toggle="isInquiryToggle"
    />
    <template v-if="!isStage3Step2" #buttons>
      <AppButton
        v-if="!isChecked && training.stage === 2"
        @click="curriculumStore.addReviewCheckedQuestions(Number(curriculumStore.getCurrentQuestion?.id))"
      >
        정답 확인
      </AppButton>
      <AppButton
        v-else-if="
          !isNil(filteredQuestions) &&
          filteredQuestions.findIndex(
            (questionResult) => questionResult.question_id === curriculumStore.getCurrentQuestion?.id,
          ) !==
            filteredQuestions.length - 1
        "
        :disabled="!isChecked"
        @click="curriculumStore.moveNextReviewQuestion()"
      >
        다음으로
      </AppButton>
      <AppButton
        v-else-if="trainingResult && stepResult"
        :disabled="!isChecked"
        @click.once="goStepResultPage(training, trainingResult, stepResult)"
      >
        다 읽었어요
      </AppButton>
    </template>
  </AppLearningLayout>
</template>

<script setup lang="ts">
  import { watch, computed } from 'vue';
  import { Question } from '@/app/api/model/question';
  import AppLearningLayout from '@/app/layouts/AppLearningLayout.vue';
  import { TrainingContents, TrainingPageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import DefaultExam from '@/app/components/question/DefaultExam.vue';
  import { isEqual, isNil } from 'lodash';
  import Training3Step2Exam from '@/app/pages/trainings/stage3/components/Stage3Step2Exam.vue';
  import { goStepResultPage } from '@/app/core/helpers/routerHelper';
  import AppButton from '@/app/components/buttons/AppButton.vue';

  const pageData = usePage<TrainingPageProps<TrainingContents>>();

  const curriculumStore = useCurriculumStoreWithOut();

  const training = computed(() => pageData.props.training);

  const trainingResult = computed(() => pageData.props.training_result);

  const stepResult = computed(() => pageData.props.step_result);

  const isInquiryToggle = computed(() => training.value.stage === 3);

  const isStage3Step2 = computed(() => Number(pageData.props.step) === 1 && training.value.stage === 3);

  const currentQuestion = computed(() => curriculumStore.getCurrentQuestion);

  const filteredQuestions = computed(() =>
    curriculumStore.stepResult?.questions.filter((question) => question.correct_percent < 100),
  );

  const isChecked = computed(() => {
    return isEqual(
      curriculumStore.getOmr,
      curriculumStore.stepResult?.questions
        .find((questionResult) => questionResult.question_id === curriculumStore.getCurrentQuestion?.id)
        ?.answers.map((answer) => answer.correctAnswer),
    );
  });

  watch(
    () => isChecked.value,
    (newVal) => {
      if (newVal) {
        curriculumStore.addReviewCheckedQuestions(Number(curriculumStore.getCurrentQuestion?.id));
      }
    },
  );

  const questions = pageData.props.contents.questions.map(
    (_question) => pageData.props.questions.find((value) => value.id === _question.id) as Question,
  );

  curriculumStore.initReviewTraining(questions);
</script>
