<template>
  <Teleport to="body">
    <div
      v-if="modalState.show"
      class="modal"
      :class="{
        'modal--md': modalState.size === 'md',
      }"
    >
      <div class="modal_head">
        <button class="btn--close" @click="closeModal">
          <font-awesome-icon icon="fa-regular fa-xmark" />
        </button>
      </div>
      <div class="modal_body">
        <strong v-if="modalState.title" class="is--size4" v-html="modalState.title"></strong>
        <p v-html="modalState.message"></p>
      </div>
      <div class="btns">
        <button class="btn--sub" @click="closeModal">취소</button>
        <button class="btn--brand" @click="onConfirmed">확인</button>
      </div>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';
  import { ModalState } from '@/app/types/modals';
  import { isNil } from 'lodash';

  const props = defineProps({
    modalState: {
      type: Object as PropType<ModalState>,
      default: null,
    },
  });

  const emits = defineEmits(['update:modalState']);

  function onConfirmed() {
    if (!isNil(props.modalState.confirmEvent)) {
      props.modalState.confirmEvent();
    }

    closeModal();
  }

  function closeModal() {
    emits('update:modalState', { show: false, message: '', confirmEvent: null });
  }
</script>
