<template>
  <CustomTable :request-url="route(pageData.props.route_name)" :select-filters="selectFilters" :table-info="tableInfo">
    <template #header-buttons="{ selectedIds }">
      <ExcelDownButton
        :href="route('admin.students.export', selectedIds.length > 0 ? { selected_ids: selectedIds as any } : route().params)"
      />
      <a class="btn btn-success" @click="isB2c = !isB2c">{{ isB2c ? '전체 보기' : 'B2C 보기' }}</a>
    </template>
    <template #academy="{ row: value }">
      <a
        v-if="value.academy_id"
        :href="route('admin.academies.show', value.academy_id)"
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
    <template #grade="{ row: value }"> {{ value.grade ? `${value.grade}학년` : '-' }} </template>

    <template #type_detail="{ row: data }">
      <template v-if="data.type == 1"> {{ data.el_grade }}-{{ data.el_term }} ({{ data.el_study_no }}) </template>
      <template v-else> LV {{ data.mh_level }} </template>
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

    <template #actions="{ row: value }">
      <div class="d-flex gap-1">
        <Link :href="route('admin.students.show', value.id)" class="btn btn-sm btn-icon btn-light-primary">
          <KTIcon icon-name="pencil" icon-class="fs-4 m-0" />
        </Link>
        <a class="btn btn-sm btn-icon btn-light-danger" @click="deleteSubscription(value.id)">
          <KTIcon icon-name="trash" icon-class="fs-4 m-0" />
        </a>
      </div>
    </template>
  </CustomTable>
</template>

<script setup lang="ts">
  import { computed, ref, watch } from 'vue';
  import { Link, router, usePage } from '@inertiajs/vue3';
  import { TableSelectFilter, TableInfo, TableSelectFilterOptions } from '@/admin/components/customTable/types';
  import KTIcon from '@/admin/core/helpers/kt-icon/KTIcon.vue';
  import CustomTable from '@/admin/components/customTable/CustomTable.vue';
  import { Collection, PageProps } from '@/admin/types';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import { Student } from '@/admin/api/model/student';
  import { commonDeleteConfirm } from '@/admin/core/helpers/commonHelpers';
  import { dayjs } from 'element-plus';
  import ExcelDownButton from "@/admin/components/admin/ExcelDownButton.vue";

  interface Page extends PageProps {
    academies: {
      [key in number]: string;
    };
    config: {
      dbcode: Pick<Dbcode, 'students'>;
    };
    collection: Collection<Student>;
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
    ];

    if (!isB2c.value) {
      result.push({
        name: 'academy_id',
        isMultiple: true,
        placeholder: '학원별보기',
        options: pageData.props.academies,
      });
    }

    return result;
  });

  const isB2c = ref(Boolean((route().params as any)?.filters?.b2c));

  watch(
    () => isB2c.value,
    (newVal) => {
      const routeParams = route().params as any;
      let filters = routeParams?.filters || {};

      if (newVal) {
        filters.b2c = true;
        delete filters.academy_id;
      } else {
        delete filters.b2c;
      }

      router.get(route(pageData.props.route_name), {
        ...routeParams,
        filters,
      });
    },
  );

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
        {
          columnName: '',
          columnLabel: 'actions',
          columnWidth: 50,
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

  const deleteSubscription = (id: number) => {
    commonDeleteConfirm(() => router.delete(route('admin.students.destroy', id)));
  };
</script>
