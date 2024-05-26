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
              :questions="question3Contents[activeQuestionTabIndex].questions"
              :question-options="activeQuestionTabIndex === 1 ? training3DefaultQuestionOptions : training3Options"
              :default-question-values="defaultQuestionValues"
              :preview-url="previewUrl"
              @store-callback="storeQuestionCallback"
              @updated-callback="onUpdatedQuestion"
              @delete-callback="deleteQuestionCallback"
            />
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
  import { computed, ref } from 'vue';
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import { Head, router, usePage } from '@inertiajs/vue3';
  import Navbar from './components/Navbar.vue';
  import { Curriculum } from '@/admin/api/model/curriculum';
  import { PageProps } from '@/admin/types';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import { Training } from '@/admin/api/model/training';
  import { Question } from '@/admin/api/model/questions';
  import { QuestionStoreParams } from '@/admin/api/questions';
  import { training3DefaultQuestionOptions, training3Options } from './trainingQuestionOptions';
  import GoListButton from '@/admin/components/admin/GoListButton.vue';
  import QuestionsTable from '@/admin/components/admin/QuestionsTable/Index.vue';
  import { isNil } from 'lodash';

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
    question:
      activeQuestionTabIndex.value === 1 ? '정답 고르기를 작성해 주세요.<br><hr>순서 맞추기를 작성해 주세요.' : '',
    answers: [
      {
        type: 1,
        action: 1,
        choices: [],
        choice_symbol: false,
        answer: [],
      },
    ],
    tags: {
      used_at: [`서술형훈련-${questionsTabList[activeQuestionTabIndex.value].label}`],
    },
    rel: {
      table: 'trainings',
      id: pageData.props.training?.id,
      extra: {
        ...pageData.props.question_extra,
        type: `${pageData.props.question_extra.type}.${activeQuestionTabIndex.value}`,
      },
    },
  }));

  const question3Contents = ref<{ questions: Question[] }[]>([]);

  function initQuestion3Contents() {
    const result: { questions: Question[] }[] = [{ questions: [] }, { questions: [] }, { questions: [] }];

    pageData.props.training.contents?.forEach((data, i) => {
      if (isNil(data.questions)) return;
      result[i].questions = data.questions.map((item) => getQuestionToId(item.id) as Question);
    });

    question3Contents.value = result;
  }

  const questionsTabList = [
    { label: '1단계 빈칸채우기', name: 0 },
    { label: '2단계 기본 문제', name: 1 },
    { label: '3단계 쌍둥이 문제', name: 2 },
  ];

  const previewUrl = computed(() =>
    route('app.trainings.stage3.preview.show', {
      training: pageData.props.training.id,
      step: activeQuestionTabIndex.value,
    }),
  );

  function getQuestionToId(id: number) {
    return questions.value.find((question) => question.id === id);
  }

  const handleUpdateData = () => {
    pageData.props.training.contents = question3Contents.value.map((data) => ({
      questions: data.questions.map((item) => ({ id: item.id })),
    }));

    router.put(
      route('admin.curricula.trainings.update', { curriculum: pageData.props.curriculum.id, trainingStage: 3 }),
      {
        is_published: isPublished.value,
        contents: pageData.props.training.contents as any,
      },
      {
        onFinish() {
          questions.value = pageData.props.questions;
          initQuestion3Contents();
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

    initQuestion3Contents();
  };

  function storeQuestionCallback(question: Question) {
    question3Contents.value[activeQuestionTabIndex.value].questions.push(question);
    handleUpdateData();
  }

  const deleteQuestionCallback = (questionId: number) => {
    question3Contents.value[activeQuestionTabIndex.value].questions = question3Contents.value[
      activeQuestionTabIndex.value
    ].questions.filter((data) => data.id !== questionId);

    handleUpdateData();
  };

  function goPreviewPage() {
    window.open(previewUrl.value);
  }

  initQuestion3Contents();
</script>
