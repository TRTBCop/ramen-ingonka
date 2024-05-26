<template>
  <CustomTable
    :request-url="route(pageData.props.route_name, pageData.props.student)"
    :date-picker="true"
    :select-filters="selectFilters"
    :table-info="tableInfo"
    :check-box-enabled="false"
  >
    <template #id="{ row: value }">
      {{ value.id }}
    </template>
    <template #name="{ row: value }">
      {{ value.description }}
    </template>
    <template #created_at="{ row: value }">
      {{ dayjs(value.created_at).format('YYYY-MM-DD HH:mm:ss') }}
    </template>
    <template #actions="{ row: value }">
      <a class="btn btn-sm btn-icon btn-light-primary" @click="showDetail(value.properties)">
        <KTIcon icon-name="magnifier" icon-class="fs-4 m-0" />
      </a>
    </template>
  </CustomTable>

  <!-- 모달 -->
  <el-dialog v-model="logModal.isShow" title="로그정보" width="600">
    <JsonViewer :data="logModal.info" />
  </el-dialog>
</template>

<script setup lang="ts">
  import { computed, reactive } from 'vue';
  import { usePage } from '@inertiajs/vue3';
  import { TableInfo, TableSelectFilter } from '@/admin/components/customTable/types';
  import CustomTable from '@/admin/components/customTable/CustomTable.vue';
  import { Collection, PageProps } from '@/admin/types';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import { Student } from '@/admin/api/model/student';
  import { dayjs } from 'element-plus';
  import KTIcon from '@/admin/core/helpers/kt-icon/KTIcon.vue';
  import JsonViewer from '@/admin/pages/student/components/JsonViewer.vue';

  interface ActiveLog {
    id: number;
    description: string;
    created_at: string;
    properties: string;
  }

  interface Page extends PageProps {
    academies: {
      [key in number]: string;
    };
    config: {
      dbcode: Pick<Dbcode, 'students'>;
    };
    collection: Collection<ActiveLog>;
    student: Student;
  }

  const pageData = usePage<Page>();

  const selectFilters = computed<TableSelectFilter[]>(() => []);

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
          columnName: '내용',
          columnLabel: 'description',
          columnMinWidth: 200,
        },
        {
          columnName: '등록일',
          columnLabel: 'created_at',
          columnWidth: 200,
        },
        {
          columnName: '등록일',
          columnLabel: 'actions',
          columnWidth: 200,
        },
      ],
    };
  });

  const logModal = reactive<{
    isShow: boolean;
    info: ActiveLog | null;
  }>({
    isShow: false,
    info: null,
  });

  const showDetail = (info: any) => {
    logModal.isShow = true;
    logModal.info = info;
  };
</script>
