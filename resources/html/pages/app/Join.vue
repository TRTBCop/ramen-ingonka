<template>
  <AppLoginLayout>
    <div class="login_wrap">
      <button class="btn--back" @click="router.get('/html/app-LoginReadingMath')">
        <font-awesome-icon icon="fa-regular fa-arrow-left" />
        뒤로가기
      </button>
      <div class="title">
        <h2>회원가입</h2>
        <p>회원가입에 필요한 정보를 입력해 주세요</p>
      </div>

      <div class="join_wrap">
        <div class="input__box">
          <h4>계정 정보</h4>
          <div class="input--row">
            <div class="input error">
              <font-awesome-icon icon="fa-regular fa-user" />
              <input type="text" class="input__text" placeholder="아이디" />
            </div>
            <button class="btn--gray">중복확인</button>
          </div>
          <div class="input">
            <font-awesome-icon icon="fa-regular fa-lock" />
            <input type="password" class="input__text" placeholder="비밀번호" />
            <button class="btn_pw">
              <font-awesome-icon icon="fa-regular fa-eye" />
            </button>
          </div>
          <div class="input">
            <font-awesome-icon icon="fa-regular fa-lock" />
            <input type="password" class="input__text" placeholder="비밀번호 확인" />
            <button class="btn_pw">
              <font-awesome-icon icon="fa-regular fa-eye" />
            </button>
          </div>

          <ul class="box__error">
            <li>
              <span class="">다시 로그인해 주세요</span>
            </li>
            <li>
              <span class="">다시 로그인해 주세요</span>
            </li>
          </ul>
        </div>

        <div class="input__box">
          <h4>학생 정보</h4>
          <div class="input">
            <font-awesome-icon icon="fa-regular fa-user" />
            <input type="text" class="input__text" placeholder="이름" />
          </div>
          <div class="input">
            <font-awesome-icon icon="fa-regular fa-calendar" />
            <input type="text" class="input__text" placeholder="생년월일 8자리" />
          </div>
          <div class="input">
            <font-awesome-icon icon="fa-regular fa-mobile-notch" />
            <input type="text" class="input__text" placeholder="휴대폰" />
          </div>
        </div>

        <div class="input__box">
          <h4>학부모 정보</h4>
          <div class="input">
            <font-awesome-icon icon="fa-regular fa-user" />
            <input type="text" class="input__text" placeholder="이름" />
          </div>
          <div class="input--row">
            <div class="input">
              <font-awesome-icon icon="fa-regular fa-mobile-notch" />
              <input type="text" class="input__text" placeholder="휴대폰" />
            </div>
            <button class="btn--gray">인증번호 발송</button>
          </div>
          <div class="input--row">
            <div class="input">
              <font-awesome-icon icon="fa-regular fa-hashtag" />
              <input type="text" class="input__text" placeholder="인증번호" disabled />
            </div>
            <button class="btn--gray">인증번호 확인</button>
          </div>
          <div class="input--row">
            <div class="input">
              <font-awesome-icon icon="fa-regular fa-hashtag" />
              <input type="text" class="input__text" placeholder="인증번호" />
              <p class="timer">01:23</p>
            </div>
            <button class="btn--gray">인증번호 확인</button>
          </div>
        </div>

        <div class="input__box">
          <h4>이벤트 정보</h4>
          <div class="input">
            <font-awesome-icon icon="fa-regular fa-party-horn" />
            <input type="text" class="input__text" placeholder="추천코드" />
          </div>
        </div>
      </div>

      <div class="input__box">
        <h4>이용 동의</h4>
        <div class="join--row border checkbox__wrap">
          <input id="ck01" type="checkbox" />
          <label for="ck01"> 모두 동의합니다. </label>
        </div>
        <hr />
        <div class="join--row checkbox__wrap">
          <input id="ck02" type="checkbox" />
          <label for="ck02"> 서비스 이용약관 동의 (필수) </label>
          <a href="javascript:;">보기</a>
        </div>
        <div class="join--row checkbox__wrap">
          <input id="ck03" type="checkbox" />
          <label for="ck03"> 개인정보 처리 방침 동의 (필수) </label>
          <a href="javascript:;">보기</a>
        </div>
        <div class="join--row checkbox__wrap">
          <input id="ck04" type="checkbox" />
          <label for="ck04"> 마케팅 활용 동의 (선택) </label>
          <a href="javascript:;">보기</a>
        </div>
      </div>

      <div class="btns">
        <button class="btn--sub">취소</button>
        <button class="btn--brand">회원가입</button>
      </div>
    </div>
  </AppLoginLayout>
</template>

<script setup lang="ts">
  import AppLoginLayout from '@/html/layouts/AppLoginLayout.vue';
  import { router } from '@inertiajs/vue3';
</script>
