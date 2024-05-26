<template>
  <div id="wrap">
    <div class="container--sm">
      <slot></slot>
    </div>
  </div>
</template>

<script setup lang="ts">
  document.querySelector('meta[name=viewport]')?.setAttribute('content', 'width=device-width,initial-scale=1');
</script>
