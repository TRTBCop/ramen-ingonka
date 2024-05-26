<template>
  <div class="pagination">
    <a class="prev" :class="{ disabled: isInFirstPage }" @click="onClickPreviousPage">
      <font-awesome-icon icon="fa-regular fa-chevron-left" />
    </a>
    <a
      v-for="(page, i) in pages"
      :key="i"
      :class="{
        currect: isPageActive(page.name),
      }"
      @click="onClickPage(page.name)"
      >{{ page.name }}</a
    >
    <a class="next" :class="{ disabled: isInLastPage }" href="javascript:;" @click="onClickNextPage">
      <font-awesome-icon icon="fa-regular fa-chevron-right" />
    </a>
  </div>
</template>

<script setup lang="ts">
  import { computed, PropType } from 'vue';

  const props = defineProps({
    maxVisibleButtons: {
      type: Number as PropType<number>,
      default: 5,
    },
    totalPages: {
      type: Number as PropType<number>,
      default: 1,
    },
    total: {
      type: Number as PropType<number>,
      default: 1,
    },
    perPage: {
      type: Number as PropType<number>,
      default: 10,
    },
    currentPage: {
      type: Number as PropType<number>,
      required: true,
      default: 1,
    },
  });

  const emits = defineEmits(['page-change']);

  const startPage = computed(() => {
    if (
      props.totalPages < props.maxVisibleButtons ||
      props.currentPage === 1 ||
      props.currentPage <= Math.floor(props.maxVisibleButtons / 2) ||
      (props.currentPage + 2 > props.totalPages && props.totalPages === props.maxVisibleButtons)
    ) {
      return 1;
    }

    if (props.currentPage + 2 > props.totalPages) {
      return props.totalPages - props.maxVisibleButtons + 1;
    }

    return props.currentPage - 2;
  });

  const endPage = computed(() => {
    return Math.min(startPage.value + props.maxVisibleButtons - 1, props.totalPages);
  });

  const pages = computed(() => {
    const range: Array<{
      name: number;
      isDisabled: boolean;
    }> = [];

    for (let i = startPage.value; i <= endPage.value; i += 1) {
      range.push({
        name: i,
        isDisabled: i === props.currentPage,
      });
    }

    return range;
  });

  const isInFirstPage = computed(() => {
    return props.currentPage === 1;
  });
  const isInLastPage = computed(() => {
    return props.currentPage === props.totalPages;
  });
  function onClickPreviousPage() {
    emits('page-change', props.currentPage - 1);
  }
  function onClickPage(page: number) {
    emits('page-change', page);
  }
  function onClickNextPage() {
    emits('page-change', props.currentPage + 1);
  }
  function isPageActive(page: number) {
    return props.currentPage === page;
  }
</script>
