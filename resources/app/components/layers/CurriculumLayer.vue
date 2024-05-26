<template>
  <!-- 커리큘럼 레이어 -->
  <div
    class="layer ly__main"
    :class="{
      show: isShow,
    }"
  >
    <div class="inner">
      <CurriculumList :title="title" :curriculums="curriculums" @close="closeModal" />
    </div>
  </div>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';
  import { Curriculum } from '@/app/api/model/curriculum';
  import CurriculumList from '@/app/components/main/mainFree/CurriculumList.vue';

  defineProps({
    title: {
      type: String as PropType<string>,
      default: '',
    },
    isShow: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    curriculums: {
      type: Array as PropType<Curriculum[]>,
      default: null,
    },
  });

  const emits = defineEmits(['update:isShow']);

  function closeModal() {
    emits('update:isShow', false);
  }
</script>
