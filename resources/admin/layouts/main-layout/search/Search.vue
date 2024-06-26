<template>
  <MenuComponent menu-selector="#kt-search-menu">
    <template #toggle>
      <!--begin::Search-->
      <div
        id="kt_header_search"
        class="header-search d-flex align-items-stretch"
        data-kt-menu-target="#kt-search-menu"
        data-kt-menu-trigger="click"
        data-kt-menu-attach="parent"
        data-kt-menu-placement="bottom-end"
        data-kt-menu-flip="bottom"
      >
        <!--begin::Search toggle-->
        <div id="kt_header_search_toggle" class="d-flex align-items-center">
          <div
            class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px"
          >
            <KTIcon icon-name="magnifier" icon-class="fs-2 fs-md-1" />
          </div>
        </div>
        <!--end::Search toggle-->
      </div>
      <!--end::Search-->
    </template>
    <template #content>
      <!--begin::Menu-->
      <div
        id="kt-search-menu"
        class="menu menu-sub menu-sub-dropdown menu-column p-7 w-325px w-md-375px"
        data-kt-menu="true"
      >
        <!--begin::Wrapper-->
        <div>
          <!--begin::Form-->
          <form class="w-100 position-relative mb-3" autocomplete="off">
            <!--begin::Icon-->
            <KTIcon
              icon-name="magnifier"
              icon-class="fs-2 fs-lg-1 text-gray-500 position-absolute top-50 translate-middle-y ms-0"
            />
            <!--end::Icon-->

            <!--begin::Input-->
            <input
              ref="inputRef"
              v-model="search"
              type="text"
              class="form-control form-control-flush ps-10"
              name="search"
              placeholder="검색..."
              @input="searching"
            />
            <!--end::Input-->

            <!--begin::Spinner-->
            <span v-if="loading" class="position-absolute top-50 end-0 translate-middle-y lh-0 me-1">
              <span class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
            </span>
            <!--end::Spinner-->

            <!--begin::Reset-->
            <span
              v-show="search.length && !loading"
              class="btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0"
              @click="reset()"
            >
              <KTIcon icon-name="cross" icon-class="fs-2 fs-lg-1 me-0" />
            </span>
            <!--end::Reset-->

            <!--begin::Toolbar-->
            <div class="position-absolute top-50 end-0 translate-middle-y">
              <!--begin::Preferences toggle-->
              <div
                v-if="!search && !loading"
                class="btn btn-icon w-20px btn-sm btn-active-color-primary me-1"
                data-bs-toggle="tooltip"
                title="Show search preferences"
                @click="state = 'preferences'"
              >
                <KTIcon icon-name="setting-2" icon-class="fs-1" />
              </div>
              <!--end::Preferences toggle-->
            </div>
            <!--end::Toolbar-->
          </form>
          <!--end::Form-->

          <!--begin::Separator-->
          <div class="separator border-gray-200 mb-6"></div>
          <!--end::Separator-->
          <Results v-if="state === 'results'"></Results>
          <PartialMain v-else-if="state === 'main'"></PartialMain>
          <Empty v-else-if="state === 'empty'"></Empty>
        </div>
        <!--end::Wrapper-->

        <form v-if="state === 'preferences'" class="pt-1">
          <!--begin::Heading-->
          <h3 class="fw-semobold text-dark mb-7">검색 범위</h3>
          <!--end::Heading-->

          <!--begin::Input group-->
          <div class="pb-4 border-bottom">
            <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack">
              <span class="form-check-label text-gray-700 fs-6 fw-semobold ms-0 me-2"> 학생</span>

              <input class="form-check-input" type="checkbox" value="student" checked />
            </label>
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="py-4 border-bottom">
            <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack">
              <span class="form-check-label text-gray-700 fs-6 fw-semobold ms-0 me-2"> 학원</span>
              <input class="form-check-input" type="checkbox" value="academy" checked />
            </label>
          </div>
          <!--end::Input group-->

          <!--begin::Actions-->
          <div class="d-flex justify-content-end pt-7">
            <div class="btn btn-sm btn-light fw-bold btn-active-light-primary me-2" @click="state = 'main'">
              Cancel
            </div>
            <button class="btn btn-sm fw-bold btn-primary">Save Changes</button>
          </div>
          <!--end::Actions-->
        </form>
      </div>
      <!--end::Menu-->
    </template>
  </MenuComponent>
</template>

<script setup lang="ts">
  import { getAssetPath } from '@/admin/core/helpers/assets';
  import { defineComponent, ref } from 'vue';
  import Results from '@/admin/layouts/main-layout/search/partials/Results.vue';
  import PartialMain from '@/admin/layouts/main-layout/search/partials/Main.vue';
  import Empty from '@/admin/layouts/main-layout/search/partials/Empty.vue';
  import MenuComponent from '@/admin/components/menu/MenuComponent.vue';

  /*
   */
  const search = ref<string>('');
  const state = ref<'main' | 'empty' | 'advanced-options' | 'preferences' | 'results'>('main');
  const loading = ref<boolean>(false);
  const inputRef = ref<HTMLInputElement | null>(null);

  const searching = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.value.length <= 1) {
      load('main');
    } else {
      if (target.value.length > 5) {
        load('empty');
        return;
      }
      load('results');
    }
  };

  const load = (current: 'main' | 'empty' | 'advanced-options' | 'preferences' | 'results') => {
    loading.value = true;
    setTimeout(() => {
      state.value = current;
      loading.value = false;
    }, 1000);
  };

  const reset = () => {
    search.value = '';
    state.value = 'main';
  };

  const setState = (curr: 'main' | 'empty' | 'advanced-options' | 'preferences' | 'results') => {
    state.value = curr;
  };
</script>
