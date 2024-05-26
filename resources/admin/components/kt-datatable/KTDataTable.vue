<template>
  <div class="dataTables_wrapper dt-bootstrap4 no-footer p-2">
    <TableContent
      :header="header"
      :data="dataToDisplay"
      :checkbox-enabled="checkboxEnabled"
      :checkbox-label="checkboxLabel"
      :empty-table-text="emptyTableText"
      :sort-label="sortLabel"
      :sort-order="sortOrder"
      :loading="loading"
      @on-items-select="onItemSelect"
      @on-sort="onSort"
    >
      <template v-for="(_, name) in $slots" #[name]="{ row: item }">
        <slot :name="name" :row="item" />
      </template>
    </TableContent>
    <TableFooter
      v-model:itemsPerPage="itemsInTable"
      :current-page="currentPage"
      :count="totalItems"
      :items-per-page-dropdown-enabled="itemsPerPageDropdownEnabled"
      @page-change="pageChange"
    />
  </div>
</template>

<script lang="ts">
  import { computed, defineComponent, ref, watch } from 'vue';
  import TableContent from '@/admin/components/kt-datatable/table-partials/table-content/TableContent.vue';
  import TableFooter from '@/admin/components/kt-datatable/table-partials/TableFooter.vue';
  import type { Sort } from '@/admin/components/kt-datatable/table-partials/models';

  export default defineComponent({
    name: 'KtDatatable',
    props: {
      header: { type: Array, required: true },
      data: { type: Array, required: true },
      itemsPerPage: { type: Number, default: 10 },
      itemsPerPageDropdownEnabled: {
        type: Boolean,
        required: false,
        default: true,
      },
      checkboxEnabled: { type: Boolean, required: false, default: false },
      checkboxLabel: { type: String, required: false, default: 'id' },
      total: { type: Number, required: false },
      loading: { type: Boolean, required: false, default: false },
      sortLabel: { type: String, required: false, default: null },
      sortOrder: {
        type: String as () => 'asc' | 'desc',
        required: false,
        default: 'asc',
      },
      emptyTableText: { type: String, required: false, default: '데이터가 없습니다' },
      currentPage: { type: Number, required: false, default: 1 },
    },
    emits: ['page-change', 'on-sort', 'on-items-select', 'on-items-per-page-change'],
    components: {
      TableContent,
      TableFooter,
    },
    setup(props, { emit }) {
      const currentPage = ref(props.currentPage);
      const itemsInTable = ref<number>(props.itemsPerPage);

      watch(
        () => itemsInTable.value,
        (val) => {
          currentPage.value = 1;
          emit('on-items-per-page-change', val);
        },
      );

      const pageChange = (page: number) => {
        currentPage.value = page;
        emit('page-change', page);
      };

      const dataToDisplay = computed(() => {
        if (props.data) {
          if (props.data.length <= itemsInTable.value) {
            return props.data;
          } else {
            let sliceFrom = (currentPage.value - 1) * itemsInTable.value;
            return props.data.slice(sliceFrom, sliceFrom + itemsInTable.value);
          }
        }
        return [];
      });

      const totalItems = computed(() => {
        if (props.data) {
          if (props.data.length <= itemsInTable.value) {
            return props.total;
          } else {
            return props.data.length;
          }
        }
        return 0;
      });

      const onSort = (sort: Sort) => {
        emit('on-sort', sort);
      };

      //eslint-disable-next-line
      const onItemSelect = (selectedItems: any) => {
        emit('on-items-select', selectedItems);
      };

      return {
        pageChange,
        dataToDisplay,
        onSort,
        onItemSelect,
        itemsInTable,
        totalItems,
      };
    },
  });
</script>
