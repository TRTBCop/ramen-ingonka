<template>
  <!-- 계통 전체 보기 -->
  <div v-if="isShowElement" class="main__body unit">
    <div v-for="element in elementList" :key="element" class="unit__sec">
      <div class="th">
        <i :class="`ico_semester ico_semester--${element / 100}`"></i>
        <strong>{{ pageData.props.config.dbcode.curricula.element[element] }}</strong>
      </div>
      <div v-for="(terms, grade) in convertedElement[element].chapter" :key="grade" class="col">
        <div
          v-for="(item, term) in terms"
          :key="term"
          :class="`unit__box unit__box--${subtractSixIfMiddleSchool(Number(grade))}-${term}`"
        >
          <strong class="grade">{{ subtractSixIfMiddleSchool(Number(grade)) }}학년 {{ term }}학기</strong>
          <div class="lst__unit">
            <a
              v-for="curriculum in item.curriculums"
              :key="curriculum.id"
              href="javascript:;"
              @click="showLayer([curriculum], `${subtractSixIfMiddleSchool(Number(grade))}학년 ${term}학기`)"
              >{{ curriculum.name }}</a
            >
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- 학기 전체 보기 -->
  <div v-else class="main__body unit">
    <div v-for="term in 2" :key="term" class="unit__sec">
      <div v-for="(terms, grade) in convertedChapter" :key="grade" class="col">
        <div :class="`unit__box unit__box--${subtractSixIfMiddleSchool(Number(grade))}-${term}`">
          <strong class="grade">{{ subtractSixIfMiddleSchool(Number(grade)) }}학년 {{ term }}학기</strong>
          <div class="lst__unit">
            <a
              v-for="curriculum in terms[term].curriculums"
              :key="curriculum.id"
              href="javascript:;"
              @click="showLayer([curriculum], `${subtractSixIfMiddleSchool(Number(grade))}학년 ${term}학기`)"
              >{{ curriculum.name }}</a
            >
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 커리큘럼 레이어 -->
  <CurriculumLayer v-model:is-show="isShowLayer" :title="detailTitle" :curriculums="detailCurriculums" />
</template>

<script setup lang="ts">
  import { ref, computed, PropType } from 'vue';
  import { MainFreePageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import { clone, cloneDeep, pickBy } from 'lodash';
  import { Curriculum } from '@/app/api/model/curriculum';
  import CurriculumLayer from '@/app/components/layers/CurriculumLayer.vue';
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

  const pageData = usePage<MainFreePageProps>();

  const elementList = computed(() =>
    props.isMiddleSchoolType ? [100, 600, 700, 800, 900] : [100, 200, 300, 400, 500],
  );

  const isShowLayer = ref(false);
  const detailTitle = ref('');
  const detailCurriculums = ref<Curriculum[]>([]);

  function showLayer(curriculums: Curriculum[], title: string) {
    isShowLayer.value = true;
    detailCurriculums.value = curriculums;
    detailTitle.value = title;
  }

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
   * 초등, 중등 중 현재 상태에 맞는 값만 반환
   */
  const convertedElement = computed(() => {
    // 중학교로 보는 숫자
    const startMiddleGradeNumber = 7;
    const result = cloneDeep(pageData.props.curricula_map.element);

    Object.keys(result).forEach((element) => {
      result[element].chapter = pickBy(result[element].chapter, (value, key) =>
        props.isMiddleSchoolType ? Number(key) >= startMiddleGradeNumber : Number(key) < startMiddleGradeNumber,
      );
    });

    return result;
  });
</script>
