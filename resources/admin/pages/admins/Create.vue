<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >

  <AdminLayout>
    <AdminView />
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import AdminView from './components/AdminView.vue';
  import { Head } from '@inertiajs/vue3';
</script>
