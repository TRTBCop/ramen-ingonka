<template>
  <CustomTable :request-url="route(pageData.props.route_name, pageData.props.student)" :date-picker="true" :select-filters="selectFilters" :table-info="tableInfo" :check-box-enabled="false">
    <template #id="{ row: value }">
      {{ value.id }}
    </template>
    
    <template #title="{ row: value }">
      {{ value.test.title }} 진단평가
    </template>
    <template #created_at="{ row: value }">
      {{ dayjs(value.created_at).format('YYYY-MM-DD HH:mm:ss') }}
    </template>
  
  </CustomTable>
</template>

<script setup lang="ts">
import {computed} from 'vue';
import {usePage} from '@inertiajs/vue3';
import {TableInfo, TableSelectFilter} from '@/admin/components/customTable/types';
import CustomTable from '@/admin/components/customTable/CustomTable.vue';
import {Collection, PageProps} from '@/admin/types';
import {Dbcode} from '@/admin/api/model/dbcode';
import {Student} from '@/admin/api/model/student';
import {dayjs} from 'element-plus';

interface ActiveLog {
  id: number;
  description: string;
  created_at:string;
}

interface Page extends PageProps {
  academies: {
    [key in number]: string;
  };
  config: {
    dbcode: Pick<Dbcode, 'students'>;
  };
  collection: Collection<ActiveLog>;
  student: Student
}

const pageData = usePage<Page>();

const selectFilters = computed<TableSelectFilter[]>(() => []);

console.log('data', pageData.props.collection);

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
        columnLabel: 'title',
        columnMinWidth: 200,
      },
      {
        columnName: '등록일',
        columnLabel: 'created_at',
        columnWidth: 200,
      },
    ],
  };
});

</script>
