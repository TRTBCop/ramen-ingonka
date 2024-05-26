<template>
  <AppLearningLayout>
    <div class="grid">
      <div class="frame col">
        <div class="frame__head title_area">
          <h3 class="title">지문</h3>
          <ol class="numbers">
            <li
              v-for="(conceptTextId, i) in pageData.props.training_concept_text_ids"
              :key="conceptTextId"
              :class="{
                done: isCompletedText(i),
                on: isCurrentText(conceptTextId),
              }"
            ></li>
          </ol>
        </div>
        <div class="frame__body">
          <div class="articles">
            <article class="articles">
              <template v-for="(reading, i) in readings" :key="i">
                <br v-if="reading.text === '[문단 나누기]'" />
                <template v-else> <span v-html="reading.text"></span>&nbsp;</template>
                <p v-if="reading.type === TrainingConceptTextReadingType.Image && reading.image?.last">
                  <img :src="reading.image.src" />
                </p>
              </template>
            </article>
          </div>
        </div>
      </div>

      <div class="frame col--sm">
        <div class="frame__head title_area">
          <h3 class="title">개념 요약</h3>
        </div>
        <div class="frame__body">
          <div class="theorem_area">
            <div v-if="isShowSummarizations" class="theorem">
              <template v-for="question in summarizationsTexts" :key="question.id">
                <p v-html="question"></p>
                <br />
              </template>
            </div>
            <div v-else class="btns">
              <button class="btn--gray" @click="showSummarizations">클릭!</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <template #buttons>
      <button class="btn--gray" @click="onFinish">다 읽었어요</button>
    </template>
  </AppLearningLayout>
</template>

<script setup lang="ts">
  import { ref, computed, onMounted, onUnmounted } from 'vue';
  import { TrainingConceptTextReading, TrainingConceptTextReadingType } from '@/app/api/model/training';
  import AppLearningLayout from '@/app/layouts/AppLearningLayout.vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import { TrainingContents, TrainingPageProps } from '@/app/types/pageData';
  import { router, usePage } from '@inertiajs/vue3';
  import { Question } from '@/app/api/model/question';
  import { isNil } from 'lodash';
  import { updateTrainingResultTimer } from '@/app/api/trainingResult';
  import { goStepResultPage } from '@/app/core/helpers/routerHelper';

  const pageData = usePage<
    TrainingPageProps<{
      readings: TrainingConceptTextReading[];
      summarizations: TrainingContents;
    }>
  >();

  const curriculumStore = useCurriculumStoreWithOut();
  const training = computed(() => pageData.props.training);

  const trainingResult = computed(() => pageData.props.training_result);

  const stepResult = computed(() => pageData.props.step_result);

  const readings = computed(() => pageData.props.contents.readings);

  /** 완료한 지문인지 여부 반환 */
  function isCurrentText(textId: number) {
    return textId === pageData.props.training_concept_text_id;
  }

  /** 완료한 지문인지 여부 반환 */
  function isCompletedText(index: number) {
    return (
      index <
      Number(
        pageData.props.training_concept_text_ids?.findIndex((id) => id === pageData.props.training_concept_text_id),
      )
    );
  }
  const isShowSummarizations = ref(false);

  function showSummarizations() {
    isShowSummarizations.value = true;
  }

  function setContents() {
    const questions = pageData.props.contents.summarizations.questions.map(
      (_question) => pageData.props.questions.find((value) => value.id === _question.id) as Question,
    );

    curriculumStore.initTraining(questions);
  }

  const summarizationsTexts = computed(() => {
    return curriculumStore.getQuestions.map((question) => {
      return curriculumStore.getFilledCorrectQuestion(question);
    });
  });

  async function onFinish() {
    const trainingConceptTextIds = pageData.props.training_concept_text_ids;
    const trainingConceptTextId = pageData.props.training_concept_text_id;

    if (isNil(trainingConceptTextIds) || isNil(trainingConceptTextId) || !pageData.props.training_result?.id) return;

    await updateTrainingResultTimer(pageData.props.training_result.id, curriculumStore.getTimer);

    const foundTextIndex = trainingConceptTextIds.findIndex((textId) => textId === trainingConceptTextId);

    if (foundTextIndex === -1) return;

    const isLastText = foundTextIndex === trainingConceptTextIds.length - 1;

    if (isLastText) {
      if (trainingResult.value && stepResult.value) {
        goStepResultPage(training.value, trainingResult.value, stepResult.value);
      }
    } else {
      router.get(
        route('app.trainings.stage1.texts.readings.show', {
          training: pageData.props.training.id,
          trainingConceptText: trainingConceptTextIds[foundTextIndex + 1],
        }),
      );
    }
  }

  setContents();

  onMounted(() => {
    if (pageData.props.timer) {
      curriculumStore.setTimer(pageData.props.timer);
    }
    curriculumStore.startTimer();
  });

  onUnmounted(() => {
    curriculumStore.stopTimer();
  });
</script>
