<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >

  <AdminLayout>
    <Navbar />
    <div class="flex-column-fluid card mt-5 p-9">
      <Training1Navbar>
        <div class="d-flex gap-5 flex-column flex-lg-row flex-column-fluid">
          <!-- ############## 문장 선택 영역 ############## -->
          <div class="w-lg-50 w-100">
            <el-table
              :data="pageData.props.contents"
              :row-class-name="tableRowClassName"
              height="650"
              @cell-click="handleClickCell"
            >
              <el-table-column prop="type" label="#" width="100">
                <template #default="{ $index }">
                  {{ $index + 1 }}
                </template>
              </el-table-column>
              <el-table-column prop="word" label="끊어읽기">
                <template #default="{ row }">
                  <span v-html="row.text"></span>
                </template>
              </el-table-column>
              <el-table-column label="이미지/문제" width="200">
                <template #default="{ row }">
                  <div :class="`badge badge-light-${typeColors[row.type]}`"
                    >{{ wordTypeTexts[row.type] }}
                    {{ row.type === TrainingWordType.image && Number(row.image.last) ? '*' : '' }}</div
                  >
                </template>
              </el-table-column>
            </el-table>
          </div>
          <!-- ############## 편집 영역 ############## -->
          <div
            v-if="pageData.props.contents?.length > 0 && !isNil(activeQuestion)"
            :key="activeQuestion"
            class="w-lg-50 w-100 p-2"
          >
            <el-alert class="mb-5" type="info" :closable="false">
              <el-radio-group v-model="formData.type">
                <el-radio v-for="(text, key) in wordTypeTexts" :key="key" :label="key">{{ text }}</el-radio>
              </el-radio-group>
            </el-alert>
            <froala v-model:value="formData.text" tag="textarea" :config="froalaEditorMathConfig()" />
            <div>
              <!-- @@@@@@@@@@@@@@ 이미지 @@@@@@@@@@@@@@ -->
              <div v-if="formData.image && formData.type === TrainingWordType.image">
                <el-divider />
                <el-checkbox v-model="formData.image.last" label="최종 이미지" size="large" />
                <div class="mb-2 rounded border border-gray-300" style="height: 200px">
                  <el-image v-if="formData.image.src" class="w-100 h-100" fit="contain" :src="formData.image.src" />
                  <div class="d-flex w-100 h-100 justify-content-center align-items-center">이미지 없음</div>
                </div>
                <el-upload
                  v-model:file-list="fileList"
                  :headers="{ 'X-CSRF-TOKEN': pageData.props.auth.csrf }"
                  :action="route('app.upload.image')"
                  :on-success="handleUploadSuccess"
                >
                  <template #file><span>&nbsp;</span></template>
                  <el-button type="success">{{ formData.image.src ? '이미지 재업로드' : '이미지 업로드' }}</el-button>
                </el-upload>
              </div>
              <!-- @@@@@@@@@@@@@@ 정답 고르기 @@@@@@@@@@@@@@ -->
              <div v-if="formData.type === TrainingWordType.question" class="w-100">
                <el-divider />
                <div class="mt-3">
                  <el-button
                    v-if="pageData.props.contents[activeQuestion].question?.id"
                    type="warning"
                    @click="showQuestionDetail(pageData.props.contents[activeQuestion].question?.id)"
                    >문제 수정</el-button
                  >
                  <el-button v-else type="success" @click="storeTextReadingQuestion">문제 생성</el-button>
                </div>
              </div>
              <div class="text-end mt-3">
                <el-button type="primary" @click="handleUpdateData">저장</el-button>
              </div>
            </div>
          </div>
        </div>
        <div class="mt-5">
          <el-button type="success" @click="showConvertedModal">끊어읽기 생성</el-button>
        </div>
        <QuestionDrawer
          :show="questionDetailDrawer.show"
          :question-id="questionDetailDrawer.questionId"
          :options="training1TextReadingOptions"
          @close="hideQuestionDetail"
        />
        <div class="mt-5 text-end">
          <el-button type="info" @click="goPreviewPage">미리보기</el-button>
        </div>
      </Training1Navbar>
    </div>
    <div class="d-flex gap-3">
      <GoListButton :list-url="route('admin.curricula.index')" />
      <el-form-item class="mb-0 mt-5" label="검수 여부">
        <el-switch v-model="isPublished" @change="handleUpdateData" />
      </el-form-item>
    </div>

    <!-- 끊어읽기 생성 모달 -->
    <el-dialog
      v-model="isConvertedModal"
      title="끊어읽기 생성"
      width="70%"
      :close-on-click-modal="false"
      :close-on-press-escape="false"
    >
      <el-alert class="mb-5" type="info" show-icon :closable="false">
        끊어읽어야 할 부분에 '//'를 입력하고, 문단을 나누는 경우 '----' 를 입력하세요.<br />
        * 지문 내용은 <b>끊어읽기 생성시</b> 저장됩니다. *
      </el-alert>
      <froala v-model:value="convertedWords" tag="textarea" :config="froalaEditorMathConfig()" />
      <template #footer>
        <span class="dialog-footer">
          <el-button type="danger" @click="hideConvertedModal">닫기</el-button>
          <el-button type="primary" @click="clickCreateSplitWords">끊어읽기 생성</el-button>
        </span>
      </template>
    </el-dialog>
  </AdminLayout>
