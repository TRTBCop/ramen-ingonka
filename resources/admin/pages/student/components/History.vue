<template>
  <CustomTable
    :request-url="route(pageData.props.route_name, pageData.props.student)"
    :date-picker="true"
    :select-filters="selectFilters"
    :table-info="tableInfo"
    :check-box-enabled="false"
    :search-place-holder="searchPlaceHolder"
  >
    <template #id="{ row: value }">
      {{ value.id }}
    </template>

    <template #completed_at="{ row: value }">
      {{ dayjs(value.completed_at).format('YYYY-MM-DD HH:mm:ss') }}
    </template>

    <template #grade="{ row: value }">
      {{ pageData.props.config.dbcode.students.grade[value.curriculum.grade] }}
    </template>

    <template #term="{ row: value }">
      {{ value.curriculum.term }}
    </template>

    <template #name="{ row: value }">
      {{ value.description }}
    </template>

    <template #bigUnit="{ row: value }">
      {{ value.curriculum.bigUnit }}
    </template>

    <template #smallUnit="{ row: value }">
      {{ value.curriculum.name }}
    </template>

    <template #training="{ row: value }">
      {{ pageData.props.config.dbcode.trainings.stage[value.training.stage] }}
    </template>

    <template #no="{ row: value }">
      {{ pageData.props.config.dbcode.training_results.round[value.round] }}
    </template>

    <template #correctRate="{ row: value }">
      {{ `${value.correct_answers}/${value.total_answers} (${value.score}%)` }}
    </template>

    <template #score="{ row: value }">
      {{ value.score }}
    </template>

    <template #created_at="{ row: value }">
      {{ dayjs(value.created_at).format('YYYY-MM-DD HH:mm:ss') }}
    </template>

    <template #actions="{ row: value }">
      <div class="d-flex gap-1">
        <a class="btn btn-sm btn-icon btn-light-primary" @click="showHistoryDetail(value)">
          <KTIcon icon-name="magnifier" icon-class="fs-4 m-0" />
        </a>
      </div>
    </template>
  </CustomTable>

  <el-dialog v-model="detailModal.isShow" :title="detailModal.title" width="600">
    <el-tag class="m-1">학습일시 {{ dayjs(detailModal.info?.completed_at).format('YYYY-MM-DD HH:mm:ss') }}</el-tag>

    <el-row v-if="detailModal.info?.training?.stage == 2 || detailModal.info?.training?.stage == 3" class="m-4">
      <el-col :span="12"><div class="grid-content ep-bg-purple" />훈련시간</el-col>
      <el-col :span="12"
        ><div class="grid-content ep-bg-purple-light" />{{
          convertSecondsToMinutesAndSeconds(detailModal.info.timer)
        }}</el-col
      >
    </el-row>

    <el-table :data="tableData" stripe style="width: 100%">
      <el-table-column prop="step" label="단계" width="180" />
      <el-table-column prop="answerCount" label="정답수/문제수" width="180" />
      <el-table-column prop="correctRate" label="정답률(%)" />
    </el-table>

    <el-row class="m-4">
      <template v-if="detailModal.info?.training?.stage == 2 || detailModal.info?.training?.stage == 3">
        <el-col :span="12"><div class="grid-content ep-bg-purple" />훈련시간(60분) 초과</el-col>
        <el-col :span="12"
          ><div class="grid-content ep-bg-purple-light" />{{ detailModal.info?.timer > 3600 ? -10 : 0 }}</el-col
        >
      </template>
      <el-col :span="12"><div class="grid-content ep-bg-purple" />최종점수</el-col>
      <el-col :span="12"><div class="grid-content ep-bg-purple-light" />{{ detailModal.info?.score }}</el-col>
    </el-row>
  </el-dialog>
</template>

