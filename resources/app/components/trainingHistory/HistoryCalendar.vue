<template>
  <div class="history__calendar">
    <div class="history__calendar__head">
      <button @click="movePrevDay"><font-awesome-icon icon="fa-regular fa-chevron-left" /></button>
      <p>
        <span>{{ dayjs(range.start).format('YYYY.MM.DD (ddd)') }}</span>
        <span>~</span>
        <span>{{ dayjs(range.end).format('YYYY.MM.DD (ddd)') }}</span>
      </p>
      <button :disabled="!isPossibleMoveNextDay" @click="moveNextDay"
        ><font-awesome-icon icon="fa-regular fa-chevron-right"
      /></button>
    </div>
    <div class="calendar">
      <VDatePicker
        :attributes="attributes"
        expanded
        mode="date"
        :disabled-dates="disabledDates"
        @dayclick="onDayclick"
      />
    </div>
    <div class="btns">
      <AppButton :loading="isLoading" color="brand" @click="onClickSubmit">적용하기</AppButton>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, computed, watch } from 'vue';
  import { HistoryPageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import { dayjs } from 'element-plus';
  import { goTrainingHistoryPage } from '@/app/core/helpers/routerHelper';
  import AppButton from '@/app/components/buttons/AppButton.vue';

  const pageData = usePage<HistoryPageProps>();

  const isLoading = ref(false);

  const attributes = computed(() => [
    {
      highlight: 'blue',
      dates: [range.value],
    },
  ]);

  const range = ref({
    start: pageData.props.start_date,
    end: pageData.props.end_date,
  });

  watch(
    () => range.value.end,
    (newVal) => {
      range.value.start = dayjs(newVal).subtract(6, 'day').format('YYYY-MM-DD');
    },
  );

  function onDayclick(event: { id: string }) {
    range.value.end = event.id;
  }

  /** 오늘 이후 날짜는 비활성화 처리 */
  const disabledDates = ref([{ start: dayjs().add(1, 'day'), end: null }]);

  /** 다음 날짜로 이동 가능한지 여부 */
  const isPossibleMoveNextDay = computed(() => {
    return !dayjs(range.value.end).isSame(dayjs(), 'day');
  });

  function onClickSubmit() {
    isLoading.value = true;
    goTrainingHistoryPage(range.value.end);
  }

  /** 다음 날로 이동하는 메서드 */
  function moveNextDay() {
    range.value.end = dayjs(range.value.end).add(1, 'day').format('YYYY-MM-DD');
  }

  /** 이전 날로 이동하는 메서드 */
  function movePrevDay() {
    range.value.end = dayjs(range.value.end).subtract(1, 'day').format('YYYY-MM-DD');
  }
</script>
