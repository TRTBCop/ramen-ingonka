<template>
  <AppLayout>
    <div class="main main__free">
      <div class="main__head">
        <MainModeButtons />

        <div class="mode mode__sub">
          <button
            :class="{
              on: !isShowElement,
            }"
            @click="hideElement"
            >학기</button
          >
          <button
            :class="{
              on: isShowElement,
            }"
            @click="showElement"
            >계통</button
          >
          <div class="mode__switch">
            <small>모두 보기</small>
            <label class="switch">
              <input v-model="isShowTotal" type="checkbox" />
              <span class="slider"></span>
            </label>
          </div>
        </div>
      </div>

      <TotalModeView v-if="isShowTotal" :is-show-element="isShowElement" :is-middle-school-type="isMiddleSchoolType" />
      <SelectModeView v-else :is-show-element="isShowElement" :is-middle-school-type="isMiddleSchoolType" />

      <div id="btn__floating__left" class="btn__floating__left">
        <!-- 중등 안보이게 -->
        <button v-if="false" class="btn--gray" @click="toggleMiddelSchoolType">
          {{ isMiddleSchoolType ? '초등' : '중등' }} 우주로 가기
        </button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
  import { ref } from 'vue';
  import MainModeButtons from '@/app/components/main/MainModeButtons.vue';
  import AppLayout from '@/app/layouts/AppLayout.vue';
  import SelectModeView from '@/app/components/main/mainFree/SelectModeView.vue';
  import TotalModeView from '@/app/components/main/mainFree/TotalModeView.vue';

  /** 모두 보기 */
  const isShowTotal = ref(false);

  /** 계통 - false인 경우 학기 */
  const isShowElement = ref(false);

  function showElement() {
    isShowElement.value = true;
  }

  function hideElement() {
    isShowElement.value = false;
  }

  const isMiddleSchoolType = ref(false);

  function toggleMiddelSchoolType() {
    isMiddleSchoolType.value = !isMiddleSchoolType.value;
  }
</script>
