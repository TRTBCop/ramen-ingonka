<template>
  <div class="d-flex justify-content-end mb-5">
    <a class="btn btn-primary" @click="storeOperationsQuestion">
      <KTIcon icon-name="plus" icon-class="fs-2" />
      문제등록
    </a>
  </div>
  <el-table :data="questions" row-key="id">
    <el-table-column label="번호" width="100">
      <template #default="{ $index }"> {{ $index + 1 }} </template>
    </el-table-column>

    <el-table-column v-if="questionOptions.isInquiry" label="발문">
      <template #default="{ row }">
        <el-text class="text-truncate" v-html="stripHtmlTags(row.inquiry || '-')"></el-text>
      </template>
    </el-table-column>

    <el-table-column v-if="!questionOptions.isInquiry && questionOptions.isQuestion" label="문제풀이">
      <template #default="{ row }">
        <el-text class="text-truncate" v-html="stripHtmlTags(row.question || '-')"></el-text>
      </template>
    </el-table-column>

    <el-table-column v-if="questionOptions.isQuestion" label="풀이답안수" width="150">
      <template #default="{ row }">
        {{ row.answers?.length || '-' }}
      </template>
    </el-table-column>
    <slot />

    <el-table-column label="검수여부" width="150">
      <template #default="{ row }">
        <el-switch :model-value="Boolean(row.published_at)" disabled />
      </template>
    </el-table-column>

    <el-table-column width="100">
      <template #default="{ row }">
        <div class="d-flex gap-1">
          <a class="btn btn-sm btn-icon btn-light-primary" @click="showQuestionDetail(row.id)">
            <KTIcon icon-name="pencil" icon-class="fs-4 m-0" />
          </a>
          <a class="btn btn-sm btn-icon btn-light-danger" @click="deleteOperationsQuestion(row.id)">
            <KTIcon icon-name="trash" icon-class="fs-4 m-0" />
          </a>
        </div>
      </template>
    </el-table-column>
  </el-table>
  <QuestionDrawer
    :show="questionDetailDrawer.show"
    :question-id="questionDetailDrawer.questionId"
    :options="questionOptions"
    :preview-url="previewUrl"
    @close="hideQuestionDetail"
    @submit-callback="(question: Question) => emits('updatedCallback', question)"
  />
</template>

<script setup lang="ts">
  import { reactive, PropType } from 'vue';
  import { QuestionDrawerOptions } from '@/admin/components/admin/QuestionDrawer/types';
  import { commonDeleteConfirm, stripHtmlTags } from '@/admin/core/helpers/commonHelpers';
  import { Question } from '@/admin/api/model/questions';
  import { QuestionStoreParams, deleteQuestion, storeQuestion } from '@/admin/api/questions';
  import QuestionDrawer from '@/admin/components/admin/QuestionDrawer/Index.vue';

  const props = defineProps({
    questions: {
      type: Array as PropType<Question[]>,
      default: null,
    },
    questionOptions: {
      type: Object as PropType<QuestionDrawerOptions>,
      default: null,
    },
    defaultQuestionValues: {
      type: Object as PropType<QuestionStoreParams>,
      default: null,
    },
    previewUrl: {
      type: String as PropType<string>,
      default: '',
    },
  });

  const emits = defineEmits(['storeCallback', 'updatedCallback', 'deleteCallback']);

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

  const storeOperationsQuestion = async () => {
    try {
      const { data } = await storeQuestion(props.defaultQuestionValues);

      emits('storeCallback', data.data.question);
    } catch (err) {
      console.log(err);
    }
  };

  const deleteOperationsQuestion = (questionId: number) => {
    commonDeleteConfirm(async () => {
      try {
        await deleteQuestion(questionId);
        emits('deleteCallback', questionId);
      } catch (err) {
        console.log(err);
      }
    });
  };
</script>
