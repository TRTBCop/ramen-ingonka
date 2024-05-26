<template>
  <CustomTable :request-url="route(pageData.props.route_name, pageData.props.academy)" :select-filters="selectFilters" :table-info="tableInfo">
    <template #academy="{ row: value }">
      <a
        v-if="value.academy_id"
        :href="route('admin.students.show', value.id)"
        class="menu-link px-3"
        target="_blank"
      >
        {{ value.academy?.name }}
      </a>
    </template>
    
    <template #name="{ row: value }">
      <Link :href="route('admin.students.show', value.id)" class="menu-link px-3">
        {{ value.name }}
      </Link>
    </template>
    <template #grade="{ row: value }"> {{ value.grade ? `${value.grade}학년` : '-' }}</template>
    
    <template #type_detail="{ row: data }">
      <template v-if="data.type == 1"> {{ data.el_grade }}-{{ data.el_term }} ({{ data.el_study_no }})</template>
      <template v-else> LV {{ data.mh_level }}</template>
    </template>
    <template #status="{ row: value }">
      <div :class="`badge badge-light-${statusColors[value.status]}`"> {{ value.txt_status }}</div>
    </template>
    <template #created_at="{ row: value }">
      {{ dayjs(value.created_at).format('YYYY-MM-DD HH:mm:ss') }}
    </template>
    <template #service_end_date="{ row: value }">
      {{ value.service_end_date ? dayjs(value.service_end_date).format('YYYY-MM-DD HH:mm:ss') : '-' }}
    </template>
  </CustomTable>
</template>

<script setup lang="ts">
import {computed, ref, watch} from 'vue';
import {Link, router, usePage} from '@inertiajs/vue3';
import {TableSelectFilter, TableInfo, TableSelectFilterOptions} from '@/admin/components/customTable/types';
import KTIcon from '@/admin/core/helpers/kt-icon/KTIcon.vue';
import CustomTable from '@/admin/components/customTable/CustomTable.vue';
import {Collection, PageProps} from '@/admin/types';
import {Dbcode} from '@/admin/api/model/dbcode';
import {Student} from '@/admin/api/model/student';
import {commonDeleteConfirm} from '@/admin/core/helpers/commonHelpers';
import {dayjs} from 'element-plus';
import {Academy} from "@/admin/api/model/academy";

interface Page extends PageProps {
  academy: Academy,
  config: {
    dbcode: Pick<Dbcode, 'students'>;
  };
  collection: Collection<Student>;
  route_name: string;
}

const pageData = usePage<Page>();

const selectFilters = computed<TableSelectFilter[]>(() => {
  const result: TableSelectFilter[] = [
    {
      name: 'status',
      isMultiple: true,
      placeholder: '서비스상태',
      options: pageData.props.config.dbcode.students.status as unknown as TableSelectFilterOptions,
    },
    {
      name: 'grade',
      isMultiple: false,
      placeholder: '학년',
      options: pageData.props.config.dbcode.students.grade as unknown as TableSelectFilterOptions,
    }
  ];
  
  return result;
});


const tableInfo = computed<TableInfo>(() => {
  const result = {
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
        columnName: '학원명',
        columnLabel: 'academy',
        columnMinWidth: 200,
      },
      {
        columnName: '학생 이름',
        columnLabel: 'name',
        columnMinWidth: 200,
      },
      {
        columnName: '아이디',
        columnLabel: 'access_id',
        columnMinWidth: 200,
      },
      {
        columnName: '부모님 연락처',
        columnLabel: 'parents_phone',
        columnMinWidth: 200,
      },
      {
        columnName: '학년',
        columnLabel: 'grade',
        columnWidth: 200,
      },
      {
        columnName: '서비스상태',
        columnLabel: 'status',
        columnMinWidth: 150,
      },
      {
        columnName: '가입일',
        columnLabel: 'created_at',
        columnWidth: 200,
      },
      {
        columnName: '서비스 종료일',
        columnLabel: 'service_end_date',
        columnWidth: 200,
      },
    ],
  };
  
  return result;
});

const statusColors = {
  '-2': 'danger',
  '-1': 'warning',
  '0': 'dark',
  '1': 'success',
};

</script>
