<template>
  <div class="mb-5">
    <el-button type="primary" size="small" @click="addTrainingConceptText">지문 +</el-button>
    <el-button v-if="activeTextTabName !== 'operations'" type="danger" size="small" @click="removeTrainingConceptText"
      >지문 -</el-button
    >
  </div>
  <el-tabs v-model="activeTextTabName" type="card" @tab-click="onClickTrainingConceptTextTab">
    <el-tab-pane
      v-for="(textTab, stepIndex) in trainingConceptTextTabs"
      :key="stepIndex"
      :label="textTab.label"
      :name="textTab.name"
    ></el-tab-pane>
  </el-tabs>
  <div class="d-flex flex-column px-5 flex-column-fluid">
    <el-tabs v-if="activeTextTabName !== 'operations'" v-model="activeTypeName" @tab-click="onClickType">
      <el-tab-pane v-for="(round, roundIndex) in typeList" :key="roundIndex" :label="round.label" :name="round.name" />
    </el-tabs>
    <div class="d-flex flex-column flex-column-fluid">
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
  import { ref, onMounted } from 'vue';
  import { Dbcode } from '@/admin/api/model/dbcode';
  import { PageProps } from '@/admin/types';
  import { Curriculum } from '@/admin/api/model/curriculum';
  import { router, usePage } from '@inertiajs/vue3';
  import { ElMessageBox } from 'element-plus';

  interface Page extends PageProps {
    config: {
      dbcode: Pick<Dbcode, 'curricula'>;
    };
    curriculum: Curriculum;
    training_concept_text_id: number;
    training_concept_text_type: string;
    training_concept_text_ids: number[];
  }

  const pageData = usePage<Page>();

  const activeTextTabName = ref<string | number>('operations');
  const trainingConceptTextTabs = ref<{ label: string; name: number | string }[]>([]);

  const activeTypeName = ref('readings');
  const typeList = ref([
    { label: '개념 읽기', name: 'readings' },
    { label: '개념 요약', name: 'summarizations' },
    { label: '개념 다지기', name: 'reinforcements' },
  ]);

  function initAtiveTabIndex() {
    activeTextTabName.value = pageData.props.training_concept_text_id || 'operations';
    trainingConceptTextTabs.value = [
      ...pageData.props.training_concept_text_ids.map((id, index) => ({ label: `지문${index + 1}`, name: id })),
      { label: '기초 연산', name: 'operations' },
    ];
    activeTypeName.value = pageData.props.training_concept_text_type;
  }

  function onClickTrainingConceptTextTab(e: { paneName: number | string; active: boolean; index: number }) {
    if (e.active) return;

    if (e.paneName === 'operations') {
      router.get(
        route('admin.curricula.training1.operations.show', {
          curriculum: pageData.props.curriculum.id,
        }),
      );
    } else {
      router.get(
        route('admin.curricula.training1.texts.show', {
          curriculum: pageData.props.curriculum.id,
          trainingConceptText: e.paneName,
          type: 'readings',
        }),
      );
    }
  }

  function onClickType(e: { paneName: number | string; active: boolean; index: number }) {
    if (e.active) return;

    router.get(
      route('admin.curricula.training1.texts.show', {
        curriculum: pageData.props.curriculum.id,
        trainingConceptText: pageData.props.training_concept_text_id,
        type: e.paneName,
      }),
    );
  }

  function addTrainingConceptText() {
    router.post(
      route('admin.curricula.texts.create', pageData.props.curriculum.id),
      {},
      {
        onSuccess() {
          initAtiveTabIndex();
        },
      },
    );
  }

  function removeTrainingConceptText() {
    ElMessageBox.confirm(
      `${
        trainingConceptTextTabs.value.find((data) => data.name === activeTextTabName.value)?.label
      } 제거 하시겠습니까?`,
      '',
      {
        type: 'warning',
      },
    ).then(() => {
      router.delete(
        route('admin.curricula.texts.destroy', {
          curriculum: pageData.props.curriculum.id,
          trainingConceptText: pageData.props.training_concept_text_id,
        }),
        {
          onSuccess() {
            initAtiveTabIndex();
          },
        },
      );
    });
  }

  onMounted(() => {
    initAtiveTabIndex();
  });
</script>
