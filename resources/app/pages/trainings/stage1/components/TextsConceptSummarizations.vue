<template>
  <div v-if="isShowSummarizations && currentQuestion" class="layer ly__math">
    <div class="inner">
      <h2 class="layer__head"><img src="@/assets/img/math/layer_textbook.svg" />개념 요약하기 </h2>
      <div class="layer__body skeleton fr-view" @click="nextSplitSentence">
        <SplitTrainingQuestion
          :splited-sentences="splitedSentences"
          :current-split-index="currentSplitIndex"
          :current-answer-row-index="currentAnswerRowIndex"
          :current-answer-col-index="currentAnswerColIndex"
        />
      </div>
      <div v-if="isShowChoices" class="btns--answers">
        <button
          v-for="choice in currentChoices"
          :key="choice.id"
          class="layer__box"
          :class="{
            correct:
              getCorrectAnswer(currentAnswerRowIndex, currentAnswerColIndex)?.id === choice.id && isCompltedQuestion,
          }"
          :disabled="
            getCorrectAnswer(currentAnswerRowIndex, currentAnswerColIndex)?.id !== choice.id && isCompltedQuestion
          "
          @click="onClickChoice(choice.id, true)"
        >
          <p v-html="choice.choice"></p>
        </button>
      </div>
      <div class="btns">
        <button
          v-if="isLastSentence"
          class="btn--primary"
          @click="curriculumStore.moveToNextQuestion(lastQuestionAction)"
        >
          다음으로
        </button>
        <button v-else class="btn--gray" @click="emits('hideSummarizations')">지문 다시 보기</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { computed, PropType } from 'vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import { useSplitTraining } from '@/app/hooks/useSplitTraining';
  import SplitTrainingQuestion from '@/app/components/question/SplitTrainingQuestion.vue';
  import { router, usePage } from '@inertiajs/vue3';
  import { TrainingPageProps } from '@/app/types/pageData';

  defineProps({
    isShowSummarizations: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });

  const emits = defineEmits(['hideSummarizations']);

  const pageData = usePage<TrainingPageProps>();

  const curriculumStore = useCurriculumStoreWithOut();

  const currentQuestion = computed(() => curriculumStore.getCurrentQuestion);

  const {
    splitedSentences,
    currentAnswerRowIndex,
    currentAnswerColIndex,
    isLastSentence,
    currentSplitIndex,
    nextSplitSentence,
    isCompltedQuestion,
    getCorrectAnswer,
    currentChoices,
    onClickChoice,
    isShowChoices,
  } = useSplitTraining();

  function lastQuestionAction() {
    if (pageData.props.is_preview) {
      window.close();
      return;
    }

    router.get(
      route('app.trainings.stage1.texts.reinforcements.show', {
        training: pageData.props.training.id,
        trainingConceptText: pageData.props.training_concept_text_id as number,
      }),
    );
  }
</script>
