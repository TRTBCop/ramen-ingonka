<template>
  <AppLayout>
    <div class="training training__theme theme--pretestIntro">
      <div class="grid">
        <div class="frame col">
          <div class="frame__body">
            <div class="frame__container">
              <NoSelectedTestIntro v-if="isNil(activeTest)" />
              <UnCompletedTestIntro
                v-else-if="!isNil(activeTest) && isNil(activeTest.result?.completed_at)"
                :test="activeTest"
              />
              <CompletedTestIntro v-else :test="activeTest" />
            </div>
          </div>
        </div>

        <div class="frame col--sm">
          <div class="frame__head title_area">
            <h3 class="title">학기를 선택해주세요.</h3>
          </div>
          <div class="frame__body">
            <ul class="pretest__semester">
              <li
                v-for="(test, i) in (pageData.props.test_group[activeTab.value] as Test[])"
                :key="i"
                :class="{
                  current: test.id === activeTest?.id,
                }"
                @click="onClickTestButton(test)"
              >
                <h3>{{ test.title }}</h3>
                <div v-if="test.result?.completed_at" class="row">
                  <div class="">
                    <strong>평가 완료</strong>
                    <p> {{ dayjs(test.result.completed_at).format('YYYY-MM-DD') }} </p>
                  </div>
                  <button><font-awesome-icon icon="fa-regular fa-file-chart-column"></font-awesome-icon></button>
                </div>
              </li>
              <!-- <li>
                <h3>3학년 1학기</h3>
                <div class="row">
                  <div class="">
                    <strong>평가 완료</strong>
                    <p> 2023-06-08 </p>
                  </div>
                  <button><font-awesome-icon icon="fa-regular fa-file-chart-column"></font-awesome-icon></button>
                </div>
              </li> -->
              <!-- <li class="current">
                <h3>3학년 1학기</h3>
                <div class="row">
                  <div class="">
                    <strong>평가 완료</strong>
                    <p> 2023-06-08 </p>
                  </div>
                  <button><font-awesome-icon icon="fa-regular fa-file-chart-column"></font-awesome-icon></button>
                </div>
              </li> -->
            </ul>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
  import { ref } from 'vue';
  import AppLayout from '@/app/layouts/AppLayout.vue';
  import { PageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import { Test } from '@/app/api/model/tests';
  import dayjs from 'dayjs';
  import { isNil } from 'lodash';
  import NoSelectedTestIntro from '@/app/components/tests/intro/NoSelectedTestIntro.vue';
  import UnCompletedTestIntro from '@/app/components/tests/intro/UnCompletedTestIntro.vue';
  import CompletedTestIntro from '@/app/components/tests/intro/CompletedTestIntro.vue';

  interface Page extends PageProps {
    test_group: {
      lower: Test[];
      upper: Test[];
    };
  }

  const pageData = usePage<Page>();

  const activeTest = ref<Test | null>(null);

  interface Tab {
    label: string;
    value: string;
  }

  const tabs: Tab[] = [
    { label: '초등', value: 'lower' },
    { label: '중등', value: 'upper' },
  ];

  const activeTab = ref<Tab>(tabs[0]);

  function onClickTestButton(test: Test) {
    if (isNil(activeTest.value) || activeTest.value.id !== test.id) {
      activeTest.value = test;
    } else {
      activeTest.value = null;
    }
  }

  function onChangeTabIndex(index: number) {
    activeTab.value = tabs[index];
  }
</script>
