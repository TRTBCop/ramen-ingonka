<template>
  <AppLearningLayout :is-show-footer="false">
    <ResultLoading v-if="isResultLoading" text="채점 중입니다..." />
    <div v-else class="container">
      <div class="result__intro">
        <div class="result__title">
          <h3>{{ getStageName(training.stage) }} 훈련을 완료했어요!</h3>
          <p>{{ curriculum.name }}의 {{ getStageName(training.stage) }} 훈련</p>
          <i :class="`star star--${getRankByScore(trainingResult.score)} star--lg`"></i>
          <button class="btn--brand" @click="goTrainingResultPage(training, trainingResult)"
            ><font-awesome-icon icon="fa-regular fa-arrow-right" />자세히 보기</button
          >
        </div>
        <div class="result__character">
          <i class="character__face"></i>
          <i class="character__hand"></i>
        </div>
      </div>
    </div>
  </AppLearningLayout>
</template>

<script setup lang="ts">
  import { ref, computed, onUnmounted } from 'vue';
  import { usePage } from '@inertiajs/vue3';
  import { PageProps } from '@/app/types/pageData';
  import { TrainingResult } from '@/app/api/model/trainingResult';
  import AppLearningLayout from '@/app/layouts/AppLearningLayout.vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import ResultLoading from '@/app/components/loadings/ResultLoading.vue';
  import { isNil } from 'lodash';
  import { goTrainingResultPage } from '@/app/core/helpers/routerHelper';
  import { getStageName, getRankByScore } from '@/app/core/helpers/trainingHelper';
  import { Training } from '@/app/api/model/training';

  interface Page extends PageProps {
    training: Training;
    training_result: TrainingResult;
  }

  const pageData = usePage<Page>();

  const curriculumStore = useCurriculumStoreWithOut();

  const training = computed(() => pageData.props.training);

  const trainingResult = computed(() => pageData.props.training_result);

  const curriculum = computed(() => training.value.curriculum);

  let loadingTimeoutId: ReturnType<typeof setTimeout>;

  const isResultLoading = ref(false);

  function showResultLoading() {
    isResultLoading.value = true;
  }

  function hideResultLoading() {
    isResultLoading.value = false;
  }

  curriculumStore.setTimer(pageData.props.training_result.timer);
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
