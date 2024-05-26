<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >

  <AdminLayout>
    <BoardCommonView />
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import { Head } from '@inertiajs/vue3';
  import BoardCommonView from '@/admin/components/admin/BoardCommonView.vue';
</script>
