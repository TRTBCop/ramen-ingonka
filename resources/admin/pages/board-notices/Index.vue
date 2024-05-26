<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >

  <AdminLayout>
    <BoardCommonList />
  </AdminLayout>
</template>

<script setup lang="ts">
  import BoardCommonList from '@/admin/components/admin/BoardCommonList.vue';
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import { Head } from '@inertiajs/vue3';
</script>
