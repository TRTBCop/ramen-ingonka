<template>
  <div v-if="currentQuestion" class="frame col--sm">
    <div class="frame__head tab_area">
      <ul class="tabs">
        <li
          v-for="(tab, index) in tabList"
          :key="index"
          :class="{
            current: tabIndex === index,
          }"
          @click="setTabIndex(index)"
          >{{ tab }}</li
        >
      </ul>
    </div>
    <div class="frame__body">
      <OmrSidebarRow v-for="(_, row) in currentQuestion.answers" v-show="tabIndex === 0" :key="row" :answer-row="row" />
      <article
        v-show="tabIndex === 1"
        class="fr-view"
        v-html="curriculumStore.getInquiry(currentQuestion.inquiry)"
      ></article>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, computed } from 'vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import OmrSidebarRow from '@/app/components/question/OmrSidebarRow.vue';

  const curriculumStore = useCurriculumStoreWithOut();

  const currentQuestion = computed(() => curriculumStore.getCurrentQuestion);

  const tabList = ['답안', '문제'];

  const tabIndex = ref(0);

  function setTabIndex(index: number) {
    tabIndex.value = index;
  }
</script>
