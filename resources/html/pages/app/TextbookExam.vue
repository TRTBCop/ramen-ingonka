<template>
  <AppLayout :theme-show="1">
    <div class="grid">
      <div class="frame col">
        <div class="frame__head tab_area">
          <ul class="tabs tabs--count">
            <li class="current"></li>
            <li class="correct"></li>
            <li class="wrong"></li>
            <li class=""></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
          </ul>
        </div>
        <div class="frame__body">
          <div class="question_area">
            <div class="question__box">
              <p>□안에 알맞은 수를 구하세요.</p>
              <img src="../../../assets/img/math/test.png" alt="" />
              <div class="question__box question__box--white">
                <span class="badge--lightgray"><i class="fa-regular fa-list-ul"></i>보기</span>
                <p>반직선은 선분보다 개 더 많습니다.</p>
                <img src="../../../assets/img/math/test.png" alt="" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="frame col--sm">
        <div class="frame__head title_area">
          <h3 class="title">6문제 남았어요</h3>
        </div>
        <div class="frame__body">
          <div class="toggle_area">
            <div class="answers answers--input">
              <div class="answer__active">
                <i class="answer__sign">(1-1)</i>
                <span class="">1</span>
              </div>
              <div class="keypad"
                ><div class="keypad__answer"> </div
                ><div class="keypad__btns"
                  ><button type="button">1</button><button type="button">2</button><button type="button">3</button
                  ><button type="button">4</button><button type="button">5</button><button type="button">6</button
                  ><button type="button">7</button><button type="button">8</button><button type="button">9</button
                  ><button type="button">0</button><button type="button">.</button
                  ><button type="button"
                    ><svg
                      class="svg-inline--fa fa-minus"
                      aria-hidden="true"
                      focusable="false"
                      data-prefix="far"
                      data-icon="minus"
                      role="img"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 448 512"
                    >
                      <path
                        class=""
                        fill="currentColor"
                        d="M432 256c0 13.3-10.7 24-24 24L40 280c-13.3 0-24-10.7-24-24s10.7-24 24-24l368 0c13.3 0 24 10.7 24 24z"
                      ></path></svg></button
                  ><button type="button"
                    ><svg
                      class="svg-inline--fa fa-plus"
                      aria-hidden="true"
                      focusable="false"
                      data-prefix="far"
                      data-icon="plus"
                      role="img"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 448 512"
                    >
                      <path
                        class=""
                        fill="currentColor"
                        d="M248 72c0-13.3-10.7-24-24-24s-24 10.7-24 24V232H40c-13.3 0-24 10.7-24 24s10.7 24 24 24H200V440c0 13.3 10.7 24 24 24s24-10.7 24-24V280H408c13.3 0 24-10.7 24-24s-10.7-24-24-24H248V72z"
                      ></path></svg></button
                  ><button type="button" class="keypad__sub"
                    ><svg
                      class="svg-inline--fa fa-rotate-left"
                      aria-hidden="true"
                      focusable="false"
                      data-prefix="far"
                      data-icon="rotate-left"
                      role="img"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 512 512"
                    >
                      <path
                        class=""
                        fill="currentColor"
                        d="M140.8 122.9C171.7 96.2 212 80 256 80c97.2 0 176 78.8 176 176s-78.8 176-176 176c-39.7 0-76.2-13.1-105.6-35.2c-10.6-8-25.6-5.8-33.6 4.8s-5.8 25.6 4.8 33.6C159 463.3 205.6 480 256 480c123.7 0 224-100.3 224-224S379.7 32 256 32c-57.3 0-109.6 21.5-149.2 56.9L76.3 58.3C69.7 51.7 60.7 48 51.3 48C31.8 48 16 63.8 16 83.3V200c0 13.3 10.7 24 24 24H156.7c19.5 0 35.3-15.8 35.3-35.3c0-9.4-3.7-18.3-10.3-25l-40.8-40.8zm-76.8-9L126.1 176H64V113.9z"
                      ></path></svg></button
                  ><button type="button" class="keypad__sub"
                    ><svg
                      class="svg-inline--fa fa-delete-left"
                      aria-hidden="true"
                      focusable="false"
                      data-prefix="far"
                      data-icon="delete-left"
                      role="img"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 576 512"
                    >
                      <path
                        class=""
                        fill="currentColor"
                        d="M205.3 64c-17 0-33.3 6.7-45.3 18.7L9.4 233.4c-6 6-9.4 14.1-9.4 22.6s3.4 16.6 9.4 22.6L160 429.3c12 12 28.3 18.7 45.3 18.7H512c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H205.3zM528 128V384c0 8.8-7.2 16-16 16H205.3c-4.2 0-8.3-1.7-11.3-4.7L54.6 256 193.9 116.7c3-3 7.1-4.7 11.3-4.7H512c8.8 0 16 7.2 16 16zm-95 47c-9.4-9.4-24.6-9.4-33.9 0l-47 47-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l47 47-47 47c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l47-47 47 47c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-47-47 47-47c9.4-9.4 9.4-24.6 0-33.9z"
                      ></path></svg></button
                  ><button class="keypad__check" type="button"
                    ><svg
                      class="svg-inline--fa fa-check"
                      aria-hidden="true"
                      focusable="false"
                      data-prefix="far"
                      data-icon="check"
                      role="img"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 448 512"
                    >
                      <path
                        class=""
                        fill="currentColor"
                        d="M441 103c9.4 9.4 9.4 24.6 0 33.9L177 401c-9.4 9.4-24.6 9.4-33.9 0L7 265c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l119 119L407 103c9.4-9.4 24.6-9.4 33.9 0z"
                      ></path></svg></button></div
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--    <AppMathButton />-->
  </AppLayout>
</template>

<script setup lang="ts">
  import AppLayout from '@/html/layouts/AppLayout.vue';
  import AppBlanks from '@/html/components/app/AppBlanks.vue';
</script>
