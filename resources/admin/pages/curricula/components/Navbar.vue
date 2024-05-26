<template>
  <div class="card">
    <div class="card-body pb-0">
      <div class="d-flex flex-column mb-5">
        <div v-if="pageData.props.curriculum.ancestors" class="d-flex gap-2">
          <template v-for="(data, i) in pageData.props.curriculum.ancestors" :key="i">
            <span :class="`badge badge-light-${statusColors[i]}`">{{ data.name }}</span>
            <template v-if="i !== pageData.props.curriculum.ancestors.length - 1"> > </template>
          </template>
        </div>
        <div class="fs-2 fw-bold mt-4">{{ pageData.props.curriculum.name }}</div>
      </div>

      <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold flex-nowrap">
        <li class="nav-item">
          <Link
            :href="route('admin.curricula.show', { curriculum: pageData.props.curriculum.id })"
            class="nav-link text-active-primary"
            :class="{ active: pageData.component === 'curricula/Show' }"
          >
            기본 정보
          </Link>
        </li>
        <li class="nav-item">
          <Link
            :href="
              route('admin.curricula.trainings.show', { curriculum: pageData.props.curriculum.id, trainingStage: 1 })
            "
            class="nav-link text-active-primary"
            :class="{ active: pageData.props.training?.stage === 1 }"
          >
            개념 훈련
          </Link>
        </li>
        <li class="nav-item">
          <Link
            :href="
              route('admin.curricula.trainings.show', { curriculum: pageData.props.curriculum.id, trainingStage: 2 })
            "
            class="nav-link text-active-primary"
            :class="{ active: pageData.props.training?.stage === 2 }"
          >
            유형 훈련
          </Link>
        </li>
        <li class="nav-item">
          <Link
            :href="
              route('admin.curricula.trainings.show', { curriculum: pageData.props.curriculum.id, trainingStage: 3 })
            "
            class="nav-link text-active-primary"
            :class="{ active: pageData.props.training?.stage === 3 }"
          >
            서술형 훈련
          </Link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { Curriculum } from '@/admin/api/model/curriculum';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import { Training } from '@/admin/api/model/training';
  import { PageProps } from '@/admin/types';
  import { Link, usePage } from '@inertiajs/vue3';

  interface Page extends PageProps {
    config: {
      dbcode: Pick<Dbcode, 'curricula'>;
    };
    curriculum: Curriculum;
    training: Training;
  }

  const pageData = usePage<Page>();

  const statusColors = ['danger', 'warning', 'success', 'dark'];
</script>
