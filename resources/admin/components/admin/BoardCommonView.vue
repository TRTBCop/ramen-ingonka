<template>
  <div class="card mb-5 mb-xl-10">
    <div class="card-header border-0 cursor-pointer" role="button" aria-expanded="true">
      <div class="card-title m-0">
        <h3 class="fw-bold m-0">{{ $page.props.page.title }}</h3>
      </div>
    </div>
    <div class="card-body border-top p-9">
      <el-form
        ref="formRef"
        :rules="rules"
        :model="form"
        class="form"
        status-icon
        label-width="130px"
        @submit.prevent="submitAction"
      >
        <el-form-item label="제목" prop="title">
          <el-input v-model="form.title" placeholder="제목을 입력해 주세요" />
        </el-form-item>
        <el-row v-if="isNoticePage">
          <el-col :span="8">
            <el-form-item label="노출 범위" prop="scope">
              <el-select v-model="form.scope" multiple placeholder="노출 범위를 선택해 주세요">
                <el-option
                  v-for="(value, key) in pageData.props.config.board.scope"
                  :key="key"
                  :label="value"
                  :value="key"
                />
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>

        <el-row>
          <el-col :span="7">
            <el-form-item label="상위 카테고리" prop="category">
              <el-select
                v-model="form.category"
                filterable
                placeholder="상위 카테고리 선택"
                @change="form.sub_category = ''"
              >
                <el-option v-for="value in categoryOptions" :key="value" :label="value" :value="value" />
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="1"></el-col>
          <el-col :span="7">
            <el-form-item label="하위 카테고리" prop="sub_category">
              <el-select v-model="form.sub_category" filterable placeholder="하위 카테고리 선택">
                <el-option v-for="value in subCategoryOptions" :key="value" :label="value" :value="value" />
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>

        <el-form-item label="게시판 노출 여부" prop="is_published">
          <el-radio-group v-model="form.is_published">
            <el-radio :label="true">노출</el-radio>
            <el-radio :label="false">비노출</el-radio>
          </el-radio-group>
        </el-form-item>

        <el-form-item label="내용" prop="contents">
          <div class="w-100">
            <froala
              v-model:value="form.contents"
              height="300px"
              tag="textarea"
              :config="{ ...froalaEditorMathConfig(), height: 400 }"
            />
          </div>
        </el-form-item>

        <el-form-item label="첨부파일" style="margin-top: 1rem">
          <el-upload v-model:file-list="form.files" :auto-upload="false" :on-remove="onRemove">
            <template #trigger>
              <el-button type="info">파일 선택</el-button>
            </template>
          </el-upload>
        </el-form-item>
        <div class="text-end">
          <el-button type="primary" @click="submitAction">저장</el-button>
          <el-button v-if="!isCreatePage" type="danger" @click="deleteBoard">삭제</el-button>
          <el-button type="default" @click="router.get(route(`admin.${currentRouteName}.index`))"> 목록 </el-button>
        </div>
      </el-form>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, computed } from 'vue';
  import { router, useForm, usePage } from '@inertiajs/vue3';
  import { ElMessageBox, FormRules } from 'element-plus';
  import { froalaEditorMathConfig } from '@/admin/core/plugins/froalaEditor';

  const pageData = usePage<any>();

  const formRef = ref<null | HTMLFormElement>(null);

  const currentRouteName = computed(() => pageData.props.route_name.split('.')[1]);

  /** 등록 페이지이면 true */
  const isCreatePage = computed(() => !pageData.props?.board);

  /** 해당 페이지가 공지사항이면 true */
  const isNoticePage = computed(() => currentRouteName.value === 'board-notices');

  const rules = ref<FormRules>({
    title: [{ required: true, message: '필수입력입니다' }],
    scope: [{ required: true, message: '필수입력입니다' }],
    category: [{ required: true, message: '필수입력입니다' }],
    is_published: [{ required: true, message: '필수입력입니다' }],
    contents: [{ required: true, message: '필수입력입니다' }],
  });

  const onRemove = (uploadFile: any) => {
    form.del_files.push(uploadFile.id);
  };

  const initFormData = () => {
    let result: {
      _method: string;
      title: string;
      category: string;
      sub_category: string;
      is_published: boolean;
      contents: string;
      files: any[];
      del_files: object[];
      scope?: string[];
      student_name?: string;
      student_grade?: string;
    } = {
      _method: 'post',
      title: '',
      category: '',
      sub_category: '',
      is_published: false,
      contents: '',
      files: [],
      del_files: [],
    };

    // 공지사항 페이지일 경우 노출 범위 속성 추가
    if (isNoticePage.value) {
      result = {
        ...result,
        scope: [] as string[],
      };
    }

    // 새로 만드는 페이지가 아닐 경우 초반 데이터 세팅
    if (!isCreatePage.value) {
      result = {
        ...result,
        _method: 'put',
        title: pageData.props.board.title,
        category: pageData.props.board.category,
        sub_category: pageData.props.board.sub_category,
        is_published: Boolean(pageData.props.board.published_at),
        contents: pageData.props.board.contents,
        files: pageData.props.board.files,
        del_files: [],
      };

      if (isNoticePage.value) {
        result = {
          ...result,
          scope: Object.keys(pageData.props.board.txt_scope),
        };
      }
    }

    return result;
  };

  const form = useForm(initFormData());

  /** 상위 카테고리 필터 옵션 */
  const categoryOptions = computed(() => {
    const result = {};
    pageData.props.config.board.category.forEach((data) => {
      result[data.name] = data.name;
    });
    return result;
  });

  /** 하위 카테고리 필터 옵션 */
  const subCategoryOptions = computed(() => {
    const result = {};

    const category = pageData.props.config.board.category.filter((data) => data.name === form.category);

    if (!category[0]?.category) return result;

    !category[0]?.category.forEach((data) => {
      result[data.name] = data.name;
    });
    return result;
  });

  const submitAction = () => {
    if (!formRef.value) return;

    formRef.value.validate((valid: boolean) => {
      if (valid) {
        form.files = form.files.map((data) => data?.raw || data);

        const action = isCreatePage.value
          ? route(`admin.${currentRouteName.value}.store`)
          : route(`admin.${currentRouteName.value}.update`, pageData.props.board.id);

        form.post(action, {
          onSuccess: () => {
            // 성공 처리
          },
          onError: (error) => {
            // 에러 처리
          },
        });
      }
    });
  };

  const deleteBoard = () => {
    ElMessageBox.confirm('정말 삭제하시겠습니까?', '', {
      type: 'warning',
    }).then(
      () => {
        router.delete(route(`admin.${currentRouteName.value}.destroy`, pageData.props.board.id));
      },
      () => {
        //
      },
    );
  };
</script>
