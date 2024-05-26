<template>
  <div id="wrap">
    <div class="login_wrap">
      <header>
        <a href="/" class="logo col" title="리딩수학">
          <img src="@/assets/img/math/logo.svg" alt="" />
        </a>
      </header>

      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
  import { onMounted, onUnmounted } from 'vue';

  onMounted(() => {
    document.querySelector('meta[name=viewport]')?.setAttribute('content', 'width=device-width,initial-scale=1');
  });

  onUnmounted(() => {
    document.querySelector('meta[name=viewport]')?.setAttribute('content', 'width=1024,user-scalable=no');
  });
</script>
