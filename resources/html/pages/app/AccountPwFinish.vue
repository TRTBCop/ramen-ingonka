<template>
  <AppLoginLayout>
    <div class="account_wrap">
      <button class="btn--back" @click="router.get('/html/app-AccountPwStep03')">
        <font-awesome-icon icon="fa-regular fa-arrow-left" />
        뒤로가기
      </button>

      <div class="title">
        <div>
          <h2>비밀번호 재설정</h2>
          <ol class="breadcrumb">
            <li class="">1</li>
            <li class="">2</li>
            <li class="">3</li>
            <li class="on"> <font-awesome-icon icon="fa-regular fa-check" /></li>
          </ol>
        </div>
        <p> 비밀번호가 재설정되었습니다. </p>
      </div>

      <div class="box__result">
        <strong>test****</strong>
        <p>가입일: 2023-07-20</p>
      </div>

      <div class="panel__btns">
        <button class="btn--brand" @click="router.get('/html/app-LoginReadingMath')">로그인</button>
      </div>
    </div>
  </AppLoginLayout>
</template>

<script setup lang="ts">
  import AppLoginLayout from '@/html/layouts/AppLoginLayout.vue';
  import { router } from '@inertiajs/vue3';
</script>
