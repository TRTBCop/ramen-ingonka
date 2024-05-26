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
          :question-options="training1TextReinforcementOptions"
          :default-question-values="defaultQuestionValues"
          :preview-url="previewUrl"
          @store-callback="storeQuestionCallback"
          @updated-callback="onUpdatedQuestion"
          @delete-callback="deleteQuestionCallback"
        />
        <div class="mt-5 text-end">
          <el-button type="info" @click="goPreviewPage">미리보기</el-button>
        </div>
      </Training1Navbar>
    </div>
    <div class="d-flex gap-3">
      <GoListButton :list-url="route('admin.curricula.index')" />
      <el-form-item class="mb-0 mt-5" label="검수 여부">
        <el-switch v-model="isPublished" @change="updateReinforcementsData" />
      </el-form-item>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
  import { ref, computed } from 'vue';
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import { Head, router, usePage } from '@inertiajs/vue3';
  import Navbar from './components/Navbar.vue';
  import Training1Navbar from './components/Training1Navbar.vue';
  import { PageProps } from '@/admin/types';
  import { Training } from '@/admin/api/model/training';
  import { Curriculum } from '@/admin/api/model/curriculum';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import { Question } from '@/admin/api/model/questions';
  import { QuestionStoreParams } from '@/admin/api/questions';
  import { find, isNil } from 'lodash';
  import { training1TextReinforcementOptions } from './trainingQuestionOptions';
  import GoListButton from '@/admin/components/admin/GoListButton.vue';
  import QuestionsTable from '@/admin/components/admin/QuestionsTable/Index.vue';

  interface Page extends PageProps {
    config: {
      dbcode: Pick<Dbcode, 'curricula'>;
    };
    curriculum: Curriculum;
    training: Training;
    contents: {
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

  const previewUrl = computed(() =>
    route('app.trainings.stage1.texts.reinforcements.preview.show', {
      training: pageData.props.training.id,
      trainingConceptText: pageData.props.training_concept_text_id,
    }),
  );

  const isPublished = ref(Boolean(pageData.props.training.published_at));

  const defaultQuestionValues: QuestionStoreParams = {
    curriculum_id: pageData.props.curriculum.id,
    inquiry: '',
    answers: [],
    tags: {
      used_at: ['커리큘럼관리상세-개념훈련-지문-개념다지기'],
    },
    rel: {
      table: 'trainings',
      id: pageData.props.training?.id,
      extra: pageData.props.question_extra,
    },
  };

  const questions = ref(pageData.props.questions);

  const contents = ref<Question[]>([]);

  function initContents() {
    contents.value =
      pageData.props.contents?.questions.map((item) => {
        return find(questions.value, (data) => data.id === item.id) as Question;
      }) || [];
  }

  function storeQuestionCallback(question: Question) {
    if (!pageData.props.contents) {
      pageData.props.contents = {
        questions: [],
      };
    }
    questions.value.push(question);

    updateReinforcementsData();
  }

  function deleteQuestionCallback(questionId: number) {
    questions.value = questions.value.filter((data) => data.id !== questionId);

    updateReinforcementsData();
  }

  function updateReinforcementsData() {
    if (!pageData.props.contents) {
      pageData.props.contents = {
        questions: [],
      };
    }

    pageData.props.contents.questions = questions.value.map((data) => ({ id: data.id }));

    router.put(
      route('admin.curricula.training1.texts.update', {
        is_published: isPublished.value,
        curriculum: pageData.props.curriculum.id,
        trainingConceptText: pageData.props.training_concept_text_id,
        type: 'reinforcements',
      }),
      {
        reinforcements: pageData.props.contents as any,
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

    initContents();
  };

  function goPreviewPage() {
    window.open(previewUrl.value);
  }

  initContents();
</script>
