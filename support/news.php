<?php
/**
 * Template Name: Support News
 * 
 * 뉴스 게시판 (카드형 Style 03)
 */

get_header();
get_template_part('template-parts/sub-visual');
?>

<?php echo do_shortcode('[uw_board name="news"]'); ?>

<?php get_footer(); ?>