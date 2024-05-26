<template>
  <div id="wrap">
    <AppLearningHeader />

    <div class="training training__theme" :class="getThemeTypeClass(training.stage)">
      <slot></slot>
      <div v-if="isShowFooter" class="frame__bottom">
        <div class="character_area">
          <i class="character"></i>
          <span
            v-if="
              curriculumStore.getIsFinishedQuestion &&
              curriculumStore.getIsSingleQuestion &&
              curriculumStore.getCurrentQuestion
            "
            class="comment"
            :class="{
              comment__wrong: !isCurrentQuestionCorrect,
              comment__correct: isCurrentQuestionCorrect,
            }"
          >
            {{ isCurrentQuestionCorrect ? '정답이에요!' : '오답이에요!' }}
          </span>
        </div>
        <i class="character"></i>
        <!-- 버튼은 Teleport나 슬롯으로 받음 -->
        <div id="frame_bottom_button_area" class="row">
          <slot name="buttons"></slot>
        </div>
      </div>
    </div>
  </div>

  <DefaultModal :modal-state="systemStore.getModalState" @close="systemStore.hideModalState" />
</template>

<script setup lang="ts">
  import { computed, onMounted, onBeforeUnmount, PropType } from 'vue';
  import { usePage } from '@inertiajs/vue3';
  import { TrainingPageProps } from '@/app/types/pageData';
  import AppLearningHeader from '@/app/components/headers/AppLearningHeader.vue';
  import { isNil } from 'lodash';
  import { updateTrainingResultTimer } from '@/app/api/trainingResult';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import { useSystemStoreWithOut } from '@/app/stores/modules/system';
  import DefaultModal from '@/app/components/modals/DefaultModal.vue';
  import { getThemeTypeClass } from '@/app/core/helpers/trainingHelper';

  defineProps({
    isShowFooter: {
      type: Boolean as PropType<boolean>,
      default: true,
    },
  });

  const pageData = usePage<TrainingPageProps>();

  const training = computed(() => pageData.props.training);

  const systemStore = useSystemStoreWithOut();
  const curriculumStore = useCurriculumStoreWithOut();

  /** 새로고침 및 창닫기 이벤트 */
  const handlePageReload = (event: any) => {
    event.preventDefault();

    const trainingResult = pageData.props.training_result;
    if (!isNil(trainingResult)) {
      updateTrainingResultTimer(trainingResult.id, curriculumStore.getTimer);
    }

    event.returnValue = '';
  };

  /**
   * 문제 풀이가 없는 형태에만 사용
   */
  const isCurrentQuestionCorrect = computed(() => {
    const currentQuestion = curriculumStore.getCurrentQuestion;

    const questionResults = curriculumStore.stepResult?.questions;

    if (isNil(currentQuestion) || isNil(questionResults)) {
      return false;
    }

    const foundQuestionResult = questionResults.find((questionResult) => questionResult.id === currentQuestion.id);

    return foundQuestionResult?.correct_percent === 100 ? true : false;
  });

  onMounted(() => {
    window.addEventListener('beforeunload', handlePageReload);
  });

  onBeforeUnmount(() => {
    window.removeEventListener('beforeunload', handlePageReload);
  });
</script>
