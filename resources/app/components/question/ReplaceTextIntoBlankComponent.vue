<template>
  <component :is="wrapTag" v-if="wrapTag" v-bind="wrapAttr">
    <ReplaceTextIntoBlankComponent :value="contents" />
  </component>
  <template v-else>
    <template v-if="splitedData.length === 1">
      <OmrCol v-if="isBlankText" :blank-length="blankLength" :answer-row="answerRow" :answer-col="answerCol" />
      <template v-else>
        {{ splitedData[0] }}
      </template>
    </template>
    <template v-else>
      <ReplaceTextIntoBlankComponent v-for="(data, i) in splitedData" :key="i" :value="data" />
    </template>
  </template>
</template>

<script lang="ts" setup>
  import { ref, onMounted, PropType } from 'vue';
  import { isNil } from 'lodash';
  import OmrCol from '@/app/components/question/OmrCol.vue';

  const props = defineProps({
    value: {
      type: String as PropType<string>,
      default: '',
    },
  });

  const wrapTag = ref<string | null>();

  const wrapAttr = ref<{ [key: string]: string }>({});

  const contents = ref('');

  const splitedData = ref<string[]>([]);

  const answerRow = ref(0);

  const answerCol = ref<number | null>(null);

  const isBlankText = ref(false);

  const blankLength = ref(1);

  function extractParentTagName(htmlString: string): string | null {
    const doc = getParserHtml(htmlString);

    const parentElement = doc.firstChild as HTMLElement | null;

    if (doc.childElementCount === 1 && parentElement) {
      return parentElement.tagName?.toLowerCase();
    } else {
      return null;
    }
  }

  function initBlankType() {
    // 빈칸 형식 체크 후 로직 실행
    const blankPattern = /\${.*}/;
    if (!blankPattern.test(splitedData.value[0])) return;

    isBlankText.value = true;

    const rowMatched = splitedData.value[0].match(/\w+/g);

    answerRow.value = isNil(rowMatched) ? 0 : Number(rowMatched[0]) - 1;

    answerCol.value = isNil(rowMatched) ? 0 : Number(rowMatched[1]) - 1;

    blankLength.value = isNil(rowMatched) ? 1 : Number(rowMatched[2]);
  }

  function getParserHtml(htmlString: string) {
    const isTbody = /^<tbody\b[^>]*>[\s\S]*<\/tbody>$/i.test(htmlString);
    const isTr = /^<tr\b[^>]*>[\s\S]*<\/tr>$/i.test(htmlString);
    const isTd = /^<td\b[^>]*>[\s\S]*<\/td>$/i.test(htmlString);

    // 특정 태그 안에서만 동작하는 태그가 있어 부모 태그를 임시로 만들어 줌.
    // ex) tbody는 table 태그 안에서만 동작 함.
    let tempDiv = document.createElement('div');
    if (isTbody) {
      tempDiv = document.createElement('table');
    } else if (isTr) {
      tempDiv = document.createElement('tbody');
    } else if (isTd) {
      tempDiv = document.createElement('tr');
    }

    tempDiv.innerHTML = htmlString;

    return tempDiv;
  }

  onMounted(() => {
    wrapTag.value = extractParentTagName(props.value);

    const doc = getParserHtml(props.value);

    if (!isNil(wrapTag.value)) {
      const parentElement = doc.firstChild as HTMLElement;

      Array.from(parentElement.attributes).forEach((attr) => {
        wrapAttr.value[attr.name] = attr.value;
      });

      contents.value = parentElement.innerHTML;
    } else {
      doc.childNodes.forEach((node: any) => {
        if (isNil(node.data)) {
          splitedData.value.push(node.outerHTML);
        } else {
          splitedData.value = [...splitedData.value, ...node.data.split(/(\$\{.*?\})/)].filter((str) => str !== '');
        }
      });

      if (splitedData.value.length === 1) {
        initBlankType();
      }
    }
  });
</script>
