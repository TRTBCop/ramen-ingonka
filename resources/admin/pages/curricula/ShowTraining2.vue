<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >

  <AdminLayout>
    <Navbar />
    <div class="flex-column-fluid card mt-5 p-9">
      <div class="mb-5">
        <el-tabs v-model="activeQuestionTabIndex" type="card">
          <el-tab-pane
            v-for="(textTab, stepIndex) in questionsTabList"
            :key="stepIndex"
            :label="textTab.label"
            :name="textTab.name"
          >
            <QuestionsTable
              :questions="question2Contents[activeQuestionTabIndex].questions"
              :question-options="training2Options"
              :default-question-values="defaultQuestionValues"
              :preview-url="previewUrl"
              @store-callback="storeQuestionCallback"
              @updated-callback="onUpdatedQuestion"
              @delete-callback="deleteQuestionCallback"
            >
              <el-table-column label="문제타입" width="100">
                <template #default="{ row }">
                  <div v-if="row.answers[0].type === 1" class="badge badge-light-success">입력형</div>
                  <div v-else class="badge badge-light-warning">선지형</div>
                </template>
              </el-table-column>
            </QuestionsTable>
          </el-tab-pane>
          <div class="mt-5 text-end">
            <el-button type="info" @click="goPreviewPage">미리보기</el-button>
          </div>
        </el-tabs>
      </div>
    </div>
    <div class="d-flex gap-3">
      <GoListButton :list-url="route('admin.curricula.index')" />
      <el-form-item class="mb-0 mt-5" label="검수 여부">
        <el-switch v-model="isPublished" @change="handleUpdateData" />
      </el-form-item>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
  import { ref } from 'vue';
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import { Head, router, usePage } from '@inertiajs/vue3';
  import Navbar from './components/Navbar.vue';
  import { Curriculum } from '@/admin/api/model/curriculum';
  import { PageProps } from '@/admin/types';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import { Training } from '@/admin/api/model/training';
  import { Question } from '@/admin/api/model/questions';
  import { QuestionStoreParams, updateQuestion } from '@/admin/api/questions';
  import { isNil, set } from 'lodash';
  import { training2Options } from './trainingQuestionOptions';
  import GoListButton from '@/admin/components/admin/GoListButton.vue';
  import QuestionsTable from '@/admin/components/admin/QuestionsTable/Index.vue';
  import { computed } from 'vue';

  interface Page extends PageProps {
    config: {
      dbcode: Pick<Dbcode, 'curricula'>;
    };
    curriculum: Curriculum;
    training: Training<
      {
        questions: { id: number }[];
      }[]
    >;
    questions: Question[];
    question_extra: {
      type: string;
    };
  }

  const pageData = usePage<Page>();
  const questions = ref(pageData.props.questions);

  const isPublished = ref(Boolean(pageData.props.training.published_at));

  const activeQuestionTabIndex = ref(0);

  const defaultQuestionValues = computed<QuestionStoreParams>(() => ({
    curriculum_id: pageData.props.curriculum.id,
    inquiry: '',
    answers: [
      {
        type: 2,
        action: 1,
        choices: ['', ''],
        choice_symbol: false,
        answer: ['1'],
      },
    ],
    tags: {
      used_at: [
        `유형훈련-${Math.floor(activeQuestionTabIndex.value / 2) + 1}차 ${
          activeQuestionTabIndex.value % 2 ? '유사' : '유형'
        }`,
      ],
    },
    rel: {
      table: 'trainings',
      id: pageData.props.training?.id,
      extra: {
        type: `${pageData.props.question_extra.type}.${activeQuestionTabIndex.value}`,
        is_vertical: 0,
      },
    },
  }));

  const question2Contents = ref<{ questions: Question[] }[]>([]);

  function initQuestion2Contents() {
    const result: { questions: Question[] }[] = [
      { questions: [] },
      { questions: [] },
      { questions: [] },
      { questions: [] },
    ];

    pageData.props.training.contents?.forEach((data, i) => {
      if (isNil(data.questions)) return;
      result[i].questions = data.questions.map((item) => getQuestionToId(item.id) as Question);
    });

    question2Contents.value = result;
  }

  const questionsTabList = [
    { label: '1차 유형 문제', name: 0 },
    { label: '1차 유사 문제', name: 1 },
    { label: '2차 유형 문제', name: 2 },
    { label: '2차 유사 문제', name: 3 },
  ];

  const previewUrl = computed(() =>
    route('app.trainings.stage2.preview.show', {
      training: pageData.props.training.id,
      step: activeQuestionTabIndex.value,
    }),
  );

  function getQuestionToId(id: number) {
    return questions.value.find((question) => question.id === id);
  }

  const handleUpdateData = () => {
    pageData.props.training.contents = question2Contents.value.map((data) => ({
      questions: data.questions.map((item) => ({ id: item.id })),
    }));

    router.put(
      route('admin.curricula.trainings.update', { curriculum: pageData.props.curriculum.id, trainingStage: 2 }),
      {
        is_published: isPublished.value,
        contents: pageData.props.training.contents as any,
      },
      {
        onFinish() {
          questions.value = pageData.props.questions;
          initQuestion2Contents();
        },
      },
    );
  };

  /**
   * 문제 수정 이후 현재 데이터에 수정된 문제 정보 반영
   */
  const onUpdatedQuestion = (question: Question) => {
    questions.value = questions.value.map((data) => {
      if (question.id === data.id) {
        return {
          ...data,
          ...question,
        };
      } else {
        return data;
      }
    });
    initQuestion2Contents();
  };

  function storeQuestionCallback(question: Question) {
    question2Contents.value[activeQuestionTabIndex.value].questions.push(question);
    handleUpdateData();
  }

  function deleteQuestionCallback(questionId: number) {
    question2Contents.value[activeQuestionTabIndex.value].questions = question2Contents.value[
      activeQuestionTabIndex.value
    ].questions.filter((data) => data.id !== questionId);

    handleUpdateData();
  }

  let isVerticalUpdating = false;

  const updateToggleQuestionIsVertical = async (questionId: number) => {
    try {
      if (isVerticalUpdating) return;
      isVerticalUpdating = true;

      const params = {
        ...getQuestionToId(questionId),
        rel: {
          table: 'trainings',
          id: pageData.props.training?.id,
          extra: {
            type: `${pageData.props.question_extra.type}.${activeQuestionTabIndex.value}`,
            is_vertical: !getQuestionToId(questionId)?.pivot.extra.is_vertical,
          },
        },
      };

      await updateQuestion(questionId, params);

      set(
        getQuestionToId(questionId) || {},
        'pivot.extra.is_vertical',
        Number(!getQuestionToId(questionId)?.pivot.extra.is_vertical),
      );

      isVerticalUpdating = false;
    } catch (err) {
      console.log(err);
    }
  };

  function goPreviewPage() {
    window.open(previewUrl.value);
  }

  initQuestion2Contents();
</script>
