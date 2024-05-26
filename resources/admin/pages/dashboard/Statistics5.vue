<template>
  <a href="#" :class="[widgetClasses, `bg-${color}`]" class="card hoverable">
    <!--begin::Body-->
    <div class="card-body">
      <KTIcon :icon-name="iconName" :icon-class="`text-${iconColor} fs-3x mx-n1`" />
      
      <div :class="`text-inverse-${color}`" class="fw-bold fs-4 mb-2 mt-5">
        {{ description }} {{ title }}
      </div>
    </div>
    <!--end::Body-->
  </a>
</template>

<script lang="ts" setup>
import {computed, defineComponent, PropType} from 'vue';

const props = defineProps({
  widgetClasses: {
    type: String as PropType<string>,
    default: '',
  },
  color: {
    type: String as PropType<string>,
    default: '',
  },
  iconColor: {
    type: String as PropType<string>,
    default: '',
  },
  iconName: {
    type: String as PropType<string>,
    default: '',
  },
  title: {
    type: String as PropType<string>,
    default: '',
  },
  description: {
    type: String as PropType<string>,
    default: '',
  }
});
</script>
