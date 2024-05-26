<template>
  <AppLoginLayout>
    <div class="account_wrap">
      <button class="btn--back" @click="router.get('/html/app-LoginReadingMath')">
        <font-awesome-icon icon="fa-regular fa-arrow-left" />
        뒤로가기
      </button>

      <div class="title">
        <div>
          <h2>비밀번호 재설정</h2>
          <ol class="breadcrumb">
            <li class="on">1</li>
            <li class="">2</li>
            <li class="">3</li>
            <li class=""> <font-awesome-icon icon="fa-regular fa-check" /></li>
          </ol>
        </div>
        <p
          >리딩수학 가입 시 등록한 아이디와 학생 이름, 학부모 휴대폰번호를 <br />
          입력하시면 비밀번호를 재설정 하실 수 있습니다.</p
        >
      </div>

      <div class="panel_group">
        <div class="input">
          <font-awesome-icon icon="fa-regular fa-user" />
          <input type="text" class="input__text" placeholder="아이디" />
        </div>
        <div class="input">
          <font-awesome-icon icon="fa-regular fa-user" />
          <input type="text" class="input__text" placeholder="학생 이름" />
        </div>
        <div class="input">
          <font-awesome-icon icon="fa-regular fa-mobile-notch" />
          <input type="text" class="input__text" placeholder="학부모 휴대폰 번호" />
        </div>
      </div>

      <div class="panel__btns">
        <button class="btn--brand" disabled>다음</button>
      </div>

      <!-- 아아디 찾기 -->
      <div class="login_btns_wrap">
        <a href="javascript:;" @click="router.get('/html/app-AccountIdStep01')">
          <font-awesome-icon icon="fa-regular fa-user" />
          아이디 찾기</a
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
          <h2>비밀번호 재설정</h2>
          <ol class="breadcrumb">
            <li class="on">1</li>
            <li class="">2</li>
            <li class="">3</li>
            <li class=""> <font-awesome-icon icon="fa-regular fa-check" /></li>
          </ol>
        </div>
        <p
          >리딩수학 가입 시 등록한 아이디와 학생 이름, 학부모 휴대폰번호를 <br />
          입력하시면 비밀번호를 재설정 하실 수 있습니다.</p
        >
      </div>

      <div class="panel_group">
        <div class="input error">
          <font-awesome-icon icon="fa-regular fa-user" />
          <input type="text" class="input__text" placeholder="아이디" />
        </div>
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
        <li>아이디: 영문/숫자 각 1가지 이상을 포함한 6~12자로 입력해 주세요.</li>
        <li>학생 이름: 한글만 입력할 수 있어요.</li>
        <li>학부모 휴대폰 번호: 숫자만 입력할 수 있어요.</li>
      </ul>

      <div class="panel__btns">
        <button class="btn--brand" disabled>다음</button>
      </div>

      <!-- 아아디 찾기 -->
      <div class="login_btns_wrap">
        <a href="javascript:;" @click="router.get('/html/app-AccountIdStep01')">
          <font-awesome-icon icon="fa-regular fa-user" />
          아이디 찾기</a
        >
        <a href="javascript:;" @click="router.get('/html/app-LoginReadingMath')">
          <font-awesome-icon icon="fa-regular fa-arrow-right-to-bracket" />
          로그인하러 가기</a
        >
      </div>
    </div>

    <hr />

    <div class="account_wrap">
      <button class="btn--back" @click="router.get('/html/app-AccountPwStep01')">
        <font-awesome-icon icon="fa-regular fa-arrow-left" />
        뒤로가기
      </button>

      <div class="title">
        <div>
          <h2>비밀번호 재설정</h2>
          <ol class="breadcrumb">
            <li class="on">1</li>
            <li class="">2</li>
            <li class="">3</li>
            <li class=""> <font-awesome-icon icon="fa-regular fa-check" /></li>
          </ol>
        </div>
        <p
          >리딩수학 가입 시 등록한 아이디와 학생 이름, 학부모 휴대폰번호를 입력하시면 비밀번호를 재설정 하실 수
          있습니다.</p
        >
      </div>

      <div class="box__result">
        <p>검색된 회원이 없습니다.</p>
      </div>

      <div class="panel__btns--row">
        <button class="btn--sub">다시 찾기</button>
        <button class="btn--brand">회원 가입</button>
      </div>

      <!-- 아아디 찾기 -->
      <div class="login_btns_wrap">
        <a href="javascript:;" @click="router.get('/html/app-AccountIdStep01')">
          <font-awesome-icon icon="fa-regular fa-user" />
          아이디 찾기</a
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
          <h2>비밀번호 재설정</h2>
          <ol class="breadcrumb">
            <li class="on">1</li>
            <li class="">2</li>
            <li class="">3</li>
            <li class=""> <font-awesome-icon icon="fa-regular fa-check" /></li>
          </ol>
        </div>
        <p
          >리딩수학 가입 시 등록한 아이디와 학생 이름, 학부모 휴대폰번호를 <br />
          입력하시면 비밀번호를 재설정 하실 수 있습니다.</p
        >
      </div>

      <div class="panel_group">
        <div class="input">
          <font-awesome-icon icon="fa-regular fa-user" />
          <input type="text" class="input__text" placeholder="아이디" />
        </div>
        <div class="input">
          <font-awesome-icon icon="fa-regular fa-user" />
          <input type="text" class="input__text" placeholder="학생 이름" />
        </div>
        <div class="input">
          <font-awesome-icon icon="fa-regular fa-mobile-notch" />
          <input type="text" class="input__text" placeholder="학부모 휴대폰 번호" />
        </div>
      </div>

      <div class="panel__btns">
        <button class="btn--brand" @click="router.get('/html/app-AccountPwStep02')">다음</button>
      </div>

      <!-- 아아디 찾기 -->
      <div class="login_btns_wrap">
        <a href="javascript:;" @click="router.get('/html/app-AccountIdStep01')">
          <font-awesome-icon icon="fa-regular fa-user" />
          아이디 찾기</a
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
