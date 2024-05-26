<template>
  <Teleport to="body">
    <div
      v-if="modalState.show"
      class="modal"
      :class="{
        'modal--sm': modalState.size === 'sm',
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
        <button class="btn--brand" @click="closeModal">확인</button>
      </div>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';
  import { ModalState } from '@/app/types/modals';

  defineProps({
    modalState: {
      type: Object as PropType<ModalState>,
      default: null,
    },
  });

  const emits = defineEmits(['close']);

  function closeModal() {
    emits('close');
  }
</script>
