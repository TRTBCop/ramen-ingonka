<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >

  <AdminLayout>
    <CustomTable
      :request-url="route(pageData.props.route_name)"
      :select-filters="selectFilters"
      :table-info="tableInfo"
    >
      <!-- <template #header-buttons>
        <a class="btn btn-primary" @click="showQuestionDetail()">
          <KTIcon icon-name="plus" icon-class="fs-2" />
          문제등록
        </a>
      </template> -->
      <template #inquiry="{ row: value }">
        <a @click="showQuestionDetail(value.id)" v-html="removeTags(value.inquiry)"></a>
      </template>
      <template #actions="{ row: value }">
        <div class="d-flex gap-1">
          <a class="btn btn-sm btn-icon btn-light-primary" @click="showQuestionDetail(value.id)">
            <KTIcon icon-name="pencil" icon-class="fs-4 m-0" />
          </a>
        </div>
      </template>
    </CustomTable>

    <QuestionDrawer
      :show="questionDetailDrawer.show"
      :question-id="questionDetailDrawer.questionId"
      :curriculum-id-to-name="pageData.props.curriculum_id_to_name"
      :options="{
        isDefault: true,
        isInquiry: true,
        isMultipleAnswer: true,
        isQuestion: true,
        isOptions: true,
        isAction: true,
        isExplanation: true,
        isOrderMatching: false,
      }"
      @close="hideQuestionDetail"
    />
  </AdminLayout>
</template>

<script setup lang="ts">
  import { reactive, computed } from 'vue';
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import CustomTable from '@/admin/components/customTable/CustomTable.vue';
  import KTIcon from '@/admin/core/helpers/kt-icon/KTIcon.vue';
  import { Head, usePage } from '@inertiajs/vue3';
  import { AppTag, Collection, PageProps } from '@/admin/types';
  import { TableSelectFilter, TableInfo } from '@/admin/components/customTable/types';

  import { Dbcode } from '@/admin/api/model/dbcode';
  import QuestionDrawer from '@/admin/components/admin/QuestionDrawer/Index.vue';
  import { mapValues } from 'lodash';

  interface Page extends PageProps {
    config: {
      dbcode: Pick<Dbcode, 'questions'>;
    };
    collection: Collection;
    tags: AppTag[];
    curriculum_id_to_name: { [key in number]: { name: string; txt_element: string } };
  }

  const pageData = usePage<Page>();

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

  const selectFilters = computed<TableSelectFilter[]>(() => [
    {
      name: 'curriculum_id_to_name',
      isMultiple: true,
      placeholder: '단원명',
      options: mapValues(pageData.props.curriculum_id_to_name, (data) => data.name),
    },
  ]);

  const tableInfo = computed<TableInfo>(() => ({
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
        columnName: '발문',
        columnLabel: 'inquiry',
        columnMinWidth: 150,
      },
      {
        columnName: '단원명',
        columnLabel: 'curriculum_full_name',
        columnWidth: 300,
      },
      {
        columnName: '',
        columnLabel: 'actions',
        columnWidth: 50,
      },
    ],
  }));

  const removeTags = (value: string) => {
    return value.replace(/<[^>]*>/g, '');
  };
</script>
