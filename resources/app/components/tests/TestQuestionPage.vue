<template>
  <DefaultExam :questions="pageData.props.questions" />

  <div v-if="curriculumStore.getCurrentQuestion" class="frame__bottom">
    <div class="character_area">
      <i class="character"></i>
    </div>
    <div class="row">
      <button
        v-if="!isMetaTooltip"
        class="btn--gray"
        :disabled="!curriculumStore.getIsAllAnswersEntered"
        @click.once="onClickNextButton"
        >답안 제출하기</button
      >
      <template v-else>
        <button
          v-for="(label, i) in metaOptions"
          :key="i"
          class="btn--gray"
          :disabled="!curriculumStore.getIsAllAnswersEntered"
          @click.once="testStore.submitAnswer(i)"
          >{{ label }}</button
        >
      </template>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { computed, onUnmounted, onMounted } from 'vue';
  import { useTestStoreWithOut } from '@/app/stores/modules/tests';
  import { TestPageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import DefaultExam from '@/app/components/question/DefaultExam.vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';

  const pageData = usePage<TestPageProps>();

  const testStore = useTestStoreWithOut();

  const question = computed(() => pageData.props.question);

  const metaOptions = ['확실해요.', '정답일 것 같아요.', '모르겠어요.'];

  const curriculumStore = useCurriculumStoreWithOut();

  const isMetaTooltip = computed(() => testStore.getIsMetaTooltip);

  function onClickNextButton() {
    if (pageData.props.is_preview) {
      if (confirm('미리보기입니다. 창을 닫으시겠습니까?')) {
        window.close();
      }
    } else if (pageData.props.is_extend) {
      testStore.submitAnswer();
    } else {
      testStore.setIsMetaTooltip(true);
      curriculumStore.setOmrFocusInfo(null);
    }
  }

  onUnmounted(() => {
    testStore.stopTestingTimeIncrement();
  });

  onMounted(() => {
    testStore.setIsMetaTooltip(false);
    testStore.initTestingTimeIncrement(pageData.props.timer);

    curriculumStore.setCurrentQuestion(question.value);
  });
</script>
