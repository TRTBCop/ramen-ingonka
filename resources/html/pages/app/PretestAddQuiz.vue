<template>
  <AppPretest>
    <div class="full_wrap row">
      <!-- [D] 문제 영역 -->
      <div class="question_area">
        <div class="question">
          <span
            >이차함수
            <img
              src="data:image/svg+xml;charset=utf8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20xmlns%3Awrs%3D%22http%3A%2F%2Fwww.wiris.com%2Fxml%2Fmathml-extension%22%20height%3D%2223%22%20width%3D%22109%22%20wrs%3Abaseline%3D%2218%22%3E%3C!--MathML%3A%20%3Cmath%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F1998%2FMath%2FMathML%22%3E%3Cmi%3Ey%3C%2Fmi%3E%3Cmo%3E%3D%3C%2Fmo%3E%3Cmi%3Ea%3C%2Fmi%3E%3Cmsup%3E%3Cmi%3Ex%3C%2Fmi%3E%3Cmn%3E2%3C%2Fmn%3E%3C%2Fmsup%3E%3Cmo%3E+%3C%2Fmo%3E%3Cmi%3Eb%3C%2Fmi%3E%3Cmi%3Ex%3C%2Fmi%3E%3Cmo%3E+%3C%2Fmo%3E%3Cmi%3Ec%3C%2Fmi%3E%3C%2Fmath%3E--%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E@font-face{font-family%3Aae2ef524fbf3d9fe611d5a8e90fefdc;src%3Aurl(data%3Afont%2Ftruetype;charset%3Dutf-8;base64,AAEAAAAMAIAAAwBAT1MvMjv%2FLJYAAADMAAAATmNtYXDgWxEdAAABHAAAADRjdnQgAAAABwAAAVAAAAAEZ2x5ZoYrxVAAAAFUAAAA0WhlYWQOdyayAAACKAAAADZoaGVhC0UVwQAAAmAAAAAkaG10eCg8AIUAAAKEAAAACGxvY2EAAAVKAAACjAAAAAxtYXhwBIoEWwAAApgAAAAgbmFtZXSF9ZsAAAK4AAABrXBvc3QDogHPAAAEaAAAACBwcmVwukanGAAABIgAAAANAAAGtAGQAAUAAAgACAAAAAAACAAIAAAAAAAAAQIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACAgICAAAAAg8AMGe%2F57AAAHPgGyAAAAAAACAAEAAQAAABQAAwABAAAAFAAEACAAAAAEAAQAAQAAAGH%2F%2FwAAAGH%2F%2F%2F+gAAEAAAAAAAAABwACAFUAAAMAA6sAAwAHAAAzESERJSERIVUCq%2F2rAgD+AAOr%2FFVVAwAAAwAt%2F3QEAwRZAAsAFwAdADsYAbAdELAD1LADELAU1LAUELAc1LAcELAJ1LAcELAOPLAJELAbPACwBhCwEdSwBhCwANSwABCwF9QwMQEiABEWEjMyEjcQJgYWAwIGIyImNTQ2MwE1BhMjEgIBs%2F7fFvWy07oDhYZwFgxOhVmysoUB7YwEslEEWf7f%2Ft71%2Ft8BM+MBp5yyLf6d%2FwBlyJzfsvxZjF0B5%2F1eAAAAAAEAAAABAACav9usXw889QADCAD%2F%2F%2F%2F%2F1a3uPf%2F%2F%2F%2F%2FVre49AAH+9QQDBkMAAAAKAAIAAQAAAAAAAQAABz7+TgAAF3AAAf%2F8BAMAAQAAAAAAAAAAAAAAAAAAAAIDUgBVBEwALQAAAAAAAAAoAAAA0QABAAAAAgAeAAMAAAAAAAIAgAQAAAAAAAQAADsAAAAAAAAAFQECAAAAAAAAAAEAFgAAAAAAAAAAAAIADgAWAAAAAAAAAAMANAAkAAAAAAAAAAQAFgBYAAAAAAAAAAUAFgBuAAAAAAAAAAYACwCEAAAAAAAAAAgAHACPAAEAAAAAAAEAFgAAAAEAAAAAAAIADgAWAAEAAAAAAAMANAAkAAEAAAAAAAQAFgBYAAEAAAAAAAUAFgBuAAEAAAAAAAYACwCEAAEAAAAAAAgAHACPAAMAAQQJAAEAFgAAAAMAAQQJAAIADgAWAAMAAQQJAAMANAAkAAMAAQQJAAQAFgBYAAMAAQQJAAUAFgBuAAMAAQQJAAYACwCEAAMAAQQJAAgAHACPAE0AYQB0AGgAIABGAG8AbgB0ACAAMgBSAGUAZwB1AGwAYQByAE0AYQB0AGgAcwAgAEYAbwByACAATQBvAHIAZQAgAE0AYQB0AGgAIABGAG8AbgB0ACAAMgBNAGEAdABoACAARgBvAG4AdAAgADIAVgBlAHIAcwBpAG8AbgAgADEALgAwTWF0aF9Gb250XzIATQBhAHQAaABzACAARgBvAHIAIABNAG8AcgBlAAAAAAMAAAAAAAADnwHPAAAAAAAAAAAAAAAAAAAAAAAAAAC5ByIAAI2FGACyAAAAAAAA)format(truetype);font-weight%3Anormal;font-style%3Anormal;}@font-face{font-family%3Amath1564b4c0e54101ac57a0cb68c16;src%3Aurl(data%3Afont%2Ftruetype;charset%3Dutf-8;base64,AAEAAAAMAIAAAwBAT1MvMi7iBBMAAADMAAAATmNtYXDEvmKUAAABHAAAADxjdnQgDVUNBwAAAVgAAAA6Z2x5ZoPi2VsAAAGUAAABK2hlYWQQC2qxAAACwAAAADZoaGVhCGsXSAAAAvgAAAAkaG10eE2rRkcAAAMcAAAADGxvY2EAHTwYAAADKAAAABBtYXhwBT0FPgAAAzgAAAAgbmFtZaBxlY4AAANYAAABn3Bvc3QB9wD6AAAE+AAAACBwcmVwa1uragAABRgAAAAUAAADSwGQAAUAAAQABAAAAAAABAAEAAAAAAAAAQEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACAgICAAAAAg1UADev96AAAD6ACWAAAAAAACAAEAAQAAABQAAwABAAAAFAAEACgAAAAGAAQAAQACACsAPf%2F%2FAAAAKwA9%2F%2F%2F%2F1v%2FFAAEAAAAAAAAAAAFUAywAgAEAAFYAKgJYAh4BDgEsAiwAWgGAAoAAoADUAIAAAAAAAAAAKwBVAIAAqwDVAQABKwAHAAAAAgBVAAADAAOrAAMABwAAMxEhESUhESFVAqv9qwIA%2FgADq%2FxVVQMAAAEAgABVAtUCqwALAEkBGLIMAQEUExCxAAP2sQEE9bAKPLEDBfWwCDyxBQT1sAY8sQ0D5gCxAAATELEBBuSxAQETELAFPLEDBOWxCwX1sAc8sQkE5TEwEyERMxEhFSERIxEhgAEAVQEA%2FwBV%2FwABqwEA%2FwBW%2FwABAAACAIAA6wLVAhUAAwAHAGUYAbAIELAG1LAGELAF1LAIELAB1LABELAA1LAGELAHPLAFELAEPLABELACPLAAELADPACwCBCwBtSwBhCwB9SwBxCwAdSwARCwAtSwBhCwBTywBxCwBDywARCwADywAhCwAzwxMBMhNSEdASE1gAJV%2FasCVQHAVdVVVQAAAQAAAAEAANV4zkFfDzz1AAMEAP%2F%2F%2F%2F%2FWOhNz%2F%2F%2F%2F%2F9Y6E3MAAP8gBIADqwAAAAoAAgABAAAAAAABAAAD6P9qAAAXcAAA%2F7YEgAABAAAAAAAAAAAAAAAAAAAAAwNSAFUDVgCAA1YAgAAAAAAAAAAoAAAAoQAAASsAAQAAAAMAXgAFAAAAAAACAIAEAAAAAAAEAADeAAAAAAAAABUBAgAAAAAAAAABABIAAAAAAAAAAAACAA4AEgAAAAAAAAADADAAIAAAAAAAAAAEABIAUAAAAAAAAAAFABYAYgAAAAAAAAAGAAkAeAAAAAAAAAAIABwAgQABAAAAAAABABIAAAABAAAAAAACAA4AEgABAAAAAAADADAAIAABAAAAAAAEABIAUAABAAAAAAAFABYAYgABAAAAAAAGAAkAeAABAAAAAAAIABwAgQADAAEECQABABIAAAADAAEECQACAA4AEgADAAEECQADADAAIAADAAEECQAEABIAUAADAAEECQAFABYAYgADAAEECQAGAAkAeAADAAEECQAIABwAgQBNAGEAdABoACAARgBvAG4AdABSAGUAZwB1AGwAYQByAE0AYQB0AGgAcwAgAEYAbwByACAATQBvAHIAZQAgAE0AYQB0AGgAIABGAG8AbgB0AE0AYQB0AGgAIABGAG8AbgB0AFYAZQByAHMAaQBvAG4AIAAxAC4AME1hdGhfRm9udABNAGEAdABoAHMAIABGAG8AcgAgAE0AbwByAGUAAAMAAAAAAAAB9AD6AAAAAAAAAAAAAAAAAAAAAAAAAAC5BxEAAI2FGACyAAAAFRQTsQABPw%3D%3D)format(truetype);font-weight%3Anormal;font-style%3Anormal;}%3C%2Fstyle%3E%3C%2Fdefs%3E%3Ctext%20font-family%3D%22Arial%22%20font-size%3D%2216%22%20font-style%3D%22italic%22%20text-anchor%3D%22middle%22%20x%3D%224.5%22%20y%3D%2218%22%3Ey%3C%2Ftext%3E%3Ctext%20font-family%3D%22math1564b4c0e54101ac57a0cb68c16%22%20font-size%3D%2216%22%20text-anchor%3D%22middle%22%20x%3D%2217.5%22%20y%3D%2218%22%3E%3D%3C%2Ftext%3E%3Ctext%20font-family%3D%22ae2ef524fbf3d9fe611d5a8e90fefdc%22%20font-size%3D%2216%22%20font-style%3D%22italic%22%20text-anchor%3D%22middle%22%20x%3D%2230.5%22%20y%3D%2218%22%3Ea%3C%2Ftext%3E%3Ctext%20font-family%3D%22Arial%22%20font-size%3D%2216%22%20font-style%3D%22italic%22%20text-anchor%3D%22middle%22%20x%3D%2239.5%22%20y%3D%2218%22%3Ex%3C%2Ftext%3E%3Ctext%20font-family%3D%22Arial%22%20font-size%3D%2212%22%20text-anchor%3D%22middle%22%20x%3D%2247.5%22%20y%3D%2211%22%3E2%3C%2Ftext%3E%3Ctext%20font-family%3D%22math1564b4c0e54101ac57a0cb68c16%22%20font-size%3D%2216%22%20text-anchor%3D%22middle%22%20x%3D%2258.5%22%20y%3D%2218%22%3E+%3C%2Ftext%3E%3Ctext%20font-family%3D%22Arial%22%20font-size%3D%2216%22%20font-style%3D%22italic%22%20text-anchor%3D%22middle%22%20x%3D%2270.5%22%20y%3D%2218%22%3Eb%3C%2Ftext%3E%3Ctext%20font-family%3D%22Arial%22%20font-size%3D%2216%22%20font-style%3D%22italic%22%20text-anchor%3D%22middle%22%20x%3D%2279.5%22%20y%3D%2218%22%3Ex%3C%2Ftext%3E%3Ctext%20font-family%3D%22math1564b4c0e54101ac57a0cb68c16%22%20font-size%3D%2216%22%20text-anchor%3D%22middle%22%20x%3D%2291.5%22%20y%3D%2218%22%3E+%3C%2Ftext%3E%3Ctext%20font-family%3D%22Arial%22%20font-size%3D%2216%22%20font-style%3D%22italic%22%20text-anchor%3D%22middle%22%20x%3D%22103.5%22%20y%3D%2218%22%3Ec%3C%2Ftext%3E%3C%2Fsvg%3E"
              alt="수식 이미지"
              style="height: 23px; width: 109px"
            />의 그래프가 다음 그림과 같을 때, 이 이차함수의 식을 구하세요.</span
          >

          <!-- [D] 문제 내 보기 영역 -->
          <div class="example_area">
            <div class="example">
              <img
                src="https://sloop-question.s3.ap-northeast-2.amazonaws.com/uploads/dev/question/1616476437783-1616476437783.png"
                style="
                  width: 195px;
                  display: block;
                  vertical-align: top;
                  margin: 5px auto;
                  text-align: center;
                  cursor: pointer;
                  padding: 0px 1px;
                  position: relative;
                  max-width: 100%;
                "
                fr-original-style="width: 195px; display: block; vertical-align: top; margin: 5px auto; text-align: center;"
                fr-original-class="fr-draggable"
              />
            </div>
          </div>
        </div>
      </div>
      <!-- [D] 답 입력 영역 -->
      <ul class="pretest__answers col">
        <li>
          <div class="pretest__answer">
            <div class="answer answer__add">
              <span class="blanks__type1">
                <em class="blank__on">분수</em>
              </span>
            </div>
          </div>

          <div class="answers col show">
            <button><span>분수</span></button>
            <button><span>기약분수</span></button>
          </div>

          <div class="keypad">
            <div class="keypad__btns">
              <button>1</button>
              <button>2</button>
              <button>3</button>
              <button>4</button>
              <button>5</button>
              <button>6</button>
              <button>7</button>
              <button>8</button>
              <button>9</button>
              <button>0</button>
              <button>.</button>
              <button>
                <font-awesome-icon icon="fa-regular fa-minus" />
              </button>
              <button>
                <font-awesome-icon icon="fa-regular fa-plus" />
              </button>
              <button class="keypad__sub">
                <font-awesome-icon icon="fa-regular fa-rotate-left" />
              </button>
              <button class="keypad__sub">
                <font-awesome-icon icon="fa-regular fa-backspace" />
              </button>
              <button class="keypad__check">
                <font-awesome-icon icon="fa-regular fa-check" />
              </button>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </AppPretest>
</template>

<script setup lang="ts">
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
  import AppPretest from '@/html/layouts/AppPretest.vue';
</script>
