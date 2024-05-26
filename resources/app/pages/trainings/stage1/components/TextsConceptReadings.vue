<template>
  <div class="grid">
    <div class="frame col">
      <div class="frame__head title_area">
        <h3 class="title">지문</h3>
        <ol class="numbers">
          <li
            v-for="(conceptTextId, i) in pageData.props.training_concept_text_ids"
            :key="conceptTextId"
            :class="{
              done: isCompletedText(i),
              on: isCurrentText(conceptTextId),
            }"
          ></li>
        </ol>
      </div>
      <div class="frame__body" @click="nextSentence">
        <div class="articles">
          <article class="skeleton">
            <TrainingConceptTextSentence
              v-for="(reading, i) in readings"
              :key="i"
              :is-show="sentenceIndex >= i"
              :is-current="sentenceIndex === i"
              :is-current-image="getImageIndex(reading.image?.src) === currentImageIndex"
              :reading="reading"
              :question="pageData.props.questions.find((question) => question.id === reading.question?.id)"
              @on-click-image-type="onClickImageType"
              @on-correct="correctQuestion"
            />
          </article>
        </div>
      </div>
    </div>

    <div class="frame col--sm">
      <div class="frame__head title_area">
        <h3 class="title">그림으로 설명하기</h3>
      </div>
      <div class="frame__body">
        <div v-if="currentImageIndex !== -1" class="image_area">
          <div class="image__box">
            <img :src="showedReadingImages[currentImageIndex]?.image?.src" />
          </div>
          <div class="image_count">
            <button class="btn__prev" :disabled="currentImageIndex <= 0" @click="currentImageIndex--"></button>
            <strong>
              <span class="num__current">{{ currentImageIndex + 1 }}</span
              >/<span class="num__total">{{ showedReadingImages.length }}</span>
            </strong>
            <button
              class="btn__next"
              :disabled="showedReadingImages.length - 1 <= currentImageIndex"
              @click="currentImageIndex++"
            ></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, onMounted, onBeforeUnmount, computed, PropType, watch } from 'vue';
  import TrainingConceptTextSentence from '@/app/components/curricula/TrainingConceptTextSentence.vue';
  import { TrainingPageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import { TrainingConceptTextReadingType, TrainingConceptTextReading } from '@/app/api/model/training';
  import { nextTick } from 'vue';
  import { isNil } from 'lodash';

  const pageData = usePage<TrainingPageProps>();

  const props = defineProps({
    readings: {
      type: Array as PropType<TrainingConceptTextReading[]>,
      default: null,
    },
    isSummarizations: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });

  const emits = defineEmits(['onFinish']);

  const isCompletedCurrentQuestion = ref(false);

  const typeImageList = computed(
    () => props.readings.filter((reading) => reading.type === TrainingConceptTextReadingType.Image) || [],
  );
  const currentImageIndex = ref(props.isSummarizations ? 0 : -1);
  const showedReadingImages = ref<TrainingConceptTextReading[]>(props.isSummarizations ? typeImageList.value : []);

  /** 현재 문장 index */
  const sentenceIndex = ref(props.isSummarizations ? props.readings.length : 0);

  const isQuestionType = computed(() => {
    return props.readings[sentenceIndex.value]?.type === TrainingConceptTextReadingType.Question;
  });

  /** 지문 다 읽었을 경우 */
  const isFinishedText = computed(() => {
    // 마지막 문장일 경우 문제까지 풀어야 지문을 다 읽은 것으로 판단
    if (isQuestionType.value) {
      return sentenceIndex.value === props.readings.length - 1 && isCompletedCurrentQuestion.value;
    }

    return sentenceIndex.value >= props.readings.length - 1;
  });

  watch(
    () => isFinishedText.value,
    (newVal) => {
      if (newVal) {
        emits('onFinish');
      }
    },
  );

  function getImageIndex(src?: string) {
    return typeImageList.value.findIndex((reading) => reading.image?.src === src);
  }

  function onClickImageType(src?: string) {
    currentImageIndex.value = typeImageList.value.findIndex((reading) => reading.image?.src === src);
  }

  /** 마지막으로 문장이 바뀐 시간 */
  let lastSentenceChangeTime = 0;

  /** 문장 바뀌는 딜레이 시간 */
  const nextSentenceDelayTime = 100;

  const correctQuestionDelay = 1000;

  let isCorrected = false;
  /** 정답 맞췄을 시 콜백 함수 */
  function correctQuestion() {
    isCorrected = true;
    setTimeout(() => {
      isCompletedCurrentQuestion.value = true;
      isCorrected = false;
      nextSentence();
    }, correctQuestionDelay);
  }

  /** 완료한 지문인지 여부 반환 */
  function isCurrentText(textId: number) {
    return textId === pageData.props.training_concept_text_id;
  }

  /** 완료한 지문인지 여부 반환 */
  function isCompletedText(index: number) {
    return (
      index <
      Number(
        pageData.props.training_concept_text_ids?.findIndex((id) => id === pageData.props.training_concept_text_id),
      )
    );
  }

  /** 다음 문장으로 이동 */
  function nextSentence() {
    if (isCorrected) return;

    // 문제 타입인데 문제를 아직 풀지 않았을 경우 넘어가지 않음
    if (isQuestionType.value && !isCompletedCurrentQuestion.value) return;
    isCompletedCurrentQuestion.value = false;

    const currentTime = new Date().getTime();

    if (currentTime - lastSentenceChangeTime >= nextSentenceDelayTime) {
      sentenceIndex.value++;
      nextTick(() => {
        scrollToCurrentSentence();
      });
      if (
        props.readings[sentenceIndex.value]?.type === TrainingConceptTextReadingType.Default &&
        (props.readings[sentenceIndex.value].text === '[문단 나누기]' ||
          props.readings[sentenceIndex.value].text.trim().replace(/<br>/g, '') === '')
      ) {
        nextSentence();
        return;
      }
      if (props.readings[sentenceIndex.value]?.type === TrainingConceptTextReadingType.Image) {
        setImageType();
      }
      lastSentenceChangeTime = currentTime;
    }
  }

  function scrollToCurrentSentence() {
    const frameBodyElem = document.querySelector('.frame__body');
    if (!isNil(frameBodyElem)) {
      const target = frameBodyElem.querySelector('.spl.current');
      if (isNil(target)) return;

      const containerRect = frameBodyElem.getBoundingClientRect();

      const targetRect = target.getBoundingClientRect();
      // 타겟 요소가 컨테이너의 아래에 위치하면 스크롤합니다.
      if (targetRect.bottom > containerRect.bottom) {
        frameBodyElem.scrollTo({
          top: frameBodyElem.scrollTop + (targetRect.bottom - containerRect.bottom) + 50,
          behavior: 'smooth',
        });
      }
    }
  }

  function setImageType() {
    showedReadingImages.value.push({ ...props.readings[sentenceIndex.value] });
    currentImageIndex.value = showedReadingImages.value.length - 1;
  }

  /**  스페이스바 이벤트 등록 */
  function spacebarEventHandler(event: KeyboardEvent) {
    const spacebarKeyCode = 32;
    if (event.keyCode === spacebarKeyCode) {
      event.preventDefault();
      nextSentence();
    }
  }

  /** 스페이스바 이벤트 취소 */
  function cancelSpacebarEvent() {
    document.removeEventListener('keydown', spacebarEventHandler);
  }

  onMounted(() => {
    document.addEventListener('keydown', spacebarEventHandler);
    if (props.readings[sentenceIndex.value]?.type === TrainingConceptTextReadingType.Image) {
      setImageType();
    }
  });

  onBeforeUnmount(() => {
    cancelSpacebarEvent();
  });
</script>
