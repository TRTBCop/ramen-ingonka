<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >
  <AdminLayout>
    <Navbar />
    <div class="flex-column-fluid card mt-5 p-9">
      <Training1Navbar>
        <QuestionsTable
          :questions="questions"
          :question-options="training1OperationsOptions"
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
        <div class="mt-5 text-end">
          <el-button type="info" @click="goPreviewPage">미리보기</el-button>
        </div>
      </Training1Navbar>
    </div>
    <div class="d-flex gap-3">
      <GoListButton :list-url="route('admin.curricula.index')" />
      <el-form-item class="mb-0 mt-5" label="검수 여부">
        <el-switch v-model="isPublished" @change="updateOperationsData" />
      </el-form-item>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
  import { computed, ref } from 'vue';
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import { Head, router, usePage } from '@inertiajs/vue3';
  import Navbar from './components/Navbar.vue';
  import Training1Navbar from './components/Training1Navbar.vue';
  import { PageProps } from '@/admin/types';
  import { Training } from '@/admin/api/model/training';
  import { Curriculum } from '@/admin/api/model/curriculum';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import { Question } from '@/admin/api/model/questions';
  import QuestionsTable from '@/admin/components/admin/QuestionsTable/Index.vue';
  import { QuestionStoreParams, updateQuestion } from '@/admin/api/questions';
  import { find, isNil } from 'lodash';
  import { training1OperationsOptions } from './trainingQuestionOptions';
  import GoListButton from '@/admin/components/admin/GoListButton.vue';

  interface Page extends PageProps {
    config: {
      dbcode: Pick<Dbcode, 'curricula'>;
    };
    curriculum: Curriculum;
    training: Training;
    contents?: {
      questions: { id: number }[];
    };
    questions: Question[];
    training_concept_text_id: number;
    training_concept_text_ids: number[];
    question_extra: {
      model: string;
      model_id: number;
      type: string;
    };
  }

  const pageData = usePage<Page>();

  const isPublished = ref(Boolean(pageData.props.training.published_at));

  const defaultQuestionValues: QuestionStoreParams = {
    curriculum_id: pageData.props.curriculum.id,
    inquiry: '',
    answers: [
      {
        type: 2,
        answer: ['1'],
        choices: ['', ''],
        choice_symbol: false,
        action: 1,
      },
    ],
    rel: {
      table: 'trainings',
      id: pageData.props.training?.id,
      extra: {
        ...pageData.props.question_extra,
        is_vertical: 0,
      },
    },
  };

  const questions = ref<Question[]>(
    pageData.props.contents?.questions.map(
      (item) => find(pageData.props.questions, (data) => data.id === item.id) as Question,
    ) || [],
  );

  const previewUrl = computed(() =>
    route('app.trainings.stage1.operations.preview.show', {
      training: pageData.props.training.id,
    }),
  );

  function getQuestionToId(id: number) {
    return pageData.props.questions.find((question) => question.id === id);
  }

  function storeQuestionCallback(question: Question) {
    if (!pageData.props.contents) {
      pageData.props.contents = {
        questions: [],
      };
    }
    questions.value.push({
      ...question,
      pivot: {
        extra: {
          ...pageData.props.question_extra,
          is_vertical: 0,
        },
      },
    });

    updateOperationsData();
  }

  function deleteQuestionCallback(questionId: number) {
    questions.value = questions.value.filter((data) => data.id !== questionId);
    updateOperationsData();
  }

  function updateOperationsData() {
    if (isNil(pageData.props.contents)) return;
    pageData.props.contents.questions = questions.value.map((data) => ({ id: data.id }));

    router.put(
      route('admin.curricula.training1.operations.update', {
        curriculum: pageData.props.curriculum.id,
      }),
      {
        is_published: isPublished.value,
        basic_operations: pageData.props.contents as any,
      },
    );
  }

  /**
   * 문제 수정 이후 현재 데이터에 수정된 문제 정보 반영
   */
  const onUpdatedQuestion = (question: Question) => {
    if (isNil(questions.value)) return;

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
  };

  let isVerticalUpdating = false;

  const updateToggleQuestionIsVertical = async (questionId: number) => {
    try {
      if (isVerticalUpdating) return;
      isVerticalUpdating = true;

      const findQuestion = questions.value.find((question) => question.id === questionId);

      const params = {
        ...getQuestionToId(questionId),
        rel: {
          table: 'trainings',
          id: pageData.props.training?.id,
          extra: {
            ...pageData.props.question_extra,
            is_vertical: Number(!findQuestion?.pivot.extra.is_vertical),
          },
        },
      };

      await updateQuestion(questionId, params);

      questions.value.forEach((question, i) => {
        if (question.id === questionId) {
          questions.value[i].pivot.extra.is_vertical = Number(!findQuestion?.pivot.extra.is_vertical);
        }
      });

      isVerticalUpdating = false;
    } catch (err) {
      console.log(err);
    }
  };

  function goPreviewPage() {
    window.open(previewUrl.value);
  }
</script>