</template>

<script setup lang="ts">
  import { reactive, ref, watch } from 'vue';
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import { Head, router, usePage } from '@inertiajs/vue3';
  import Navbar from './components/Navbar.vue';
  import { Curriculum } from '@/admin/api/model/curriculum';
  import { PageProps } from '@/admin/types';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import { Training, TextReading, TrainingWordType, ConceptBasicOperations } from '@/admin/api/model/training';
  import { ElMessageBox, UploadProps } from 'element-plus';
  import { froalaEditorMathConfig } from '@/admin/core/plugins/froalaEditor';
  import GoListButton from '@/admin/components/admin/GoListButton.vue';
  import Training1Navbar from './components/Training1Navbar.vue';
  import { Question } from '@/admin/api/model/questions';
  import QuestionDrawer from '@/admin/components/admin/QuestionDrawer/Index.vue';
  import { QuestionStoreParams, storeQuestion } from '@/admin/api/questions';
  import { isNil } from 'lodash';
  import { training1TextReadingOptions } from './trainingQuestionOptions';
  import { splitByDoubleSlash } from '@/app/core/helpers/trainingHelper';

  interface Page extends PageProps {
    config: {
      dbcode: Pick<Dbcode, 'curricula'>;
    };
    curriculum: Curriculum;
    training: Training<ConceptBasicOperations>;
    contents: TextReading[];
    questions: Question[];
    training_concept_text_id: number;
    training_concept_text_ids: number[];
    question_extra: {
      model: string;
      model_id: number;
      type: string;
    };
  }

  const pageData = usePage<Page>();

  const activeQuestion = ref<number | null>(0);

  const fileList = ref<unknown[]>([]);

  const sectionSeparator = '----';
  const sectionSeparatorWord = '[문단 나누기]';

  const isPublished = ref(Boolean(pageData.props.training.published_at));

  const isConvertedModal = ref(false);

  function showConvertedModal() {
    isConvertedModal.value = true;
    createConvertedWords();
  }

  function hideConvertedModal() {
    isConvertedModal.value = false;
  }

  const questionDetailDrawer = reactive<{ show: boolean; questionId: number | null }>({
    show: false,
    questionId: 0,
  });

  function showQuestionDetail(questionId?: number) {
    questionDetailDrawer.show = true;
    questionDetailDrawer.questionId = questionId || null;
  }

  function hideQuestionDetail() {
    questionDetailDrawer.show = false;
    questionDetailDrawer.questionId = 0;
  }

  /** 끊어읽기를 이어 붙인 지문 생성 */
  function createConvertedWords() {
    if (!pageData.props.training || isNil(pageData.props.contents)) return '';
    convertedWords.value = pageData.props.contents?.reduce((prev, current, currentIndex) => {
      // '[문단 나누기]'를 '----'로 치환 작업
      if (current.text === sectionSeparatorWord) {
        return `${prev}//${sectionSeparator}`;
      }

      if (prev.substr(-sectionSeparator.length) === sectionSeparator || currentIndex === 0) {
        return `${prev}${current.text}`;
      }
      return `${prev}//${current.text}`;
    }, '');
  }

  function clickCreateSplitWords() {
    ElMessageBox.confirm('기존에 저장된 끊어읽기 데이터는 삭제됩니다. 진행하시겠습니까?', '', {
      type: 'warning',
    }).then(
      () => {
        pageData.props.contents = createSplitWords();

        router.put(
          route('admin.curricula.training1.texts.update', {
            curriculum: pageData.props.curriculum.id,
            trainingConceptText: pageData.props.training_concept_text_id,
            type: 'readings',
          }),
          {
            readings: pageData.props.contents as any,
          },
        );

        hideConvertedModal();
        activeQuestion.value = 0;
        initFormData(pageData.props.contents[0]);
      },
      () => {
        //
      },
    );
  }

  function createSplitWords(): TextReading[] {
    return splitByDoubleSlash(convertedWords.value.replace(/----/g, '[문단 나누기]//')).map(
      (data): TextReading => ({
        type: TrainingWordType.default,
        text: data,
      }),
    );
  }

  /** 끊어읽기를 형식에 맞게 합친 하나의 지문 */
  const convertedWords = ref('');

  function tableRowClassName({ rowIndex }) {
    if (rowIndex === activeQuestion.value) {
      return 'active-row';
    }

    return '';
  }

  function goPreviewPage() {
    window.open(
      route('app.trainings.stage1.texts.readings.preview.show', {
        training: pageData.props.training.id,
        trainingConceptText: pageData.props.training_concept_text_id,
      }),
    );
  }

  async function storeTextReadingQuestion() {
    try {
      if (activeQuestion.value === null) return;

      const params: QuestionStoreParams = {
        curriculum_id: pageData.props.curriculum.id,
        inquiry: pageData.props.contents[activeQuestion.value].text,
        is_published: true,
        answers: [
          {
            type: 2,
            answer: ['1'],
            choices: ['', ''],
            choice_symbol: false,
            action: 1,
          },
        ],
        tags: {
          used_at: ['커리큘럼관리상세-개념훈련-지문-개념읽기'],
        },
        rel: {
          table: 'trainings',
          id: pageData.props.training.id,
          extra: pageData.props.question_extra,
        },
      };
      const { data } = await storeQuestion(params);

      pageData.props.contents[activeQuestion.value].question = { id: data.data.question.id };
      handleUpdateData();
    } catch (err) {
      console.log(err);
    }
  }

  function handleClickCell(row: TextReading, column: unknown, cell) {
    const rowElem = cell.parentElement;
    const tableElem = rowElem.parentElement;

    if (tableElem) {
      let index = Array.from(tableElem.children).indexOf(rowElem);
      activeQuestion.value = index;
    } else {
      activeQuestion.value = null;
    }
  }

  function handleUpdateData() {
    if (activeQuestion.value !== null && pageData.props.training) {
      const newItem = pageData.props.contents[activeQuestion.value];

      newItem.type = formData.type;
      newItem.text = formData.text;

      if (formData.image) {
        newItem.image = { ...formData.image };
      }

      pageData.props.contents[activeQuestion.value] = newItem;

      router.put(
        route('admin.curricula.training1.texts.update', {
          is_published: isPublished.value,
          curriculum: pageData.props.curriculum.id,
          trainingConceptText: pageData.props.training_concept_text_id,
          type: 'readings',
        }),
        {
          readings: pageData.props.contents as any,
        },
      );
    }
  }

  const wordTypeTexts = ['효과없음', '이미지', '문제형'];
  const typeColors = ['dark', 'warning', 'success'];

  const handleUploadSuccess: UploadProps['onSuccess'] = ({ data }) => {
    if (fileList.value.length > 1) {
      fileList.value.shift();
    }

    if (formData.image && activeQuestion.value !== null && formData.type === TrainingWordType.image) {
      formData.image.src = data.url;
    }
  };

  const formData = reactive<TextReading>({
    type: 0,
    text: '',
    image: {
      src: '',
      last: false,
    },
  });

  function initFormData(word: TextReading) {
    formData.type = word.type;
    formData.text = word.text;

    formData.image = {
      src: '',
      last: false,
    };

    if (word.image) {
      formData.image = {
        src: word.image.src,
        last: Boolean(Number(word.image.last)),
      };
    }
  }

  if (pageData.props.contents?.length > 0) {
    initFormData(pageData.props.contents[0]);
  }
  createConvertedWords();

  watch(
    () => activeQuestion.value,
    (newVal) => {
      if (newVal !== null && pageData.props.training) {
        initFormData(pageData.props.contents[newVal]);
      }
    },
  );
</script>

<style>
  .el-table .active-row {
    --el-table-tr-bg-color: rgba(0, 0, 0, 0.1);
  }
</style>
