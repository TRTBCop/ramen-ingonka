<template>
  <Head
    ><title>{{ $page.props.page.title }}</title></Head
  >

  <AdminLayout>
    <div class="card mb-5 mb-xl-10">
      <div class="card-body">
        <el-alert class="mb-5" type="info" show-icon :closable="false"
          >드래그로 위치 이동 및 우클릭으로 추가 메뉴 열기
        </el-alert>
        <el-tree
          ref="treeRef"
          :data="treeData"
          draggable
          node-key="id"
          :allow-drag="handleAllowDrag"
          :allow-drop="handleAllowDrop"
          @node-drag-start="handleDragStart"
          @node-drop="handleDrop"
        >
          <template #default="{ node, data }">
            <div class="w-100" @contextmenu="onContextMenu($event, data)">
              <el-input
                v-if="modifyModeState.isModifyMode && modifyModeState.id === data.id"
                v-model="data.label"
                :class="`node-id-${data.id}`"
                type="text"
                @keyup.enter="stopModifyMode(data, node.parent.data.id)"
                @blur="stopModifyMode(data, node.parent.data.id)"
              />
              <div v-else class="w-100">{{ data.label }}</div>
            </div>
          </template>
        </el-tree></div
      >
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
  import { nextTick, reactive, ref } from 'vue';
  import { Head, router, usePage } from '@inertiajs/vue3';
  import AdminLayout from '@/admin/layouts/AdminLayout.vue';
  import { PageProps } from '@/admin/types';
  import type { NodeDropType } from 'element-plus/es/components/tree/src/tree.type';
  import Node from 'element-plus/es/components/tree/src/model/node';
  import ContextMenu from '@imengyu/vue3-context-menu';
  import '@imengyu/vue3-context-menu/lib/vue3-context-menu.css';
  import { deleteCurriculum, storeCurriculum, updateCurriculum } from '@/admin/api/curriculum';
  import { CurriculumNode } from '@/admin/api/model/curriculum';
  import { ElMessage, ElTree } from 'element-plus';

  interface Page extends PageProps {
    nodes: CurriculumNode[];
  }

  const pageData = usePage<Page>();

  const treeData = ref(pageData.props.nodes);

  const treeRef = ref<InstanceType<typeof ElTree>>();

  interface ModifyModeState {
    id: number | null;
    isModifyMode: boolean;
  }

  const modifyModeState = reactive<ModifyModeState>({
    id: null,
    isModifyMode: false,
  });
  
  console.log('page', pageData.props);

  const startModifyMode = (nodeId: number) => {
    modifyModeState.id = nodeId;
    modifyModeState.isModifyMode = true;
    nextTick(() => {
      const activeInputElem = document.querySelector(`.node-id-${nodeId} input`);
      if (activeInputElem) {
        (activeInputElem as HTMLElement).focus();
      }
    });
  };

  const stopModifyMode = (nodeData: CurriculumNode, parent_id: number) => {
    modifyModeState.id = null;
    modifyModeState.isModifyMode = false;

    handleUpdateCurriculum(nodeData, parent_id || undefined);
  };

  const handleAllowDrag = (node: Node) => {
    if (getNodeDeps(node) <= 2) {
      ElMessage({
        message: '드래그 불가 영역입니다.',
        type: 'error',
      });
      return false;
    }

    return true;
  };

  /** 상위 뎁스로는 이동 못하게 막음 */
  const handleAllowDrop = (draggingNode: Node, dropNode: Node) => {
    return getNodeDeps(draggingNode) <= getNodeDeps(dropNode);
  };

  /**
   * 현재 노드가 최상단 node로 부터 몇번째 deps인지 구하는 재귀 함수
   * @param node
   * @param deps 몇번째 deps를 올라 갔는지 체크
   */
  const getNodeDeps = (node: Node, deps = 0) => {
    if (node.parent) {
      return getNodeDeps(node.parent, deps + 1);
    }

    return deps;
  };

  let oldPosition: number | undefined = undefined;

  const handleDragStart = (node: Node) => {
    // 드래그를 시작하면 드래그된 커리큘럼의 순서를 저장 (수정시 old_position의 값을 주기 위함)
    oldPosition = node.parent.childNodes.findIndex((data) => data.id === node.id);
  };

  const handleDrop = (draggingNode: Node, dropNode: Node, dropType: NodeDropType) => {
    let parentId = 0;
    let position = 0;

    // 드롭 할 때 드롭의 타입에 따라 dropNode의 값이 달라지기 때문에 분기 처리
    switch (dropType) {
      case 'inner':
        parentId = dropNode.data.id;
        position = dropNode.childNodes.length;
        break;
      case 'after':
        parentId = dropNode.parent.data.id;
        position = dropNode.parent.childNodes.findIndex((data) => data.id === dropNode.id) + 1;
        break;
      case 'before':
        parentId = dropNode.parent.data.id;
        position = dropNode.parent.childNodes.findIndex((data) => data.id === dropNode.id) - 1;
        break;
      default:
        break;
    }

    handleUpdateCurriculum(draggingNode.data as CurriculumNode, parentId, position);
  };

  /**
   * 마우스 우클릭시 메뉴 생성
   */
  const onContextMenu = (e: MouseEvent, data: CurriculumNode) => {
    e.preventDefault();

    ContextMenu.showContextMenu({
      x: e.x,
      y: e.y,
      items: [
        {
          label: '생성',
          onClick: () => {
            handleStoreCurriculum(data);
          },
        },
        {
          label: '수정',
          onClick: () => {
            startModifyMode(data.id);
          },
        },
        {
          label: '상세',
          onClick: () => {
            router.get(route('admin.curricula.show', data.id));
          },
        },
        {
          label: '삭제',
          onClick: () => {
            handelDeleteCurriculum(data);
          },
        },
      ],
    });
  };

  const handleStoreCurriculum = async (nodeData: CurriculumNode) => {
    try {
      const initName = '커리큘럼명을 입력해주세요';

      const { data } = await storeCurriculum(initName, nodeData.id);

      if (!data.success) throw new Error();

      nodeData.children?.push({
        id: data.data.curriculum.id,
        label: data.data.curriculum.name,
      });

      await nextTick();
      if (treeRef.value) {
        treeRef.value.setCurrentKey(data.data.curriculum.id);
      }

      await nextTick();
      startModifyMode(data.data.curriculum.id);
    } catch (err) {
      console.log(err);
    }
  };

  let isUpdateCurricula = false;

  const handleUpdateCurriculum = async (nodeData: CurriculumNode, parent_id?: number, position?: number) => {
    try {
      if (isUpdateCurricula) return;
      isUpdateCurricula = true;
      const { data } = await updateCurriculum({
        id: nodeData.id,
        name: nodeData.label,
        parent_id,
        position,
        old_position: oldPosition,
      });

      if (!data.success) throw new Error();

      isUpdateCurricula = false;
    } catch (err) {
      isUpdateCurricula = false;
    }
  };

  const handelDeleteCurriculum = async (nodeData: CurriculumNode) => {
    try {
      const { data } = await deleteCurriculum(nodeData.id);

      if (!data.success) throw new Error();

      if (treeRef.value) {
        treeRef.value.remove(treeRef.value.getNode(nodeData.id));
      }
    } catch (err) {
      console.log(err);
    }
  };
</script>

<style>
  .el-tree-node {
    width: 100%;
    padding: 0.5rem 0rem;
  }
</style>
