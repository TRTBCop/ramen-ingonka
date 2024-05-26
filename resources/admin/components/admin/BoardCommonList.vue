<template>
  <CustomTable
    :request-url="route(pageData.props.route_name)"
    :select-filters="selectFilters"
    :table-info="tableInfo"
    :check-box-enabled="false"
  >
    <template #header-buttons>
      <Link :href="route(`admin.${currentRouteName}.create`)" class="btn btn-primary">
        <KTIcon icon-name="plus" icon-class="fs-2" />
        게시글 등록
      </Link>
    </template>
    <template #admin="{ row: value }">
      {{ value.admin?.name }}
    </template>
    <template #scope="{ row: value }">
      {{ printScope(value.scope) }}
    </template>
    <template #title="{ row: value }">
      <Link :href="route(`admin.${currentRouteName}.show`, value.id)">{{ value.title }}</Link>
    </template>
    <template #published_at="{ row: value }">
      {{ value.published_at ? '노출' : '비노출' }}
    </template>
    <template #created_at="{ row: value }">
      {{ dayjs(value.created_at).format('YYYY-MM-DD HH:mm:ss') }}
    </template>
    <template #actions="{ row: value }">
      <div class="d-flex gap-1">
        <Link :href="route(`admin.${currentRouteName}.show`, value.id)" class="btn btn-sm btn-icon btn-light-primary">
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
  import CustomTable from '@/admin/components/customTable/CustomTable.vue';
  import { TableSelectFilter, TableInfo } from '@/admin/components/customTable/types';
  import { PageProps } from '@/admin/types';
  import { Link, router, usePage } from '@inertiajs/vue3';
  import { ElMessageBox } from 'element-plus';
  import { computed } from 'vue';
  import dayjs from 'dayjs';

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

  const currentRouteName = computed(() => pageData.props.route_name.split('.')[1]);

  const selectFilters = computed<TableSelectFilter[]>(() => {
    const result = [
      {
        name: 'category',
        options: categoryOptions.value,
        placeholder: '상위 카테고리',
        isMultiple: true,
      },
      {
        name: 'sub_category',
        options: subCategoryOptions.value,
        placeholder: '하위 카테고리',
        isMultiple: true,
      },
    ];

    // 공지사항에서만 '노출범위' 필터 적용
    if (currentRouteName.value === 'board-notices') {
      result.unshift({
        name: 'scope',
        options: pageData.props.config.board.scope,
        placeholder: '노출 범위',
        isMultiple: true,
      });
    }

    return result;
  });

  //노출 범위
  const printScope = (scope: number) => {
    const labels: string[] = [];

    if (scope & 1) labels.push('학원');
    if (scope & 2) labels.push('학생');
    if (scope & 4) labels.push('브랜드');

    // 라벨들을 결합하여 반환합니다.
    return labels.join(' + ');
  };

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
        columnName: '상위 카테고리',
        columnLabel: 'category',
        columnWidth: 200,
      },
      {
        columnName: '하위 카테고리',
        columnLabel: 'sub_category',
        columnWidth: 200,
      },
      {
        columnName: '운영자',
        columnLabel: 'admin',
        columnWidth: 200,
      },
      {
        columnName: '제목',
        columnLabel: 'title',
        columnMinWidth: 300,
      },
      {
        columnName: '노출범위',
        columnLabel: 'scope',
        columnMinWidth: 300,
      },
      {
        columnName: '노출상태',
        columnLabel: 'published_at',
        columnMinWidth: 300,
      },
      {
        columnName: '등록일',
        columnLabel: 'created_at',
        columnWidth: 300,
      },
      {
        columnName: '',
        columnLabel: 'actions',
        columnWidth: 100,
      },
    ],
  }));

  /** 상위 카테고리 필터 옵션 */
  const categoryOptions = computed(() => {
    const result = {};
    pageData.props.config.board.category.forEach((data) => {
      result[data.name] = data.name;
    });
    return result;
  });

  /** 하위 카테고리 필터 옵션 */
  const subCategoryOptions = computed(() => {
    const result = {};
    pageData.props.config.board.category.forEach((data) => {
      if (!data?.category) return;
      data.category.forEach((value) => {
        result[value.name] = value.name;
      });
    });
    return result;
  });

  const deleteSubscription = (id: number) => {
    ElMessageBox.confirm('정말 삭제하시겠습니까?', '', {
      type: 'warning',
    }).then(
      () => {
        router.delete(route(`admin.${currentRouteName.value}.destroy`, id));
      },
      () => {
        //
      },
    );
  };
</script>
