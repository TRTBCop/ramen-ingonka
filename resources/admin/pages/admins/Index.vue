<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >

  <AdminLayout>
    <CustomTable
      :request-url="route(pageData.props.route_name)"
      :select-filters="selectFilters"
      :table-info="tableInfo"
      :check-box-enabled="false"
    >
      <template #header-buttons>
        <Link :href="route('admin.admins.create')" class="btn btn-primary">
          <KTIcon icon-name="plus" icon-class="fs-2" />
          관리자 등록
        </Link>
      </template>

      <template #name="{ row: value }">
        <Link :href="route('admin.admins.show', value.id)">
          {{ value.name }}
        </Link>
      </template>

      <template #roles="{ row: value }">
        <span
          v-for="(item, key) in value.roles"
          :key="key"
          :class="`badge badge-light-${getAdminRolesColor(item.name)} me-1`"
        >
          {{ pageData.props.roles.find((value) => value.value === item.name)?.text }}
        </span>
      </template>
      <template #created_at="{ row: value }">
        {{ dayjs(value.created_at).format('YYYY-MM-DD HH:mm:ss') }}
      </template>

      <template #actions="{ row: value }">
        <div class="d-flex gap-1">
          <Link :href="route('admin.admins.show', value.id)" class="btn btn-sm btn-icon btn-light-primary">
            <KTIcon icon-name="pencil" icon-class="fs-4 m-0" />
          </Link>
          <a class="btn btn-sm btn-icon btn-light-danger" @click="deleteAdmin(value.id)">
            <KTIcon icon-name="trash" icon-class="fs-4 m-0" />
          </a>
        </div>
      </template>
    </CustomTable>
  </AdminLayout>
</template>

<script setup lang="ts">
  import { computed } from 'vue';
  import CustomTable from '@/admin/components/customTable/CustomTable.vue';
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import { PageProps } from '@/admin/types';
  import { Head, Link, router, usePage } from '@inertiajs/vue3';
  import { TableSelectFilter, TableInfo } from '@/admin/components/customTable/types';
  import { ElMessageBox, dayjs } from 'element-plus';

  interface Collection {
    data: any[];
    links: any[];
    meta: {
      current_page: number;
      total: number;
      per_page: number;
    };
  }

  const pageData = usePage<
    PageProps<{
      collection: Collection;
      route_name: string;
      roles: {
        text: string;
        name: string;
        value: string;
      }[];
      config: {
        board: {
          name: string;
          scope: {
            [key in string]: string;
          };
          category: { name: string; category: { name: string }[] }[];
        };
      };
    }>
  >();

  const selectFilters = computed<TableSelectFilter[]>(() => []);

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
        columnName: 'ID',
        columnLabel: 'access_id',
        columnMinWidth: 200,
      },
      {
        columnName: '이름',
        columnLabel: 'name',
        columnMinWidth: 200,
      },
      {
        columnName: '타입',
        columnLabel: 'roles',
        columnMinWidth: 200,
      },
      {
        columnName: '등록일',
        columnLabel: 'created_at',
        columnMinWidth: 200,
      },
      {
        columnName: '',
        columnLabel: 'actions',
        columnWidth: 100,
      },
    ],
  }));

  const getAdminRolesColor = (value: string) => {
    const colors = ['success', 'danger', 'warning', 'info'];

    return colors[pageData.props.roles.findIndex((data) => data.value === value)];
  };

  const deleteAdmin = (id: number) => {
    ElMessageBox.confirm('정말 삭제하시겠습니까?', '', {
      type: 'warning',
    }).then(
      () => {
        router.delete(route('admin.admins.destroy', id));
      },
      () => {
        //
      },
    );
  };
</script>
