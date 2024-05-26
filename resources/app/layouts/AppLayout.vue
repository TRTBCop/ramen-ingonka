<template>
  <div id="wrap">
    <AppHeader v-if="isShowHeader" />
    <slot></slot>

    <DefaultModal :modal-state="systemStore.getModalState" @close="systemStore.hideModalState" />

    <ExpiredFreeLayer v-if="isShowExpiredFreeLayer" />
  </div>
</template>

<script setup lang="ts">
  import { ref, PropType } from 'vue';
  import AppHeader from '@/app/components/headers/AppHeader.vue';
  import DefaultModal from '@/app/components/modals/DefaultModal.vue';
  import ExpiredFreeLayer from '@/app/components/layers/ExpiredFreeLayer.vue';
  import { useSystemStoreWithOut } from '@/app/stores/modules/system';
  import { getUserData } from '@/app/core/helpers/userHelper';
  import { useErrorModal } from '@/app/hooks/useErrorModal';

  defineProps({
    isShowHeader: {
      type: Boolean as PropType<boolean>,
      default: true,
    },
  });

  const systemStore = useSystemStoreWithOut();

  useErrorModal();

  const isShowExpiredFreeLayer = ref(getUserData().extra?.free_trial?.expired);
</script>
