<template>
  <div id="wrap">
    <div class="error_wrap">
      <div class="error__text">
        <h3>404/500/점검중</h3>
        <h4>죄송합니다.요청하신 페이지를 찾을수 없습니다.</h4>
        <p>
          방문하시려는 페이지의 주소가 잘못 입력되었거나, <br />
          페이지의 주소가 변경 혹은 삭제되어 요청하신 페이지를 찾을 수 없습니다. <br />
          입력하신 주소가 정확한지 다시 한번 확인해 주세요.
        </p>
        <div class="btns">
          <button class="btn--sub">
            <font-awesome-icon icon="fa-regular fa-arrow-left"></font-awesome-icon>이전 화면
          </button>
          <button class="btn--brand">
            <font-awesome-icon icon="fa-regular fa-home"></font-awesome-icon>홈으로 이동
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts"></script>
