<template>
  <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
    {{ (currentPage - 1) * perPage + 1 }} -
    {{ (currentPage - 1) * perPage + perPage > total ? total : (currentPage - 1) * perPage + perPage }} / 전체
    {{ total.toLocaleString() }}
    <label for="items-per-page">
      <select
        v-if="itemsPerPageDropdownEnabled"
        id="items-per-page"
        v-model="itemsCountInTable"
        class="form-select form-select-sm form-select-solid"
        name="items-per-page"
      >
        <option :value="10">10</option>
        <option :value="25">25</option>
        <option :value="50">50</option>
      </select>
    </label>
  </div>
</template>

<script lang="ts">
  import { computed, defineComponent, onMounted, ref, type WritableComputedRef } from 'vue';

  export default defineComponent({
    name: 'TableItemsPerPageSelect',
    components: {},
    props: {
      total: { type: Number, default: 0 },
      perPage: {
        type: Number,
        required: true,
      },
      currentPage: {
        type: Number,
        required: true,
      },
      itemsPerPage: { type: Number, default: 10 },
      itemsPerPageDropdownEnabled: {
        type: Boolean,
        required: false,
        default: true,
      },
    },
    emits: ['update:itemsPerPage'],
    setup(props, { emit }) {
      const inputItemsPerPage = ref(10);

      onMounted(() => {
        inputItemsPerPage.value = props.itemsPerPage;
      });

      const itemsCountInTable: WritableComputedRef<number> = computed({
        get(): number {
          return props.itemsPerPage;
        },
        set(value: number): void {
          emit('update:itemsPerPage', value);
        },
      });

      return {
        itemsCountInTable,
      };
    },
  });
</script>
