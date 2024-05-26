<template>
  <AppLoginIntroLayout>
    <h2 class="brand_logo"> LOGIN </h2>
    <div class="panel_group">
      <div class="input error">
        <font-awesome-icon icon="fa-regular fa-user" />
        <input type="text" class="input__text" placeholder="아이디" />
      </div>
      <div class="input">
        <font-awesome-icon icon="fa-regular fa-lock" />
        <input type="password" class="input__text" placeholder="비밀번호" />
        <button class="btn_pw">
          <font-awesome-icon icon="fa-regular fa-eye" />
        </button>
      </div>
    </div>

    <ul class="box__error">
      <li>
        <span class="">아이디: 아이디를 입력해 주세요.</span>
      </li>
      <li>
        <span class="">비밀번호: 비밀번호를 입력해 주세요.</span>
      </li>
    </ul>
    <div class="checkbox__wrap">
      <input id="keep" type="checkbox" />
      <label for="keep"> 아이디 저장</label>
    </div>

    <div class="panel__btns">
      <button class="btn--brand" @click="router.get('/html/app-Join')">다음</button>
    </div>

    <div class="login_btns_wrap">
      <a href="javascript:;" @click="router.get('/html/app-AccountIdStep01')">
        <font-awesome-icon icon="fa-regular fa-user" />
        아이디 찾기</a
      >
      <a href="javascript:;" @click="router.get('/html/app-AccountPwStep01')">
        <font-awesome-icon icon="fa-regular fa-lock" />
        비밀번호 재설정</a
      >
      <a href="javascript:;" class="mt-1" @click="router.get('/html/app-Login')">
        <i class="fa-regular fa-arrow-left"></i>뒤로 가기
      </a>
    </div>
  </AppLoginIntroLayout>
</template>

<script setup lang="ts">
  import AppLoginIntroLayout from '@/html/layouts/AppLoginIntroLayout.vue';
  import { router } from '@inertiajs/vue3';
</script>
