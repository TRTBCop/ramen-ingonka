<template>
  <BoardLayout page-name="마이페이지">
    <div class="mypage">
      <div class="grid">
        <div class="frame col--sm">
          <button @click="showProfileImgModifyModal"
            ><i :class="`profile__img profile__img--lg profile__img--${getUserProfileType()}`"></i
          ></button>
          <div class="mypage__link">
            <a :class="{ current: isProfile }" href="javascript:;" @click="goMyPage">내 정보</a>
            <a v-if="isB2cUser()" :class="{ current: isPayments }" href="javascript:;" @click="goMyPaymentPage"
              >이용권 정보
            </a>
          </div>
        </div>
        <div class="frame col">
          <slot />
        </div>
      </div>
    </div>
    <ProfileImgModifyModal v-model:is-show="isShowProfileImgModifyModal" />
  </BoardLayout>
</template>

<script setup lang="ts">
  import { ref, computed } from 'vue';
  import BoardLayout from './BoardLayout.vue';
  import ProfileImgModifyModal from '@/app/components/modals/ProfileImgModifyModal.vue';
  import { getUserProfileType, isB2cUser } from '@/app/core/helpers/userHelper';
  import { goMyPage, goMyPaymentPage } from '@/app/core/helpers/routerHelper';

  const isProfile = computed(() => /profile|withdraw/.test(String(route().current())));
  const isPayments = computed(() => /payments/.test(String(route().current())));

  /** 학년 학기 변경 모달 여부 */
  const isShowProfileImgModifyModal = ref(false);

  function showProfileImgModifyModal() {
    isShowProfileImgModifyModal.value = true;
  }
</script>
