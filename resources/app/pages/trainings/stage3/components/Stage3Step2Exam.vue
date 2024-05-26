<template>
  <div v-if="currentQuestion" class="grid">
    <div class="frame col">
      <div class="frame__head tab_area">
        <ul class="tabs tabs--count">
          <li
            v-for="question in pageData.props.contents.questions"
            :key="question.id"
            :class="getQuestionStateClass(question.id)"
          ></li>
        </ul>
      </div>
      <div class="frame__body" @click="nextSplitSentence()">
        <div class="question_area">
          <div v-if="currentQuestion.inquiry" class="question__box">
            <div class="question__box--toggle">
              <h3>문제</h3>
            </div>
            <div class="articles">
              <article class="fr-view" v-html="curriculumStore.getInquiry(currentQuestion.inquiry)"></article>
            </div>

            <div v-if="currentQuestion.options" class="question__box">
              <span class="badge--lightgray"><i class="fa-regular fa-list-ul"></i>보기</span>
              <p class="fr-view" v-html="currentQuestion.options"></p>
            </div>
          </div>

          <div class="question__box">
            <span class="badge--lightgray"><i class="fa-regular fa-scroll-old"></i>문제 풀이</span>
            <p class="skeleton">
              <SplitTrainingQuestion
                :is-review="isReview"
                :splited-sentences="splitedSentences"
                :current-split-index="currentSplitIndex"
                :current-answer-row-index="currentAnswerRowIndex"
                :current-answer-col-index="currentAnswerColIndex"
              />
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="frame col--sm">
      <div class="frame__head tab_area">
        <ul class="tabs">
          <li
            v-for="(tab, index) in tabList"
            :key="index"
            :class="{
              current: tabIndex === index,
            }"
            @click="setTabIndex(index)"
            >{{ tab }}</li
          >
        </ul>
      </div>
      <div
        class="frame__body"
        :class="{
          'frame__body--center': isOrdering,
        }"
      >
        <div v-if="isShowChoices" v-show="tabIndex === 0" class="toggle_area">
          <div v-if="isOrdering" class="order__btns">
            <button
              v-for="choice in currentChoices"
              :key="choice.id"
              :disabled="
                curriculumStore.getOmr[currentAnswerRowIndex].findIndex((answer) => answer === String(choice.id)) !== -1
              "
              @click="onClickOrderingChoice(choice.id)"
              v-html="choice.choice"
            ></button>
          </div>
          <div v-else class="answers">
            <div class="answers--choice">
              <button
                v-for="choice in currentChoices"
                :key="choice.id"
                :class="{
                  answer__correct:
                    String(curriculumStore.getOmr[currentAnswerRowIndex][currentAnswerColIndex]) === String(choice.id),
                  answer__wrong: wrongClickedChoices.findIndex((id) => id === String(choice.id)) !== -1,
                }"
                @click="onClickChoice(choice.id)"
              >
                <i class="answer__number"></i>
                <span v-html="choice.choice"></span>
              </button>
            </div>
          </div>
        </div>

        <article
          v-show="tabIndex === 1"
          class="fr-view"
          v-html="curriculumStore.getInquiry(currentQuestion.inquiry)"
        ></article>
      </div>
    </div>
  </div>

  <SplitInquiryModal v-if="isShowSplitInquiry && !isReview" @on-complete="() => setIsShowSplitInquiry(false)" />

  <Teleport v-if="isShowButton" to="#frame_bottom_button_area">
    <template v-if="isReview && trainingResult && stepResult">
      <AppButton
        v-if="
          isOrdering &&
          !isNil(wrongQuestionResults) &&
          wrongQuestionResults.findIndex(
            (questionResult) => questionResult.question_id === curriculumStore.getCurrentQuestion?.id,
          ) !==
            wrongQuestionResults.length - 1
        "
        :disabled="!isChecked"
        @click="curriculumStore.moveNextReviewQuestion()"
      >
        다음으로
      </AppButton>
      <AppButton v-else-if="!isOrdering" :disabled="!getIsAllReivewCheckedChoiceType()" @click="setOrderingType">
        순서 맞추기
      </AppButton>
      <AppButton v-else :disabled="!isChecked" @click="goStepResultPage(training, trainingResult, stepResult)">
        순서 맞추기
      </AppButton>
    </template>
    <template v-else>
      <AppButton v-if="isOrdering" :disabled="!isLastSentence" @click="curriculumStore.moveToNextQuestion(stageFinish)">
        다음으로
      </AppButton>
      <AppButton v-else :disabled="!isLastSentence" @click="setOrderingType"> 순서 맞추기 </AppButton>
    </template>
  </Teleport>
</template>

