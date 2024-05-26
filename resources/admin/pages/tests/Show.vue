<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >

  <AdminLayout>
    <div class="card mt-5">
      <div class="card-body border-top p-9">
        <h3>진단평가 - {{ pageData.props.test.title }}</h3>
        <el-divider />
        <div class="d-flex justify-content-end mb-5">
          <a class="btn btn-primary" @click="storeTestQuestion">
            <KTIcon icon-name="plus" icon-class="fs-2" />
            문제등록
          </a>
        </div>
        <el-table :data="questions" row-key="id">
          <!-- ############ 번호 ############ -->
          <el-table-column label="번호" width="100">
            <template #default="{ $index }"> {{ $index + 1 }} </template>
          </el-table-column>
          <!-- ############ //번호 ############ -->

          <!-- ############ 단원 ############ -->
          <el-table-column label="단원" width="400">
            <template #default="{ row }">
              {{ row.question?.curriculum?.name }}
            </template>
          </el-table-column>
          <!-- ############ //단원 ############ -->

          <!-- ############ 내용영역 ############ -->
          <el-table-column label="내용영역" width="150">
            <template #default="{ row }">
              {{ row.question?.curriculum?.txt_element }}
            </template>
          </el-table-column>
          <!-- ############ //내용영역 ############ -->

          <!-- ############ 난이도 ############ -->
          <el-table-column label="난이도" width="100">
            <template #default="{ row }">
              {{ pageData.props.config.code.test.questions.level[row.question?.level] }}
            </template>
          </el-table-column>
          <!-- ############ //난이도 ############ -->

          <!-- ############ 발문 ############ -->
          <el-table-column label="발문">
            <template #default="{ row }">
              <el-text class="text-truncate" v-html="stripHtmlTags(row.question?.inquiry || '-')"></el-text>
            </template>
          </el-table-column>
          <!-- ############ //발문 ############ -->

          <!-- ############ 풀이답안수 ############ -->
          <el-table-column label="풀이답안수" width="150">
            <template #default="{ row }">
              {{ row.question.answers?.length || '-' }}
            </template>
          </el-table-column>
          <!-- ############ //풀이답안수 ############ -->

          <!-- ############ 확장문제 ############ -->
          <el-table-column label="확장문제" width="100">
            <template #default="{ row }">
              <el-checkbox v-model="row.is_extend" />
            </template>
          </el-table-column>
          <!-- ############ // 확장문제 ############ -->

          <el-table-column label="검수여부" width="150">
            <template #default="{ row }">
              <el-switch :model-value="Boolean(row.question.published_at)" disabled />
            </template>
          </el-table-column>

          <!-- ############ 편집 ############ -->
          <el-table-column width="100">
            <template #default="{ row }">
              <div class="d-flex gap-1">
                <a class="btn btn-sm btn-icon btn-light-primary" @click="showQuestionDetail(row.question.id)">
                  <KTIcon icon-name="pencil" icon-class="fs-4 m-0" />
                </a>
                <a class="btn btn-sm btn-icon btn-light-danger" @click="deleteTestQuestion(row.id)">
                  <KTIcon icon-name="trash" icon-class="fs-4 m-0" />
                </a>
              </div>
            </template>
          </el-table-column>
          <!-- ############ //편집 ############ -->
        </el-table>
        <div class="d-flex mt-10 justify-content-end">
          <el-form-item class="mx-5" label="검수 여부">
            <el-switch v-model="isPublished" />
          </el-form-item>
          <el-button type="danger" @click="deleteSubscription">삭제</el-button>
        </div>
      </div>
    </div>
    <GoListButton :list-url="route('admin.tests.index')" />

    <QuestionDrawer
      :show="questionDetailDrawer.show"
      :question-id="questionDetailDrawer.questionId"
      :options="testsQuestionOptions"
      :preview-url="route('app.tests.preview.show', { test: pageData.props.test.id })"
      @close="hideQuestionDetail"
      @submit-callback="router.get(route('admin.tests.show', pageData.props.test.id))"
    />
  </AdminLayout>
