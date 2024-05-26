<template>
  <div class="answers--bubble">
    <div class="keypad">
      <div class="keypad__answer">
        <input type="text" name="answer" class="" placeholder="정답을 입력해주세요." />
      </div>
      <div class="keypad__btns">
        <button type="button">1</button>
        <button type="button">2</button>
        <button type="button">3</button>
        <button type="button">4</button>
        <button type="button">5</button>
        <button type="button">6</button>
        <button type="button">7</button>
        <button type="button">8</button>
        <button type="button">9</button>
        <button type="button">0</button>
        <button type="button">.</button>
        <button type="button">
          <font-awesome-icon icon="fa-regular fa-minus" />
        </button>
        <button type="button">
          <font-awesome-icon icon="fa-regular fa-plus" />
        </button>
        <button type="button" class="keypad__sub">
          <font-awesome-icon icon="fa-regular fa-rotate-left" />
        </button>
        <button type="button" class="keypad__sub">
          <font-awesome-icon icon="fa-regular fa-backspace" />
        </button>
        <button type="button" class="keypad__check">
          <font-awesome-icon icon="fa-regular fa-check" />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { PropType } from 'vue';

  const props = defineProps({
    toast: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  });
</script>
