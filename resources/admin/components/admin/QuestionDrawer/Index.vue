<template>
  <el-dialog
    v-model="isShow"
    title="문제 상세"
    top="5vh"
    width="90%"
    :close-on-click-modal="false"
    :close-on-press-escape="false"
  >
    <template v-if="!isLoading">
      <el-form ref="formRef" :model="formData" :rules="formRules">
        <template v-if="options.isDefault">
          <div class="card">
            <div class="card-body p-9 py-0">
              <div class="mb-5">
                <h4 class="m-0">기본 정보</h4>
              </div>

              <el-form-item label="난이도">
                <el-radio-group v-model="formData.level">
                  <el-radio v-for="(item, key) in levelType" :key="key" :label="Number(key)">
                    {{ item }}
                  </el-radio>
                </el-radio-group>
              </el-form-item>

              <el-form-item label="소단원">
                <el-select v-model="formData.curriculum_id" filterable>
                  <el-option
                    v-for="(item, key) in curriculumIdToName"
                    :key="key"
                    :label="item.name"
                    :value="Number(key)"
                  />
                </el-select>
              </el-form-item>

              <el-form-item class="mb-0" label="내용영역">
                {{ curriculumIdToName[formData.curriculum_id || 0]?.txt_element || '속성없음' }}
              </el-form-item>
            </div>
          </div>
          <el-divider></el-divider>
        </template>

        <div class="d-flex gap-5">
          <div v-if="options.isQuestion" class="card w-100 h-800px scroll">
            <div class="card-body p-9 pt-0">
              <!-- ######################## 문제 풀이 ######################## -->
              <div class="d-flex justify-content-between align-items-center mb-5">
                <h4 class="m-0">문제 풀이</h4>
                <el-button size="small" @click="createContentsAnswers">풀이 답안 생성</el-button>
              </div>
              <div class="w-100">
                <el-alert class="mb-5" type="info" show-icon :closable="false">
                  <div class="d-flex gap-5 flex-wrap">
                    <div v-for="(command, i) in textMatchCommand" :key="i"
                      ><span class="badge badge-light-primary">{{ command.value }}</span
                      ><b>{{ command.label }}</b></div
                    >
                  </div>
                </el-alert>
                <froala
                  ref="textFroalaEditor"
                  v-model:value="formData.question"
                  tag="textarea"
                  :config="textFroalaConfig"
                />
              </div>
              <!-- ######################## // 문제 풀이 ######################## -->
            </div>
          </div>
          <div class="w-100 gap-5 h-800px scroll">
            <div class="card p-9 pt-0">
              <!-- ######################## 발문 ######################## -->
              <template v-if="options.isInquiry">
                <div class="mb-5">
                  <h4 class="m-0">발문</h4>
                </div>
                <div class="w-100">
                  <froala
                    v-model:value="formData.inquiry"
                    tag="textarea"
                    :config="froalaEditorMathConfig(uploadImagePath)"
                  />
                </div>
              </template>
              <!-- ######################## //발문 ###################################### -->

              <!-- ######################## 보기 ######################## -->
              <template v-if="options.isOptions">
                <div class="mt-5 mb-5">
                  <h4 class="m-0">보기</h4>
                </div>
                <div class="w-100">
                  <froala
                    v-model:value="formData.options"
                    tag="textarea"
                    :config="froalaEditorMathConfig(uploadImagePath)"
                  />
                </div>
              </template>
              <!-- ######################## //보기 ###################################### -->

              <!-- ######################## 답안 ######################## -->
              <el-divider></el-divider>
              <div class="mb-5">
                <h4 class="m-0">답안</h4>
              </div>
              <div v-if="!formData.answers || formData.answers.length === 0">답안을 추가해 주세요.</div>
              <draggable
                v-model="formData.answers"
                item-key="id"
                handle=".handle"
                class="d-flex flex-column gap-5"
                v-bind="dragOptions"
              >
                <template #item="{ element, index }">
                  <div class="rounded" style="border: 1px solid #dbdbdb">
                    <!-- @@@@@@@@@@@@ 답안 헤더 @@@@@@@@@@@@ -->
                    <div
                      class="d-flex flex-wrap justify-content-between align-items-center py-3 px-5"
                      style="background-color: #f4f4f4; border-bottom: 1px solid #dbdbdb"
                    >
                      <div class="d-flex align-items-center gap-5">
                        <div v-if="options.isMultipleAnswer">{{ `${index + 1}. ` }}</div>
                        <div class="d-flex gap-4">
                          <el-select
                            v-model="element.type"
                            placeholder="타입"
                            class="w-150px"
                            @change="onChangeAnswerType(element)"
                          >
                            <el-option
                              v-for="(value, key) in questionType"
                              :key="key"
                              :value="Number(key)"
                              :label="value"
                            />
                          </el-select>

                          <el-select
                            v-if="options.isAction"
                            v-model="element.action"
                            placeholder="행동영역"
                            class="w-100px"
                          >
                            <el-option
                              v-for="(value, key) in actionType"
                              :key="key"
                              :value="Number(key)"
                              :label="value"
                            />
                          </el-select>
                        </div>
                      </div>
                      <div class="d-flex align-items-center gap-3">
                        <el-checkbox
                          v-if="element.type === QuestionAnswerEnum.Input"
                          v-model="element.choice_symbol"
                          placeholder="타입"
                          label="㉠㉡㉢"
                        />
                        <div
                          v-if="
                            element.type === QuestionAnswerEnum.Input ||
                            element.type === QuestionAnswerEnum.OrderMatching
                          "
                          class="mx-3"
                        >
                          <el-select
                            v-model="selectBlankType"
                            placeholder="빈칸 추가"
                            class="w-150px"
                            :disabled="element.type === QuestionAnswerEnum.OrderMatching"
                          >
                            <el-option v-for="(item, i) in blankTypeOptions" :key="i" :value="i" :label="item" />
                          </el-select>
                          <el-button @click="addBlankItem(index, selectBlankType)">추가</el-button>
                        </div>
                        <template v-if="options.isMultipleAnswer">
                          <el-button
                            :icon="Delete"
                            type="danger"
                            size="small"
                            circle
                            @click="removeContentsAnswers(index)"
                          />
                          <KTIcon class="handle fs-3 my-2" style="cursor: move" icon-name="burger-menu-5" />
                        </template>
                      </div>
                    </div>
                    <!-- @@@@@@@@@@@@ // 답안 헤더 @@@@@@@@@@@@ -->

                    <!-- @@@@@@@@@@@@ 답안 바디 @@@@@@@@@@@@ -->
                    <div>
                      <!-- ********** 입력형 ********** -->
                      <div
                        v-if="element.type === QuestionAnswerEnum.Input"
                        class="d-flex flex-wrap align-items-center p-10 gap-15"
                      >
                        <span v-if="element.answer.length === 0"> 빈칸을 추가해 주세요. </span>
                        <div v-for="(answerItem, i) in element.answer" :key="i" class="blank-item">
                          <el-button
                            class="delete-button"
                            :icon="Close"
                            type="danger"
                            size="small"
                            circle
                            @click="removeBlankItem(index, i)"
                          />
                          <div v-if="element.choice_symbol">{{ String.fromCharCode(12896 + i) }}</div>
                          <input
                            v-if="!Array.isArray(answerItem)"
                            v-model="element.answer[i]"
                            :placeholder="`${index + 1}-${i + 1}`"
                            class="blank-input"
                            type="text"
                          />
                          <div v-if="Array.isArray(answerItem) && answerItem.length === 2">
                            <input
                              v-model="element.answer[i][0]"
                              :placeholder="`${index + 1}-${i + 1}`"
                              class="blank-input"
                              type="text"
                            />
                            <el-divider class="my-2"></el-divider>
                            <input
                              v-model="element.answer[i][1]"
                              :placeholder="`${index + 1}-${i + 1}`"
                              class="blank-input"
                              type="text"
                            />
                          </div>
                          <template v-else-if="Array.isArray(answerItem) && answerItem.length === 3">
                            <div>
                              <input
                                v-model="element.answer[i][0]"
                                :placeholder="`${index + 1}-${i + 1}`"
                                class="blank-input"
                                type="text"
                              />
                            </div>
                            <div>
                              <input
                                v-model="element.answer[i][1]"
                                :placeholder="`${index + 1}-${i + 1}`"
                                class="blank-input"
                                type="text"
                              />
                              <el-divider class="my-2"></el-divider>
                              <input
                                v-model="element.answer[i][2]"
                                :placeholder="`${index + 1}-${i + 1}`"
                                class="blank-input"
                                type="text"
                              />
                            </div>
                          </template>
                        </div>
                      </div>
                      <!-- ********** //입력형 ********** -->
                      <!-- ********** 선지형 ********** -->
                      <div v-if="element.type === QuestionAnswerEnum.Choice" class="p-5">
                        <el-form-item label="정답" label-width="50px">
                          <el-checkbox-group v-model="element.answer" class="d-flex w-100 justify-content-between">
                            <div class="d-flex gap-5">
                              <el-badge
                                v-for="item in element.choices.length"
                                :key="item"
                                type="warning"
                                :value="
                                  element.answer.length > 1
                                    ? element.answer.findIndex((answer) => answer === String(item)) + 1 || ''
                                    : ''
                                "
                              >
                                <el-checkbox :label="String(item)" :checked="element.answer.includes(String(item))">
                                  {{ item }}
                                </el-checkbox>
                              </el-badge>
                            </div>
                            <el-button type="success" @click="addChoice(index)">선지 추가</el-button>
                          </el-checkbox-group>
                        </el-form-item>
                        <div v-if="element.choices.length === 0">
                          <el-divider />
                          <p class="px-3">선지를 추가해 주세요.</p>
                        </div>
                        <div v-for="(choice, key) in element.choices" v-else :key="key" class="d-flex gap-3 mb-5">
                          <div class="d-flex flex-column gap-3 align-items-center">
                            <span class="mt-2">
                              {{ `선지${key + 1}` }}
                            </span>
                            <el-button :icon="Delete" type="danger" @click="removeChoice(index, key)" />
                          </div>
                          <div class="d-flex gap-2 w-100">
                            <div class="w-100">
                              <froala
                                v-model:value="element.choices[key]"
                                tag="textarea"
                                :config="froalaEditorMathConfig(uploadImagePath)"
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- ********** //선지형 ********** -->
                      <!-- ********** 순서맞추기 ********** -->
                      <div
                        v-if="element.type === QuestionAnswerEnum.OrderMatching"
                        class="d-flex flex-wrap align-items-center p-5 gap-3"
                      >
                        <span v-if="element.answer.length === 0"> 빈칸을 추가해 주세요. </span>
                        <div v-for="(choice, i) in element.choices" :key="i" class="blank-item w-100">
                          <el-button
                            class="delete-button"
                            :icon="Close"
                            type="danger"
                            size="small"
                            circle
                            @click="removeBlankItem(index, i)"
                          />

                          <div class="w-100">
                            <froala
                              v-model:value="element.choices[i]"
                              tag="textarea"
                              :config="froalaEditorMathConfig(uploadImagePath)"
                            />
                          </div>
                        </div>
                      </div>
                      <!-- ********** //순서맞추기 ********** -->
                    </div>
                    <!-- @@@@@@@@@@@@ // 답안 바디 @@@@@@@@@@@@ -->
                  </div>
                </template>
              </draggable>

              <div v-if="options.isMultipleAnswer" class="mt-5 text-end">
                <el-button type="success" @click="addContentsAnswers">답안 추가</el-button>
              </div>
              <!-- ######################## // 답안 ######################## -->

              <!-- ######################## 해설 ######################## -->
              <template v-if="options.isExplanation">
                <el-divider></el-divider>
                <div class="mb-5">
                  <h4 class="m-0">해설</h4>
                </div>
                <div class="w-100">
                  <froala
                    v-model:value="formData.explanation"
                    tag="textarea"
                    :config="froalaEditorMathConfig(uploadImagePath)"
                  />
                </div>
              </template>
              <!-- ######################## // 해설 ######################## -->
            </div>
          </div>
        </div>
      </el-form>
      <div class="d-flex justify-content-end align-items-center gap-4 w-100 px-9">
        <el-form-item v-if="props.questionId" class="m-0" label="검수 여부">
          <el-switch v-model="formData.is_published" />
        </el-form-item>
        <el-button v-if="previewUrl" size="large" class="m-0" type="warning" @click="goPreview">미리보기</el-button>
        <el-button size="large" class="m-0" type="primary" @click="onSubmit">문제 저장</el-button>
        <el-button size="large" class="m-0" type="info" @click="isShow = false">닫기</el-button>
      </div>
    </template>
    <div v-else v-loading="isLoading" class="w-100 h-100"></div>
  </el-dialog>