<script setup lang="ts">
  import { computed, reactive, ref } from 'vue';
  import { usePage } from '@inertiajs/vue3';
  import { TableInfo, TableSelectFilter, TableSelectFilterOptions } from '@/admin/components/customTable/types';
  import CustomTable from '@/admin/components/customTable/CustomTable.vue';
  import { Collection, PageProps } from '@/admin/types';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import { Student } from '@/admin/api/model/student';
  import { dayjs } from 'element-plus';
  import KTIcon from '@/admin/core/helpers/kt-icon/KTIcon.vue';
  import { TrainingResult } from '@/app/api/model/trainingResult';

  interface Page extends PageProps {
    academies: {
      [key in number]: string;
    };
    config: {
      dbcode: Pick<Dbcode, 'students'> & Pick<Dbcode, 'trainings'> & Pick<Dbcode, 'training_results'>;
    };
    collection: Collection<TrainingResult>;
    student: Student;
  }

  const pageData = usePage<Page>();
  const searchPlaceHolder = ref('소단원 검색');

  const detailModal = reactive<{
    title: string;
    isShow: boolean;
    info: TrainingResult | null;
    stage: number;
  }>({
    title: '',
    isShow: false,
    info: null,
    stage: 1,
  });

  const selectFilters = computed<TableSelectFilter[]>(() => {
    const result = [
      {
        name: 'grade',
        options: pageData.props.config.dbcode.students.grade as unknown as TableSelectFilterOptions,
        placeholder: '학년',
        isMultiple: false,
      },
      {
        name: 'term',
        options: { '1': '1', '2': '2' } as unknown as TableSelectFilterOptions,
        placeholder: '학기',
        isMultiple: false,
      },
      {
        name: 'stage',
        options: pageData.props.config.dbcode.trainings.stage as unknown as TableSelectFilterOptions,
        placeholder: '훈련',
        isMultiple: false,
      },
      {
        name: 'round',
        options: { '0': '1R', '1': '2R', '2': '3R' } as TableSelectFilterOptions,
        placeholder: '학습구분',
        isMultiple: false,
      },
    ];

    return result;
  });

  const tableData = ref<{ step: string; answerCount: string; correctRate: number }[]>([]);

  const tableInfo = computed<TableInfo>(() => {
    return {
      perPage: pageData.props.collection.meta.per_page,
      total: pageData.props.collection.meta.total,
      currentPage: pageData.props.collection.meta.current_page,
      data: pageData.props.collection.data,
      header: [
        {
          columnName: '고유번호',
          columnLabel: 'id',
          columnWidth: 100,
        },
        {
          columnName: '학습일시',
          columnLabel: 'completed_at',
          columnWidth: 100,
        },
        {
          columnName: '학년',
          columnLabel: 'grade',
          columnWidth: 100,
        },
        {
          columnName: '학기',
          columnLabel: 'term',
          columnWidth: 50,
        },
        {
          columnName: '대단원',
          columnLabel: 'bigUnit',
          columnWidth: 150,
        },
        {
          columnName: '소단원',
          columnLabel: 'smallUnit',
          columnWidth: 200,
        },
        {
          columnName: '훈련',
          columnLabel: 'training',
          columnWidth: 100,
        },
        {
          columnName: '학습구분',
          columnLabel: 'round',
          columnWidth: 80,
        },
        {
          columnName: '정답수/문제수(정답률)',
          columnLabel: 'correctRate',
          columnMinWidth: 150,
        },
        {
          columnName: '점수',
          columnLabel: 'score',
          columnMinWidth: 50,
        },
        {
          columnName: '등록일',
          columnLabel: 'created_at',
          columnWidth: 200,
        },
        {
          columnName: '상세',
          columnLabel: 'actions',
          columnWidth: 100,
        },
      ],
    };
  });

  function getStepResultByKey(trainingResult: TrainingResult, key: number | string) {
    return trainingResult.steps.find((stepResult) => stepResult.key == key);
  }

  /**
   * 훈련 상세
   * @param result
   */
  const showHistoryDetail = (result: TrainingResult) => {
    detailModal.stage = result.training?.stage as number;
    detailModal.isShow = true;
    detailModal.title = `${pageData.props.config.dbcode.trainings.stage[detailModal.stage]}훈련 상세 ${
      pageData.props.config.dbcode.training_results.round[result.round]
    }`;
    detailModal.info = result;

    // 개념훈련
    if (result.training.stage == 1) {
      const conceptTraining = {
        step: '개념 학습',
        answerCount: `${getStepResultByKey(result, 'texts')?.correct_answers}/${
          getStepResultByKey(result, 'texts')?.total_answers
        }`,
        correctRate: Number(getStepResultByKey(result, 'texts')?.correct_percent),
      };

      const basicOperationTraining = {
        step: '기초 연산',
        answerCount: `${getStepResultByKey(result, 'operations')?.correct_answers}/${
          getStepResultByKey(result, 'operations')?.total_answers
        }`,
        correctRate: Number(getStepResultByKey(result, 'operations')?.correct_percent),
      };

      const total = {
        step: '합계',
        answerCount: `${result.correct_answers}/${result.total_answers}`,
        correctRate: result.correct_percent,
      };

      tableData.value = [conceptTraining, basicOperationTraining, total];
    } else if (result.training.stage == 2) {
      const firstTrainingType = {
        step: '1차 유형 문제',
        answerCount: `${getStepResultByKey(result, 0)?.correct_answers}/${
          getStepResultByKey(result, 0)?.total_answers
        }`,
        correctRate: Number(getStepResultByKey(result, 0)?.correct_percent),
      };

      const firstTrainingSimilarity = {
        step: '1차 유사 문제',
        answerCount: `${getStepResultByKey(result, 1)?.correct_answers}/${
          getStepResultByKey(result, 1)?.total_answers
        }`,
        correctRate: Number(getStepResultByKey(result, 1)?.correct_percent),
      };

      const secondTrainingType = {
        step: '2차 유형 문제',
        answerCount: `${getStepResultByKey(result, 2)?.correct_answers}/${
          getStepResultByKey(result, 2)?.total_answers
        }`,
        correctRate: Number(getStepResultByKey(result, 2)?.correct_percent),
      };

      const secondTrainingSimilarity = {
        step: '2차 유사 문제',
        answerCount: `${getStepResultByKey(result, 3)?.correct_answers}/${
          getStepResultByKey(result, 3)?.total_answers
        }`,
        correctRate: Number(getStepResultByKey(result, 3)?.correct_percent),
      };

      const total = {
        step: '합계',
        answerCount: `${result.correct_answers}/${result.total_answers}`,
        correctRate: result.timer > 3600 ? result.correct_percent + 10 : result.correct_percent,
      };

      tableData.value = [
        firstTrainingType,
        firstTrainingSimilarity,
        secondTrainingType,
        secondTrainingSimilarity,
        total,
      ];
    } else if (result.training.stage == 3) {
      // 서술형훈련
      const firstQuestion = {
        step: '1단계 빈칸채우기',
        answerCount: `${getStepResultByKey(result, 0)?.correct_answers}/${
          getStepResultByKey(result, 0)?.total_answers
        }`,
        correctRate: Number(getStepResultByKey(result, 0)?.correct_percent),
      };

      const secondQuestion = {
        step: '2단계 기본 문제',
        answerCount: `${getStepResultByKey(result, 1)?.correct_answers}/${
          getStepResultByKey(result, 1)?.total_answers
        }`,
        correctRate: Number(getStepResultByKey(result, 1)?.correct_percent),
      };

      const thirdQuestion = {
        step: '3단계 쌍둥이 문제',
        answerCount: `${getStepResultByKey(result, 2)?.correct_answers}/${
          getStepResultByKey(result, 2)?.total_answers
        }`,
        correctRate: Number(getStepResultByKey(result, 2)?.correct_percent),
      };

      const total = {
        step: '합계',
        answerCount: `${result.correct_answers}/${result.total_answers}`,
        correctRate: result.timer > 3600 ? result.correct_percent + 10 : result.correct_percent,
      };

      tableData.value = [firstQuestion, secondQuestion, thirdQuestion, total];
    }
  };

  // 초를 분과 초로 분할하는 함수
  const convertSecondsToMinutesAndSeconds = (seconds: number) => {
    const minutes = Math.floor(seconds / 60); // 분 계산
    const remainingSeconds = seconds % 60; // 남은 초 계산
    return `${minutes}분 ${remainingSeconds}초`;
  };
</script>
