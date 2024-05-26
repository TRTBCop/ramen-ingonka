<template>
  <AppLearningLayout>
    <TextsConceptReadings :readings="readings" :is-summarizations="true" />

    <!-- 개념 요약 문제가 바뀌면 컴포넌트 리로드 되게 key값 부여 -->
    <TextsConceptSummarizations
      :key="curriculumStore.getCurrentQuestion?.id"
      :is-show-summarizations="isShowSummarizations"
      @hide-summarizations="hideSummarizations"
    />

    <template #buttons>
      <button class="btn--primary" @click="showSummarizations">내용 요약해보기</button>
    </template>
  </AppLearningLayout>
</template>

<script setup lang="ts">
  import { computed, ref, onUnmounted } from 'vue';
  import { TrainingContents, TrainingPageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import { TrainingConceptTextReading } from '@/app/api/model/training';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import AppLearningLayout from '@/app/layouts/AppLearningLayout.vue';
  import TextsConceptSummarizations from './components/TextsConceptSummarizations.vue';
  import TextsConceptReadings from './components/TextsConceptReadings.vue';
  import { Question } from '@/app/api/model/question';

  const pageData = usePage<
    TrainingPageProps<{
      readings: TrainingConceptTextReading[];
      summarizations: TrainingContents;
    }>
  >();

  const curriculumStore = useCurriculumStoreWithOut();

  const stepResult = computed(() => pageData.props.step_result);
  const readings = computed(() => pageData.props.contents.readings);

  const isShowSummarizations = ref(true);

  function hideSummarizations() {
    isShowSummarizations.value = false;
  }

  function showSummarizations() {
    isShowSummarizations.value = true;
  }

  function setContents() {
    const questions = pageData.props.contents.summarizations.questions.map(
      (_question) => pageData.props.questions.find((value) => value.id === _question.id) as Question,
    );

    curriculumStore.initTraining(questions, stepResult.value);
  }

  setContents();

  onUnmounted(() => {
    curriculumStore.stopTimer();
  });
</script>
