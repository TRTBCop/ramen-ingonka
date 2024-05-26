<template>
  <div class="keypad">
    <div class="keypad__answer">
      <input
        v-if="!isSidebar"
        type="text"
        name="answer"
        placeholder="정답을 입력해주세요."
        :value="omrValue"
        disabled
      />
    </div>
    <div class="keypad__btns">
      <button v-for="key in keyPadKeys" :key="key" type="button" @click="onClickNumber(key)">{{ key }}</button>
      <button type="button" @click="onClickDot">.</button>
      <button type="button" @click="onClickSign('-')">
        <font-awesome-icon icon="fa-regular fa-minus" />
      </button>
      <button type="button" @click="onClickSign('+')">
        <font-awesome-icon icon="fa-regular fa-plus" />
      </button>
      <button type="button" class="keypad__sub" @click="onClickReset">
        <font-awesome-icon icon="fa-regular fa-rotate-left" />
      </button>
      <button type="button" class="keypad__sub" @click="onClickBackSpace">
        <font-awesome-icon icon="fa-regular fa-backspace" />
      </button>
      <button type="button" class="keypad__check" @click="onClickCheck">
        <font-awesome-icon icon="fa-regular fa-check" />
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { computed, onUnmounted, PropType } from 'vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import { includes, isNil } from 'lodash';
  import { onMounted } from 'vue';

  const props = defineProps({
    answerRow: {
      type: Number as PropType<number>,
      default: 0,
    },
    answerCol: {
      type: Number as PropType<number | null>,
      default: null,
    },
    isSidebar: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });

  const curriculumStore = useCurriculumStoreWithOut();

  const omrPosition = computed(() => curriculumStore.getOmrFocusInfo);

  const isFinishedQuestion = computed(() => curriculumStore.getIsFinishedQuestion);

  const omrValue = computed(() => curriculumStore.getOmrValueTypeInput(omrPosition.value));

  /** 캐피드 종류 숫자 0~9, -1: 지움, -2: 초기화 */
  const keyPadKeys = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];

  function onClickNumber(value: string) {
    if (!omrPosition.value) return;

    if (omrValue.value && omrValue.value === '0') {
      if (value === '0') return;

      setOmrValue(value);
    } else {
      setOmrValue(omrValue.value ? omrValue.value + value : value);
    }
  }

  function onClickDot() {
    if (omrValue.value && /\d$/.test(String(omrValue.value)) && !includes(String(omrValue.value), '.')) {
      setOmrValue(omrValue.value + '.');
    } else if (!omrValue.value) {
      setOmrValue('0.');
    }
  }

  /** 부호 클릭 시 */
  function onClickSign(value: '+' | '-') {
    // '+' 일 때의 기능이 지금은 정의된 바 없어서 동작 안함
    // 부호는 제일 앞에만 붙을 수 있음
    if (value === '+' || String(omrValue.value).length !== 0) return;

    setOmrValue(value);
  }

  function onClickBackSpace() {
    setOmrValue(String(omrValue.value).slice(0, -1));
  }

  function onClickReset() {
    setOmrValue('');
  }

  function onClickCheck() {
    curriculumStore.setOmrFocusInfo(null);
  }

  function setOmrValue(value: string) {
    if (isFinishedQuestion.value) return;

    curriculumStore.setOmrValueTypeInput(value, omrPosition.value);
  }

  function handleKeyDown(event: KeyboardEvent) {
    const key = event.key;

    if (key >= '0' && key <= '9') {
      event.preventDefault();
      onClickNumber(key);
    } else if (key === '.') {
      event.preventDefault();
      onClickDot();
    } else if (key === '+') {
      event.preventDefault();
      onClickSign(key);
    } else if (key === '-') {
      event.preventDefault();
      onClickSign(key);
    } else if (key === 'Backspace') {
      event.preventDefault();
      onClickBackSpace();
    } else if (key === 'Enter' || key === 'Escape') {
      event.preventDefault();
      onClickCheck();
    }
  }

  onMounted(() => {
    document.addEventListener('keydown', handleKeyDown);

    const frameBodyIndex = props.isSidebar ? 1 : 0;
    const targetFrameBody = document.querySelectorAll('.frame__body')[frameBodyIndex];
    if (!isNil(targetFrameBody)) {
      const target = targetFrameBody.querySelector('.answers--bubble');
      if (isNil(target)) return;

      const containerRect = targetFrameBody.getBoundingClientRect();

      const targetRect = target.getBoundingClientRect();
      if (targetRect.bottom > containerRect.bottom) {
        targetFrameBody.scrollTo({
          top: targetFrameBody.scrollTop + (targetRect.bottom - containerRect.bottom),
          behavior: 'smooth',
        });
      }
    }
  });

  onUnmounted(() => {
    document.removeEventListener('keydown', handleKeyDown);
  });
</script>
