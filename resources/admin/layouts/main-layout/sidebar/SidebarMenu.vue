<template>
  <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <div
      id="kt_app_sidebar_menu_wrapper"
      class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
      data-kt-scroll="true"
      data-kt-scroll-activate="true"
      data-kt-scroll-height="auto"
      data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
      data-kt-scroll-wrappers="#kt_app_sidebar_menu"
      data-kt-scroll-offset="5px"
      data-kt-scroll-save-state="true"
    >
      <div id="#kt_app_sidebar_menu" class="menu menu-column menu-rounded menu-sub-indention px-3" data-kt-menu="true">
        <template v-for="(item, i) in menuConfig" :key="i">
          <div v-if="item.heading" class="menu-item pt-5">
            <div class="menu-content">
              <span class="menu-heading fw-bold text-uppercase fs-7">
                {{ item.heading }}
              </span>
            </div>
          </div>
          <template v-for="(menuItem, j) in item.pages" :key="j">
            <template v-if="checkAllowedRoles(menuItem?.roles)">
              <template v-if="menuItem.heading && !menuItem.sub">
                <div class="menu-item">
                  <Link
                    v-if="menuItem.route && route().has(menuItem.route)"
                    class="menu-link"
                    :class="{
                      active: menuItem.route == route().current() || menuItem.route == $page.props.page.active,
                    }"
                    :href="route(menuItem.route, menuItem?.params)"
                  >
                    <span v-if="menuItem.keenthemesIcon || menuItem.bootstrapIcon" class="menu-icon">
                      <i v-if="sidebarMenuIcons === 'bootstrap'" :class="menuItem.bootstrapIcon" class="bi fs-3"></i>
                      <KTIcon
                        v-else-if="sidebarMenuIcons === 'keenthemes'"
                        :icon-name="menuItem.keenthemesIcon ?? ''"
                        icon-class="fs-2"
                      />
                    </span>
                    <span class="menu-title">{{ menuItem.heading }}</span>
                  </Link>
                </div>
              </template>
              <div
                v-if="menuItem.sub && menuItem.route && menuItem.heading"
                class="menu-item menu-accordion"
                data-kt-menu-sub="accordion"
                :data-kt-menu-trigger="`${!menuItem.show ? 'click' : ''}`"
                :class="{
                  show: menuItem.show,
                }"
              >
                <span class="menu-link">
                  <span v-if="menuItem.keenthemesIcon || menuItem.bootstrapIcon" class="menu-icon">
                    <i v-if="sidebarMenuIcons === 'bootstrap'" :class="menuItem.bootstrapIcon" class="bi fs-3"></i>
                    <KTIcon
                      v-else-if="sidebarMenuIcons === 'keenthemes' && menuItem.keenthemesIcon"
                      :icon-name="menuItem.keenthemesIcon"
                      icon-class="fs-2"
                    />
                  </span>
                  <span class="menu-title">{{ menuItem.heading }}</span>
                  <span v-if="!menuItem.show" class="menu-arrow"></span>
                </span>
                <div class="menu-sub menu-sub-accordion">
                  <template v-for="(item2, k) in menuItem.sub" :key="k">
                    <div v-if="item2.heading" class="menu-item">
                      <Link
                        v-if="item2.route && route().has(item2.route)"
                        class="menu-link"
                        :class="{ active: item2.route == route().current() || item2.route == $page.props.page.active }"
                        :href="route(item2.route, item2?.params)"
                      >
                        <span class="menu-bullet">
                          <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">{{ item2.heading }}</span>
                      </Link>
                    </div>
                    <div
                      v-if="item2.sub && item2.route"
                      class="menu-item menu-accordion"
                      data-kt-menu-sub="accordion"
                      data-kt-menu-trigger="click"
                    >
                      <span class="menu-link">
                        <span class="menu-bullet">
                          <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">{{ item2.heading }}</span>
                        <span class="menu-arrow"></span>
                      </span>
                      <div class="menu-sub menu-sub-accordion">
                        <template v-for="(item3, k) in item2.sub" :key="k">
                          <div v-if="item3.heading" class="menu-item">
                            <Link v-if="item3.route" class="menu-link" active-class="active" :href="item3.route">
                              <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                              </span>
                              <span class="menu-title">{{ item3.heading }}</span>
                            </Link>
                          </div>
                        </template>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
            </template>
          </template>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { onMounted, ref, PropType } from 'vue';
  import { MenuItem } from '@/admin/core/config/MainMenuConfig';
  import { sidebarMenuIcons } from '@/admin/core/helpers/config';
  import { Link, usePage } from '@inertiajs/vue3';

  const scrollElRef = ref<null | HTMLElement>(null);

  defineProps({
    menuConfig: {
      type: Array as PropType<MenuItem[]>,
      default: null,
    },
  });

  const pageData = usePage();

  const checkAllowedRoles = (roles: string[] | undefined): boolean => {
    // 지정된 roles가 없으면 모든 사용자 허용
    if (!roles) return true;

    let result = false;

    pageData.props.auth.user.role_names.forEach((value) => {
      result = value === 'super' || roles.includes(value);
    });

    return result;
  };

  onMounted(() => {
    if (scrollElRef.value) {
      scrollElRef.value.scrollTop = 0;
    }
    const active = document.querySelector('#kt_app_sidebar_menu_wrapper .menu-link.active');
    if (active) {
      active.closest('div.menu-accordion')?.classList.add('show');
    }
  });
</script>
