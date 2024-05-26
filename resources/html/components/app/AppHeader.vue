<template>
  <header class="navbar">
    <div class="col">
      <a href="javascript:;" class="logo" @click="router.get('/html/app-Main')"
        ><img src="../../../assets/img/math/ico_logo.svg" alt=""
      /></a>
      <ol class="breadcrumb">
        <li><strong>김학생</strong></li>
      </ol>
    </div>

    <div class="btns">
      <button title="도움말">
        <font-awesome-icon icon="fa-regular fa-circle-question" />
      </button>
      <button title="오류신고">
        <font-awesome-icon icon="fa-regular fa-light-emergency-on" />
      </button>
      <button title="공지사항" @click="router.get('/html/app-BoardNotice')">
        <font-awesome-icon icon="fa-regular fa-bullhorn" />
      </button>
      <button class="btn__menu" title="메뉴" @click="toggleMenu">
        <font-awesome-icon icon="fa-regular fa-bars" />
      </button>
    </div>
  </header>

  <div class="sidebar" :class="{ show: openToggleMenu }">
    <div class="menu">
      <button class="btn--close" @click="openToggleMenu = false">
        <font-awesome-icon icon="fa-regular fa-xmark" />
      </button>
      <a class="menu__head" a href="javascript:;" @click="router.get('/html/app-Mypage')">
        <i class="profile__img profile__img--type08 profile__img--sm"></i>
        <div class="profile__info">
          <strong>김리딩 <i class="fa-regular fa-chevron-right"></i></strong>
          <span>초등 3학년 1학기</span>
          <small class="badge--lightgray">2024년 3월 31일까지</small>
          <small class="badge--primary--alpha">무료체험 기간 종료</small>
        </div>
      </a>
      <ui class="menu__section">
        <li class="disabled"><a href="javascript:;">학습 기록</a></li>
        <li class="disabled"><a href="javascript:;">학습보고서</a></li>
        <li class="disabled"><a href="javascript:;">오답노트</a></li>
        <li><a href="javascript:;" @click="router.get('/html/app-MathHistory')">학습 기록</a></li>
        <li><a href="javascript:;" @click="router.get('/html/app-BoardReport')">학습보고서</a></li>
        <li><a href="javascript:;" @click="router.get('/html/app-BoardIncorrect')">오답노트</a></li>
        <li><a href="javascript:;" @click="router.get('/html/app-PretestIntro')">진단평가</a></li>
      </ui>
      <ui class="menu__section">
        <li
          ><a href="javascript:;" @click="router.get('/html/app-Payment')"
            >이용권 결제 <small class="badge--primary--alpha">더 많은 서비스를 이용해보세요!</small></a
          ></li
        >
        <li><a href="javascript:;">이용가이드</a></li>
      </ui>
      <div class="btns">
        <button>로그아웃</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref } from 'vue';
  import { router } from '@inertiajs/vue3';

  const openToggleMenu = ref(false);

  const toggleMenu = () => {
    openToggleMenu.value = !openToggleMenu.value;
  };
</script>
