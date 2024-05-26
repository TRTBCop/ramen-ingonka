<template>
  <AppLoginLayout>
    <div class="account_wrap">
      <button class="btn--back" @click="router.get('/html/app-LoginReadingMath')">
        <font-awesome-icon icon="fa-regular fa-arrow-left" />
        뒤로가기
      </button>

      <div class="title">
        <div>
          <h2>아이디 찾기</h2>
        </div>
        <p>회원가입 시 등록한 정보를 입력해 주세요.</p>
      </div>

      <div class="panel_group">
        <div class="input">
          <font-awesome-icon icon="fa-regular fa-user" />
          <input type="text" class="input__text" placeholder="학생 이름" />
        </div>
        <div class="input">
          <font-awesome-icon icon="fa-regular fa-mobile-notch" />
          <input type="text" class="input__text" placeholder="학부모 휴대폰 번호" />
        </div>
      </div>

      <ul class="box__error">
        <li>학생 이름: 한글만 입력할 수 있어요.</li>
      </ul>

      <div class="panel__btns">
        <button class="btn--brand" disabled>다음</button>
      </div>

      <!-- 비밀번호 찾기 -->
      <div class="login_btns_wrap">
        <a href="javascript:;" @click="router.get('/html/app-AccountPwStep01')">
          <font-awesome-icon icon="fa-regular fa-lock" />
          비밀번호 재설정</a
        >
        <a href="javascript:;" @click="router.get('/html/app-LoginReadingMath')">
          <font-awesome-icon icon="fa-regular fa-arrow-right-to-bracket" />
          로그인하러 가기</a
        >
      </div>
    </div>

    <hr />

    <div class="account_wrap">
      <button class="btn--back" @click="router.get('/html/app-LoginReadingMath')">
        <font-awesome-icon icon="fa-regular fa-arrow-left" />
        뒤로가기
      </button>

      <div class="title">
        <div>
          <h2>아이디 찾기</h2>
        </div>
        <p>회원가입 시 등록한 정보를 입력해 주세요.</p>
      </div>

      <div class="panel_group">
        <div class="input error">
          <font-awesome-icon icon="fa-regular fa-user" />
          <input type="text" class="input__text" placeholder="학생 이름" />
        </div>
        <div class="input error">
          <font-awesome-icon icon="fa-regular fa-mobile-notch" />
          <input type="text" class="input__text" placeholder="학부모 휴대폰 번호" />
        </div>
      </div>

      <ul class="box__error">
        <li>학생 이름: 한글만 입력할 수 있어요.</li>
        <li>학부모 휴대폰 번호: 숫자만 입력할 수 있어요.</li>
      </ul>

      <div class="panel__btns">
        <button class="btn--brand" disabled>다음</button>
      </div>

      <!-- 비밀번호 찾기 -->
      <div class="login_btns_wrap">
        <a href="javascript:;" @click="router.get('/html/app-AccountPwStep01')">
          <font-awesome-icon icon="fa-regular fa-lock" />
          비밀번호 재설정</a
        >
        <a href="javascript:;" @click="router.get('/html/app-LoginReadingMath')">
          <font-awesome-icon icon="fa-regular fa-arrow-right-to-bracket" />
          로그인하러 가기</a
        >
      </div>
    </div>

    <hr />

    <div class="account_wrap">
      <button class="btn--back" @click="router.get('/html/app-LoginReadingMath')">
        <font-awesome-icon icon="fa-regular fa-arrow-left" />
        뒤로가기
      </button>

      <div class="title">
        <div>
          <h2>아이디 찾기</h2>
        </div>
        <p>회원가입 시 등록한 정보를 입력해 주세요.</p>
      </div>

      <div class="panel_group">
        <div class="input">
          <font-awesome-icon icon="fa-regular fa-user" />
          <input type="text" class="input__text" placeholder="학생 이름" value="김리딩" />
        </div>
        <div class="input">
          <font-awesome-icon icon="fa-regular fa-mobile-notch" />
          <input type="text" class="input__text" placeholder="학부모 휴대폰 번호" value="01012341234" />
        </div>
      </div>

      <div class="panel__btns">
        <button class="btn--brand" @click="router.get('/html/app-AccountIdFinish')">다음</button>
      </div>

      <!-- 비밀번호 찾기 -->
      <div class="login_btns_wrap">
        <a href="javascript:;" @click="router.get('/html/app-AccountPwStep01')">
          <font-awesome-icon icon="fa-regular fa-lock" />
          비밀번호 재설정</a
        >
        <a href="javascript:;" @click="router.get('/html/app-LoginReadingMath')">
          <font-awesome-icon icon="fa-regular fa-arrow-right-to-bracket" />
          로그인하러 가기</a
        >
      </div>
    </div>
  </AppLoginLayout>
</template>

<script setup lang="ts">
  import AppLoginLayout from '@/html/layouts/AppLoginLayout.vue';
  import { router } from '@inertiajs/vue3';
</script>
