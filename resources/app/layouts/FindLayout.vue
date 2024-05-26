<template>
  <div id="wrap">
    <div class="container--sm">
      <div class="account_wrap">
        <!-- 헤더 -->
        <button class="btn--back" @click="goOtherLoginPage">
          <font-awesome-icon icon="fa-regular fa-arrow-left" />
          뒤로가기
        </button>
        <div class="title">
          <div>
            <h2>{{ pageInfo.title }}</h2>
            <ol class="breadcrumb">
              <li v-for="index in stepCount" :key="index" :class="{ on: index === pageStep }">
                <!-- 마지막 단계는 체크 -->
                <font-awesome-icon v-if="index === stepCount" icon="fa-regular fa-check" />
                <template v-else>
                  {{ index }}
                </template>
              </li>
            </ol>
          </div>
          <p v-html="pageInfo.description"></p>
        </div>
        <slot></slot>

        <!-- 풋터 -->
        <div class="login_btns_wrap">
          <Link v-if="pageType === 'password'" :href="route('app.register.find-account')">
            <font-awesome-icon icon="fa-regular fa-user" />
            아이디 찾기</Link
          >
          <Link v-else :href="route('app.register.find-password')">
            <font-awesome-icon icon="fa-regular fa-lock" />
            비밀번호 재설정</Link
          >
          <Link :href="route('app.auth.other.create')">
            <font-awesome-icon icon="fa-regular fa-arrow-right-to-bracket" />
            로그인하러 가기</Link
          >
        </div>
      </div>
    </div>
  </div>
  <DefaultModal :modal-state="systemStore.getModalState" @close="systemStore.hideModalState" />
</template>

<script lang="ts" setup>
  import { onMounted, onUnmounted, nextTick, computed, PropType } from 'vue';
  import { Link, usePage } from '@inertiajs/vue3';
  import DefaultModal from '@/app/components/modals/DefaultModal.vue';
  import { useSystemStoreWithOut } from '@/app/stores/modules/system';
  import { useErrorModal } from '@/app/hooks/useErrorModal';
  import { goOtherLoginPage } from '@/app/core/helpers/routerHelper';

  const props = defineProps({
    pageType: {
      type: String as PropType<'id' | 'password'>,
      default: 'id',
    },
    pageStep: {
      type: Number as PropType<number>,
      default: 1,
    },
  });

  const systemStore = useSystemStoreWithOut();

  useErrorModal();

  /** 아이디/비밀번호 찾기 각각 스텝 수 */
  const stepCount = computed(() => (props.pageType === 'password' ? 4 : 2));

  const pageInfo = computed(() => {
    const result = {
      title: '',
      description: '',
    };

    if (props.pageType === 'id') {
      result.title = '아이디 찾기';

      switch (props.pageStep) {
        case 1:
          result.description = '회원가입 시 등록한 정보를 입력해 주세요.';
          break;
        case 2:
          result.description = '아이디 찾기가 완료되었습니다.';
          break;
      }
    } else {
      result.title = '비밀번호 찾기';

      switch (props.pageStep) {
        case 1:
          result.description =
            '<p>리딩수학 가입 시 등록한 아이디와 학생 이름, 학부모 휴대폰번호를 <br> 입력하시면 비밀번호를 재설정 하실 수 있습니다.</p>';
          break;
        case 2:
          result.description = '비밀번호 재설정을 위해 학부모 휴대폰번호를 인증해 주세요.';
          break;
        case 3:
          result.description = '새롭게 변경하실 비밀번호를 입력해 주세요.';
          break;
        case 4:
          result.description = '비밀번호가 설정되었습니다.';
          break;
      }
    }

    return result;
  });
  1;
  onMounted(() => {
    document.querySelector('meta[name=viewport]')?.setAttribute('content', 'width=device-width,initial-scale=1');

    nextTick(() => {
      if (usePage().props.flash.message) {
        alert(usePage().props.flash.message[1]);
      }
    });
  });

  onUnmounted(() => {
    document.querySelector('meta[name=viewport]')?.setAttribute('content', 'width=1024,user-scalable=no');
  });
</script>
