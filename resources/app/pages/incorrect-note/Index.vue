<template>
  <BoardLayout page-name="오답노트">
    <div class="data_area">
      <div class="bar__form">
        <div class="dropdown">
          <button class="dropdown__trigger" @click="toggleShowGradeTermSelectBox">
            {{ pageData.props.grade_term_options[pageData.props.parent_curriculum_id]
            }}<font-awesome-icon :icon="`fa-regular fa-chevron-${isShowGradeTermSelectBox ? 'up' : 'down'}`" />
          </button>
          <ul
            class="dropdown__menu"
            :class="{
              show: isShowGradeTermSelectBox,
            }"
          >
            <li
              v-for="(value, key) in pageData.props.grade_term_options"
              :key="key"
              @click="onClickGradeTermOption(key)"
              >{{ value }}</li
            >
          </ul>
        </div>
        <div class="dropdown">
          <button class="dropdown__trigger" @click="toggleShowStageSelectBox">
            {{ pageData.props.stage_options[pageData.props.stage]
            }}<font-awesome-icon :icon="`fa-regular fa-chevron-${isShowStageSelectBox ? 'up' : 'down'}`" />
          </button>
          <ul
            class="dropdown__menu"
            :class="{
              show: isShowStageSelectBox,
            }"
          >
            <li v-for="(value, key) in pageData.props.stage_options" :key="key" @click="onClickStageOption(key)">{{
              value
            }}</li>
          </ul>
        </div>
      </div>
      <template v-if="trainingResults.length > 0">
        <table class="tbl__board">
          <colgroup>
            <col style="width: 12%" />
            <col style="width: 8%" />
            <col style="width: 50%" />
            <col style="width: 8%" />
            <col style="width: 7%" />
            <col style="width: 15%" />
          </colgroup>
          <thead>
            <tr>
              <th>학습 일시</th>
              <th>학기</th>
              <th>학습 내용</th>
              <th>훈련</th>
              <th>ROUND</th>
              <th>오답수/정답수</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="trainingResult in trainingResults"
              :key="trainingResult.id"
              @click="goDetailPage(trainingResult)"
            >
              <td>{{ dayjs(trainingResult.completed_at).format('YYYY.MM.DD') }}</td>
              <td>{{ getGradeTermLabel(trainingResult) }}</td>
              <td class="title">
                <a href="javascript:;">
                  <span class="badge--lightgray">
                    {{ getMainCurriculumName(trainingResult) }}
                  </span>
                  {{ trainingResult.curriculum?.name }}
                </a>
              </td>
              <td v-if="trainingResult.training"> {{ getStageName(trainingResult.training.stage) }}</td>
              <td>{{ trainingResult.round + 1 }}</td>
              <td
                ><span class="badge--empha--alpha"
                  ><font-awesome-icon icon="fa-regular fa-xmark" />{{
                    trainingResult.total_answers - trainingResult.correct_answers
                  }}</span
                >&nbsp;<span class="badge--success--alpha"
                  ><font-awesome-icon icon="fa-regular fa-o" />{{ trainingResult.correct_answers }}</span
                ></td
              >
            </tr>
          </tbody>
        </table>

        <TablePagination
          :total-pages="collectionMeta.last_page"
          :total="collectionMeta.total"
          :per-page="collectionMeta.per_page"
          :current-page="collectionMeta.current_page"
          @page-change="movePage"
        />
      </template>

      <div v-else class="data_area data__none">
        <img src="@/assets/img/math/no_data.svg" alt="" />
        <p>오답노트가 없습니다.</p>
      </div>
    </div>
  </BoardLayout>
</template>

<script setup lang="ts">
  import { computed, ref } from 'vue';
  import { TrainingResult } from '@/app/api/model/trainingResult';
  import BoardLayout from '@/app/layouts/BoardLayout.vue';
  import { PageProps } from '@/app/types/pageData';
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  import { usePage } from '@inertiajs/vue3';
  import { dayjs } from 'element-plus';
  import { isNil } from 'lodash';
  import { getStageName } from '@/app/core/helpers/trainingHelper';
  import { Collection } from '@/app/types';
  import { goIncorrectNotePage } from '@/app/core/helpers/routerHelper';
  import TablePagination from '@/app/components/table/TablePagination.vue';

  interface Page extends PageProps {
    collection: Collection<TrainingResult>;
    grade_term_options: {
      [curriculumId in number]: string;
    };
    stage_options: {
      [stage in number]: string;
    };
    parent_curriculum_id: number;
    stage: number;
  }

  const pageData = usePage<Page>();

  console.log(pageData.props);

  const collectionMeta = computed(() => pageData.props.collection.meta);

  const filters = computed(() => (route().params as any).filters);

  const trainingResults = computed(() => pageData.props.collection.data);

  /** 학기 선택 셀렉트 박스 여부 */
  const isShowGradeTermSelectBox = ref(false);

  function toggleShowGradeTermSelectBox() {
    isShowGradeTermSelectBox.value = !isShowGradeTermSelectBox.value;
  }

  /** 훈련 선택 셀렉트 박스 여부 */
  const isShowStageSelectBox = ref(false);

  function toggleShowStageSelectBox() {
    isShowStageSelectBox.value = !isShowStageSelectBox.value;
  }

  function onClickGradeTermOption(curriculumId: number) {
    goIncorrectNotePage(1, {
      ...filters.value,
      parent_curriculum_id: curriculumId,
    });
  }

  function onClickStageOption(stage: number) {
    goIncorrectNotePage(1, {
      ...filters.value,
      stage: stage,
    });
  }

  /** 학습 결과에 해당하는 학년 학기 라벨을 반환 하는 메서드 */
  function getGradeTermLabel(trainingResult: TrainingResult) {
    if (isNil(trainingResult.curriculum) || isNil(trainingResult.curriculum?.ancestors)) return '';

    return pageData.props.grade_term_options[trainingResult.curriculum?.ancestors[1].id];
  }

  /** 학습 결과에 해당 하는 대단원 이름을 반환 하는 메서드 */
  function getMainCurriculumName(trainingResult: TrainingResult) {
    if (isNil(trainingResult?.curriculum?.ancestors)) return '';

    return trainingResult.curriculum?.ancestors[2].name;
  }

  function goDetailPage(trainingResult: TrainingResult) {
    window.open(route('app.incorrect-note.show', trainingResult.id));
  }

  function movePage(page: number) {
    goIncorrectNotePage(page, filters.value);
  }
</script>
