<template>
  <AppLearningLayout>
    <TextsConceptReadings :readings="pageData.props.contents" @on-finish="isFinished = true" />
    <template #buttons>
      <!-- 개념 읽기 하단 툴바 -->
      <button v-if="pageData.props.is_preview" class="btn--gray" @click="closeWindow">미리보기 종료</button>
      <button v-else class="btn--primary" :disabled="!isFinished" @click="moveSummarizationsTraining"
        >내용 요약해보기</button
      >
    </template>
  </AppLearningLayout>
</template>

<script setup lang="ts">
  import { ref, onUnmounted } from 'vue';
  import AppLearningLayout from '@/app/layouts/AppLearningLayout.vue';
  import TextsConceptReadings from './components/TextsConceptReadings.vue';
  import { router, usePage } from '@inertiajs/vue3';
  import { TrainingPageProps } from '@/app/types/pageData';
  import { TrainingConceptTextReading } from '@/app/api/model/training';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import { updateTrainingResultTimer } from '@/app/api/trainingResult';

  const pageData = usePage<TrainingPageProps<TrainingConceptTextReading[]>>();

  const isFinished = ref(false);

  const curriculumStore = useCurriculumStoreWithOut();

  function closeWindow() {
    window.close();
  }

  async function moveSummarizationsTraining() {
    if (!isFinished.value || !pageData.props.training_result?.id) return;

    await updateTrainingResultTimer(pageData.props.training_result.id, curriculumStore.getTimer);

    router.get(
      route('app.trainings.stage1.texts.summarizations.show', {
        training: pageData.props.training.id,
        trainingConceptText: pageData.props.training_concept_text_id as number,
      }),
    );
  }

  if (pageData.props.timer) {
    curriculumStore.setTimer(pageData.props.timer);
  }
  curriculumStore.startTimer();

  onUnmounted(() => {
    curriculumStore.stopTimer();
  });
</script>
