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
      <template #learning_progress="{ row: value }">{{ value.ancestors[0]?.name }}</template>
      <template #main_chapter="{ row: value }">
        {{ value.ancestors[1]?.name }}
      </template>
      <template #sub_chapter="{ row: value }">
        {{ value.ancestors[2]?.name }}
      </template>

      <template #ancestors="{ row: value }">
        <template v-for="(data, i) in value.ancestors" :key="i">
          <span :class="`badge badge-light-${statusColors[i]}`">{{ data.name }}</span>
          <template v-if="i !== value.ancestors.length - 1"> > </template>
        </template>
      </template>

      <template #name="{ row: value }">
        <Link :href="route('admin.curricula.show', value.id)">
          {{ value.name }}
        </Link>
      </template>

      <template #published_at="{ row: value }">
        <div class="d-flex gap-2">
          <el-tag
            v-for="(name, i) in trainingsNames"
            :key="name"
            :type="getPublishedAtColor(Boolean(value.trainings[i]?.published_at))"
            >{{ name }}</el-tag
          >
        </div>
      </template>

      <template #actions="{ row: value }">
        <div class="d-flex gap-1">
          <Link :href="route('admin.curricula.show', value.id)" class="btn btn-sm btn-icon btn-light-primary">
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
  import { Collection, PageProps } from '@/admin/types';
  import { TableSelectFilter, TableInfo } from '@/admin/components/customTable/types';
  import { Curriculum } from '@/admin/api/model/curriculum';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import { deleteCurriculum } from '@/admin/api/curriculum';
  import { commonDeleteConfirm } from '@/admin/core/helpers/commonHelpers';

  interface Page extends PageProps {
    config: {
      dbcode: Pick<Dbcode, 'curricula'>;
    };
    collection: Collection<Curriculum>;
    curriculum_depth_1: {
      [key in string]: string;
    };
    curriculum_depth_2: {
      [key in string]: {
        [key in string]: string;
      };
    };
  }

  const pageData = usePage<Page>();

  const selectFilters = computed<TableSelectFilter[]>(() => [
    {
      name: 'category_depth_1_id',
      options: pageData.props.curriculum_depth_1,
      placeholder: '대단원',
      isMultiple: false,
      subOptions: {
        name: 'category_depth_2_id',
        options: pageData.props.curriculum_depth_2,
        placeholder: '소단원',
        isMultiple: false,
      },
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
        columnName: '속성',
        columnLabel: 'txt_element',
        columnWidth: 150,
      },
      {
        columnName: '학습과정',
        columnLabel: 'learning_progress',
        columnMinWidth: 200,
      },
      {
        columnName: '단원',
        columnLabel: 'main_chapter',
        columnMinWidth: 200,
      },
      {
        columnName: '중단원',
        columnLabel: 'sub_chapter',
        columnMinWidth: 200,
      },
      {
        columnName: '소단원',
        columnLabel: 'name',
        columnMinWidth: 200,
      },
      {
        columnName: '검수상태',
        columnLabel: 'published_at',
        columnWidth: 200,
      },
      {
        columnName: '',
        columnLabel: 'actions',
        columnWidth: 100,
      },
    ],
  }));

  const trainingsNames = ['개념', '유형', '서술형'];

  const statusColors = ['danger', 'warning', 'success', 'dark', 'info'];

  function getPublishedAtColor(isPublished: boolean) {
    if (isPublished) {
      return statusColors[2];
    } else {
      return statusColors[4];
    }
  }

  const handelDeleteCurriculum = async (id: number) => {
    try {
      const { data } = await deleteCurriculum(id);

      if (!data.success) throw new Error();

      router.get(route('admin.curricula.index'));
    } catch (err) {
      console.log(err);
    }
  };

  const deleteSubscription = (id: number) => {
    commonDeleteConfirm(() => handelDeleteCurriculum(id));
  };
</script>
