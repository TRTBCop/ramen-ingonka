<template>
  <div id="wrap">
    <AppHeader />
    <div class="board">
      <div class="hero">
        <div class="container">
          <h2>{{ pageName }}</h2>
        </div>
      </div>
      <div class="container">
        <slot></slot>
      </div>
    </div>

    <DefaultModal :modal-state="systemStore.getModalState" @close="systemStore.hideModalState" />
  </div>
</template>
<script setup lang="ts">
  import { PropType } from 'vue';
  import AppHeader from '@/app/components/headers/AppHeader.vue';
  import DefaultModal from '@/app/components/modals/DefaultModal.vue';
  import { useSystemStoreWithOut } from '@/app/stores/modules/system';
  import { useErrorModal } from '@/app/hooks/useErrorModal';

  defineProps({
    pageName: {
      type: String as PropType<string>,
      default: '',
    },
  });

  const systemStore = useSystemStoreWithOut();
  useErrorModal();
</script>