</template>

<script setup lang="ts">
  import { reactive, ref, watch, onMounted } from 'vue';
  import { Test, TestQuestion } from '@/admin/api/model/tests';
  import KTIcon from '@/admin/core/helpers/kt-icon/KTIcon.vue';
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import { PageProps } from '@/admin/types';
  import { Head, router, usePage } from '@inertiajs/vue3';
  import { commonDeleteConfirm, stripHtmlTags } from '@/admin/core/helpers/commonHelpers';
  import GoListButton from '@/admin/components/admin/GoListButton.vue';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import QuestionDrawer from '@/admin/components/admin/QuestionDrawer/Index.vue';
  import { QuestionStoreParams, deleteQuestion, storeQuestion } from '@/admin/api/questions';
  import Sortable from 'sortablejs';
  import { cloneDeep } from 'lodash';
  import { testsQuestionOptions } from './testsQuestionOptions';

  interface Page extends PageProps {
    config: {
      code: Pick<Dbcode, 'test'>;
    };
    test: Test;
  }

  const pageData = usePage<Page>();

  const questions = ref<TestQuestion[]>(
    pageData.props.test.contents.questions.map((question) => ({
      ...question,
      is_extend: Boolean(question.is_extend),
    })),
  );

  const isPublished = ref(Boolean(pageData.props.test.published_at));

  const questionDetailDrawer = reactive<{ show: boolean; questionId: number | null }>({
    show: false,
    questionId: 0,
  });

  function showQuestionDetail(questionId?: number) {
    questionDetailDrawer.show = true;
    questionDetailDrawer.questionId = questionId || null;
  }

  function hideQuestionDetail() {
    questionDetailDrawer.show = false;
    questionDetailDrawer.questionId = 0;
  }

  const handleUpdateData = () => {
    router.put(route('admin.tests.update', pageData.props.test.id), {
      is_published: isPublished.value,
      contents: {
        questions: questions.value.map((question) => ({
          id: question.id,
          is_extend: Number(question.is_extend),
        })),
      },
    });
  };

  const deleteSubscription = () => {
    commonDeleteConfirm(() => router.delete(route('admin.tests.destroy', pageData.props.test.id)));
  };

  const deleteTestQuestion = (questionId: number) => {
    commonDeleteConfirm(async () => {
      try {
        await deleteQuestion(questionId);
        questions.value = questions.value.filter((data) => data.id !== questionId);
      } catch (err) {
        console.log(err);
      }
    });
  };

  const storeTestQuestion = async () => {
    try {
      const params: QuestionStoreParams = {
        level: 1,
        answers: [],
        tags: {
          used_at: ['진단평가'],
        },
        rel: {
          table: 'tests',
          id: pageData.props.test.id,
        },
      };
      const { data } = await storeQuestion(params);
      questions.value.push({
        id: data.data.question.id,
        question: data.data.question,
        is_extend: false,
      });
    } catch (err) {
      console.log(err);
    }
  };

  function initTableDraggable() {
    const tableElem = document.querySelector('.el-table__body tbody');
    if (tableElem) {
      new Sortable(tableElem, {
        animation: 150,
        onEnd(e: { newIndex: number; oldIndex: number }) {
          if (e.oldIndex !== e.newIndex) {
            const temp = cloneDeep(questions.value[e.oldIndex]);
            questions.value.splice(e.oldIndex, 1);
            questions.value.splice(e.newIndex, 0, temp);
          }
        },
      });
    }
  }

  onMounted(() => {
    initTableDraggable();
  });

  watch(
    () => questions.value,
    () => {
      handleUpdateData();
      initTableDraggable();
    },
    { deep: true },
  );

  watch(
    () => isPublished.value,
    () => {
      handleUpdateData();
      initTableDraggable();
    },
  );
</script>
