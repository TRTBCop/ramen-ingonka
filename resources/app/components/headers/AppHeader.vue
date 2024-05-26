<template>
  <header class="navbar">
    <div class="col">
      <Link :href="route('app.main')" class="logo"><img src="@/assets/img/math/ico_logo.svg" alt="" /></Link>
      <ol class="breadcrumb">
        <li
          ><strong>{{ userData.name }}</strong></li
        >
      </ol>
    </div>

    <div class="btns">
      <button title="도움말" @click="showHelpLayer"><font-awesome-icon icon="fa-regular fa-circle-question" /></button>
      <button title="오류신고" @click="errorReport"
        ><font-awesome-icon icon="fa-regular fa-light-emergency-on"
      /></button>
      <button title="공지사항" @click="goBoardNoticesPage()">
        <font-awesome-icon icon="fa-regular fa-bullhorn" />
      </button>
      <button title="메뉴" @click="openSidebar"><font-awesome-icon icon="fa-regular fa-bars" /></button>
    </div>
  </header>

  <HelpLayer v-model:is-show="isHelpLayer" />
  <MainSidebar v-model:is-show="isSidebarOpen" />
</template>

<script setup lang="ts">
  import { ref, computed } from 'vue';
  import MainSidebar from '@/app/components/sidebar/MainSidebar.vue';
  import { Link } from '@inertiajs/vue3';
  import { getUserData } from '@/app/core/helpers/userHelper';
  import Gleap from 'gleap';
  import HelpLayer from '../layers/HelpLayer.vue';
  import { goBoardNoticesPage } from '@/app/core/helpers/routerHelper';

  const userData = computed(() => getUserData());

  const pageName = ref('');

  const isSidebarOpen = ref(false);

  const isHelpLayer = ref(false);

  function showHelpLayer() {
    isHelpLayer.value = true;
  }

  function openSidebar() {
    isSidebarOpen.value = true;
  }

  /**
   * 헤더 페이지 네임 세팅
   */
  function setPageName() {
    switch (location.href) {
      case route('app.main'):
        pageName.value = '리딩수학';
        break;
      case route('app.tests.index'):
        pageName.value = '진단평가';
        break;
      default:
        pageName.value = '리딩수학';
    }
  }

  /**
   * gleap 유저 오류신고
   */
  const errorReport = () => {
    if (!Gleap.isOpened()) {
      Gleap.open();
    } else {
      Gleap.close();
    }
  };

  setPageName();

  //gleap 프론트 피드백 설정
  Gleap.identify(String(userData.value.id), {
    name: userData.value.name,
    email: userData.value.email,
  });
</script>
