<template>
  <AppTraining :theme-show="1">
    <div class="grid">
      <div class="frame col">
        <div class="training__head">
          <div class="training__subject">
            <button class="btn__layer" @click="toggleMenu"><i class="fa-regular fa-indent"></i></button>
            <div class="training__unit">
              <h3>1. 곱셈과 나눗셈</h3>
              <span class="">십의 자리에서 올림이 있는 (몇십몇)x(몇), 일의 자리에서 올림이 있는 (몇십몇)x(몇)</span>
            </div>
          </div>
          <a class="btn__training__info">
            <span
              >유형 훈련
              <font-awesome-icon icon="fa-regular fa-circle-info"></font-awesome-icon>
            </span>
            <ol class="training__progress">
              <li class="done"></li>
              <li class="done current"></li>
              <li class=""></li>
            </ol>
          </a>

          <div class="modal__training__info">
            <div class="btns btns--right">
              <button><i class="fa-regular fa-close"></i></button>
            </div>
            <p
              >개념 훈련은 소단원의 개념을 이해하기 위한 첫 번째 훈련입니다. <br />
              지문을 끊어 읽으며 개념을 익힌 뒤 핵심 개념을 정리하고, 이해한 내용을 개념 다지기 문제로 확인하고, 다시
              한번 지문과 핵심 개념을 확인해 봅니다. 마지막으로 학습한 개념을 바탕으로 기초 연산 문제를 풀이하면 개념
              훈련이 종료됩니다.</p
            >
          </div>
        </div>

        <div class="training__body">
          <ul class="training__round">
            <li>
              <span
                >ROUND 1 <br />
                <div class="timer">
                  <span class="time time-pause">36:30</span>
                </div>
              </span>
              <div class="training__round__btns">
                <div>
                  <small>1차 유형 문제</small>
                  <button class="btn__done">학습하기</button>
                </div>
                <div>
                  <small>1차 유형 문제</small>
                  <button class=""><i class="rank rank--s rank--sm"></i></button>
                </div>
                <div>
                  <small>1차 유형 문제</small>
                  <button class=""><i class="rank rank--a rank--sm"></i></button>
                </div>
                <div>
                  <small>1차 유형 문제</small>
                  <button class=""><i class="rank rank--b rank--sm"></i></button>
                </div>
              </div>
              <i class="training_star training_star--s"></i>
            </li>
            <li>
              <span
                >ROUND 2
                <div class="timer">
                  <span class="time time-pause">36:30</span>
                </div>
              </span>

              <div class="training__round__btns">
                <div>
                  <small>1차 유형 문제</small>
                  <button class="btn__done">학습하기</button>
                </div>
                <div>
                  <small>1차 유형 문제</small>
                  <button class="btn__done">학습하기</button>
                </div>
                <div>
                  <small>1차 유형 문제</small>
                  <button class="btn__done">학습하기</button>
                </div>
              </div>
              <i class="training_star training_star--b"></i>
            </li>
            <li>
              <span
                >ROUND 3
                <div class="timer">
                  <span class="time timeover time-pause">36:30</span>
                </div>
              </span>
              <div class="training__round__btns">
                <div>
                  <small>1차 유형 문제</small>
                  <button class="btn__done">학습하기</button>
                </div>
                <div>
                  <small>1차 유형 문제</small>
                  <button class="btn__done">학습하기</button>
                </div>
              </div>
              <i class="training_star training_star--c"></i>
            </li>
          </ul>
        </div>
      </div>
      <div class="frame col col--sm">
        <div class="training__object">
          <a href="javascript:;" @click="router.get('/html/app-Main')">
            <i class="alien"></i>
            <span>
              <font-awesome-icon icon="fa-regular fa-planet-ringed"></font-awesome-icon>
              행성 메인으로
            </span>
          </a>
        </div>
      </div>
    </div>

    <div class="layer ly__main" :class="{ show: openToggleMenu }">
      <div class="inner">
        <div class="btns btns--right">
          <button><i class="fa-regular fa-close"></i></button>
        </div>
        <div class="progress">
          <span>80% 진행했어요</span>
          <progress value="50" min="0" max="100"></progress>
        </div>
        <div class="lst__curriculum">
          <h3>
            <span class="badge--lightgray">
              <font-awesome-icon icon="fa-regular fa-school"></font-awesome-icon> n학년 n학기
            </span>
            <strong>n단원</strong> 대단원 타이틀
          </h3>
          <ul class="lst__curriculum__sub">
            <li class="done">
              <div class="row">
                <span>소단원소단원소단원소단원소단원소단원소단원소단원소단원 타이틀</span>
                <div class="stars">
                  <i class="star star--sm"></i>
                  <i class="star star--a star--sm review--1"></i>
                  <i class="star star--b star--sm review--2"></i>
                </div>
              </div>
              <ul class="lst__curriculum__sub__detail">
                <li>
                  <i class="star star--a star--md"></i>
                  <strong>개념 훈련</strong>
                  <span>ROUND1</span>
                </li>
                <li>
                  <i class="star star--s star--md"></i>
                  <strong>유형 훈련</strong>
                  <span>ROUND1</span>
                </li>
                <li>
                  <i class="star star--c star--md"></i>
                  <strong>서술형 훈련</strong>
                  <span>ROUND1</span>
                </li>
              </ul>
            </li>
            <li class="current">
              <div class="row">
                <span>소단원소단원소단원소단원소단원소단원소단원소단원소단원 타이틀</span>
                <div class="stars">
                  <i class="star star--sm"></i>
                  <i class="star star--a star--sm review--1"></i>
                  <i class="star star--b star--sm review--2"></i>
                </div>
              </div>
              <ul class="lst__curriculum__sub__detail">
                <li>
                  <i class="star star--a star--md"></i>
                  <strong>개념 훈련</strong>
                  <span>ROUND1</span>
                </li>
                <li>
                  <i class="star star--s star--md"></i>
                  <strong>유형 훈련</strong>
                  <span>ROUND1</span>
                </li>
                <li>
                  <i class="star star--c star--md"></i>
                  <strong>서술형 훈련</strong>
                  <span>ROUND1</span>
                </li>
              </ul>
            </li>
            <li>
              <div class="row">
                <span>소단원소단원소단원소단원소단원소단원소단원소단원소단원 타이틀</span>
                <div class="stars">
                  <i class="star star--sm"></i>
                  <i class="star star--a star--sm review--1"></i>
                  <i class="star star--b star--sm review--2"></i>
                </div>
              </div>
              <ul class="lst__curriculum__sub__detail">
                <li>
                  <i class="star star--a star--md"></i>
                  <strong>개념 훈련</strong>
                  <span>ROUND1</span>
                </li>
                <li>
                  <i class="star star--s star--md"></i>
                  <strong>유형 훈련</strong>
                  <span>ROUND1</span>
                </li>
                <li>
                  <i class="star star--c star--md"></i>
                  <strong>서술형 훈련</strong>
                  <span>ROUND1</span>
                </li>
              </ul>
            </li>
            <li class="current">
              <div class="row">
                <span>소단원소단원소단원소단원소단원소단원소단원소단원소단원 타이틀</span>
                <div class="stars">
                  <i class="star star--sm"></i>
                  <i class="star star--a star--sm review--1"></i>
                  <i class="star star--b star--sm review--2"></i>
                </div>
              </div>
              <ul class="lst__curriculum__sub__detail">
                <li>
                  <i class="star star--a star--md"></i>
                  <strong>개념 훈련</strong>
                  <span>ROUND1</span>
                </li>
                <li>
                  <i class="star star--s star--md"></i>
                  <strong>유형 훈련</strong>
                  <span>ROUND1</span>
                </li>
                <li>
                  <i class="star star--c star--md"></i>
                  <strong>서술형 훈련</strong>
                  <span>ROUND1</span>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </AppTraining>
</template>
<script setup lang="ts">
  import AppTraining from '@/html/components/app/AppTraining.vue';
  import { router } from '@inertiajs/vue3';
  import { ref } from 'vue';

  const openToggleMenu = ref(false);

  const toggleMenu = () => {
    openToggleMenu.value = !openToggleMenu.value;
  };
</script>
