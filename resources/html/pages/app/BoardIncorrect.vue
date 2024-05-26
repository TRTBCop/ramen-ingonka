<template>
  <AppHeader />
  <div class="board">
    <div class="hero">
      <div class="container">
        <h2>오답노트</h2>
      </div>
    </div>
    <div class="container">
      <div class="data_area">
        <div class="bar__form">
          <div class="dropdown">
            <button class="dropdown__trigger">학기 전체<i class="fa-regular fa-chevron-down"></i> </button>
            <ul class="dropdown__menu show">
              <li>3학년 1학기</li>
              <li>3학년 2학기</li>
              <li>4학년 1학기</li>
              <li>4학년 2학기</li>
              <li>5학년 1학기</li>
              <li>5학년 2학기</li>
            </ul>
          </div>
          <div class="dropdown">
            <button class="dropdown__trigger">훈련 전체<i class="fa-regular fa-chevron-down"></i> </button>
            <ul class="dropdown__menu show">
              <li>훈련전체</li>
              <li>유형</li>
              <li>서술형</li>
            </ul>
          </div>
        </div>
        <table class="tbl__board">
          <colgroup>
            <col style="width: 12%" />
            <col style="width: 8%" />
            <col style="width: 50%" />
            <col style="width: 8%" />
            <col style="width: 7%" />
            <col style="width: 15%" />
          </colgroup>
          <thead>
            <tr>
              <th>학습 일시</th>
              <th>학기</th>
              <th>학습 내용</th>
              <th>훈련</th>
              <th>ROUND</th>
              <th>오답수/정답수</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>2024.01.01</td>
              <td>초등3-1</td>
              <td class="title"
                ><a href="javascript:;" @click="router.get('/html/app-IncorrectNote')"
                  ><span class="badge--lightgray">3단원 나눗셈</span>똑같이 나누기</a
                ></td
              >
              <td>유형</td>
              <td>3</td>
              <td
                ><span class="badge--empha--alpha"><i class="fa-regular fa-xmark"></i>12</span
                ><span class="badge--success--alpha"><i class="fa-regular fa-o"></i>12</span></td
              >
            </tr>
            <tr>
              <td>2024.01.01</td>
              <td>초등3-1</td>
              <td class="title"
                ><a href="javascript:;" @click="router.get('/html/app-IncorrectNote')"
                  ><span class="badge--lightgray">3단원 나눗셈</span>똑같이 나누기똑같이 나누기똑같이 나누기똑같이
                  나누기똑같이 나누기똑같이 나누기똑같이 나누기똑같이 나누기똑같이 나누기똑같이 나누기똑같이
                  나누기똑같이 나누기똑같이 나누기똑같이 나누기똑같이 나누기</a
                ></td
              >
              <td>유형</td>
              <td>3</td>
              <td
                ><span class="badge--empha--alpha"><i class="fa-regular fa-xmark"></i>12</span
                ><span class="badge--success--alpha"><i class="fa-regular fa-o"></i>12</span></td
              >
            </tr>
            <tr>
              <td>2024.01.01</td>
              <td>초등3-1</td>
              <td class="title"
                ><a href="javascript:;" @click="router.get('/html/app-IncorrectNote')"
                  ><span class="badge--lightgray">3단원 나눗셈</span>똑같이 나누기</a
                ></td
              >
              <td>유형</td>
              <td>3</td>
              <td
                ><span class="badge--empha--alpha"><i class="fa-regular fa-xmark"></i>12</span
                ><span class="badge--success--alpha"><i class="fa-regular fa-o"></i>12</span></td
              >
            </tr>
            <tr>
              <td>2024.01.01</td>
              <td>초등3-1</td>
              <td class="title"
                ><a href="javascript:;" @click="router.get('/html/app-IncorrectNote')"
                  ><span class="badge--lightgray">3단원 나눗셈</span>똑같이 나누기</a
                ></td
              >
              <td>유형</td>
              <td>3</td>
              <td
                ><span class="badge--empha--alpha"><i class="fa-regular fa-xmark"></i>12</span
                ><span class="badge--success--alpha"><i class="fa-regular fa-o"></i>12</span></td
              >
            </tr>
            <tr>
              <td>2024.01.01</td>
              <td>초등3-1</td>
              <td class="title"
                ><a href="javascript:;" @click="router.get('/html/app-IncorrectNote')"
                  ><span class="badge--lightgray">3단원 나눗셈</span>똑같이 나누기</a
                ></td
              >
              <td>유형</td>
              <td>3</td>
              <td
                ><span class="badge--empha--alpha"><i class="fa-regular fa-xmark"></i>12</span
                ><span class="badge--success--alpha"><i class="fa-regular fa-o"></i>12</span></td
              >
            </tr>
            <tr>
              <td>2024.01.01</td>
              <td>초등3-1</td>
              <td class="title"
                ><a href="javascript:;" @click="router.get('/html/app-IncorrectNote')"
                  ><span class="badge--lightgray">3단원 나눗셈</span>똑같이 나누기</a
                ></td
              >
              <td>유형</td>
              <td>3</td>
              <td
                ><span class="badge--empha--alpha"><i class="fa-regular fa-xmark"></i>12</span
                ><span class="badge--success--alpha"><i class="fa-regular fa-o"></i>12</span></td
              >
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
