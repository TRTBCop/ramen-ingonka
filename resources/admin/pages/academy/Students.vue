<template>
  <Head
  ><title>{{ $page.props.page.title }}</title></Head
  >
  
  <AdminLayout>
    <Navbar />
    <StudentList />
  </AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/admin/layouts/AdminLayout.vue';
import Navbar from './components/Navbar.vue';
import StudentList from './components/StudentList.vue';
import { Head } from '@inertiajs/vue3';
</script>
