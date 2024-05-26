<template>
  <AppLoginLayout>
    <div class="account_wrap">
      <button class="btn--back" @click="router.get('/html/app-AccountPwStep02')">
        <font-awesome-icon icon="fa-regular fa-arrow-left" />
        뒤로가기
      </button>

      <div class="title">
        <div>
          <h2>비밀번호 재설정</h2>
          <ol class="breadcrumb">
            <li class="">1</li>
            <li class="">2</li>
            <li class="on">3</li>
            <li class=""> <font-awesome-icon icon="fa-regular fa-check" /></li>
          </ol>
        </div>
        <p>새롭게 변경하실 비밀번호를 입력해 주세요.</p>
      </div>

      <div class="box__result">
        <strong>test1234</strong>
        <p>가입일: 2023-07-20</p>
      </div>

      <div class="panel_group">
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
      </div>

      <div class="panel__btns">
        <button class="btn--brand" disabled>다음</button>
      </div>

      <!-- 비밀번호 찾기 -->
      <div class="login_btns_wrap">
        <a href="javascript:;" @click="router.get('/html/app-LoginReadingMath')">
          <font-awesome-icon icon="fa-regular fa-arrow-right-to-bracket" />
          로그인하러 가기</a
        >
      </div>
    </div>

    <hr />

    <div class="account_wrap">
      <button class="btn--back" @click="router.get('/html/app-AccountPwStep02')">
        <font-awesome-icon icon="fa-regular fa-arrow-left" />
        뒤로가기
      </button>

      <div class="title">
        <div>
          <h2>비밀번호 재설정</h2>
          <ol class="breadcrumb">
            <li class="">1</li>
            <li class="">2</li>
            <li class="on">3</li>
            <li class=""> <font-awesome-icon icon="fa-regular fa-check" /></li>
          </ol>
        </div>
        <p>새롭게 변경하실 비밀번호를 입력해 주세요.</p>
      </div>

      <div class="box__result">
        <strong>test1234</strong>
        <p>가입일: 2023-07-20</p>
      </div>

      <div class="panel_group">
        <div class="input error">
          <font-awesome-icon icon="fa-regular fa-lock" />
          <input type="password" class="input__text" placeholder="비밀번호" value="11111111" />
          <button class="btn_pw">
            <font-awesome-icon icon="fa-regular fa-eye" />
          </button>
        </div>
        <div class="input error">
          <font-awesome-icon icon="fa-regular fa-lock" />
          <input type="password" class="input__text" placeholder="비밀번호 확인" value="11111111" />
          <button class="btn_pw">
            <font-awesome-icon icon="fa-regular fa-eye" />
          </button>
        </div>
      </div>

      <ul class="box__error">
        <li>
          <span class="">비밀번호: 영문/숫자/특수문자 각 1가지 이상을 포함한 8~18자로 입력해 주세요.</span>
        </li>
        <li>
          <span class="">비밀번호 확인: 비밀번호가 일치하지 않아요.</span>
        </li>
      </ul>

      <div class="panel__btns">
        <button class="btn--brand" disabled>다음</button>
      </div>

      <!-- 비밀번호 찾기 -->
      <div class="login_btns_wrap">
        <a href="javascript:;" @click="router.get('/html/app-LoginReadingMath')">
          <font-awesome-icon icon="fa-regular fa-arrow-right-to-bracket" />
          로그인하러 가기</a
        >
      </div>
    </div>

    <hr />

    <div class="account_wrap">
      <button class="btn--back" @click="router.get('/html/app-AccountPwStep02')">
        <font-awesome-icon icon="fa-regular fa-arrow-left" />
        뒤로가기
      </button>

      <div class="title">
        <div>
          <h2>비밀번호 재설정</h2>
          <ol class="breadcrumb">
            <li class="">1</li>
            <li class="">2</li>
            <li class="on">3</li>
            <li class=""> <font-awesome-icon icon="fa-regular fa-check" /></li>
          </ol>
        </div>
        <p>새롭게 변경하실 비밀번호를 입력해 주세요.</p>
      </div>

      <div class="box__result">
        <strong>test1234</strong>
        <p>가입일: 2023-07-20</p>
      </div>

      <div class="panel_group">
        <div class="input">
          <font-awesome-icon icon="fa-regular fa-lock" />
          <input type="password" class="input__text" placeholder="비밀번호" value="11111111" />
          <button class="btn_pw">
            <font-awesome-icon icon="fa-regular fa-eye" />
          </button>
        </div>
        <div class="input">
          <font-awesome-icon icon="fa-regular fa-lock" />
          <input type="password" class="input__text" placeholder="비밀번호 확인" value="11111111" />
          <button class="btn_pw">
            <font-awesome-icon icon="fa-regular fa-eye" />
          </button>
        </div>
      </div>

      <div class="panel__btns">
        <button class="btn--brand" @click="router.get('/html/app-AccountPwFinish')">다음</button>
      </div>

      <!-- 비밀번호 찾기 -->
      <div class="login_btns_wrap">
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
