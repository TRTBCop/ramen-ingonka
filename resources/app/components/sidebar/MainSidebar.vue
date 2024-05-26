<template>
  <div class="sidebar" :class="{ show: isShow }">
    <div class="menu">
      <button class="btn--close" @click="hideSidebar">
        <font-awesome-icon icon="fa-regular fa-xmark" />
      </button>
      <a class="menu__head" @click="goMyPage">
        <i :class="`profile__img profile__img--${getUserProfileType()} profile__img--sm`"></i>
        <div class="profile__info">
          <strong>{{ userData.name }} <i class="fa-regular fa-chevron-right"></i></strong>
          <span v-if="userSchoolInfo"
            >{{ userSchoolInfo.step }} {{ userSchoolInfo.grade }}학년 {{ userSchoolInfo.term }}학기</span
          >
          <template v-if="isB2cUser()">
            <template v-if="isFreeUser()">
              <small v-if="isFreeExpired()" class="badge--primary--alpha">무료체험 기간 종료</small>
              <small v-else class="badge--primary--alpha">
                무료체험 남은 기간 D-{{ freeDaysDiffFromToday() || 'Day' }}
              </small>
            </template>
            <template v-else-if="userData.service_end_date">
              <small class="badge--lightgray"
                >{{ dayjs(userData.service_end_date).format('YYYY년 MM월 DD일') }}까지</small
              >
            </template>
          </template>
        </div>
      </a>
      <ul class="menu__section">
        <li
          :class="{
            disabled: isFreeUser(),
          }"
          ><a href="javascript:;" @click="goTrainingHistoryPage()">학습 기록</a></li
        >
        <li
          v-if="false"
          :class="{
            disabled: isFreeUser(),
          }"
          ><a href="javascript:;">학습보고서</a></li
        >
        <li
          :class="{
            disabled: isFreeUser(),
          }"
          ><a href="javascript:;" @click="goIncorrectNotePage()">오답노트</a></li
        >
        <li><a href="javascript:;" @click="goTestMainPage">진단평가</a></li>
      </ul>
      <ul class="menu__section">
        <li v-if="isB2cUser()">
          <a href="javascript:;" @click="goPaymentPage">
            이용권 결제 <small class="badge--primary--alpha">더 많은 서비스를 이용해보세요!</small>
          </a>
        </li>
        <li><a href="javascript:;" @click="goCominSoonPage">이용가이드</a></li>
      </ul>
      <div class="btns">
        <button v-if="prompt" @click="clickCallback">홈 화면에 추가</button>
        <button @click="router.post(route('app.auth.destroy'))">로그아웃</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, computed, PropType } from 'vue';
  import { router } from '@inertiajs/vue3';
  import {
    freeDaysDiffFromToday,
    getUserData,
    getUserProfileType,
    getUserSchoolInfo,
    isB2cUser,
    isFreeExpired,
    isFreeUser,
  } from '@/app/core/helpers/userHelper';
  import { dayjs } from 'element-plus';
  import {
    goMyPage,
    goIncorrectNotePage,
    goTestMainPage,
    goPaymentPage,
    goTrainingHistoryPage,
    goCominSoonPage,
  } from '@/app/core/helpers/routerHelper';

  defineProps({
    isShow: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });

  const userData = computed(() => getUserData());

  const userSchoolInfo = computed(() => getUserSchoolInfo());

  const emits = defineEmits(['update:isShow']);

  function hideSidebar() {
    emits('update:isShow', false);
  }

  const prompt = ref();

  // pwa 사용 가능한 상태가 되면 prompt 저장
  window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    prompt.value = e;
  });

  function clickCallback() {
    prompt.value.prompt();

    prompt.value.userChoice.then((choiceResult: any) => {
      // 확인 누를시
      if (choiceResult.outcome === 'accepted') {
        //
      } else {
        //취소버튼 누를시
        window.addEventListener('beforeinstallprompt', (e) => {
          e.preventDefault();
          prompt.value = e;
        });
      }
    });
  }
</script>
