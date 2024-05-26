<template>
  <MyLayout page-name="마이페이지">
    <button class="btn--back" @click="goMyPage">
      <font-awesome-icon icon="fa-regular fa-arrow-left" />
      뒤로가기
    </button>

    <h3>계정 정보</h3>
    <div class="input__box">
      <dl class="mypage__info">
        <dt>학생이름</dt>
        <dd>{{ userData.name }}</dd>
        <dt>아이디</dt>
        <dd>{{ userData.access_id }}</dd>
        <dt>가입일</dt>
        <dd>{{ userData.created_at.slice(0, 10) }}</dd>
      </dl>
    </div>

    <h3>탈퇴 사유</h3>
    <div class="input__box">
      <ul class="lst_withdraw">
        <li v-for="(reason, index) in withdrawReasons" :key="index">
          <label>
            <input v-model="selectedReason" type="radio" :value="reason" @change="checkOther" />
            {{ reason }}
          </label>
        </li>
      </ul>
      <div class="input">
        <input
          v-model="additionalReason"
          :disabled="!isOtherSelected"
          type="text"
          class="input__text"
          placeholder="리딩수학 이용 중 아쉬운 점에 대해 알려주세요."
        />
      </div>
    </div>

    <h3>탈퇴 신청 전 유의사항</h3>
    <div class="input__box">
      <ul class="lst_withdraw">
        <li v-for="(agreement, index) in agreements" :key="index">
          <label>
            <input v-model="agreement.checked" type="checkbox" />
            {{ agreement.text }}
          </label>
        </li>
        <li>
          <label class="txt--bold">
            <input v-model="allChecked" type="checkbox" class="check-all" @change="checkAll" />
            탈퇴 신청 전 유의사항에 모두 동의합니다.
          </label>
        </li>
      </ul>
    </div>

    <div class="btns">
      <button class="btn--sub" @click="goPrevious">취소</button>
      <button class="btn--brand" @click="clickWithdraw">탈퇴하기</button>
    </div>
  </MyLayout>

  <ConfirmModal v-model:modal-state="confirmModalState" />
</template>
<script setup lang="ts">
  import MyLayout from '@/app/layouts/MyLayout.vue';
  import { computed, ref } from 'vue';
  import { getUserData } from '@/app/core/helpers/userHelper';
  import ConfirmModal from '@/app/components/modals/ConfirmModal.vue';
  import { ModalState } from '@/app/types/modals';
  import { postWithDraw } from '@/app/api/withdraw';
  import { useSystemStoreWithOut } from '@/app/stores/modules/system';
  import { goMyPage } from '@/app/core/helpers/routerHelper';

  const userData = computed(() => getUserData());

  const systemStore = useSystemStoreWithOut();

  const confirmModalState = ref<ModalState>({
    show: false,
    title: '',
    message: '',
    size: 'sm',
    confirmEvent: null,
  });

  //탈퇴사유
  const withdrawReasons = [
    '학습 콘텐츠 불만',
    '고객센터 불만',
    '시스템 장애 불만',
    '회원 혜택 불만',
    '개인 사정',
    '기타',
  ];

  const selectedReason = ref('');
  const additionalReason = ref('');
  const isOtherSelected = ref(false);

  const checkOther = () => {
    isOtherSelected.value = selectedReason.value === '기타';
  };

  //탈퇴 유의사항
  const agreements = ref([
    { text: '회원 탈퇴 완료 시 회원님의 모든 개인 정보와 학습 이력이 삭제되며, 복구가 불가능합니다.', checked: false },
    { text: '회원 탈퇴 완료 즉시 리딩수학의 모든 서비스를 사용할 수 없게 됩니다.', checked: false },
    { text: '학습기간이 남은 경우, 학습기간이 모두 종료된 이후 회원 탈퇴가 가능합니다.', checked: false },
    { text: '회원 혜택 불만', checked: false },
  ]);

  const allAgreementsChecked = computed(() => {
    return agreements.value.every((agreement) => agreement.checked);
  });

  const allChecked = ref(false);

  const checkAll = () => {
    agreements.value.forEach((agreement) => (agreement.checked = allChecked.value));
  };

  // 취소시 뒤로가기
  const goPrevious = () => {
    history.back();
  };

  //탙퇴하기
  const clickWithdraw = () => {
    if (!selectedReason.value) {
      systemStore.setModalState({
        show: true,
        message: '탈퇴 사유를 선택하세요.',
      });
      return;
    }

    if (!allAgreementsChecked.value) {
      systemStore.setModalState({
        show: true,
        message: '탈퇴 신청 전 유의사항에 모두 동의해주세요.',
      });
      return;
    }

    //탈퇴 모달 open
    confirmModalState.value.show = true;
    confirmModalState.value.message = '회원 탈퇴를 진행할까요?';
    confirmModalState.value.confirmEvent = onClickConfirm;
  };

  const apiWithdraw = async () => {
    handleWithdraw((success: boolean, message: string) => {
      if (success) {
        systemStore.setModalState({
          show: true,
          message: message,
        });
        window.location.href = route('app.auth.create');
      } else {
        systemStore.setModalState({
          show: true,
          message: message,
        });
      }
    });
  };

  const handleWithdraw = async (callback: (success: boolean, message: string) => void) => {
    try {
      const { data } = await postWithDraw();

      callback(data.success, data.message);
    } catch (err: any) {
      console.log('err', err, err.message);
      callback(false, err.data);
    }
  };

  // 탈퇴하기 확인시
  const onClickConfirm = () => {
    apiWithdraw();
  };
</script>
