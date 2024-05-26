<template>
  <AppLoginLayout>
    <div class="account_wrap">
      <button class="btn--back">
        <font-awesome-icon icon="fa-regular fa-arrow-left" />
        뒤로가기
      </button>

      <div class="title">
        <div>
          <h2>아이디 찾기</h2>
        </div>
        <p> 아이디 찾기가 완료되었습니다. </p>
      </div>

      <div class="box__result">
        <strong>test1234</strong>
        <p>가입일: 2023-07-20</p>
      </div>

      <div class="panel__btns--row">
        <button class="btn--sub" @click="router.get('/html/app-AccountPwStep01')">비밀번호 재설정</button>
        <button class="btn--brand" @click="router.get('/html/app-LoginReadingMath')">로그인하러 가기</button>
      </div>
    </div>

    <hr />

    <div class="account_wrap">
      <button class="btn--back">
        <font-awesome-icon icon="fa-regular fa-arrow-left" />
        뒤로가기
      </button>

      <div class="title">
        <div>
          <h2>아이디 찾기</h2>
        </div>
      </div>

      <div class="box__result">
        <p>검색된 회원이 없습니다.</p>
      </div>

      <div class="panel__btns--row">
        <button class="btn--sub" @click="router.get('/html/app-AccountIdStep01')">다시 찾기</button>
        <button class="btn--brand" @click="router.get('/html/app-Join')">회원 가입</button>
      </div>
    </div>
  </AppLoginLayout>
</template>

<script setup lang="ts">
  import AppLoginLayout from '@/html/layouts/AppLoginLayout.vue';
  import { router } from '@inertiajs/vue3';
</script>
