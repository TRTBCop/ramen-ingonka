<template>
  <div :class="`wrap ${isMain ? 'main_page' : 'sub_page'}`">
    <!-- header -->
    <nav class="navbar">
      <Link :href="route('brand.index')" class="logo col" title="리딩수학">
        <i class="logo__img"></i>
      </Link>
      <div
        class="menu"
        :class="{
          show: isShowMobileMenu,
        }"
      >
        <Link
          :href="route('brand.contents.index')"
          :class="{ current: route().current() == 'brand.contents.index' }"
          title="콘텐츠 소개"
          >콘텐츠 소개</Link
        >
        <Link
          :href="route('brand.price.index')"
          :class="{ current: route().current() == 'brand.price.index' }"
          title="가격 안내"
          >가격 안내</Link
        >
        <Link
          :href="route('brand.spec.index')"
          :class="{ current: route().current() == 'brand.spec.index' }"
          title="사양 안내"
          >사양 안내</Link
        >
        <a href="javascript:;" class="btn__mobile__login" title="로그인" @click="goLoginPage">로그인</a>
      </div>
      <a href="javascript:;" class="col btn__login" title="로그인" @click="goLoginPage">로그인</a>
      <a href="javascript:;" class="mobile__menu" @click="toggleMobileMenu"
        ><img src="@/assets/img/brand/ico/menu_bar.svg"
      /></a>
    </nav>
    <!-- //header -->

    <slot></slot>

    <!-- footer -->
    <footer class="footer">
      <div class="footer__link">
        <div class="container">
          <Link :href="route('brand.policy.terms')">서비스이용약관</Link>
          <Link :href="route('brand.policy.privacy')">개인정보처리방침</Link>
          <!-- <Link href="/html/brand-Index">공지사항</Link> -->
        </div>
      </div>
      <div class="footer__info">
        <div class="container">
          <div class="footer__logo">
            <Link :href="route('brand.index')" title="리딩수학" class="brand">
              <img src="@/assets/img/brand/ico/logo.svg"
            /></Link>
          </div>

          <div class="footer__content">
            <div>
              <strong>(주)매일국어</strong><br />
              <ul>
                <li>
                  <span>주소 : 서울특별시 서초구 서초대로25길 67, 2층(방배동,기산빌딩)</span>
                  <span>대표 : 이동수</span>
                  <span>개인정보보호책임자: 전용선 (jsy0321@dailykor.com)</span>
                </li>
                <li>
                  <span> 사업자등록번호 : 135-86-53767 </span>
                  <span> 통신판매업 신고번호 : 제2018-서울서초-0397 </span>
                  <span> 대표번호 : 1800-5039 </span>
                </li>
              </ul>
            </div>
            <p class="copyright">COPYRIGHT(C)READINGMATH, ALL RIGHTS RESERVED</p>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
  import { ref, computed } from 'vue';
  import '@/assets/css/brand/brand.scss';
  import { Link } from '@inertiajs/vue3';
  import { goLoginPage } from '@/brand/core/helpers/routerHelper';

  const isMain = computed(() => route().current() == 'brand.index');

  const isShowMobileMenu = ref(false);

  function toggleMobileMenu() {
    isShowMobileMenu.value = !isShowMobileMenu.value;
  }
</script>
