<template>
  <Teleport to="body">
    <div v-if="isShow" class="modal modal--md">
      <div class="modal_head">
        <button class="btn--close" @click="closeModal">
          <font-awesome-icon icon="fa-regular fa-xmark" />
        </button>
      </div>
      <div class="modal_body">
        <strong class="is--size4">학습할 학기를 선택해 주세요.</strong>
        <span>(선택한 학기는 마이페이지의 내 정보에서 변경이 가능해요.)</span>
        <!-- <label class="switch grade__switch">
          <input v-model="isUpperMode" type="checkbox" />
          <span class="slider"><small class="switch__text"></small></span>
        </label> -->
        <ul class="modal__semester radio_wrap">
          <template v-for="grade in currentGrdeGroup" :key="grade">
            <li v-for="term in 2" :key="grade + term">
              <input
                :id="`semester${grade}${term}`"
                v-model="gradeTermRadioValue"
                type="radio"
                name="gradeTerm"
                :value="`${grade}-${term}`"
              />
              <label :for="`semester${grade}${term}`">{{ subtractSixIfMiddleSchool(grade) }}학년 {{ term }}학기</label>
            </li>
          </template>
        </ul>
      </div>
      <div class="btns">
        <button class="btn--brand" @click="updateGradeTerm">시작하기</button>
      </div>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
  import { ref, computed, PropType } from 'vue';
  import { router, usePage } from '@inertiajs/vue3';
  import { PageProps } from '@/app/types/pageData';
  import { getUserSchoolInfo } from '@/app/core/helpers/userHelper';
  import { subtractSixIfMiddleSchool } from '@/app/core/helpers/formattingHelper';

  defineProps({
    isShow: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });

  const emits = defineEmits(['update:isShow']);

  const pageData = usePage<PageProps>();

  const gradeGroup = pageData.props.config.training.grade_group;

  /** [학년]-[학기] 형식의 값  ex) 3-1, 3-2 */
  const gradeTermRadioValue = ref(getUserSchoolInfo()?.gradeTerm || '');

  const isUpperMode = ref(false);

  const currentGrdeGroup = computed(() => (isUpperMode.value ? gradeGroup.upper : gradeGroup.lower));

  function closeModal() {
    emits('update:isShow', false);
  }

  function updateGradeTerm() {
    const splitedGradeTerm = gradeTermRadioValue.value.split('-');

    router.patch(
      route('app.my.grade-term.update'),
      {
        grade: splitedGradeTerm[0],
        term: splitedGradeTerm[1],
      },
      {
        onFinish() {
          closeModal();
        },
      },
    );
  }
</script>
