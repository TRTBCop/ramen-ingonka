<template>
  <BoardLayout page-name="공지사항">
    <div v-if="notices.length > 0" class="data_area">
      <table class="tbl__board">
        <colgroup>
          <col style="width: 10%" />
          <col style="width: 75%" />
          <col style="width: 15%" />
        </colgroup>
        <thead>
          <tr>
            <th>구분</th>
            <th>제목</th>
            <th>등록일</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="notice in notices" :key="notice.id" @click="goBoardNoticeDetailPage(notice.id)">
            <td class="board__type">{{ notice.category }}</td>
            <td class="title">
              <a href="javascript:;">{{ notice.title }}</a>
            </td>
            <td>{{ dayjs(notice.created_at).format('YYYY.MM.DD') }}</td>
          </tr>
        </tbody>
      </table>
      <TablePagination
        :total-pages="collectionMeta.last_page"
        :total="collectionMeta.total"
        :per-page="collectionMeta.per_page"
        :current-page="collectionMeta.current_page"
        @page-change="movePage"
      />
    </div>
    <div v-else class="data_area data__none">
      <img src="@/assets/img/math/no_data.svg" alt="" />
      <p>공지사항이 없습니다.</p>
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
  import { goBoardNoticeDetailPage, goBoardNoticesPage } from '@/app/core/helpers/routerHelper';
  import { Collection } from '@/app/types';
  import TablePagination from '@/app/components/table/TablePagination.vue';

  interface Page extends PageProps {
    collection: Collection<BoardNotice>;
  }

  const pageData = usePage<Page>();

  function movePage(page: number) {
    goBoardNoticesPage(page);
  }

  const notices = computed(() => pageData.props.collection.data);

  const collectionMeta = computed(() => pageData.props.collection.meta);
</script>
