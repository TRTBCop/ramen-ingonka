<template>
  <h2>
    {{ title }} <button @click="emits('close')"><font-awesome-icon icon="fa-regular fa-xmark" /></button
  ></h2>
  <template v-if="isArray(curriculums)">
    <template v-for="curriculum in curriculums" :key="curriculum.id">
      <div class="curriculum__box">
        <div class="curriculum__semester">
          <span :class="`badge__semester badge__semester--${curriculum.element / 100}`"> </span>
          {{ curriculum.name }}
        </div>
        <template v-for="middleNode in curriculum.children" :key="middleNode.id">
          <h4 v-if="!isLastNode(middleNode)" class="curriculum__sub__title">{{ middleNode.name }}</h4>
          <ul v-if="isLastNode(middleNode)" class="curriculum__sub">
            <LastCurriculumNode :curriculum="middleNode" />
          </ul>
          <ul v-else class="curriculum__sub">
            <LastCurriculumNode v-for="lowNode in middleNode.children" :key="lowNode.id" :curriculum="lowNode" />
          </ul>
        </template>
      </div>
    </template>
  </template>
  <template v-for="(terms, grade) in curriculums" v-else :key="grade">
    <template v-for="(items, term) in terms" :key="`${grade}-${term}`">
      <h3>{{ grade }}학년 {{ term }}학기</h3>
      <template v-for="curriculum in items.curriculums" :key="curriculum.id">
        <div class="curriculum__box">
          <div class="curriculum__semester">
            <span :class="`badge__semester badge__semester--${curriculum.element / 100}`"> </span>
            {{ curriculum.name }}
          </div>
          <template v-for="middleNode in curriculum.children" :key="middleNode.id">
            <h4 v-if="!isLastNode(middleNode)" class="curriculum__sub__title">{{ middleNode.name }}</h4>
            <ul v-if="isLastNode(middleNode)" class="curriculum__sub">
              <LastCurriculumNode :curriculum="middleNode" />
            </ul>
            <ul v-else class="curriculum__sub">
              <LastCurriculumNode v-for="lowNode in middleNode.children" :key="lowNode.id" :curriculum="lowNode" />
            </ul>
          </template>
        </div>
      </template>
    </template>
  </template>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';
  import { Curriculum } from '@/app/api/model/curriculum';
  import { isArray } from 'lodash';
  import LastCurriculumNode from '../LastCurriculumNode.vue';
  import { Chapter } from '@/app/types/pageData';

  defineProps({
    title: {
      type: String as PropType<string>,
      default: '',
    },
    curriculums: {
      type: Array as PropType<Curriculum[] | Chapter>,
      default: null,
    },
    isShowElement: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });

  const emits = defineEmits(['close']);

  function isLastNode(curriculum: Curriculum) {
    return Number(curriculum.children?.length) <= 0;
  }
</script>
