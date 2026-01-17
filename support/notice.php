<?php
/**
 * Template Name: Support Notice
 * 
 * 공지사항 게시판 (리스트형 Style 01)
 */

get_header();
get_template_part('template-parts/sub-visual');
?>

<?php echo do_shortcode('[uw_board name="notice"]'); ?>

<?php get_footer(); ?>