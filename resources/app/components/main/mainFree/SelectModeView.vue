<template>
  <div class="main__body training">
    <!-- 학년 학기 상세 보기 -->
    <template v-if="selectedSlideItem">
      <div class="lst__semester">
        <a href="javascript:;" class="semester semester--on" @click="selectedSlideItem = null">
          <i :class="`semester__thmb ${getThumbThemeClassName(selectedSlideItem)}`"></i>
          <span>{{ getSlideItemTitle(selectedSlideItem) }}</span>
          <div class="progress">
            <small class="is--size4">{{ getSlideItemProgress(selectedSlideItem) }}% 진행했어요!</small>
            <progress :value="getSlideItemProgress(selectedSlideItem)" min="0" max="100"></progress>
          </div>
        </a>
      </div>
      <div class="curriculum">
        <CurriculumList
          :title="getSlideItemTitle(selectedSlideItem)"
          :curriculums="getSlideItemCurriculums(selectedSlideItem)"
          :is-show-element="isShowElement"
          @close="selectedSlideItem = null"
        />
      </div>
      <Teleport to="#btn__floating__left">
        <button class="btn--gray" @click="selectedSlideItem = null"
          ><font-awesome-icon icon="fa-regular fa-arrow-left"></font-awesome-icon>뒤로 가기</button
        >
      </Teleport>
    </template>
    <!-- 학년 학기 목록 -->
    <div v-show="!selectedSlideItem" class="training__body">
      <div class="container">
        <ItemSlider
          :key="Number(isMiddleSchoolType) + Number(isShowElement)"
          :slide-items="convertedItemList"
          @on-click-active-item="onClickActiveItem"
        >
          <template #item-title="{ row }">{{ getSlideItemTitle(row) }}</template>
          <template #item-progress="{ row }">
            <div class="progress">
              <small class="is--size4">{{ getSlideItemProgress(row) }}% 진행했어요!</small>
              <progress :value="getSlideItemProgress(row)" min="0" max="100"></progress>
            </div>
          </template>
        </ItemSlider>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, computed, PropType, watch } from 'vue';
  import { MainFreePageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import { forEach, isNil, pickBy } from 'lodash';
  import ItemSlider from './ItemSlider.vue';
  import CurriculumList from './CurriculumList.vue';
  import { subtractSixIfMiddleSchool } from '@/app/core/helpers/formattingHelper';

  const props = defineProps({
    isShowElement: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    isMiddleSchoolType: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });

  watch(
    () => props.isShowElement,
    () => {
      selectedSlideItem.value = null;
    },
  );

  watch(
    () => props.isMiddleSchoolType,
    () => {
      selectedSlideItem.value = null;
    },
  );

  interface SlideItem {
    grade?: number;
    term?: number;
    element?: number;
  }

  const pageData = usePage<MainFreePageProps>();

  /**
   * 상세보기에 선택된 아이템 정보
   */
  const selectedSlideItem = ref<SlideItem | null>(null);

  /**
   * 초등, 중등 중 현재 상태에 맞는 값만 반환
   */
  const convertedChapter = computed(() => {
    // 중학교로 보는 숫자
    const startMiddleGradeNumber = 7;
    return pickBy(pageData.props.curricula_map.chapter, (value, key) =>
      props.isMiddleSchoolType ? Number(key) >= startMiddleGradeNumber : Number(key) < startMiddleGradeNumber,
    );
  });

  /**
   * 현재 슬라이드 아이템 세팅된 값
   */
  const convertedItemList = computed(() => {
    let result: SlideItem[] = [];

    // 계통 보기일 때
    if (props.isShowElement) {
      const elementaryElement = [
        { element: 100 },
        { element: 200 },
        { element: 300 },
        { element: 400 },
        { element: 500 },
      ];

      const middleElement = [{ element: 100 }, { element: 600 }, { element: 700 }, { element: 800 }, { element: 900 }];

      result = props.isMiddleSchoolType ? middleElement : elementaryElement;
      // 슬라이드 갯수가 너무 작아서 임의로 늘림
      result = [...result, ...result];
    }
    // 학기 보기 일 때
    else {
      forEach(convertedChapter.value, (terms, grade) => {
        forEach(terms, (_, term) => {
          result.push({
            grade: Number(grade),
            term: Number(term),
          });
        });
      });
    }

    return result;
  });

  /** 슬라이드 아이템 타이틀을 반환 하는 메서드 */
  function getSlideItemTitle(item: SlideItem) {
    if (props.isShowElement) {
      return pageData.props.config.dbcode.curricula.element[Number(item.element)];
    } else {
      return `${subtractSixIfMiddleSchool(Number(item.grade))}학년 ${item.term}학기`;
    }
  }

  function getSlideItemCurriculums(slideItem: SlideItem | null) {
    if (isNil(slideItem)) return [];

    if (props.isShowElement) {
      if (isNil(slideItem.element)) return;

      return pageData.props.curricula_map.element[slideItem.element].chapter;
    } else {
      if (isNil(slideItem.grade) || isNil(slideItem.term)) return;

      return pageData.props.curricula_map.chapter[slideItem.grade][slideItem.term].curriculums;
    }
  }

  function getSlideItemProgress(slideItem: SlideItem | null) {
    if (isNil(slideItem)) return 0;

    if (!isNil(slideItem.element)) {
      return pageData.props.curricula_map.element[slideItem.element].progress;
    } else {
      if (!isNil(slideItem.grade) && !isNil(slideItem.term)) {
        return pageData.props.curricula_map.chapter[slideItem.grade][slideItem.term].progress;
      }
    }
  }

  /**
   * 활성화된 아이템을 클릭하였을 경우 실행되는 메서드
   */
  function onClickActiveItem(item: SlideItem) {
    selectedSlideItem.value = item;
  }

  /**
   * 행성 클래스명
   */
  function getThumbThemeClassName(item: SlideItem) {
    if (!isNil(item?.element)) {
      return `semester--${item.element / 100}`;
    } else {
      return `semester--${item.grade}-${item.term}`;
    }
  }
</script>
