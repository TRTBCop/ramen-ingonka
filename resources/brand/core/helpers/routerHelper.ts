/**
 * 로그인 페이지
 */
export function goLoginPage() {
  // window.location.href = route('app.auth.create');

  // 임시로 준비중 페이지로
  goComingSoonPage();
}

/**
 * 준비중
 */
export function goComingSoonPage() {
  window.location.href = route('app.coming-soon.show');
}