</template>

<script setup lang="ts">
  import { reactive, ref, PropType, computed, watch } from 'vue';
  import { getQuestion, storeQuestion } from '@/admin/api/questions';
  import { Question, QuestionAnswer, QuestionAnswerEnum } from '@/admin/api/model/questions';
  import { froalaEditorMathConfig } from '@/admin/core/plugins/froalaEditor';
  import { FormRules } from 'element-plus';
  import draggable from 'vuedraggable';
  import { Close, Delete } from '@element-plus/icons-vue';
  import { set } from 'lodash';
  import { updateQuestion } from '@/admin/api/questions';
  import { QuestionDrawerOptions } from './types';
  import { usePage } from '@inertiajs/vue3';
  import { PageProps } from '@/admin/types';
  import { Training } from '@/admin/api/model/training';
  import { Curriculum } from '@/admin/api/model/curriculum';

  interface Page extends PageProps {
    curriculum: Curriculum;
    training?: Training;
    training_concept_text_type?: 'readings' | 'summarizations' | 'reinforcements';
  }

  const pageData = usePage<Page>();

  const props = defineProps({
    questionId: {
      type: Number as PropType<number | null>,
      default: null,
    },
    curriculumIdToName: {
      type: Object as PropType<{ [key in number]: { name: string; txt_element: string } }>,
      default: null,
    },
    previewUrl: {
      type: String as PropType<string>,
      default: null,
    },
    show: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    options: {
      type: Object as PropType<QuestionDrawerOptions>,
      default: null,
    },
  });

  const emits = defineEmits(['close', 'submitCallback']);

  function goPreview() {
    window.open(`${props.previewUrl}/${props.questionId}`);
  }

  const isShow = ref(false);

  const curriculumIdToName = ref<{ [key in number]: { name: string; txt_element: string } }>(props.curriculumIdToName);

  const formRef = ref();

  const questionType = {
    [QuestionAnswerEnum.Input]: '입력형',
    [QuestionAnswerEnum.Choice]: '선지형',
    [QuestionAnswerEnum.OrderMatching]: '순서맞추기',
  };

  const levelType = { 1: '하', 2: '중하', 3: '중', 4: '중상', 5: '상' };
  const actionType = { 1: '문해', 2: '계산', 3: '추론', 4: '문제해결' };
  const textFroalaEditor = ref();

  const defaultChoices = ['', ''];

  const uploadImagePath = computed(() => `/question`);

  /** 문제 풀이 변환 매칭 커맨드 */
  const textMatchCommand = [
    { value: '${[n]}', label: '빈칸길이 기본 (alt + 1)' },
    { value: '${[n-]}', label: '빈칸길이 2배 (alt + 2)' },
    { value: '${[n--]}', label: '빈칸길이 3배 (alt + 3)' },
    { value: '${[n---]}', label: '빈칸길이 4배 (alt + 4)' },
    { value: '${[n----]}', label: '빈칸길이 5배 (alt + 5)' },
    { value: '${[n-----]}', label: '빈칸길이 6배 (alt + 6)' },
    { value: '${[n]/[n]}', label: '진분수 (alt + 7)' },
    { value: '${[n]/[n]/[n]}', label: '대분수 (alt + 8)' },
    { value: '#{[n-n]}', label: '빈칸 문제 복사 (alt + 9)' },
    { value: '//', label: '끊어 읽기' },
  ];

  /**
   * 문제 풀이 에디터 전용 froalaEditorConfig
   */
  const textFroalaConfig = computed(() => {
    const result = froalaEditorMathConfig(uploadImagePath.value);

    // 문제풀이 에디터 키 다운 이벤트 등록
    set(result, 'events.keydown', (event: KeyboardEvent) => {
      if (event.altKey) {
        // Alt + (1 ~ 6) 외의 키 입력시 작업 안함
        if (!(Number(event.key) >= 1 && Number(event.key) <= 9)) return;

        const addString = `${textMatchCommand[Number(event.key) - 1].value}`;

        event.preventDefault();
        const editor = textFroalaEditor.value.getEditor();

        const selection = editor.selection.get();
        const focusNode = selection.focusNode;
        const focusOffset = selection.focusOffset;

        const newNode = document.createElement('span');
        newNode.innerHTML = addString;

        // 노드의 타입에 따라서 삽입 로직이 달라짐
        if (focusNode.nodeType === 3) {
          const parentElement = focusNode.parentNode;

          const secondTextNode = focusNode.splitText(focusOffset);

          parentElement.insertBefore(newNode, secondTextNode);
        } else {
          focusNode.childNodes[Math.max(0, focusOffset - 1)].after(newNode);
        }

        // 커서 위치 지정
        editor.selection.setAtEnd(newNode);
        editor.selection.restore();
      }
    });
    return result;
  });

  const dragOptions = ref({
    animation: 200,
    group: 'description',
    disabled: false,
    ghostClass: 'ghost',
  });

  const selectBlankType = ref<0 | 1 | 2>(0);

  const blankTypeOptions = ['기본', '분수', '대분수'];

  const formRules = ref<FormRules>({
    curriculum_id: [{ required: true, message: '필수입력입니다', trigger: 'blur' }],
  });

  const formData = reactive<{
    type: QuestionAnswerEnum;
    inquiry: string; // 발문
    answers: QuestionAnswer[]; // 문제
    question?: string; // 문제풀이
    options?: string; // 보기
    explanation?: string; // 해설
    level?: number; // 문제 레벨
    curriculum_id?: number;
    is_published: boolean;
  }>({
    type: 1,
    inquiry: '',
    answers: [
      {
        type: 1,
        action: 1,
        choices: [...defaultChoices],
        choice_symbol: false,
        answer: [],
      },
    ],
    question: '',
    options: '',
    explanation: '',
    level: 1,
    curriculum_id: undefined,
    is_published: false,
  });

  function initFormData() {
    formData.type = 1;
    formData.inquiry = '';
    formData.answers = [
      {
        type: 1,
        action: 1,
        choices: [...defaultChoices],
        choice_symbol: false,
        answer: [],
      },
    ];
    formData.question = '';
    formData.options = '';
    formData.explanation = '';
    formData.level = 1;
    formData.curriculum_id = undefined;
  }

  function setFormData(question?: Question) {
    if (question) {
      formData.type = question.type || 1;
      formData.inquiry = question.inquiry || '';
      formData.answers = question.answers || [
        {
          type: 1,
          action: 1,
          choices: [...defaultChoices],
          choice_symbol: false,
          answer: [],
        },
      ];
      formData.question = question.question;
      formData.options = question.options;
      formData.explanation = question.explanation;
      formData.level = question.level;
      formData.curriculum_id = question.curriculum_id;
    }
  }

  const isLoading = ref(false);

  async function getQuestionData(id: number) {
    try {
      if (isLoading.value) return;
      isLoading.value = true;
      const { data } = await getQuestion(id);

      curriculumIdToName.value = data.data.curriculum_id_to_name;
      setFormData(data.data.question);
      formData.is_published = Boolean(data.data.question.published_at);
      isLoading.value = false;
    } catch (err) {
      isLoading.value = false;
      console.log(err);
    }
  }

  /**
   * 풀이 답안 생성 버튼 클릭 시
   */
  const createContentsAnswers = () => {
    if (!formData.question) return;

    const resultAnswers: QuestionAnswer[] = [];

    const processMatchArray = (matchArray, type: QuestionAnswerEnum) => {
      matchArray.forEach((data, i) => {
        const splitedData = data.split('/');
        const answerIndex = Number(splitedData[0].match(/\d+/g)) - 1;

        if (resultAnswers.length - 1 < answerIndex) {
          if (type === QuestionAnswerEnum.Input) {
            resultAnswers.push({
              type,
              action: 1,
              choices: [...defaultChoices],
              choice_symbol: false,
              answer: [],
            });
          } else if (type === QuestionAnswerEnum.Choice) {
            resultAnswers.push({
              type,
              action: 1,
              choices: [...defaultChoices],
              choice_symbol: false,
              answer: ['1'],
            });
          } else if (type === QuestionAnswerEnum.OrderMatching) {
            resultAnswers.push({
              type,
              action: 1,
              choices: [],
              choice_symbol: false,
              answer: [],
            });
          }
        }

        if (type === QuestionAnswerEnum.Input) {
          let answerItem: string | string[] = '';

          if (splitedData.length === 2) {
            answerItem = ['', ''];
          } else if (splitedData.length === 3) {
            answerItem = ['', '', ''];
          }

          resultAnswers[answerIndex].answer.push(answerItem);
        } else if (type === QuestionAnswerEnum.OrderMatching) {
          resultAnswers[answerIndex].choices.push('');
          resultAnswers[answerIndex].answer.push(`${resultAnswers[answerIndex].choices.length}`);
        }
      });
    };

    if (props.options.isOrderMatching) {
      const [choicesQuestion, orderMatchingQuestion] = formData.question.split('<hr>');

      processMatchArray(
        [...choicesQuestion.matchAll(/\${(.*?)}/g)].map((match) => match[1]),
        2,
      );
      processMatchArray(
        [...orderMatchingQuestion.matchAll(/\${(.*?)}/g)].map((match) => match[1]),
        3,
      );
    } else {
      let type = 1;
      if (pageData.props.training_concept_text_type === 'summarizations') {
        type = 2;
      }

      processMatchArray(
        [...formData.question.matchAll(/\${(.*?)}/g)].map((match) => match[1]),
        type,
      );
    }

    formData.answers = resultAnswers;
  };

  const addContentsAnswers = () => {
    if (!formData.answers) {
      formData.answers = [];
    }

    let type = 1;
    if (pageData.props.training_concept_text_type === 'summarizations' || !props.options.isQuestion) {
      type = 2;
    }

    formData.answers.push({
      type: type,
      action: 1,
      choices: [...defaultChoices],
      choice_symbol: false,
      answer: type === 2 ? [] : [''],
    });
  };

  function onChangeAnswerType(element: any) {
    element.answer = [];
    if (element.type === QuestionAnswerEnum.Input) {
      element.answer = [''];
    } else if (element.type === QuestionAnswerEnum.Choice) {
      element.choices = [...defaultChoices];
    } else if (element.type === QuestionAnswerEnum.OrderMatching) {
      // 순서 맞추기의 수동 추가 작업 불가
      element.answer = [''];
    }
  }

  const removeContentsAnswers = (answerIndex: number) => {
    if (!formData.answers) return;
    formData.answers.splice(answerIndex, 1);
  };

  const addChoice = (answerIndex: number) => {
    if (!formData.answers) {
      formData.answers = [];
    }

    formData.answers[answerIndex].choices.push('');
  };

  const removeChoice = (answerIndex: number, choiceIndex: number) => {
    if (!formData.answers) return;
    formData.answers[answerIndex].choices.splice(choiceIndex, 1);
    formData.answers[answerIndex].answer = [];
  };

  /**
   *
   * @param answerIndex
   * @param type 0: 기본, 1: 분수, 2: 대분수
   */
  const addBlankItem = (answerIndex: number, type?: 0 | 1 | 2) => {
    if (!formData.answers || type === undefined) return;

    let blankItem: string | string[] = '';

    switch (type) {
      case 0:
        break;
      case 1:
        blankItem = ['', ''];
        break;
      case 2:
        blankItem = ['', '', ''];
        break;
      default:
        break;
    }

    formData.answers[answerIndex].answer.push(blankItem);
  };

  const removeBlankItem = (answersIndex: number, answerIndex: number) => {
    if (!formData.answers) return;

    formData.answers[answersIndex].answer.splice(answerIndex, 1);
  };

  const onSubmit = () => {
    formRef.value?.validate(async (valid: boolean) => {
      if (valid) {
        if (props.questionId) {
          try {
            const { data } = await updateQuestion(props.questionId, formData);
            emits('submitCallback', data.data.question);
          } catch (err) {
            console.log(err);
          }
        } else {
          await storeQuestion(formData);
        }
      }
    });
  };

  watch(
    () => props.show,
    (newVal) => {
      if (newVal) {
        isShow.value = newVal;
        if (props.questionId) {
          getQuestionData(props.questionId);
        }
      }
    },
  );

  watch(
    () => isShow.value,
    (newVal) => {
      if (!newVal) {
        initFormData();
        emits('close');
      }
    },
  );
</script>

<style lang="scss">
  .fr-counter {
    display: none;
  }

  .blank-item {
    position: relative;
    display: flex;
    align-items: center;
    gap: 1rem;

    &:hover {
      .delete-button {
        opacity: 1;
      }
    }

    .delete-button {
      opacity: 0;
      position: absolute;
      top: -20px;
      right: -20px;
      transition: 0.3s;
    }

    .blank-input {
      display: block;
      width: 90px;
      height: 40px;
      border: 1px solid #a4a4a4;
      border-radius: 5px;
      outline: none;
      padding-left: 1rem;
      resize: both;
      &::placeholder {
        color: rgba($color: #000000, $alpha: 0.2);
      }
    }
  }
</style>
