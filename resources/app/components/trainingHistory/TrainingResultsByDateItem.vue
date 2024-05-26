<template>
  <div class="box__history">
    <div class="box__history__head">
      <div class="box__history__date">
        <strong>{{ date }}</strong>
      </div>
    </div>
    <div class="box__history__body">
      <div v-for="trainingResult in trainingResults" :key="trainingResult.id" class="history__round">
        <div class="box__history__title">
          <em v-if="trainingResult.curriculum?.ancestors" class="row">
            <small class="time"
              ><font-awesome-icon icon="fa-regular fa-play"></font-awesome-icon><b>시작</b
              >{{ dayjs(trainingResult.completed_at).format('HH:mm') }}</small
            ><span>{{ trainingResult.curriculum.ancestors[1].name }}</span
            >{{ trainingResult.curriculum.ancestors[2].name }}
          </em>
          <strong class="row"
            ><small class="time"
              ><font-awesome-icon icon="fa-regular fa-hourglass-clock" /><b>학습</b
              >{{ Math.floor(trainingResult.timer / 60) }}분</small
            ><p>{{ trainingResult.curriculum?.name }}</p></strong
          >
        </div>
        <div class="history__round__btns">
          <div class="history__round__step">ROUND {{ trainingResult.round + 1 }}</div>
          <button v-if="trainingResult.training" @click="showTrainingResultLayer(trainingResult)"
            >{{ getStageName(trainingResult.training.stage) }}
            <span class="stars"
              ><i :class="`star star--sm star--${getRankByScore(trainingResult.score)}`"></i
              >{{ trainingResult.score }}</span
            >
            <font-awesome-icon icon="fa-regular fa-magnifying-glass" />
          </button>
        </div>
      </div>
    </div>

    <TrainingResultLayer
      v-if="isShowTrainingResultLayer"
      v-model:is-show="isShowTrainingResultLayer"
      :training-result="trainingResultLayerValue"
    />
  </div>
</template>

<script setup lang="ts">
  import { ref, PropType } from 'vue';
  import { TrainingResult } from '@/app/api/model/trainingResult';
  import { getRankByScore, getStageName } from '@/app/core/helpers/trainingHelper';
  import { dayjs } from 'element-plus';
  import TrainingResultLayer from '@/app/components/layers/TrainingResultLayer.vue';

  defineProps({
    trainingResults: {
      type: Object as PropType<TrainingResult[]>,
      default: null,
    },
    date: {
      type: String as PropType<string>,
      default: '',
    },
  });

  const isShowTrainingResultLayer = ref(false);
  const trainingResultLayerValue = ref<TrainingResult>();

  function showTrainingResultLayer(trainingResult: TrainingResult) {
    isShowTrainingResultLayer.value = true;
    trainingResultLayerValue.value = trainingResult;
  }
</script>
@/app/pages/trainings/data
