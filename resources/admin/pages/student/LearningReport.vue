<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >
  <AdminLayout>
    <Navbar />
    <Report />
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import Navbar from './components/Navbar.vue';
  import Report from '@/admin/pages/student/components/Report.vue';
  import { Head } from '@inertiajs/vue3';
</script>
