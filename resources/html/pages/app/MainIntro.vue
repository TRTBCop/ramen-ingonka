<template>
  <div id="wrap">
    <div class="main__intro">
      <!--      <strong>-->
      <!--        초등 3학년 1학기 행성으로 <br />-->
      <!--        이동중이에요 …-->
      <!--      </strong>-->
      <div class="modal modal--md">
        <div class="modal_head">
          <button class="btn--close">
            <font-awesome-icon icon="fa-regular fa-xmark" />
          </button>
        </div>
        <div class="modal_body modal_body__top">
          <strong class="is--size4">학습할 학기를 선택해 주세요.</strong>
          <span>(선택한 학기는 마이페이지의 내 정보에서 변경이 가능해요.)</span>
          <label class="switch grade__switch">
            <input type="checkbox" checked />
            <span class="slider"><small class="switch__text"></small></span>
          </label>
          <ul class="modal__semester">
            <li>
              <input id="semester1" type="radio" name="lst_semester" value="1" />
              <label for="semester1">3학년 1학기</label>
            </li>
            <li>
              <input id="semester2" type="radio" name="lst_semester" value="2" />
              <label for="semester2">3학년 2학기</label>
            </li>
            <li>
              <input id="semester3" type="radio" name="lst_semester" value="3" />
              <label for="semester3">4학년 1학기</label>
            </li>
            <li>
              <input id="semester4" type="radio" name="lst_semester" value="4" />
              <label for="semester4">4학년 2학기</label>
            </li>
            <li>
              <input id="semester5" type="radio" name="lst_semester" value="5" />
              <label for="semester5">5학년 1학기</label>
            </li>
            <li>
              <input id="semester6" type="radio" name="lst_semester" value="6" />
              <label for="semester6">5학년 2학기</label>
            </li>
            <li>
              <input id="semester7" type="radio" name="lst_semester" value="7" />
              <label for="semester7">6학년 1학기</label>
            </li>
            <li>
              <input id="semester8" type="radio" name="lst_semester" value="8" />
              <label for="semester8">6학년 2학기</label>
            </li>
          </ul>
        </div>
        <div class="btns">
          <button class="btn--brand">시작하기</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts"></script>
