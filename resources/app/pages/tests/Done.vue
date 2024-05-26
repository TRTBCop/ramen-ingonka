<template>
  <AppLayout>
    <div class="training training__theme theme--pretestIntro">
      <div class="container--sm">
        <div class="pretest__result">
          <h2 class="txt--center">{{ pageData.props.test.title }} <br />수리문해력 종합진단평가 결과</h2>
          <div class="result__box">
            <ul class="">
              <li>
                <font-awesome-icon icon="fa-regular fa-ballot-check"></font-awesome-icon>
                <div>
                  <p class="is--gray"
                    >문항 정답 수<span>{{ pageData.props.group_correct_count }}개</span></p
                  >
                  <p
                    >총 문항 수<span>{{ pageData.props.group_question_count }}개</span></p
                  >
                </div>
              </li>
              <li>
                <font-awesome-icon icon="fa-regular fa-ballot-check"></font-awesome-icon>
                <div>
                  <p class="is--gray"
                    >풀이 답안 정답 수<span>{{ pageData.props.correct_count }}개</span></p
                  >
                  <p
                    >총 풀이 답안 수<span>{{ pageData.props.question_count }}개</span></p
                  >
                </div>
              </li>
              <li>
                <font-awesome-icon icon="fa-regular fa-timer"></font-awesome-icon>
                <div>
                  <p class="is--gray"
                    >소요 시간<span>{{ pageData.props.test_minute_second }}</span></p
                  >
                  <p>기준 시간<span>40분 00초</span></p>
                </div>
              </li>
              <li>
                <font-awesome-icon icon="fa-regular fa-user"></font-awesome-icon>
                <div>
                  <p class="is--gray"
                    >나의 점수<span>{{ pageData.props.point }}점</span></p
                  >
                  <p class="is--gray"
                    >나의 레벨<span>{{ pageData.props.level }}레벨</span></p
                  >
                </div>
              </li>
            </ul>
          </div>
          <p class="txt--center"
            >보다 자세한 내용은 <br />
            진단평가 보고서를 통해 확인하세요.</p
          >
          <div class="btns">
            <button class="btn--brand" @click="openTestReport">보고서 보기</button>
          </div>
          <ul v-if="!isFreeUser()" class="links">
            <!-- <li>
              <a href="javascript:;" @click="shareAction"> <font-awesome-icon icon="fa-regular fa-share-nodes" /></a>
              <p>결과 공유</p>
            </li> -->
            <li v-if="!isB2cUser()">
              <a href="javascript:;" @click="goTestMainPage">
                <font-awesome-icon icon="fa-regular fa-file-magnifying-glass"
              /></a>
              <p
                >다른 학기 <br />
                진단</p
              >
            </li>
            <li>
              <a href="javascript:;" @click="goMainPage"> <font-awesome-icon icon="fa-regular fa-layer-group" /></a>
              <p
                >수리문해력 <br />
                높이기</p
              >
            </li>
          </ul>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
  import { onMounted } from 'vue';
  import AppLayout from '@/app/layouts/AppLayout.vue';
  import { useTestStoreWithOut } from '@/app/stores/modules/tests';
  import { PageProps } from '@/app/types/pageData';
  import { Test } from '@/app/api/model/tests';
  import { usePage } from '@inertiajs/vue3';
  import { isB2cUser, isFreeUser } from '@/app/core/helpers/userHelper';
  import { goMainPage, goTestMainPage } from '@/app/core/helpers/routerHelper';

  interface Page extends PageProps {
    test: Test;
    completed_at: string;
    wrong_questions: {
      [key in number]: string;
    };
    uuid: string;
    correct_count: number;
    question_count: number;
    test_minute_second: string;
    point: number;
    level: number;
    group_question_count: number;
    group_correct_count: number;
  }

  const pageData = usePage<Page>();

  const testStore = useTestStoreWithOut();

  function shareAction() {
    alert('미구현 기능');
  }

  function openTestReport() {
    window.open(route('app.tests.reports.show', pageData.props.uuid));
  }

  onMounted(() => {
    testStore.stopTestingTimeIncrement();
  });
</script>
