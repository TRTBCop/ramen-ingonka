<template>
  <AppTraining>
    <div class="training__head">
      <div class="training__subject">
        <button class="btn__layer"><i class="fa-regular fa-indent"></i></button>
        <div class="training__unit">
          <div class="row">
            <h3>n단원 대단원 타이틀</h3>
            <ol class="training__progress">
              <li class="done"></li>
              <li class=""></li>
              <li class=""></li>
              <li class=""></li>
            </ol>
          </div>
          <h4 class="">n-n. 소단원 타이틀</h4>
        </div>
      </div>

      <div class="training__btns">
        <a href="javascript:;">
          <span class="star--b">
            <i class="star"></i>
          </span>
          <p>개념훈련</p>
        </a>
        <a href="javascript:;" class="current">
          <span class="star--s">
            <i class="star"></i>
          </span>
          <p>유형훈련</p>
        </a>
        <a href="javascript:;">
          <span class="star--c">
            <i class="star"></i>
          </span>
          <p>서술형훈련 </p>
        </a>
      </div>
    </div>

    <div class="training__body">
      <ul class="training__bots">
        <li class="bot bot--ready bot--textbook">
          <div class="bot__head"></div>
          <div class="bot__body">
            <span>개념 훈련</span>
            <strong></strong>
          </div>
          <div class="bot__count">
            <div class="dots">
              <i class="done"></i>
              <i></i>
              <i></i>
            </div>
          </div>
        </li>
        <li class="bot bot--s bot--textbook">
          <div class="bot__head"></div>
          <div class="bot__body">
            <span>개념 훈련</span>
            <strong></strong>
          </div>
          <div class="bot__count">
            <div class="dots">
              <i></i>
              <i></i>
              <i></i>
            </div>
          </div>
        </li>
        <li class="bot bot--a bot--question">
          <div class="bot__head"></div>
          <div class="bot__body">
            <span>유형 훈련</span>
            <strong></strong>
          </div>
          <div class="bot__count">
            <div class="dots">
              <i class="done"></i>
              <i class="done"></i>
              <i></i>
            </div>
          </div>
        </li>
        <li class="bot bot--b bot--descriptive">
          <div class="bot__head"></div>
          <div class="bot__body">
            <span>서술형 이름</span>
            <strong></strong>
          </div>
          <div class="bot__count">
            <div class="dots">
              <i class="done"></i>
              <i></i>
              <i></i>
            </div>
          </div>
        </li>
        <li class="bot bot--c bot--descriptive">
          <div class="bot__head"></div>
          <div class="bot__body">
            <span>서술형 이름</span>
            <strong></strong>
          </div>
          <div class="bot__count">
            <div class="dots">
              <i class="done"></i>
              <i class="done"></i>
              <i></i>
            </div>
          </div>
        </li>
      </ul>

      <div class="training__rank">
        <div class="stars stars--lg">
          <span class="star--s">
            <i class="star"></i>
          </span>
        </div>
      </div>

      <div class="slide">
        <button class="prev"><i class="fa-regular fa-chevron-left"></i></button>
        <button class="next"><i class="fa-regular fa-chevron-right"></i></button>
      </div>
    </div>
  </AppTraining>
</template>
<script setup lang="ts">
  import AppTraining from '@/html/components/app/AppTraining.vue';
</script>
