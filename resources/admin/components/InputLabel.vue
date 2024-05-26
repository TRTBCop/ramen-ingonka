<script setup lang="ts">
  defineProps<{
    value?: string;
    required?: boolean;
    tooltip?: string;
  }>();
</script>

<template>
  <label v-if="value" class="col-lg-4 col-form-label fw-semobold fs-6">
    <span :class="{ required: required }"> {{ value }}</span>

    <i
      v-if="tooltip"
      class="fas fa-exclamation-circle ms-1 fs-7"
      v-tooltip
      data-bs-toggle="tooltip"
      :title="tooltip"
    ></i>
  </label>
  <label v-else>
    <slot />
  </label>
</template>
