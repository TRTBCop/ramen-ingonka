<template>
  <div class="layer ly__math">
    <div class="inner">
      <h2 class="layer__head">
        <img src="@/assets/img/math/layer_descriptive.svg" />문제{{ curriculumStore.getCurrentQuestionIndex + 1 }}
      </h2>
      <div class="layer__body skeleton fr-view" @click="nextSentence">
        <p>
          <template v-for="(sentence, i) in splitedInquiry" :key="i">
            <br v-if="/<br[^>]*>/gi.test(sentence)" />
            <span
              class="spl"
              :class="{
                show: currentSentenceIndex >= i,
                current: currentSentenceIndex == i,
              }"
              v-html="sentence.replace(/<br[^>]*>/gi, '').replace(/\*\*(.*?)\*\*/g, '<b>$1</b>')"
            ></span>
          </template>
        </p>
      </div>
      <div class="btns">
        <button class="btn--gray" :disabled="!isLastSentence" @click="emits('onComplete')">문제 풀기</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
  import { useCurriculumStoreWithOut } from '@/app/stores/modules/curriculum';
  import { splitByDoubleSlash } from '@/app/core/helpers/trainingHelper';

  const emits = defineEmits(['onComplete']);

  const curriculumStore = useCurriculumStoreWithOut();

  const currentQuestion = computed(() => curriculumStore.getCurrentQuestion);

  const splitedInquiry = computed(() => splitByDoubleSlash(currentQuestion.value?.inquiry || ''));

  const currentSentenceIndex = ref(0);

  const isLastSentence = computed(() => currentSentenceIndex.value >= Number(splitedInquiry.value?.length) - 1);

  const nextSentenceDelayTime = 100;

  /** 마지막으로 문장이 바뀐 시간 */
  let lastSentenceChangeTime = 0;

  function nextSentence() {
    if (isLastSentence.value) return;

    const currentTime = new Date().getTime();
    if (currentTime - lastSentenceChangeTime >= nextSentenceDelayTime) {
      currentSentenceIndex.value++;
      lastSentenceChangeTime = currentTime;
    }
  }

  /**  스페이스바 이벤트 등록 */
  function spacebarEventHandler(event: KeyboardEvent) {
    const spacebarKeyCode = 32;
    if (event.keyCode === spacebarKeyCode) {
      nextSentence();
    }
  }

  /** 스페이스바 이벤트 취소 */
  function cancelSpacebarEvent() {
    document.removeEventListener('keydown', spacebarEventHandler);
  }

  onMounted(() => {
    document.addEventListener('keydown', spacebarEventHandler);
  });

  onBeforeUnmount(() => {
    cancelSpacebarEvent();
  });
</script>
