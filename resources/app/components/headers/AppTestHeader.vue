<template>
  <header class="navbar">
    <div class="col">
      <a lass="logo" @click="showErrorModal('진단평가를 중단하시겠습니까?')"
        ><img src="@/assets/img/math/ico_logo.svg" alt=""
      /></a>
      <ul class="breadcrumb">
        <li
          ><strong>{{ userSchoolInfo?.step }} {{ userSchoolInfo?.gradeTerm }}</strong></li
        >
        <li
          ><span>진단평가</span>
          <font-awesome-icon class="ico_chevron" icon="fa-regular fa-chevron-right" />
        </li>
        <li
          ><span>{{ pageData.props.test.title }}</span>
        </li>
      </ul>
    </div>

    <div class="col">
      <template v-if="isShowTimer">
        <div class="timer">
          <span
            class="time"
            :class="{
              timeover: isTimeOver,
            }"
            >{{ secondsToMinutesSeconds(testingTime) }}</span
          >
        </div>
      </template>

      <div class="btns">
        <button @click="showErrorModal('진단평가를 중단하시겠습니까?')">
          <font-awesome-icon icon="fa-regular fa-right-from-bracket" />
        </button>
      </div>
    </div>
  </header>

  <ConfirmModal v-model:modal-state="errorModalState" />
</template>

<script setup lang="ts">
  import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
  import { TestPageProps } from '@/app/types/pageData';
  import { Link, usePage } from '@inertiajs/vue3';
  import ConfirmModal from '@/app/components/modals/ConfirmModal.vue';
  import { getUserSchoolInfo } from '@/app/core/helpers/userHelper';
  import { secondsToMinutesSeconds } from '@/app/core/helpers/formattingHelper';
  import { ModalState } from '@/app/types/modals';
  import { useTestStoreWithOut } from '@/app/stores/modules/tests';
  import { goMainPage } from '@/app/core/helpers/routerHelper';
  import { updateTestTimer } from '@/app/api/tests';

  const pageData = usePage<TestPageProps>();
  const testStore = useTestStoreWithOut();

  const isCompletedPage = computed(() => pageData.props.completed_at);

  const isExtendPage = computed(() => pageData.props.is_extend);

  const isShowTimer = computed(() => {
    if (isCompletedPage.value) return false;

    if (isExtendPage.value) return false;

    return true;
  });

  const errorModalState = ref<ModalState>({ show: false, message: '', confirmEvent: null });

  const userSchoolInfo = getUserSchoolInfo();

  const testingTime = computed(() => testStore.getTestingTime);

  const isTimeOver = computed(() => testStore.getIsOverTime);

  /** 시간 저장 후 메인 페이지로 */
  async function saveTimeAndTrainingMainPage() {
    try {
      await updateTestTimer(pageData.props.test.id, testingTime.value);

      goMainPage();
    } catch (err) {
      console.log(err);
    }
  }

  const showErrorModal = (message: string) => {
    errorModalState.value.show = true;
    errorModalState.value.message = message;
    errorModalState.value.confirmEvent = saveTimeAndTrainingMainPage;
  };

  /** 새로고침 및 창닫기 이벤트 */
  const handlePageReload = (event: any) => {
    event.preventDefault();
    updateTestTimer(pageData.props.test.id, testingTime.value);

    event.returnValue = '';
  };

  onMounted(() => {
    window.addEventListener('beforeunload', handlePageReload);
  });

  onBeforeUnmount(() => {
    window.removeEventListener('beforeunload', handlePageReload);
  });
</script>
