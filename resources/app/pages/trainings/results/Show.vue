<template>
  <AppLayout>
    <div class="result_wrap training__theme" :class="getThemeTypeClass(training.stage)">
      <div class="result__head">
        <h4>
          {{ curriculum.name }}
          <font-awesome-icon class="ico_chevron" icon="fa-regular fa-chevron-right" />
          {{ getStageName(training.stage) }} 훈련
          <span style="margin-left: 0.5rem; font-weight: normal">ROUND {{ trainingResult.round + 1 }}</span>
        </h4>

        <div>
          <strong class="badge__time"
            >훈련 완료 일시<span>{{ dayjs(trainingResult.completed_at).format('YYYY-MM-DD HH:mm') }}</span></strong
          >
          <template v-if="pageData.props.stage !== 1">
            <strong class="badge__time"
              >훈련 소요 시간<span>{{ formatTime(trainingResult.timer) }}</span></strong
            >
            <small v-if="isTimeOver" class="is--empha"
              >기준 시간보다 {{ formatTime(trainingResult.timer - 3600) }}초 더 걸렸어요.</small
            >
            <small v-else class="badge--success--alpha is--gray"
              ><font-awesome-icon icon="fa-regular fa-check" />기준 시간내에 풀었어요.</small
            >
          </template>
        </div>
      </div>
      <div class="result__body">
        <div class="result__rank__total">
          <div :class="`star__card star__card--${getRankByScore(trainingResult.score)}`">
            <i :class="`star star--${getRankByScore(trainingResult.score)}`"></i>
          </div>
          <button v-if="isTimeOver" class="btn__layer" @click="isDetailScoreLayer = true">
            <font-awesome-icon icon="fa-regular fa-circle-info" />
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
              ><font-awesome-icon icon="fa-regular fa-circle-xmark"
            /></button>
          </div>
        </div>
        <table class="tbl__result">
          <colgroup>
            <col style="width: 18%" />
            <col style="width: 18%" />
            <col style="width: 18%" />
            <col style="width: 18%" />
            <col style="width: 18%" />
            <col style="width: 8%" />
          </colgroup>
          <thead>
            <tr>
              <th>학습 구분</th>
              <th>학습 점수</th>
              <th>정답수</th>
              <th>문제수</th>
              <th>정답률</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="stepResult in trainingResult.steps" :key="stepResult.id">
              <th>{{ getStepName(training.stage, stepResult.key) }}</th>
              <td><i :class="`rank rank--${getRankByScore(stepResult.correct_percent)} rank--sm`"></i></td>
              <td>{{ stepResult.correct_answers }}</td>
              <td>{{ stepResult.total_answers }}</td>
              <td>{{ stepResult.correct_percent }}%</td>
              <td>
                <button @click="showTrainingStepResultLayer(stepResult)"
                  ><i class="fa-regular fa-magnifying-glass"></i
                ></button>
              </td>
            </tr>
            <tr>
              <th>전체</th>
              <td></td>
              <td>{{ trainingResult.correct_answers }}</td>
              <td>{{ trainingResult.total_answers }}</td>
              <td>{{ trainingResult.correct_percent }}%</td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="result__tip">
        <i class="result--character"></i>
        <p>
          {{ getUserData().name }}님의 {{ getStageName(training.stage) }} 훈련 결과는 100점 만점 중
          {{ trainingResult.score }}점입니다.<br />
          {{ getTrainingDontComment(training.stage, trainingResult.score) }}
        </p>
      </div>
      <div class="frame__bottom">
        <div class="row">
          <button class="btn--primary" @click="goTrainingMainPage(training)"
            ><i class="fa-regular fa-home"></i>홈으로</button
          >
          <button v-if="pageData.props.stage !== 1 && !isFreeUser()" class="btn--gray" @click="goIncorrectNote">
            <i class="fa-regular fa-note"></i>오답노트 확인하기
          </button>
        </div>
      </div>
    </div>

    <TrainingStepResultLayer
      v-if="trainingStepResultLayer.show && trainingStepResultLayer.stepResult"
      v-model:is-show="trainingStepResultLayer.show"
      :training="training"
      :training-result="trainingResult"
      :step-result="trainingStepResultLayer.stepResult"
    />
  </AppLayout>
</template>

<script setup lang="ts">
  import { ref, computed } from 'vue';
  import { usePage } from '@inertiajs/vue3';
  import { PageProps } from '@/app/types/pageData';
  import { TrainingResult } from '@/app/api/model/trainingResult';
  import { Training } from '@/app/api/model/training';
  import { formatTime } from '@/app/core/helpers/formattingHelper';
  import { getTrainingDontComment } from '@/app/pages/trainings/data';
  import { getUserData, isFreeUser } from '@/app/core/helpers/userHelper';
  import AppLayout from '@/app/layouts/AppLayout.vue';
  import { dayjs } from 'element-plus';
  import TrainingStepResultLayer from '@/app/components/layers/TrainingStepResultLayer.vue';
  import { reactive } from 'vue';
  import { goTrainingMainPage } from '@/app/core/helpers/routerHelper';
  import { getRankByScore, getStageName, getStepName, getThemeTypeClass } from '@/app/core/helpers/trainingHelper';
  import { StepResult } from '@/app/api/model/stepResult';

  interface Page extends PageProps {
    training: Training;
    training_result: TrainingResult;
  }

  const pageData = usePage<Page>();

  const training = computed(() => pageData.props.training);
  const trainingResult = computed(() => pageData.props.training_result);
  const curriculum = computed(() => training.value.curriculum);

  /** 60분 초과 여부 */
  const isTimeOver = ref(trainingResult.value.timer > 3600);

  /** 60분 초과시 나오는 레이어 */
  const isDetailScoreLayer = ref(trainingResult.value.timer > 3600);

  const trainingStepResultLayer = reactive<{
    show: boolean;
    stepResult: StepResult | null;
  }>({
    show: false,
    stepResult: null,
  });

  function showTrainingStepResultLayer(stepResult: StepResult) {
    trainingStepResultLayer.show = true;
    trainingStepResultLayer.stepResult = stepResult;
  }

  function goIncorrectNote() {
    window.open(
      route('app.incorrect-note.show', {
        trainingResult: pageData.props.training_result.id,
      }),
    );
  }
</script>
