<template>
  <AppHeader />
  <div class="board">
    <div class="hero">
      <div class="container">
        <h2>학습보고서</h2>
      </div>
    </div>
    <div class="container">
      <!-- 보고서 있을때  -->
      <div class="data_area">
        <table class="tbl__board">
          <colgroup>
            <col style="width: 10%" />
            <col style="width: 50%" />
            <col style="width: 25%" />
            <col style="width: 15%" />
          </colgroup>
          <thead>
            <tr>
              <th>번호</th>
              <th>제목</th>
              <th>학습기간</th>
              <th>등록일</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>3</td>
              <td class="title"><a href="javascript:;">3월 보고서</a></td>
              <td>2024.01.01 ~ 2024.01.31</td>
              <td>2024.01.01</td>
            </tr>
            <tr>
              <td>2</td>
              <td class="title"><a href="javascript:;">2월 보고서</a></td>
              <td>2024.01.01 ~ 2024.01.31</td>
              <td>2024.01.01</td>
            </tr>
            <tr>
              <td>1</td>
              <td class="title"><a href="javascript:;">1월 보고서</a></td>
              <td>2024.01.01 ~ 2024.01.31</td>
              <td>2024.01.01</td>
            </tr>
          </tbody>
        </table>

        <div class="pagination">
          <a class="prev" href="javascript:;">
            <i class="fa-solid fa-chevron-left"></i>
          </a>
          <a class="currect" href="javascript:;">1</a>
          <a href="javascript:;">2</a>
          <a href="javascript:;">3</a>
          <a class="next" href="javascript:;">
            <i class="fa-solid fa-chevron-right"></i>
          </a>
        </div>
      </div>

      <!-- 보고서 없을때  -->
      <div class="data_area data__none">
        <img src="@/assets/img/math/no_data.svg" alt="" />
        <p>보고서가 존재하지 않습니다.</p>
        <table class="tbl__board">
          <colgroup>
            <col style="width: 10%" />
            <col style="width: 50%" />
            <col style="width: 25%" />
            <col style="width: 15%" />
          </colgroup>
          <thead>
            <tr>
              <th>번호</th>
              <th>제목</th>
              <th>학습기간</th>
              <th>등록일</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>3</td>
              <td class="title"><a href="javascript:;" @click="router.get('/html/app-MathReport')">3월 보고서</a></td>
              <td>2024.01.01 ~ 2024.01.31</td>
              <td>2024.01.01</td>
            </tr>
            <tr>
              <td>2</td>
              <td class="title"><a href="../../../html/pages/app/monthly_report.html">2월 보고서</a></td>
              <td>2024.01.01 ~ 2024.01.31</td>
              <td>2024.01.01</td>
            </tr>
            <tr>
              <td>1</td>
              <td class="title"><a href="javascript:;" @click="router.get('/html/app-MathReport')">1월 보고서</a></td>
              <td>2024.01.01 ~ 2024.01.31</td>
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
