<template>
  <thead>
    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
      <th v-if="checkboxEnabled" :style="{ width: '30px' }">
        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
          <input v-model="checked" class="form-check-input" type="checkbox" @change="selectAll()" />
        </div>
      </th>
      <template v-for="(column, i) in header" :key="i">
        <th
          :class="`
          ${column.columnMinWidth ? `min-w-${column.columnMinWidth}px` : 'min-w-50px'}
          ${column.columnWidth ? `min-w-${column.columnWidth}px` : ''}
          ${column.columnWidth ? `w-${column.columnWidth}px` : ''}
          `"
          :style="{
            cursor: column.sortEnabled ? 'pointer' : 'auto',
          }"
          @click="onSort(column.columnLabel, column.sortEnabled)"
        >
          {{ column.columnName }}
          <span v-if="columnLabelAndOrder.label === column.columnLabel && column.sortEnabled" v-html="sortArrow"></span>
        </th>
      </template>
    </tr>
  </thead>
</template>

<script lang="ts">
  import { computed, defineComponent, ref, watch } from 'vue';
  import type { Sort } from '@/admin/components/kt-datatable/table-partials/models';

  export default defineComponent({
    name: 'TableHeadRow',
    props: {
      checkboxEnabledValue: { type: Boolean, required: false, default: false },
      checkboxEnabled: { type: Boolean, required: false, default: false },
      sortLabel: { type: String, required: false, default: null },
      sortOrder: {
        type: String as () => 'asc' | 'desc',
        required: false,
        default: 'asc',
      },
      header: { type: Array as () => Array<any>, required: true },
    },
    emits: ['on-select', 'on-sort'],
    components: {},
    setup(props, { emit }) {
      const checked = ref<boolean>(false);
      const columnLabelAndOrder = ref<Sort>({
        label: props.sortLabel,
        order: props.sortOrder,
      });

      watch(
        () => props.checkboxEnabledValue,
        (currentValue) => {
          checked.value = currentValue;
        },
      );

      const selectAll = () => {
        emit('on-select', checked.value);
      };

      const onSort = (label: string, sortEnabled: boolean) => {
        if (sortEnabled) {
          if (columnLabelAndOrder.value.label === label) {
            if (columnLabelAndOrder.value.order === 'asc') {
              columnLabelAndOrder.value.order = 'desc';
            } else {
              if (columnLabelAndOrder.value.order === 'desc') {
                columnLabelAndOrder.value.order = 'asc';
              }
            }
          } else {
            columnLabelAndOrder.value.order = 'asc';
            columnLabelAndOrder.value.label = label;
          }
          emit('on-sort', columnLabelAndOrder.value);
        }
      };

      const sortArrow = computed(() => {
        return columnLabelAndOrder.value.order === 'asc' ? '&#x2191;' : '&#x2193;';
      });

      return {
        onSort,
        selectAll,
        checked,
        sortArrow,
        columnLabelAndOrder,
      };
    },
  });
</script>
