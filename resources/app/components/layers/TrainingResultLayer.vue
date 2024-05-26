<template>
  <div
    class="layer ly__history"
    :class="{
      show: isShow,
    }"
  >
    <div class="inner">
      <div class="layer__head">
        <h2>{{ getStageName(training.stage) }} 훈련 결과</h2>
        <ul class="row">
          <li>
            <h4>훈련 완료 일시</h4>
            <strong>{{ dayjs(trainingResult.completed_at).format('YYYY-MM-DD HH:mm') }}</strong>
          </li>
          <li>
            <h4>소단원</h4>
            <strong
              >{{ trainingResult.curriculum?.name }}
              <small class="badge--lightgray">ROUND {{ trainingResult.round + 1 }}</small></strong
            >
          </li>
        </ul>
      </div>
      <div class="layer__body">
        <div class="result_wrap">
          <div class="result__body">
            <div class="result__rank__total">
              <div :class="`star__card star__card--${getRankByScore(trainingResult.score)}`">
                <i :class="`star star--${getRankByScore(trainingResult.score)}`"></i>
              </div>
              <button v-if="isTimeOver" class="btn__layer" @click="isDetailScoreLayer = true">
                <FontAwesomeIcon icon="fa-regular fa-circle-info" />
              </button>
              <div v-if="isDetailScoreLayer" class="result__rank__total__layer">
                <div>
                  <dl>
                    <dt>최종 훈련 점수</dt>
                    <dd>{{ trainingResult.correct_percent }}점</dd>
                    <dt><i>－</i>60분 초과</dt>
                    <dd>10점</dd>
                    <hr />
                    <dt>나의 점수</dt>
                    <dd>{{ trainingResult.score }}점</dd>
                  </dl>
                </div>
                <button class="btn__layer btn__layer__close" @click="isDetailScoreLayer = false"
                  ><i class="fa-regular fa-circle-xmark"></i
                ></button>
              </div>
            </div>
            <table class="tbl__result">
              <colgroup>
                <col style="width: 30%" />
                <col style="width: 20%" />
                <col style="width: 20%" />
                <col style="width: 30%" />
              </colgroup>
              <thead>
                <tr>
                  <th>학습 구분</th>
                  <th>정답수</th>
                  <th>문제수</th>
                  <th>정답률</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="stepResult in trainingResult.steps" :key="stepResult.id">
                  <th>{{ getStepName(training.stage, stepResult.key) }}</th>
                  <td>{{ stepResult.correct_answers }}</td>
                  <td>{{ stepResult.total_answers }}</td>
                  <td>{{ stepResult.correct_percent }}%</td>
                </tr>
                <tr>
                  <th>전체</th>
                  <td>{{ trainingResult.correct_answers }}</td>
                  <td>{{ trainingResult.total_answers }}</td>
                  <td>{{ trainingResult.correct_percent }}%</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="btns btns--center">
        <button class="btn--sub" @click="closeModal">닫기</button>
        <button v-if="trainingResult.round < 2" class="btn--brand" @click="goTrainingMainPage(trainingResult.training)"
          >복습하기</button
        >
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, computed, PropType } from 'vue';
  import { TrainingResult } from '@/app/api/model/trainingResult';
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  import { dayjs } from 'element-plus';
  import { goTrainingMainPage } from '@/app/core/helpers/routerHelper';
  import { getStageName, getStepList, getStepName, getRankByScore } from '@/app/core/helpers/trainingHelper';

  const props = defineProps({
    isShow: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    trainingResult: {
      type: Object as PropType<TrainingResult>,
      default: null,
    },
  });

  const emits = defineEmits(['update:isShow']);

  function closeModal() {
    emits('update:isShow', false);
  }

  const training = computed(() => props.trainingResult.training);

  const stepList = computed(() => getStepList(training.value.stage));

  const isTimeOver = ref(props.trainingResult.timer > 3600);

  const isDetailScoreLayer = ref(props.trainingResult.timer > 3600);
</script>
