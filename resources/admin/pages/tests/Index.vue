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
      <template #title="{ row: value }">
        <Link :href="route('admin.tests.show', value.id)">
          {{ value.title }}
        </Link>
      </template>
      <template #questions_count="{ row: value }">
        {{ value.contents.questions.length }}
      </template>
      <template #published_at="{ row: value }">
        <div v-if="value.published_at" class="badge badge-light-success">검수완료</div>
        <div v-else class="badge badge-light-dark">검수전</div>
      </template>
      <template #actions="{ row: value }">
        <div class="d-flex gap-1">
          <Link :href="route('admin.tests.show', value.id)" class="btn btn-sm btn-icon btn-light-primary">
            <KTIcon icon-name="pencil" icon-class="fs-4 m-0" />
          </Link>
        </div>
      </template>
    </CustomTable>
  </AdminLayout>
</template>

<script setup lang="ts">
  import { computed } from 'vue';
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import CustomTable from '@/admin/components/customTable/CustomTable.vue';
  import KTIcon from '@/admin/core/helpers/kt-icon/KTIcon.vue';
  import { Head, Link, usePage } from '@inertiajs/vue3';
  import { AppTag, Collection, PageProps } from '@/admin/types';
  import { TableSelectFilter, TableInfo } from '@/admin/components/customTable/types';
  import { Dbcode } from '@/admin/api/model/dbcode';

  interface Page extends PageProps {
    config: {
      dbcode: Pick<Dbcode, 'questions'>;
    };
    collection: Collection;
    tags: AppTag[];
  }

  const pageData = usePage<Page>();

  const selectFilters = computed<TableSelectFilter[]>(() => [
    {
      name: 'published_at',
      options: {
        0: '검수전',
        1: '검수완료',
      },
      placeholder: '상태',
      isMultiple: false,
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
        columnName: '학년/학기',
        columnLabel: 'title',
        columnMinWidth: 150,
      },
      {
        columnName: '총 문제 수',
        columnLabel: 'questions_count',
        columnMinWidth: 150,
      },
      {
        columnName: '등록일시',
        columnLabel: 'created_at',
        columnMinWidth: 150,
      },
      {
        columnName: '상태',
        columnLabel: 'published_at',
        columnMinWidth: 150,
      },
      {
        columnName: '',
        columnLabel: 'actions',
        columnWidth: 100,
      },
    ],
  }));
</script>
