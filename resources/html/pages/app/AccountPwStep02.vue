<template>
  <AppLoginLayout>
    <div class="account_wrap">
      <button class="btn--back" @click="router.get('/html/app-AccountPwStep01')">
        <font-awesome-icon icon="fa-regular fa-arrow-left" />
        뒤로가기
      </button>

      <div class="title">
        <div>
          <h2>비밀번호 재설정</h2>
          <ol class="breadcrumb">
            <li class="">1</li>
            <li class="on">2</li>
            <li class="">3</li>
            <li class=""> <font-awesome-icon icon="fa-regular fa-check" /></li>
          </ol>
        </div>
        <p>비밀번호 재설정을 위해 학부모 휴대폰번호를 인증해 주세요.</p>
      </div>

      <div class="box__result">
        <strong>test1234</strong>
        <p>가입일: 2023-07-20</p>
      </div>

      <div class="panel_group">
        <div class="input--row">
          <div class="input">
            <font-awesome-icon icon="fa-regular fa-mobile-notch" />
            <input type="text" class="input__text" placeholder="학부모 휴대폰 번호" />
          </div>
          <button class="btn--gray" disabled>인증번호 발송</button>
        </div>
        <div class="input--row">
          <div class="input">
            <font-awesome-icon icon="fa-regular fa-hashtag" />
            <input type="text" class="input__text" placeholder="인증번호 4자리" disabled />
          </div>
          <button class="btn--gray" disabled>인증번호 발송</button>
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
      <button class="btn--back" @click="router.get('/html/app-AccountPwStep01')">
        <font-awesome-icon icon="fa-regular fa-arrow-left" />
        뒤로가기
      </button>

      <div class="title">
        <div>
          <h2>비밀번호 재설정</h2>
          <ol class="breadcrumb">
            <li class="">1</li>
            <li class="on">2</li>
            <li class="">3</li>
            <li class=""> <font-awesome-icon icon="fa-regular fa-check" /></li>
          </ol>
        </div>
        <p>비밀번호 재설정을 위해 학부모 휴대폰번호를 인증해 주세요.</p>
      </div>

      <div class="box__result">
        <strong>test1234</strong>
        <p>가입일: 2023-07-20</p>
      </div>

      <div class="panel_group">
        <div class="input--row">
          <div class="input error">
            <font-awesome-icon icon="fa-regular fa-mobile-notch" />
            <input type="text" class="input__text" placeholder="학부모 휴대폰 번호" />
          </div>
          <button class="btn--gray" disabled>인증번호 발송</button>
        </div>
        <div class="input--row">
          <div class="input error">
            <font-awesome-icon icon="fa-regular fa-hashtag" />
            <input type="text" class="input__text" placeholder="인증번호 4자리" />
          </div>
          <button class="btn--gray" disabled>인증번호 발송</button>
        </div>
      </div>

      <ul class="box__error">
        <li>학부모 휴대폰 번호: 숫자만 입력할 수 있어요.</li>
        <li>인증번호: 숫자만 입력할 수 있어요.</li>
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
      <button class="btn--back" @click="router.get('/html/app-AccountPwStep01')">
        <font-awesome-icon icon="fa-regular fa-arrow-left" />
        뒤로가기
      </button>

      <div class="title">
        <div>
          <h2>비밀번호 재설정</h2>
          <ol class="breadcrumb">
            <li class="">1</li>
            <li class="on">2</li>
            <li class="">3</li>
            <li class=""> <font-awesome-icon icon="fa-regular fa-check" /></li>
          </ol>
        </div>
        <p>비밀번호 재설정을 위해 학부모 휴대폰번호를 인증해 주세요.</p>
      </div>

      <div class="box__result">
        <strong>test1234</strong>
        <p>가입일: 2023-07-20</p>
      </div>

      <div class="panel_group">
        <div class="input--row">
          <div class="input">
            <font-awesome-icon icon="fa-regular fa-mobile-notch" />
            <input type="text" class="input__text" placeholder="학부모 휴대폰 번호" value="010-2222-5182" />
          </div>
          <button class="btn--gray">다시 보내기</button>
        </div>
        <div class="input--row">
          <div class="input">
            <font-awesome-icon icon="fa-regular fa-hashtag" />
            <input type="text" class="input__text" value="1234" />
            <p class="timer">01:23</p>
          </div>
          <button class="btn--gray">인증번호 확인</button>
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
              <li class="">1</li>
              <li class="on">2</li>
              <li class="">3</li>
              <li class=""> <font-awesome-icon icon="fa-regular fa-check" /></li>
            </ol>
          </div>
          <p>비밀번호 재설정을 위해 학부모 휴대폰번호를 인증해 주세요.</p>
        </div>

        <div class="box__result">
          <strong>test1234</strong>
          <p>가입일: 2023-07-20</p>
        </div>

        <div class="panel_group">
          <div class="input--row">
            <div class="input">
              <font-awesome-icon icon="fa-regular fa-mobile-notch" />
              <input type="text" class="input__text" placeholder="학부모 휴대폰 번호" value="010-2222-5182" />
            </div>
            <button class="btn--gray" disabled>다시 보내기</button>
          </div>
          <div class="input--row">
            <div class="input">
              <font-awesome-icon icon="fa-regular fa-hashtag" />
              <input type="text" class="input__text" value="1234" />
            </div>
            <button class="btn--gray" disabled>인증번호 확인</button>
          </div>
        </div>

        <div class="panel__btns">
          <button class="btn--brand" @click="router.get('/html/app-AccountPwStep03')">다음</button>
        </div>

        <!-- 비밀번호 찾기 -->
        <div class="login_btns_wrap">
          <a href="javascript:;" @click="router.get('/html/app-LoginReadingMath')">
            <font-awesome-icon icon="fa-regular fa-arrow-right-to-bracket" />
            로그인하러 가기</a
          >
        </div>
      </div>
    </div>
  </AppLoginLayout>
</template>

<script setup lang="ts">
  import AppLoginLayout from '@/html/layouts/AppLoginLayout.vue';
  import { router } from '@inertiajs/vue3';
</script>
