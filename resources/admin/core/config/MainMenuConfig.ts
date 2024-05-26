export interface MenuItem {
  heading?: string;
  route?: string;
  params?: any;
  pages?: MenuItem[];
  keenthemesIcon?: string;
  bootstrapIcon?: string;
  sub?: MenuItem[];
  roles?: string[];
  show?: boolean;
}

// icon  https://preview.keenthemes.com/html/metronic/docs/icons/keenicons
export const AdminMenuConfig: MenuItem[] = [
  {
    heading: '본사관리자',
    pages: [
      {
        heading: '대시보드',
        route: 'admin.dashboard',
        keenthemesIcon: 'home',
      },
      {
        heading: '학원관리',
        keenthemesIcon: 'lots-shopping',
        route: 'admin.academies.index',
        show: true,
        sub: [
          {
            heading: '학원목록',
            route: 'admin.academies.index',
            params: {
              filters: {
                status: [0, 1],
              },
            },
          },
        ],
        roles: ['manager', 'cs'],
      },
      {
        heading: '학생관리',
        keenthemesIcon: 'people',
        route: 'admin.students.index',
        roles: ['manager', 'cs'],
        show: true,
        sub: [
          {
            heading: '학생목록',
            route: 'admin.students.index',
          },
          {
            heading: '결제목록',
            route: 'admin.payments.index',
          },
        ],
      },
      {
        heading: '콘텐츠관리',
        keenthemesIcon: 'book-open',
        route: 'admin.curricula.index',
        roles: ['manager'],
        show: true,
        sub: [
          {
            heading: '진단평가관리',
            route: 'admin.tests.index',
          },
          {
            heading: '학습관리',
            route: 'admin.curricula.index',
          },
          {
            heading: '커리큘럼관리',
            route: 'admin.curricula.nested-set',
          },
          {
            heading: '문제관리',
            route: 'admin.questions.index',
          },
        ],
      },
      {
        heading: '게시판관리',
        keenthemesIcon: 'notification-status',
        route: 'admin.board-notices.index',
        roles: ['manager'],
      },
      {
        heading: '환경설정',
        keenthemesIcon: 'setting-4',
        route: 'admin.settings.policy.show',
        roles: ['manager'],
        show: true,
        sub: [
          {
            heading: '이용약관수정',
            route: 'admin.settings.policy.show',
          },
        ],
      },
      {
        heading: '운영자관리',
        keenthemesIcon: 'user-edit',
        route: 'admin.admins.index',
        roles: ['manager'],
      },
    ],
  },
];
