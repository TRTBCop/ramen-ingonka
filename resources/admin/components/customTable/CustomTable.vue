<template>
  <div class="card">
    <div class="card-header flex-column border-0 pt-6">
      <div v-if="datePicker" class="card-title gap-4 align-items-center flex-wrap">
        <el-date-picker
          v-model="selectedMonth"
          type="month"
          placeholder="월 선택"
          style="width: 200px"
          @change="onDateSelect"
        />

        <div class="d-flex">
          <el-button type="info" @click="resetFillters">초기화</el-button>
        </div>
      </div>

      <div v-if="selectFilters.length > 0" class="card-title gap-4 align-items-center flex-wrap">
        <template v-for="(item, i) in selectFilters" :key="i">
          <el-select
            v-model="filterOptions[item.name]"
            :placeholder="item.placeholder"
            :multiple="item.isMultiple"
            collapse-tags
            filterable
            clearable
            class="w-100 mw-sm-200px"
          >
            <el-option v-for="(value, key) in item.options" :key="key" :value="key" :label="value" />
          </el-select>
          <!-- 상위 필터를 선택 했을 경우 바뀌는 하위 필터 -->
          <template v-if="item.subOptions">
            <el-select
              v-model="filterOptions[item.subOptions.name]"
              :placeholder="item.subOptions.placeholder"
              :multiple="item.subOptions.isMultiple"
              collapse-tags
              filterable
              clearable
              class="w-100 mw-sm-200px"
            >
              <el-option
                v-for="(value, key) in item.subOptions.options[filterOptions[item.name]]"
                :key="key"
                :value="key"
                :label="value"
              />
            </el-select>
          </template>
        </template>

        <div class="d-flex">
          <el-button type="primary" @click="onApplyFilters">적용</el-button>
          <el-button type="info" @click="resetFillters">초기화</el-button>
        </div>
      </div>
      <div class="card-toolbar justify-content-between flex-wrap">
        <div class="d-flex align-items-center position-relative my-1">
          <input
            v-model="filterText"
            type="text"
            class="form-control form-control-solid w-300px pe-14"
            :placeholder="searchPlaceHolder"
            @keyup.enter="onSearch"
          />
          <KTIcon
            icon-name="magnifier"
            icon-class="fs-1 position-absolute end-0 me-4 cursor-pointer"
            @click="onSearch"
          />
        </div>
        <div
          class="d-flex justify-content-end align-items-center flex-wrap gap-3"
          data-kt-subscription-table-toolbar="base"
        >
          <div v-if="selectedIds.length > 0" class="fw-bold">
            <span class="me-2">{{ selectedIds.length }}</span>
            선택됨
          </div>
          <slot name="header-buttons" :selected-ids="selectedIds" />
        </div>
      </div>
    </div>

    <div class="card-body pt-0">
      <KTDatatable
        :items-per-page-dropdown-enabled="false"
        :items-per-page="tableInfo.perPage"
        :total="tableInfo.total"
        :current-page="tableInfo.currentPage"
        :data="tableInfo.data"
        :header="tableInfo.header"
        :checkbox-enabled="checkBoxEnabled"
        @page-change="onPageChange"
        @on-items-select="onItemSelect"
      >
        <template v-for="(item, i) in tableInfo.header" :key="i" #[item.columnLabel]="{ row: value }">
          <slot :name="item.columnLabel" :row="value">{{ value[item.columnLabel] }}</slot>
        </template>
      </KTDatatable>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, onMounted, PropType } from 'vue';
  import KTDatatable from '@/admin/components/kt-datatable/KTDataTable.vue';
  import { router } from '@inertiajs/vue3';
  import { TableSelectFilter, TableInfo } from './types';
  import { size } from 'lodash';
  import { dayjs } from 'element-plus';

  const props = defineProps({
    requestUrl: {
      type: String as PropType<string>,
      default: '',
    },
    selectFilters: {
      type: Array as PropType<TableSelectFilter[]>,
      default: null,
    },
    tableInfo: {
      type: Object as PropType<TableInfo>,
      default: null,
    },
    checkBoxEnabled: {
      type: Boolean as PropType<boolean>,
      default: true,
    },
    datePicker: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    searchPlaceHolder: {
      type: String as PropType<string>,
      default: '검색어를 입력해주세요',
    },
  });

  const params = new URLSearchParams(window.location.search);

  const page = ref<number>(1);

  const selectedIds = ref<Array<number>>([]);

  const filterText = ref<string>(params.get('filter_text') || '');

  const filterOptions = ref<any>({});

  const onItemSelect = (selectedItems: Array<number>) => {
    selectedIds.value = selectedItems;
  };

  const onPageChange = (p) => {
    page.value = p;
    getRoutePage();
  };

  const onSearch = () => {
    getRoutePage();
  };

  const onApplyFilters = () => {
    page.value = 1;
    getRoutePage();
  };

  const resetFillters = () => {
    const params = {
      page: 1,
    };
    router.get(props.requestUrl, params);
  };

  const getRoutePage = () => {
    const params = {
      filters: (route().params as any)?.filters || {},
      page: page.value,
    };

    // 파라미터에 필터 적용
    Object.keys(filterOptions.value).forEach((key) => {
      if (filterOptions.value[key]?.length <= 0) {
        delete params.filters[key];
        return;
      }
      params.filters[key] = filterOptions.value[key];
    });

    if (filterText.value) {
      params['filter_text'] = filterText.value;
    }

    selectedMonth.value = params.filters.month;

    router.get(props.requestUrl, params);
  };

  const initFilterOptions = () => {
    props.selectFilters.forEach((data) => {
      filterOptions.value[data.name] = '';
      if (data.subOptions) {
        filterOptions.value[data.subOptions.name] = '';
      }
    });

    if (Object.prototype.hasOwnProperty.call(route().params, 'filters')) {
      for (const [key, value] of Object.entries((route().params as any).filters)) {
        if (Object.prototype.hasOwnProperty.call(filterOptions.value, key)) {
          filterOptions.value[key] = value;
        }

        if (props.datePicker) {
          selectedMonth.value = value;
        }
      }
    }
  };

  const cachingTableOptions = () => {
    const params = route().params;

    if (size(params)) {
      sessionStorage.setItem(props.requestUrl, JSON.stringify(route().params));
    } else {
      sessionStorage.removeItem(props.requestUrl);
    }
  };

  const selectedMonth = ref<any>(null);
  const onDateSelect = () => {
    if (selectedMonth.value) {
      filterOptions.value['month'] = dayjs(selectedMonth.value).format('YYYY-MM');
    } else {
      delete filterOptions.value['month'];
    }

    getRoutePage();
  };

  onMounted(async () => {
    cachingTableOptions();
    initFilterOptions();
  });
</script>
