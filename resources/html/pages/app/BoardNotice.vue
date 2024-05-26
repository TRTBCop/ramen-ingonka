<template>
  <AppHeader />
  <div class="board">
    <div class="hero">
      <div class="container">
        <h2>공지사항</h2>
      </div>
    </div>
    <div class="container">
      <div class="data_area">
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
            <tr>
              <td class="board__type">공지</td>
              <td class="title title__unread"
                ><a href="javascript:;" @click="router.get('/html/app-BoardNoticeDetail')"
                  >읽지 않은 게시글 타이틀읽지 않은 게시글 타이틀읽지 않은 게시글 타이틀읽지 않은 게시글 타이틀읽지 않은
                  게시글 타이틀읽지 않은 게시글 타이틀읽지 않은 게시글 타이틀</a
                ></td
              >
              <td>2024.01.01</td>
            </tr>
            <tr>
              <td class="board__type">업데이트</td>
              <td class="title title__unread"
                ><a href="javascript:;" @click="router.get('/html/app-BoardNoticeDetail')"
                  >읽지 않은 게시글 타이틀</a
                ></td
              >
              <td>2024.01.01</td>
            </tr>
            <tr>
              <td class="board__type">업데이트</td>
              <td class="title title__unread"
                ><a href="javascript:;" @click="router.get('/html/app-BoardNoticeDetail')"
                  >읽지 않은 게시글 타이틀</a
                ></td
              >
              <td>2024.01.01</td>
            </tr>
            <tr>
              <td class="board__type">공지</td>
              <td class="title"
                ><a href="javascript:;" @click="router.get('/html/app-BoardNoticeDetail')"
                  >읽지 않은 게시글 타이틀</a
                ></td
              >
              <td>2024.01.01</td>
            </tr>
            <tr>
              <td class="board__type">업데이트</td>
              <td class="title"
                ><a href="javascript:;" @click="router.get('/html/app-BoardNoticeDetail')"
                  >읽지 않은 게시글 타이틀</a
                ></td
              >
              <td>2024.01.01</td>
            </tr>
            <tr>
              <td class="board__type">업데이트</td>
              <td class="title"
                ><a href="javascript:;" @click="router.get('/html/app-BoardNoticeDetail')"
                  >읽지 않은 게시글 타이틀</a
                ></td
              >
              <td>2024.01.01</td>
            </tr>
          </tbody>
        </table>

        <div class="pagination">
          <a class="prev disabled" href="javascript:;">
            <i class="fa-regular fa-chevron-left"></i>
          </a>
          <a class="currect" href="javascript:;">1</a>
          <a href="javascript:;">2</a>
          <a href="javascript:;">3</a>
          <a class="next" href="javascript:;">
            <i class="fa-regular fa-chevron-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
  import AppHeader from '@/html/components/app/AppHeader.vue';
  import { router } from '@inertiajs/vue3';
</script>
