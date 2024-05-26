<template>
  <AppMypage>
    <button class="btn--back" @click="router.get('/html/app-AccountPwStep01')">
      <font-awesome-icon icon="fa-regular fa-arrow-left" />
      뒤로가기
    </button>

    <h3>계정 정보</h3>
    <div class="input__box">
      <dl class="mypage__info">
        <dt>학생이름</dt>
        <dd>김리딩</dd>
        <dt>아이디</dt>
        <dd>test1234</dd>
        <dt>가입일</dt>
        <dd>2023-10-15</dd>
      </dl>
    </div>

    <h3>탈퇴 사유</h3>
    <div class="input__box">
      <ul class="lst_withdraw">
        <li>
          <label for="withdraw_chk-1">
            <input id="withdraw_chk-1" type="radio" name="withdraw" value="withdraw_chk-1" />
            학습 콘텐츠 불만
          </label>
        </li>
        <li>
          <label for="withdraw_chk-2">
            <input id="withdraw_chk-2" type="radio" name="withdraw" value="withdraw_chk-2" />
            고객센터 불만
          </label>
        </li>
        <li>
          <label for="withdraw_chk-3">
            <input id="withdraw_chk-3" type="radio" name="withdraw" value="withdraw_chk-3" />
            시스템 장애 불만
          </label>
        </li>
        <li>
          <label for="withdraw_chk-4">
            <input id="withdraw_chk-4" type="radio" name="withdraw" value="withdraw_chk-4" />
            회원 혜택 불만
          </label>
        </li>
        <li>
          <label for="withdraw_chk-5">
            <input id="withdraw_chk-5" type="radio" name="withdraw" value="withdraw_chk-5" />
            개인 사정
          </label>
        </li>
        <li>
          <label for="withdraw_chk-6">
            <input id="withdraw_chk-6" type="radio" name="withdraw" value="withdraw_chk-6" />
            기타
          </label>
        </li>
      </ul>
      <div class="input">
        <input type="text" class="input__text" placeholder="리딩수학 이용 중 아쉬운 점에 대해 알려주세요." />
      </div>
    </div>

    <h3>탈퇴 신청 전 유의사항</h3>
    <div class="input__box">
      <ul class="lst_withdraw">
        <li>
          <label>
            <input type="checkbox" />
            회원 탈퇴 완료 시 회원님의 모든 개인 정보와 학습 이력이 삭제되며, 복구가 불가능합니다.
          </label>
        </li>
        <li>
          <label>
            <input type="checkbox" />
            회원 탈퇴 완료 즉시 리딩수학의 모든 서비스를 사용할 수 없게 됩니다.
          </label>
        </li>
        <li>
          <label>
            <input type="checkbox" />
            학습기간이 남은 경우, 학습기간이 모두 종료된 이후 회원 탈퇴가 가능합니다.
          </label>
        </li>
        <li>
          <label>
            <input type="checkbox" />
            회원 혜택 불만
          </label>
        </li>
        <li>
          <label class="txt--bold">
            <input type="checkbox" />
            탈퇴 신청 전 유의사항에 모두 동의합니다.
          </label>
        </li>
      </ul>
    </div>

    <div class="btns">
      <buttton class="btn--sub">취소</buttton>
      <buttton class="btn--brand">탈퇴하기</buttton>
    </div>
  </AppMypage>
</template>
<script setup lang="ts">
  import AppMypage from '@/html/layouts/AppMypage.vue';
  import { router } from '@inertiajs/vue3';
</script>
