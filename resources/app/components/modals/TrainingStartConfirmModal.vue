<template>
  <ConfirmModal v-show="isShow" v-model:modal-state="modalState" />
</template>

<script setup lang="ts">
  import { ref, computed, PropType, watch } from 'vue';
  import ConfirmModal from '@/app/components/modals/ConfirmModal.vue';
  import { ModalState } from '@/app/types/modals';
  import { Curriculum } from '@/app/api/model/curriculum';
  import { getChapterByCurriculum } from '@/app/core/helpers/trainingHelper';
  import { TrainingResult } from '@/app/api/model/trainingResult';
  import { isNil } from 'lodash';
  import { TrainingStage } from '@/app/api/model/training';
  import { goTrainingMainPage } from '@/app/core/helpers/routerHelper';

  const props = defineProps({
    isShow: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    curriculum: {
      type: [null, Object] as PropType<Curriculum | null>,
      default: null,
    },
    stage: {
      type: Number as PropType<TrainingStage>,
      default: 1,
    },
    trainingResult: {
      type: [null, Object] as PropType<TrainingResult | null>,
      default: null,
    },
  });

  const emits = defineEmits(['update:isShow']);

  const modalState = ref<ModalState>({
    show: props.isShow,
    title: '',
    message: '',
    size: 'sm',
    confirmEvent: null,
  });

  watch(
    () => props.isShow,
    (newVal) => {
      if (newVal) {
        modalState.value.show = newVal;
        modalState.value.title = title.value;
        modalState.value.message = message.value;
        modalState.value.confirmEvent = onClickConfirm;
      }
    },
  );

  watch(
    () => modalState.value,
    (newVal) => {
      emits('update:isShow', newVal.show);
    },
  );

  const title = computed(() => (props.curriculum ? getChapterByCurriculum(props.curriculum)?.name : ''));
  const message = computed(() => {
    const curriculumName = props.curriculum?.name;
    const isRestudy = props.trainingResult?.round !== 0;

    return `${curriculumName}<br />${isRestudy ? '복습' : '학습'}을 ${
      isNil(props.trainingResult) ? '시작할까요?' : '이어서 진행할까요?'
    }`;
  });

  function onClickConfirm() {
    const training = props.trainingResult?.training;
    if (isNil(training)) return;

    goTrainingMainPage(training);
  }
</script>
