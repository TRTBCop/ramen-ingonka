<template>
  <AppTestLayout>
    <!-- 추가 문제 -->
    <template v-if="pageData.props.is_extend">
      <TestExtendIntro v-if="!testStore.getIsStartExtend" />
      <TestQuestionPage v-else :key="pageData.props.question.id" />
    </template>
    <!-- 일반 문제 -->
    <TestQuestionPage v-else :key="pageData.props.question.id" />
  </AppTestLayout>
</template>

<script setup lang="ts">
  import AppTestLayout from '@/app/layouts/AppTestLayout.vue';
  import { TestPageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import TestQuestionPage from '@/app/components/tests/TestQuestionPage.vue';
  import TestExtendIntro from '@/app/components/tests/TestExtendIntro.vue';
  import { useTestStoreWithOut } from '@/app/stores/modules/tests';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';

  const curriculumStore = useCurriculumStoreWithOut();

  const testStore = useTestStoreWithOut();

  const pageData = usePage<TestPageProps>();

  curriculumStore.setCurrentQuestion(null);
  curriculumStore.setIsFinishedQuestion(false);
</script>
