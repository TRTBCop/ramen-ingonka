<template>
  <i :class="`ki-${currentIconType} ki-${props.iconName}${props.iconClass ? ' ' + props.iconClass : ''}`">
    <template v-if="icons[props.iconName] && currentIconType === 'duotone'">
      <span v-for="i in icons[props.iconName]" :key="i" :class="`path${i}`"></span>
    </template>
  </i>
</template>

<script setup lang="ts">
  import { computed } from 'vue';
  // icon  https://preview.keenthemes.com/html/metronic/docs/icons/keenicons
  import icons from '@/admin/core/helpers/kt-icon/icons.json';
  import { useConfigStore } from '@/admin/stores/config';

  const store = useConfigStore();

  const props = defineProps({
    iconName: { type: String, default: '', required: true },
    iconType: {
      type: String,
      default: '',
      required: false,
    },
    iconClass: { type: String, default: '', required: false },
  });

  const currentIconType = computed(() => {
    return props.iconType ? props.iconType : store.config.general.iconsType;
  });
</script>
