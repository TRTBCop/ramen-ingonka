<template>
  <BoardLayout page-name="공지사항">
    <div class="board__detail">
      <div class="subject">
        <strong
          ><span>{{ notice.category }}</span
          >{{ notice.title }}</strong
        >
        <span>{{ dayjs(notice.created_at).format('YYYY-MM-DD') }}</span>
      </div>
      <div class="content">
        <p v-html="notice.contents"></p>
      </div>
      <div v-if="notice.files.length > 0" class="download">
        <p>첨부파일</p>
        <div>
          <a v-for="file in notice.files" :key="file.id" :href="file.url" download="asdasd.png">
            <FontAwesomeIcon icon="fa-regular fa-file" />{{ file.name }}
          </a>
        </div>
      </div>
      <div class="btns btns--left">
        <button class="btn--gray" @click="goBoardNoticesPage()"
          ><i class="fa-regular fa-arrow-left"></i>뒤로가기</button
        >
      </div>
    </div>
  </BoardLayout>
</template>
<script setup lang="ts">
  import { computed } from 'vue';
  import { BoardNotice } from '@/app/api/model/boardNotice';
  import BoardLayout from '@/app/layouts/BoardLayout.vue';
  import { PageProps } from '@/app/types/pageData';
  import { usePage } from '@inertiajs/vue3';
  import { dayjs } from 'element-plus';
  import { goBoardNoticesPage } from '@/app/core/helpers/routerHelper';

  interface Page extends PageProps {
    notice: BoardNotice;
  }

  const pageData = usePage<Page>();

  const notice = computed(() => pageData.props.notice);
</script>
