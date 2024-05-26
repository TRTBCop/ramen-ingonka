<template>
  <AppLearningLayout>
    <DefaultExam :questions="operations.questions" :is-show-question-result="true" />

    <template #buttons>
      <AppButton v-if="curriculumStore.getIsFinishedQuestion && !isAllChecked" @click="onClickCorrectCheckButton">
        정답 확인
      </AppButton>
      <AppButton v-else-if="isAllChecked" @click="curriculumStore.moveToNextQuestion(stageFinish)">
        다음으로
      </AppButton>
      <AppButton
        v-else
        :loading="isLoading"
        :disabled="!curriculumStore.getIsAllAnswersEntered"
        @click="onClickNextButton"
      >
        제출하기
      </AppButton>
    </template>
  </AppLearningLayout>
</template>

<script setup lang="ts">
  import { ref, computed, onUnmounted } from 'vue';
  import AppLearningLayout from '@/app/layouts/AppLearningLayout.vue';
  import { TrainingContents, TrainingPageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import { Question } from '@/app/api/model/question';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import DefaultExam from '@/app/components/question/DefaultExam.vue';
  import { isArray, isEqual } from 'lodash';
  import { QuestionAnswerEnum } from '@/admin/api/model/questions';
  import { goStepResultPage } from '@/app/core/helpers/routerHelper';
  import AppButton from '@/app/components/buttons/AppButton.vue';

  const pageData = usePage<TrainingPageProps<TrainingContents>>();

  const curriculumStore = useCurriculumStoreWithOut();

  const isLoading = ref(false);

  const operations = computed(() => pageData.props.contents);

  const training = computed(() => pageData.props.training);
  const trainingResult = computed(() => pageData.props.training_result);
  const stepResult = computed(() => pageData.props.step_result);

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
    await curriculumStore.submitResult({ answers: curriculumStore.getOmr, timer: curriculumStore.getTimer });
    curriculumStore.setOmrFocusInfo(null);
    curriculumStore.setIsFinishedQuestion(true);
    isLoading.value = false;
  }

  function onClickCorrectCheckButton() {
    curriculumStore.setOmrValueCorrect(Number(curriculumStore.getCurrentQuestion?.id));

    if (curriculumStore.getOmrAnswerType(0) === QuestionAnswerEnum.Input) {
      curriculumStore.getOmr[0].forEach((value, answerCol) => {
        if (isArray(value)) {
          value.forEach((_, blank) => {
            curriculumStore.addReviewCheckedAnswers({
              row: 0,
              col: answerCol,
              blank,
            });
          });
        } else {
          curriculumStore.addReviewCheckedAnswers({
            row: 0,
            col: answerCol,
            blank: null,
          });
        }
      });
    } else {
      curriculumStore.addReviewCheckedAnswers({
        row: 0,
        col: null,
        blank: null,
      });
    }
  }

  function stageFinish() {
    if (pageData.props.is_preview) {
      window.close();
      return;
    }

    if (trainingResult.value && stepResult.value) {
      goStepResultPage(training.value, trainingResult.value, stepResult.value);
    }
  }

  const questions = operations.value.questions.map(
    (_question) => pageData.props.questions.find((value) => value.id === _question.id) as Question,
  );

  curriculumStore.initTraining(questions, stepResult.value);

  onUnmounted(() => {
    curriculumStore.stopTimer();
  });
</script>
