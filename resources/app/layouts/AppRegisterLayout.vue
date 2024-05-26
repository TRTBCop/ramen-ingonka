<template>
  <div id="wrap">
    <div class="container--sm">
      <slot></slot>
    </div>
  </div>

  <DefaultModal :modal-state="systemStore.getModalState" @close="systemStore.hideModalState" />
</template>

<script setup lang="ts">
  import { onMounted, onUnmounted } from 'vue';
  import DefaultModal from '@/app/components/modals/DefaultModal.vue';
  import { useSystemStoreWithOut } from '@/app/stores/modules/system';
  import { useErrorModal } from '@/app/hooks/useErrorModal';

  const systemStore = useSystemStoreWithOut();

  useErrorModal();

  onMounted(() => {
    document.querySelector('meta[name=viewport]')?.setAttribute('content', 'width=device-width,initial-scale=1');
  });

  onUnmounted(() => {
    document.querySelector('meta[name=viewport]')?.setAttribute('content', 'width=1024,user-scalable=no');
  });
</script>
