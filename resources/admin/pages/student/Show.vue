<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >
  <AdminLayout>
    <Navbar />
    <Basic />
  </AdminLayout>
</template>

<script setup lang="ts">
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import Navbar from './components/Navbar.vue';
  import Basic from './components/Basic.vue';
  import { Head } from '@inertiajs/vue3';
</script>
