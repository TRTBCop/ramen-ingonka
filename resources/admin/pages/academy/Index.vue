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
      <template #header-buttons="{ selectedIds }">
        <ExcelDownButton
          :href="route('admin.academies.export', selectedIds.length > 0 ? { selected_ids: selectedIds as any } : route().params)"
        />
        <Link :href="route('admin.academies.create')" class="btn btn-primary">
          <KTIcon icon-name="plus" icon-class="fs-2" />
          학원추가
        </Link>
      </template>

      <template #name="{ row: value }">
        <Link :href="route('admin.academies.show', value.id)">
          {{ value.name }}
        </Link>
      </template>

      <template #status="{ row: value }">
        <div :class="`badge badge-light-${statusColors[value.status]}`"> {{ value.txt_status }}</div>
      </template>

      <template #actions="{ row: value }">
        <div class="d-flex gap-1">
          <Link :href="route('admin.academies.show', value.id)" class="btn btn-sm btn-icon btn-light-primary">
            <KTIcon icon-name="pencil" icon-class="fs-4 m-0" />
          </Link>
          <a class="btn btn-sm btn-icon btn-light-danger" @click="deleteSubscription(value.id)">
            <KTIcon icon-name="trash" icon-class="fs-4 m-0" />
          </a>
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
  import { Head, Link, usePage, router } from '@inertiajs/vue3';
  import { AppTag, Collection, PageProps } from '@/admin/types';
  import { TableSelectFilter, TableInfo } from '@/admin/components/customTable/types';
  import ExcelDownButton from '@/admin/components/admin/ExcelDownButton.vue';
  import { Academy } from '@/admin/api/model/academy';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import { commonDeleteConfirm } from '@/admin/core/helpers/commonHelpers';

  interface Page extends PageProps {
    config: {
      dbcode: Pick<Dbcode, 'academies'>;
    };
    collection: Collection<Academy>;
    tags: AppTag[];
  }

  const pageData = usePage<Page>();

  const tagsOptions = computed(() => {
    const res = {};
    pageData.props.tags.forEach((data) => {
      res[data.name.ko] = data.name.ko;
    });
    return res;
  });

  const selectFilters = computed<TableSelectFilter[]>(() => [
    {
      name: 'status',
      isMultiple: true,
      placeholder: '운영상태',
      options: pageData.props.config.dbcode.academies.status,
    },
    {
      name: 'tags',
      isMultiple: true,
      placeholder: '태그',
      options: tagsOptions.value,
    },
  ]);
  
  console.log('data', pageData.props.collection.data);
  
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
        columnName: '학원명',
        columnLabel: 'name',
        columnMinWidth: 200,
      },
      {
        columnName: '전화번호',
        columnLabel: 'phone',
        columnMinWidth: 200,
      },
      {
        columnName: '이용중 학생수',
        columnLabel: 'active_students_count',
        columnMinWidth: 200,
      },
      {
        columnName: '전체 학생수',
        columnLabel: 'students_count',
        columnMinWidth: 200,
      },
      {
        columnName: '담당자 이름',
        columnLabel: 'staff_name',
        columnWidth: 200,
      },
      {
        columnName: '담당자 이메일',
        columnLabel: 'staff_email',
        columnWidth: 200,
      },
      {
        columnName: '상태',
        columnLabel: 'status',
        columnWidth: 100,
      },
      {
        columnName: '',
        columnLabel: 'actions',
        columnWidth: 100,
      },
    ],
  }));

  const statusColors = {
    '-1': 'danger',
    '0': 'warning',
    '1': 'success',
  };

  const deleteSubscription = (id: number) => {
    commonDeleteConfirm(() => router.delete(route('admin.academies.destroy', id)));
  };
</script>
