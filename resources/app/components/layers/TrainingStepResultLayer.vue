<template>
  <div
    class="layer ly__history"
    :class="{
      show: isShow,
    }"
  >
    <div class="inner">
      <TrainingStepResult :training="training" :training-result="trainingResult" :step-result="stepResult" />

      <div class="btns btns--center">
        <button class="btn--sub" @click="closeModal">닫기</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';
  import TrainingStepResult from '@/app/components/trainingResult/TrainingStepResult.vue';
  import { StepResult } from '@/app/api/model/stepResult';
  import { Training } from '@/app/api/model/training';
  import { TrainingResult } from '@/app/api/model/trainingResult';

  defineProps({
    isShow: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    training: {
      type: Object as PropType<Training>,
      default: null,
    },
    trainingResult: {
      type: Object as PropType<TrainingResult>,
      default: null,
    },
    stepResult: {
      type: Object as PropType<StepResult>,
      default: null,
    },
  });

  const emits = defineEmits(['update:isShow']);

  function closeModal() {
    emits('update:isShow', false);
  }
</script>
