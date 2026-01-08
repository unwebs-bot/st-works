<?php
/**
 * Template Name: UW Board Test Page
 * 
 * 게시판 테스트 페이지 템플릿
 */

get_header();
get_template_part('template-parts/sub-visual');
?>

<?php
// 숏코드가 있는 경우 렌더링 (section.sub-page.board 래퍼 포함)
while (have_posts()) {
  the_post();
  the_content();
}

// 만약 콘텐츠가 없다면 기본 공지사항 게시판 표시
if (!has_shortcode(get_the_content(), 'uw_board')) {
  echo do_shortcode('[uw_board name="notice"]');
}
?>

<?php get_footer(); ?>