<template>
  <div class="mt-5">
    <el-button type="default" @click="goListPage">
      <KTIcon icon-name="burger-menu-5" icon-class="fs-3 me-2" />
      목록
    </el-button>
  </div>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';
  import { router } from '@inertiajs/vue3';
  import KTIcon from '@/admin/core/helpers/kt-icon/KTIcon.vue';

  const props = defineProps({
    listUrl: {
      type: String as PropType<string>,
      default: '',
    },
    useOptionsCache: {
      type: Boolean as PropType<boolean>,
      default: true,
    },
  });

  function goListPage() {
    const jsonParams = sessionStorage.getItem(props.listUrl);
    if (props.useOptionsCache && jsonParams) {
      if (jsonParams) {
        // 캐시된 설정 값을 사용하는 경우
        router.get(props.listUrl, JSON.parse(jsonParams));
      }
    } else {
      // 캐시된 설정 값을 사용하지 않는 경우
      router.get(props.listUrl);
    }
  }
</script>
