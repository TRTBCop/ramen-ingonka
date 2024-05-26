<template>
  <AppLoginLayout>
    <div class="grid pv-0">
      <div class="frame col">
        <div class="container--sm">
          <h2 class="brand_logo"> LOGIN </h2>
          <div class="panel_group">
            <div
              class="input"
              :class="{
                error: errorMessage.length > 0,
              }"
            >
              <font-awesome-icon icon="fa-regular fa-user" />
              <input
                v-model="formData.access_id"
                type="text"
                class="input__text"
                :placeholder="t('placehoder.access_id')"
                @keyup.enter="handleLoginAction"
              />
            </div>
            <div
              class="input"
              :class="{
                error: errorMessage.length > 0,
              }"
            >
              <font-awesome-icon icon="fa-regular fa-lock" />
              <input
                v-model="formData.password"
                class="input__text"
                :placeholder="t('placehoder.password')"
                :type="showPassword ? 'text' : 'password'"
                @keyup.enter="handleLoginAction"
              />
              <button class="btn_pw" @click="showPassword = !showPassword">
                <font-awesome-icon :icon="`fa-regular ${showPassword ? 'fa-eye-slash' : 'fa-eye'}`" />
              </button>
            </div>
          </div>

          <ul v-if="errorMessage.length > 0" class="box__error">
            <li v-for="(message, i) in errorMessage" :key="i">
              <span>{{ message }}</span>
            </li>
          </ul>
          <div class="checkbox__wrap">
            <input id="keep" v-model="rememberId" type="checkbox" />
            <label for="keep">아이디 저장</label>
          </div>

          <div class="panel__btns">
            <button class="btn--brand" @click="handleLoginAction">다음</button>
          </div>

          <!-- 비밀번호 찾기 -->
          <div class="login_btns_wrap">
            <Link :href="route('app.register.create')">
              <font-awesome-icon icon="fa-regular fa-user-plus" />
              회원가입
            </Link>
            <Link :href="route('app.register.find-account')">
              <font-awesome-icon icon="fa-regular fa-user" />
              아이디 찾기
            </Link>
            <Link :href="route('app.register.find-password')">
              <font-awesome-icon icon="fa-regular fa-lock" />
              비밀번호 재설정
            </Link>
            <a href="javascript:;" class="mt-1" @click="goLoginPage">
              <font-awesome-icon icon="fa-regular fa-arrow-left" />뒤로 가기
            </a>
          </div>
        </div>
      </div>
      <div class="frame col bg--gray">
        <LoginSlider />
      </div>
    </div>
  </AppLoginLayout>
</template>

<script setup lang="ts">
  import { useI18n } from 'vue-i18n';
  import { ref, watch, onMounted, nextTick } from 'vue';
  import { Link, useForm, usePage } from '@inertiajs/vue3';
  import { isEmpty } from 'lodash';
  import AppLoginLayout from '@/app/layouts/AppLoginLayout.vue';
  import LoginSlider from '@/app/components/login/LoginSlider.vue';
  import { goLoginPage } from '@/app/core/helpers/routerHelper';

  const { t } = useI18n();

  const pageData = usePage();

  const formData = useForm({
    access_id: '',
    password: '',
  });

  const rememberId = ref(false);

  const errorMessage = ref<string[]>([]);

  const showPassword = ref(false);

  const handleLoginAction = () => {
    // 아이디 저장
    if (rememberId.value) {
      localStorage.setItem('userId', formData.access_id);
    }

    formData.post(route('app.auth.store'));
  };

  const setErrorMessage = (message: string[]) => {
    errorMessage.value = message;
  };

  const handleErrorMessage = () => {
    if (!isEmpty(pageData.props.errors)) {
      setErrorMessage(Object.values(pageData.props.errors));
    }
  };

  onMounted(() => {
    // 기억된 아이디 있을 경우 가져옴
    const userId = localStorage.getItem('userId');
    if (userId) {
      formData.access_id = userId;
      rememberId.value = true;
    }

    // 에러 메시지 노출
    nextTick(() => {
      handleErrorMessage();
    });
  });

  // 아이디 저장 체크 해제시 액션
  watch(
    () => rememberId.value,
    (newVal) => {
      if (!newVal) {
        localStorage.removeItem('userId');
      }
    },
  );

  // 에러 메시지 노출
  watch(
    () => pageData.props.errors,
    () => {
      handleErrorMessage();
    },
  );
</script>

<style>
  .swiper-pagination-horizontal {
    bottom: 0 !important;
  }
</style>
