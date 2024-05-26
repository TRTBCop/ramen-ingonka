<template>
  <AppMypage>
    <h3>계정 정보</h3>
    <div class="input__box mb-0">
      <dl class="mypage__info">
        <dt>학생이름</dt>
        <dd>김리딩</dd>
        <dt>아이디</dt>
        <dd>test1234</dd>
        <dt>가입일</dt>
        <dd>2023-10-15</dd>
      </dl>
    </div>
    <a class="btn__withdraw" href="javascript:;" @click="router.get('/html/app-MypageWithdraw')">회원 탈퇴</a>

    <h3>학생 정보</h3>
    <div class="input__box">
      <div class="input">
        <font-awesome-icon icon="fa-regular fa-user" />
        <input type="text" class="input__text" value="김리딩" readonly />
      </div>
      <div class="input">
        <font-awesome-icon icon="fa-regular fa-calendar" />
        <input type="text" class="input__text" value="20160101" readonly />
      </div>
      <div class="input">
        <font-awesome-icon icon="fa-regular fa-mobile-notch" />
        <input type="text" class="input__text" value="01012341234" readonly />
      </div>
      <div class="input--row">
        <div class="input">
          <font-awesome-icon icon="fa-regular fa-school" />
          <input type="text" class="input__text" value="4학년 2학기" readonly />
        </div>
        <button class="btn--gray">변경하기</button>
      </div>
    </div>

    <h3>비밀번호 변경</h3>
    <div class="input__box">
      <div class="input">
        <font-awesome-icon icon="fa-regular fa-lock" />
        <input type="password" class="input__text" placeholder="새 비밀번호" />
        <button class="btn_pw">
          <font-awesome-icon icon="fa-regular fa-eye" />
        </button>
      </div>
      <div class="input">
        <font-awesome-icon icon="fa-regular fa-lock" />
        <input type="password" class="input__text" placeholder="새 비밀번호 확인" />
        <button class="btn_pw">
          <font-awesome-icon icon="fa-regular fa-eye" />
        </button>
      </div>
    </div>

    <h3>학부모 정보</h3>
    <div class="input__box">
      <div class="input">
        <font-awesome-icon icon="fa-regular fa-user" />
        <input type="text" class="input__text" value="김부모" readonly />
      </div>
      <div class="input--row">
        <div class="input">
          <font-awesome-icon icon="fa-regular fa-mobile-notch" />
          <input type="text" class="input__text" value="01012341234" />
        </div>
        <button class="btn--gray" disabled>인증번호 발송</button>
      </div>
      <div class="input--row">
        <div class="input">
          <font-awesome-icon icon="fa-regular fa-hashtag" />
          <input type="text" class="input__text" placeholder="인증번호 4자리" disabled />
        </div>
        <button class="btn--gray" disabled>인증번호 확인</button>
      </div>
    </div>

    <h3>마케팅 활용 동의</h3>
    <div class="input__box">
      <div class="checkbox_wrap checkbox--sm">
        <label class="row">
          <input type="checkbox" />
          동의합니다.
        </label>
        <button class="is--size5 txt--bold">약관 보기</button>
      </div>
    </div>

    <h3>SNS 연동</h3>
    <div class="input__box row">
      <span class="row"> <img src="../../../assets/img/math/ico_naver.svg" alt="" />네이버</span>
      <button class="is--size5">연동 해제</button>
    </div>

    <div class="btns">
      <button class="btn--sub">메인으로</button>
      <button class="btn--brand">저장하기</button>
    </div>
  </AppMypage>
</template>
<script setup lang="ts">
  import AppMypage from '@/html/layouts/AppMypage.vue';
  import { router } from '@inertiajs/vue3';
</script>
