<template>
  <AppLearningLayout>
    <ResultLoading v-if="isResultLoading" text="채점 중입니다..." />
    <div class="grid">
      <div class="frame col">
        <TrainingStepResult :training="training" :training-result="trainingResult" :step-result="stepResult" />
      </div>

      <div class="frame col--sm">
        <div class="frame__head title_area">
          <h3 class="title">학습 진행 현황</h3>
        </div>
        <div class="frame__body">
          <div v-for="step in trainingResult.steps" :key="step.id" class="result__box result__box--white">
            <span>{{ getStepName(training.stage, step.key) }}</span>
            <i v-if="step.completed_at" :class="`rank rank--${getRankByScore(step.correct_percent)} rank--md`"></i>
            <button v-else class="btn--gray" @click="goTrainingByStep(training, step.key)">학습하기</button>
          </div>
        </div>
      </div>
    </div>
    <template #buttons>
      <button class="btn--primary" @click="goTrainingMainPage(training)">홈으로</button>
      <button
        v-if="isTrainingCompleted"
        class="btn--gray"
        @click="goTrainingResultSummaryPage(training, trainingResult)"
        >훈련 결과 보기</button
      >
    </template>
  </AppLearningLayout>
</template>

<script setup lang="ts">
  import { ref, computed, onUnmounted } from 'vue';
  import AppLearningLayout from '@/app/layouts/AppLearningLayout.vue';
  import { usePage } from '@inertiajs/vue3';
  import { PageProps } from '@/app/types/pageData';
  import { TrainingResult } from '@/app/api/model/trainingResult';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import ResultLoading from '@/app/components/loadings/ResultLoading.vue';
  import { isNil } from 'lodash';
  import TrainingStepResult from '@/app/components/trainingResult/TrainingStepResult.vue';
  import { goTrainingMainPage, goTrainingResultSummaryPage, goTrainingByStep } from '@/app/core/helpers/routerHelper';
  import { getRankByScore, getStepName } from '@/app/core/helpers/trainingHelper';
  import { StepResult } from '@/app/api/model/stepResult';
  import { Training } from '@/app/api/model/training';

  interface Page extends PageProps {
    training: Training;
    training_result: TrainingResult;
    step_result: StepResult;
  }

  const pageData = usePage<Page>();

  const curriculumStore = useCurriculumStoreWithOut();

  const training = computed(() => pageData.props.training);
  const trainingResult = computed(() => pageData.props.training_result);
  const stepResult = computed(() => pageData.props.step_result);

  const isResultLoading = ref(false);

  function showResultLoading() {
    isResultLoading.value = true;
  }

  function hideResultLoading() {
    isResultLoading.value = false;
  }

  let loadingTimeoutId: ReturnType<typeof setTimeout>;

  const isTrainingCompleted = computed(() => pageData.props.training_result.completed_at);

  curriculumStore.setTimer(pageData.props.training_result.timer);
  curriculumStore.setCurrentQuestion(null);
  showResultLoading();
  // 로딩 시간
  const hideLoadingTime = 1000;

  loadingTimeoutId = setTimeout(() => {
    hideResultLoading();
  }, hideLoadingTime);

  onUnmounted(() => {
    if (!isNil(loadingTimeoutId)) {
      clearTimeout(loadingTimeoutId);
    }
  });
</script>