<script setup lang="ts">
  import { onMounted, ref, computed, PropType, watch } from 'vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import { TrainingContents, TrainingPageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import SplitInquiryModal from '@/app/components/question/SplitInquiryModal.vue';
  import SplitTrainingQuestion from '@/app/components/question/SplitTrainingQuestion.vue';
  import { useSplitTraining } from '@/app/hooks/useSplitTraining';
  import { isEqual, isNil } from 'lodash';
  import { QuestionAnswerEnum } from '@/app/api/model/question';
  import { updateTrainingResultTimer } from '@/app/api/trainingResult';
  import AppButton from '@/app/components/buttons/AppButton.vue';
  import { goStepResultPage } from '@/app/core/helpers/routerHelper';

  const props = defineProps({
    isReview: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });

  const {
    splitedSentences,
    currentAnswerRowIndex,
    currentAnswerColIndex,
    currentSplitIndex,
    nextSplitSentence,
    isOrdering,
    isLastSentence,
    setOrderingType,
    wrongClickedChoices,
    onClickChoice,
    onClickOrderingChoice,
    currentChoices,
    isShowChoices,
  } = useSplitTraining();

  const pageData = usePage<TrainingPageProps<TrainingContents>>();

  const curriculumStore = useCurriculumStoreWithOut();

  const training = computed(() => pageData.props.training);
  const trainingResult = computed(() => pageData.props.training_result);
  const stepResult = computed(() => pageData.props.step_result);

  const wrongQuestionResults = computed(() =>
    curriculumStore.stepResult?.questions.filter((questionResult) => questionResult.correct_percent < 100),
  );

  const isShowSplitInquiry = ref(true);

  const questionResults = computed(() => curriculumStore.stepResult?.questions || []);

  const isChecked = computed(() => {
    return (
      props.isReview &&
      isEqual(
        curriculumStore.getOmr,
        curriculumStore.stepResult?.questions
          .find((questionResult) => questionResult.question_id === curriculumStore.getCurrentQuestion?.id)
          ?.answers.map((answer) => answer.correctAnswer),
      )
    );
  });

  const tabList = ['답안', '문제'];

  const tabIndex = ref(0);

  function setTabIndex(index: number) {
    tabIndex.value = index;
  }

  watch(
    () => isChecked.value,
    (newVal) => {
      if (newVal) {
        curriculumStore.addReviewCheckedQuestions(Number(curriculumStore.getCurrentQuestion?.id));
      }
    },
  );

  function getIsAllReivewCheckedChoiceType() {
    return curriculumStore.getCurrentQuestion?.answers
      .filter((answer) => answer.type === QuestionAnswerEnum.Choice)
      .reduce((prev, current, answerRowIndex) => {
        return prev && isEqual(current.answer, curriculumStore.getOmr[answerRowIndex]);
      }, true);
  }

  function getQuestionStateClass(questionId: number) {
    const foundQuestionResult = questionResults.value.find(
      (questionResult) => questionResult.question_id === questionId,
    );

    let result = '';

    if (currentQuestion.value?.id !== questionId && isNil(foundQuestionResult)) {
      return result;
    }

    if (currentQuestion.value?.id === questionId) {
      result = 'current ';
    }

    if (isNil(foundQuestionResult)) return result;

    if (props.isReview) {
      if (curriculumStore.getReviewCheckedQuestions.findIndex((id) => id === questionId) !== -1) {
        result += 'check';
      } else if (foundQuestionResult.correct_percent === 100) {
        result += 'correct';
      } else if (foundQuestionResult.correct_percent >= 70) {
        result += 'triangle';
      } else {
        result += 'wrong';
      }
    }

    return result;
  }
  function setIsShowSplitInquiry(state: boolean) {
    isShowSplitInquiry.value = state;
  }

  const currentQuestion = computed(() => curriculumStore.getCurrentQuestion);

  async function stageFinish() {
    if (pageData.props.is_preview || isNil(trainingResult.value?.id)) {
      window.close();
      return;
    }

    if (trainingResult.value) {
      await updateTrainingResultTimer(trainingResult.value?.id, curriculumStore.getTimer);
    }

    if (trainingResult.value && stepResult.value) {
      goStepResultPage(training.value, trainingResult.value, stepResult.value);
    }
  }

  watch(
    () => isOrdering.value,
    (newVal) => {
      if (newVal && props.isReview) {
        currentSplitIndex.value = splitedSentences.value.length;
      }
    },
  );

  const isShowButton = ref(false);

  onMounted(() => {
    if (props.isReview) {
      currentSplitIndex.value = splitedSentences.value.length;
    }
    isShowButton.value = true;
  });
</script>
