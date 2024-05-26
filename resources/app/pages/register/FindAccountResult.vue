<template>
  <FindLayout page-type="id" :page-step="2">
    <div class="box__result">
      <template v-if="isFindStudent">
        <strong>{{ pageData.props.access_id }}</strong>
        <p>가입일: {{ pageData.props.created_date }}</p>
      </template>

      <p v-else>{{ t('system.findRegister.notFound') }}</p>
    </div>

    <div class="panel__btns">
      <button v-if="isFindStudent" class="btn--brand" @click="goOtherLoginPage">로그인하러 가기</button>

      <template v-else>
        <button class="btn--sub" @click="goRegisterPage">회원가입</button>
        <button class="btn--brand" @click="goFindAccountPage">다시 찾기</button>
      </template>
    </div>
  </FindLayout>
</template>

<script setup lang="ts">
  import { computed } from 'vue';
  import { PageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import FindLayout from '@/app/layouts/FindLayout.vue';
  import { useI18n } from 'vue-i18n';
  import { goFindAccountPage, goOtherLoginPage, goRegisterPage } from '@/app/core/helpers/routerHelper';

  const { t } = useI18n();

  interface Page extends PageProps {
    access_id: string;
    created_date: string;
    student_id: string;
    route_name: string;
    parents_phone: string;
  }

  const pageData = usePage<Page>();

  /** 아이디 찾기 성공 여부 */
  const isFindStudent = computed(() => Boolean(pageData.props.student_id));
</script>
