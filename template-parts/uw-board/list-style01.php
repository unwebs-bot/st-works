<?php
/**
 * UW Board Style 01 (Table Row) Template
 * 
 * Vars available:
 * - $is_pinned
 * - $num
 * - $slug
 */

$post_id = get_the_ID();
extract($args);
$views = get_post_meta($post_id, '_uw_views', true) ?: 0;
$is_new = (time() - get_the_time('U')) < 86400;
$attachments = get_post_meta($post_id, '_uw_attachments', true);
$has_attachment = !empty($attachments);
$category = get_post_meta($post_id, '_uw_category', true);

global $wp;
$single_url = add_query_arg(array('view' => 'single', 'id' => $post_id), home_url($wp->request));
?>
<tr class="<?php echo $is_pinned ? 'uw-pinned' : ''; ?>">
  <td class="col-num">
    <?php echo $is_pinned ? '<span class="uw-notice-badge">공지</span>' : $num; ?>
  </td>
  <td class="col-title">
    <a href="<?php echo esc_url($single_url); ?>">
      <?php if ($category): ?>
        <span class="uw-category-badge">[<?php echo esc_html($category); ?>]</span>
      <?php endif; ?>
      <?php the_title(); ?>
      <?php if ($is_new): ?>
        <span class="uw-new-badge">N</span>
      <?php endif; ?>
      <?php if ($has_attachment): ?>
        <i class="xi-attachment uw-file-icon" title="첨부파일"></i>
      <?php endif; ?>
    </a>
  </td>
  <td class="col-author">
    <?php echo UW_Board_CPT::get_author_display_name($post_id); ?>
  </td>
  <td class="col-date">
    <?php echo get_the_date('Y.m.d'); ?>
  </td>
  <td class="col-views">
    <?php echo number_format($views); ?>
  </td>
</tr>