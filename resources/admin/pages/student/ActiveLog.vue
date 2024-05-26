<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >
  <AdminLayout>
    <Navbar />
    <Log />
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import Navbar from './components/Navbar.vue';
  import Log from '@/admin/pages/student/components/Log.vue';
  import { Head } from '@inertiajs/vue3';
</script>
