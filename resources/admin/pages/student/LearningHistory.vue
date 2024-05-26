<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >
  <AdminLayout>
    <Navbar />
    <History />
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import Navbar from './components/Navbar.vue';
  import History from '@/admin/pages/student/components/History.vue';
  import { Head } from '@inertiajs/vue3';
</script>
