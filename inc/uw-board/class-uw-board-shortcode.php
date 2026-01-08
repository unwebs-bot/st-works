<?php
/**
 * UW Board Shortcode Handler
 * 
 * 프론트엔드 숏코드 처리
 * [uw_board name="게시판슬러그"]
 * 
 * @package St-works
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
  exit;
}

class UW_Board_Shortcode
{

  private static $instance = null;

  public static function get_instance()
  {
    if (null === self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  private function __construct()
  {
    add_shortcode('uw_board', array($this, 'render_shortcode'));
    add_shortcode('latest_posts', array($this, 'render_latest_posts'));
    add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    add_action('wp_ajax_uw_board_front_save', array($this, 'ajax_front_save'));
    add_action('wp_ajax_nopriv_uw_board_front_save', array($this, 'ajax_front_save'));
    add_action('wp_ajax_uw_board_verify_password', array($this, 'ajax_verify_password'));
    add_action('wp_ajax_nopriv_uw_board_verify_password', array($this, 'ajax_verify_password'));
    // 프론트엔드 이미지 업로드 핸들러
    add_action('wp_ajax_uw_board_front_upload_image', array($this, 'ajax_front_upload_image'));
    add_action('wp_ajax_nopriv_uw_board_front_upload_image', array($this, 'ajax_front_upload_image'));
    // 프론트엔드 글 삭제
    add_action('wp_ajax_uw_board_front_delete', array($this, 'ajax_front_delete'));
    add_action('wp_ajax_nopriv_uw_board_front_delete', array($this, 'ajax_front_delete'));
  }



  /**
   * 프론트엔드 에셋 로드 (숏코드 렌더링 시 호출)
   */
  public function enqueue_assets()
  {
    // 이미 로드되었는지 체크
    static $assets_loaded = false;
    if ($assets_loaded) {
      return;
    }
    $assets_loaded = true;

    // Xeicon (아이콘 폰트) - GitHub 기반 jsDelivr CDN
    wp_enqueue_style('xeicon', 'https://cdn.jsdelivr.net/gh/xpressengine/XEIcon@2.3.3/xeicon.min.css');

    // Summernote (글쓰기 시)
    wp_enqueue_style('summernote', 'https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css');
    wp_enqueue_script('summernote', 'https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js', array('jquery'), '0.8.18', true);

    // Custom styles - 캐시 버스팅
    wp_enqueue_style('uw-board', get_theme_file_uri('/assets/css/uw-board.css'), array(), '1.0.7');
    wp_enqueue_script('uw-board', get_theme_file_uri('/assets/js/uw-board.js'), array('jquery'), '1.0.1', true);

    wp_localize_script('uw-board', 'uwBoard', array(
      'ajaxUrl' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('uw_board_front_nonce'),
    ));
  }

  /**
   * 숏코드 렌더링
   */
  public function render_shortcode($atts)
  {
    // 에셋 로드 (CSS/JS)
    $this->enqueue_assets();

    $atts = shortcode_atts(array(
      'name' => '',
    ), $atts);

    $slug = sanitize_key($atts['name']);

    if (empty($slug)) {
      return '<p class="uw-board-error">게시판 이름이 지정되지 않았습니다.</p>';
    }

    $board = $this->get_board_settings($slug);

    if (!$board) {
      return '<p class="uw-board-error">존재하지 않는 게시판입니다.</p>';
    }

    // 읽기 권한 체크
    if ($board['read_permission'] === 'logged_in' && !is_user_logged_in()) {
      return '<p class="uw-board-error">로그인이 필요합니다.</p>';
    }
    // 검색 파라미터 처리
    $search_type = isset($_GET['search_type']) ? sanitize_key($_GET['search_type']) : 'title';
    $search = isset($_GET['board_search']) ? sanitize_text_field($_GET['board_search']) : '';

    ob_start();
    ?>
    <section class="sub-page board">
      <div class="uw-board-container" data-board="<?php echo esc_attr($slug); ?>">
        <?php


        $view = isset($_GET['view']) ? sanitize_key($_GET['view']) : 'list';
        $post_id = isset($_GET['id']) ? absint($_GET['id']) : 0;

        switch ($view) {
          case 'single':
            $this->render_single($slug, $board, $post_id);
            break;
          case 'write':
            $this->render_write_form($slug, $board, $post_id);
            break;
          case 'edit':
            $this->render_write_form($slug, $board, $post_id);
            break;
          default:
            $this->render_list($slug, $board);
            break;
        }

        echo '</div>';
        echo '</section>';

        return ob_get_clean();
  }

  /**
   * 게시판 설정 가져오기
   */
  private function get_board_settings($slug)
  {
    $boards = get_option('uw_board_settings', array());
    return isset($boards[$slug]) ? $boards[$slug] : null;
  }

  /**
   * 목록 뷰 렌더링
   */
  private function render_list($slug, $board)
  {
    // Pretty Permalinks (/page/2/) 및 쿼리스트링 (?paged=2) 모두 지원
    $paged = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
    $paged = max(1, absint($paged));
    $search = isset($_GET['board_search']) ? sanitize_text_field($_GET['board_search']) : '';
    $search_type = isset($_GET['search_type']) ? sanitize_key($_GET['search_type']) : 'title';

    $per_page = $board['per_page'] ?? 10;

    // 상단 고정글 쿼리 (1페이지에서만 노출)
    $pinned_query = null;
    if ($paged <= 1) {
      $pinned_args = array(
        'post_type' => 'uw_board',
        'posts_per_page' => -1,
        'meta_key' => '_uw_is_pinned',
        'meta_value' => '1',
        'tax_query' => array(
          array(
            'taxonomy' => 'uw_board_type',
            'field' => 'slug',
            'terms' => $slug,
          ),
        ),
      );
      $pinned_query = new WP_Query($pinned_args);
    }

    // 일반글 쿼리
    $args = array(
      'post_type' => 'uw_board',
      'posts_per_page' => $per_page,
      'paged' => $paged,
      'orderby' => 'date',
      'order' => 'DESC',
      'tax_query' => array(
        array(
          'taxonomy' => 'uw_board_type',
          'field' => 'slug',
          'terms' => $slug,
        ),
      ),
      'meta_query' => array(
        'relation' => 'OR',
        array(
          'key' => '_uw_is_pinned',
          'compare' => 'NOT EXISTS',
        ),
        array(
          'key' => '_uw_is_pinned',
          'value' => '1',
          'compare' => '!=',
        ),
      ),
    );

    // 검색
    if ($search) {
      if ($search_type === 'title') {
        add_filter('posts_search', array($this, 'filter_search_title'), 10, 2);
        $args['s'] = $search;
      } elseif ($search_type === 'content') {
        add_filter('posts_search', array($this, 'filter_search_content'), 10, 2);
        $args['s'] = $search;
      } elseif ($search_type === 'author') {
        $user = get_user_by('login', $search) ?: get_user_by('slug', $search) ?: get_user_by('nicename', $search);
        if ($user) {
          $args['author'] = $user->ID;
        } else {
          // 비회원 작성자 검색
          $args['meta_query'][] = array(
            'key' => '_uw_guest_name',
            'value' => $search,
            'compare' => 'LIKE'
          );
        }
      } else {
        // 전체 (기본)
        $args['s'] = $search;
      }
    }

    $query = new WP_Query($args);

    // 필터 해제
    if ($search) {
      remove_filter('posts_search', array($this, 'filter_search_title'), 10);
      remove_filter('posts_search', array($this, 'filter_search_content'), 10);
    }
    $total = $query->found_posts;
    $total_pages = $query->max_num_pages;

    // 현재 페이지 URL에서 paged 파라미터 제외한 기본 URL 생성
    $base_url = remove_query_arg('paged');
    ?>

        <div class="uw-board-header">
          <span class="uw-total">전체 <strong>
              <?php echo $total + ($pinned_query ? $pinned_query->found_posts : 0); ?>
            </strong>건</span>

          <form class="uw-search-form" method="get" action="<?php echo esc_url($base_url); ?>">
            <div class="uw-search-filter">
              <button type="button" class="uw-filter-trigger">
                <span class="uw-filter-text"><?php
                $type_labels = array('title' => '제목', 'content' => '내용', 'author' => '작성자');
                echo isset($type_labels[$search_type]) ? $type_labels[$search_type] : '제목';
                ?></span>
              </button>
              <ul class="uw-filter-dropdown">
                <li><button type="button" data-value="title" <?php echo $search_type === 'title' ? 'class="active"' : ''; ?>>제목</button></li>
                <li><button type="button" data-value="content" <?php echo $search_type === 'content' ? 'class="active"' : ''; ?>>내용</button></li>
                <li><button type="button" data-value="author" <?php echo $search_type === 'author' ? 'class="active"' : ''; ?>>작성자</button></li>
              </ul>
              <input type="hidden" name="search_type" value="<?php echo esc_attr($search_type); ?>">
            </div>
            <div class="uw-search-input-wrap">
              <input type="text" name="board_search" value="<?php echo esc_attr($search); ?>" placeholder="검색어를 입력해 주세요.">
              <button type="submit" class="uw-search-btn"></button>
            </div>
          </form>
        </div>

        <table class="uw-board-table">
          <thead>
            <tr>
              <th class="col-num">번호</th>
              <th class="col-title">제목</th>
              <th class="col-author">작성자</th>
              <th class="col-date">등록일</th>
              <th class="col-views">조회</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // 상단 고정글
            if ($pinned_query && $pinned_query->have_posts()) {
              while ($pinned_query->have_posts()):
                $pinned_query->the_post();
                $this->render_list_row($slug, true);
              endwhile;
              wp_reset_postdata();
            }

            // 일반글
            $num = $total - (($paged - 1) * $per_page);
            while ($query->have_posts()):
              $query->the_post();
              $this->render_list_row($slug, false, $num--);
            endwhile;
            wp_reset_postdata();
            ?>

            <?php
            $has_posts = ($pinned_query && $pinned_query->have_posts()) || $query->have_posts();
            if (!$has_posts): ?>
              <tr>
                <td colspan="5" class="uw-no-posts">등록된 글이 없습니다.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>

        <div class="uw-board-footer">
          <div class="uw-pagination">
            <?php
            if ($total_pages > 1):
              // 맨앞 (항상 표시)
              if ($paged > 1) {
                echo '<a href="' . esc_url(get_pagenum_link(1)) . '">«</a>';
                echo '<a href="' . esc_url(get_pagenum_link($paged - 1)) . '">‹</a>';
              } else {
                echo '<span class="disabled">«</span>';
                echo '<span class="disabled">‹</span>';
              }

              // 숫자
              for ($i = max(1, $paged - 2); $i <= min($total_pages, $paged + 2); $i++) {
                $class = ($i === $paged) ? 'current' : '';
                echo '<a href="' . esc_url(get_pagenum_link($i)) . '" class="' . $class . '">' . $i . '</a>';
              }

              // 맨끝 (항상 표시)
              if ($paged < $total_pages) {
                echo '<a href="' . esc_url(get_pagenum_link($paged + 1)) . '">›</a>';
                echo '<a href="' . esc_url(get_pagenum_link($total_pages)) . '">»</a>';
              } else {
                echo '<span class="disabled">›</span>';
                echo '<span class="disabled">»</span>';
              }
            endif;
            ?>
          </div>
          <?php if ($this->can_write($board)): ?>
            <a href="<?php echo esc_url(add_query_arg('view', 'write', $base_url)); ?>" class="uw-btn uw-btn-write">글쓰기</a>
          <?php endif; ?>
        </div>

        <?php
  }

  /**
   * 목록 행 렌더링
   */
  private function render_list_row($slug, $is_pinned = false, $num = 0)
  {
    $post_id = get_the_ID();
    $views = get_post_meta($post_id, '_uw_views', true) ?: 0;
    $is_new = (time() - get_the_time('U')) < 86400;
    $attachments = get_post_meta($post_id, '_uw_attachments', true);
    $has_attachment = !empty($attachments); // 실제 첨부파일만 체크 (썸네일 제외)

    global $wp;
    $single_url = add_query_arg(array('view' => 'single', 'id' => $post_id), home_url($wp->request));
    ?>
        <tr class="<?php echo $is_pinned ? 'uw-pinned' : ''; ?>">
          <td class="col-num">
            <?php echo $is_pinned ? '<span class="uw-notice-badge">공지</span>' : $num; ?>
          </td>
          <td class="col-title">
            <a href="<?php echo esc_url($single_url); ?>">
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
            <?php
            $guest_name = get_post_meta($post_id, '_uw_guest_name', true);
            if ($guest_name) {
              echo esc_html($guest_name);
            } else {
              $author_id = get_the_author_meta('ID');
              $user = get_userdata($author_id);
              echo ($user && in_array('administrator', $user->roles)) ? '관리자' : get_the_author();
            }
            ?>
          </td>
          <td class="col-date">
            <?php echo get_the_date('Y.m.d'); ?>
          </td>
          <td class="col-views">
            <?php echo number_format($views); ?>
          </td>
        </tr>
        <?php
  }

  /**
   * 상세 뷰 렌더링
   */
  private function render_single($slug, $board, $post_id)
  {
    $post = get_post($post_id);

    if (!$post || $post->post_type !== 'uw_board') {
      echo '<p class="uw-board-error">글을 찾을 수 없습니다.</p>';
      return;
    }

    // 조회수 증가
    $views = get_post_meta($post_id, '_uw_views', true) ?: 0;
    update_post_meta($post_id, '_uw_views', $views + 1);

    $attachments = get_post_meta($post_id, '_uw_attachments', true);
    global $wp;
    $base_url = home_url($wp->request);

    // 이전/다음 글
    $prev_post = $this->get_adjacent_post($slug, $post_id, 'prev');
    $next_post = $this->get_adjacent_post($slug, $post_id, 'next');
    ?>

        <article class="uw-single-post">
          <header class="uw-post-header">
            <h2 class="uw-post-title">
              <?php echo esc_html($post->post_title); ?>
            </h2>
            <div class="uw-post-meta">
              <span>작성자:
                <?php
                $guest_name = get_post_meta($post_id, '_uw_guest_name', true);
                if ($guest_name) {
                  echo esc_html($guest_name);
                } else {
                  $author_user = get_userdata($post->post_author);
                  echo ($author_user && in_array('administrator', $author_user->roles)) ? '관리자' : get_the_author_meta('display_name', $post->post_author);
                }
                ?>
              </span>
              <span>등록일:
                <?php echo get_the_date('Y.m.d', $post); ?>
              </span>
              <span>조회:
                <?php echo number_format($views + 1); ?>
              </span>
            </div>
          </header>

          <div class="uw-post-content">
            <?php echo apply_filters('the_content', $post->post_content); ?>
          </div>

          <?php if (!empty($attachments)): ?>
            <div class="uw-post-attachments">
              <ul>
                <?php
                $att_index = 1;
                foreach ($attachments as $att_id):
                  $file_url = wp_get_attachment_url($att_id);
                  $file_name = basename(get_attached_file($att_id));
                  if ($file_url):
                    ?>
                    <li>
                      <span class="uw-att-label">첨부파일 #<?php echo $att_index++; ?></span>
                      <a href="<?php echo esc_url($file_url); ?>" download>
                        <?php echo esc_html($file_name); ?>
                      </a>
                    </li>
                  <?php endif; endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <nav class="uw-post-navigation">
            <?php if ($prev_post): ?>
              <a href="<?php echo esc_url(add_query_arg(array('view' => 'single', 'id' => $prev_post->ID), $base_url)); ?>"
                class="uw-nav-prev">
                <span>이전 글</span>
                <strong>
                  <?php echo esc_html($prev_post->post_title); ?>
                </strong>
              </a>
            <?php else: ?>
              <div class="uw-nav-prev uw-nav-empty">
                <span>이전 글</span>
                <strong>이전 글이 없습니다.</strong>
              </div>
            <?php endif; ?>

            <?php if ($next_post): ?>
              <a href="<?php echo esc_url(add_query_arg(array('view' => 'single', 'id' => $next_post->ID), $base_url)); ?>"
                class="uw-nav-next">
                <span>다음 글</span>
                <strong>
                  <?php echo esc_html($next_post->post_title); ?>
                </strong>
              </a>
            <?php else: ?>
              <div class="uw-nav-next uw-nav-empty">
                <span>다음 글</span>
                <strong>다음 글이 없습니다.</strong>
              </div>
            <?php endif; ?>
          </nav>

          <div class="uw-post-actions">
            <?php $list_url = remove_query_arg(array('view', 'id', 'token'), $base_url); ?>
            <a href="<?php echo esc_url($list_url); ?>" class="uw-btn">목록</a>
            <?php
            // 비회원 작성 글에 대한 수정/삭제 버튼 노출 (비밀번호 검증 필요)
            $has_password = get_post_meta($post_id, '_uw_password', true);
            $is_guest_writable = $board['write_permission'] === 'all';

            if ($is_guest_writable && $has_password):
              $clean_base = remove_query_arg(array('view', 'id', 'token'), $base_url);
              $edit_url = add_query_arg(array('view' => 'write', 'id' => $post_id), $clean_base);
              ?>
              <a href="<?php echo esc_url($edit_url); ?>" class="uw-btn uw-verify-password"
                data-post-id="<?php echo esc_attr($post_id); ?>" data-action="edit">수정</a>
              <button type="button" class="uw-btn uw-verify-password" data-post-id="<?php echo esc_attr($post_id); ?>"
                data-action="delete" data-board-slug="<?php echo esc_attr($slug); ?>">삭제</button>
            <?php endif; ?>
          </div>
        </article>

        <?php
  }

  /**
   * 글쓰기 폼 렌더링
   */
  private function render_write_form($slug, $board, $post_id = 0)
  {
    // 쓰기 권한 체크
    if (!$this->can_write($board)) {
      echo '<p class="uw-board-error">글쓰기 권한이 없습니다.</p>';
      return;
    }

    $post = $post_id ? get_post($post_id) : null;
    $is_edit = !empty($post);

    // 수정 시 권한 체크
    if ($is_edit && !$this->can_edit_post($post, $board)) {
      echo '<p class="uw-board-error">수정 권한이 없습니다.</p>';
      return;
    }

    $title = $post ? $post->post_title : '';
    $content = $post ? $post->post_content : '';
    $attachments = $post ? get_post_meta($post_id, '_uw_attachments', true) : array();

    $require_password = $board['write_permission'] === 'all' && !is_user_logged_in();
    global $wp;
    $base_url = home_url($wp->request);
    ?>

        <form class="uw-write-form" id="uw-write-form">
          <input type="hidden" name="board_slug" value="<?php echo esc_attr($slug); ?>">
          <input type="hidden" name="post_id" value="<?php echo esc_attr($post_id); ?>">
          <?php if (isset($_REQUEST['token'])): ?>
            <input type="hidden" name="uw_token" value="<?php echo esc_attr($_REQUEST['token']); ?>">
          <?php endif; ?>

          <div class="uw-form-field">
            <label for="post_title">제목 *</label>
            <input type="text" id="post_title" name="title" value="<?php echo esc_attr($title); ?>" required>
          </div>

          <?php if ($require_password): ?>
            <div class="uw-form-field">
              <label for="guest_name">작성자 *</label>
              <input type="text" id="guest_name" name="guest_name"
                value="<?php echo esc_attr($post ? get_post_meta($post_id, '_uw_guest_name', true) : ''); ?>" required>
            </div>
            <div class="uw-form-field">
              <label for="post_password">비밀번호 *</label>
              <input type="password" id="post_password" name="password" required>
              <p class="uw-field-desc">수정/삭제 시 필요합니다.</p>
            </div>
          <?php endif; ?>

          <div class="uw-form-field">
            <label>본문</label>
            <textarea id="uw-editor" name="content"><?php echo esc_textarea($content); ?></textarea>
          </div>

          <div class="uw-form-field">
            <label>첨부파일 (최대 3개)</label>
            <div class="uw-file-upload">
              <?php for ($i = 0; $i < 3; $i++):
                $att_id = isset($attachments[$i]) ? $attachments[$i] : '';
                $att_name = $att_id ? basename(get_attached_file($att_id)) : '';
                ?>
                <div class="uw-file-slot">
                  <input type="file" name="files[]" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.hwp,.zip">
                  <input type="hidden" name="existing_files[]" value="<?php echo esc_attr($att_id); ?>">
                  <?php if ($att_name): ?>
                    <span class="uw-existing-file">
                      <?php echo esc_html($att_name); ?>
                    </span>
                  <?php endif; ?>
                </div>
              <?php endfor; ?>
            </div>
          </div>

          <?php if (!empty($board['require_privacy'])): ?>
            <div class="uw-form-field uw-privacy-field">
              <label>
                <input type="checkbox" name="privacy_agree" required>
                개인정보 수집 및 이용에 동의합니다. *
              </label>
            </div>
          <?php endif; ?>

          <div class="uw-form-actions">
            <a href="<?php echo esc_url($base_url); ?>" class="uw-btn">목록으로</a>
            <button type="submit" class="uw-btn uw-btn-primary">
              <?php echo $is_edit ? '저장하기' : '작성하기'; ?>
            </button>
          </div>
        </form>

        <?php
  }

  /**
   * 쓰기 권한 체크
   */
  private function can_write($board)
  {
    if ($board['write_permission'] === 'logged_in') {
      return is_user_logged_in();
    }
    return true; // all
  }

  /**
   * 수정 권한 체크
   */
  private function can_edit_post($post, $board)
  {
    if (current_user_can('manage_options')) {
      return true;
    }

    if (is_user_logged_in() && get_current_user_id() === (int) $post->post_author) {
      return true;
    }

    // 비회원 글 검증 (토큰 방식)
    if ($board['write_permission'] === 'all') {
      // 1. URL 파라미터 또는 POST 데이터에서 토큰 확인
      $token = isset($_REQUEST['token']) ? $_REQUEST['token'] : (isset($_POST['uw_token']) ? $_POST['uw_token'] : '');

      if ($token && get_transient('uw_board_auth_' . $post->ID . '_' . $token)) {
        return true;
      }
    }

    return false;
  }

  /**
   * 이전/다음 글 조회
   */
  private function get_adjacent_post($slug, $post_id, $direction = 'prev')
  {
    global $wpdb;

    $post = get_post($post_id);
    $op = $direction === 'prev' ? '<' : '>';
    $order = $direction === 'prev' ? 'DESC' : 'ASC';

    $query = $wpdb->prepare("
            SELECT p.ID, p.post_title
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
            INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            INNER JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
            WHERE p.post_type = 'uw_board'
            AND p.post_status = 'publish'
            AND t.slug = %s
            AND p.post_date {$op} %s
            ORDER BY p.post_date {$order}
            LIMIT 1
        ", $slug, $post->post_date);

    return $wpdb->get_row($query);
  }

  /**
   * AJAX: 프론트엔드 글 저장
   */
  public function ajax_front_save()
  {
    check_ajax_referer('uw_board_front_nonce', 'nonce');

    $board_slug = sanitize_key($_POST['board_slug']);
    $post_id = absint($_POST['post_id']);
    $board = $this->get_board_settings($board_slug);

    if (!$board) {
      wp_send_json_error('게시판을 찾을 수 없습니다.');
    }

    // 권한 체크
    if (!$this->can_write($board)) {
      wp_send_json_error('권한이 없습니다.');
    }

    // 입력값 유효성 검사
    $title = sanitize_text_field($_POST['title']);
    $content = wp_kses_post($_POST['content']);

    if (empty($title)) {
      wp_send_json_error('제목을 입력해주세요.');
    }

    if (empty(strip_tags($content))) {
      wp_send_json_error('내용을 입력해주세요.');
    }

    $post_data = array(
      'post_title' => $title,
      'post_content' => $content,
      'post_type' => 'uw_board',
      'post_status' => 'publish',
    );

    if (!is_user_logged_in()) {
      $post_data['post_author'] = 0;
    }

    if ($post_id) {
      $post_data['ID'] = $post_id;
      wp_update_post($post_data);
    } else {
      $post_id = wp_insert_post($post_data);
      wp_set_object_terms($post_id, $board_slug, 'uw_board_type');
    }

    // 비회원 작성자명 저장
    if (!empty($_POST['guest_name'])) {
      update_post_meta($post_id, '_uw_guest_name', sanitize_text_field($_POST['guest_name']));
    }

    // 비회원 비밀번호 저장
    if (!empty($_POST['password'])) {
      update_post_meta($post_id, '_uw_password', wp_hash_password($_POST['password']));
    }

    // 첨부파일 처리
    $attachments = array();

    // 기존 파일 유지
    if (!empty($_POST['existing_files'])) {
      foreach ($_POST['existing_files'] as $existing_id) {
        if (!empty($existing_id)) {
          $attachments[] = absint($existing_id);
        }
      }
    }

    // 새 파일 업로드
    if (!empty($_FILES['files'])) {
      require_once(ABSPATH . 'wp-admin/includes/image.php');
      require_once(ABSPATH . 'wp-admin/includes/file.php');
      require_once(ABSPATH . 'wp-admin/includes/media.php');

      foreach ($_FILES['files']['name'] as $key => $value) {
        if ($_FILES['files']['error'][$key] === UPLOAD_ERR_OK && !empty($_FILES['files']['name'][$key])) {
          $file = array(
            'name' => $_FILES['files']['name'][$key],
            'type' => $_FILES['files']['type'][$key],
            'tmp_name' => $_FILES['files']['tmp_name'][$key],
            'error' => $_FILES['files']['error'][$key],
            'size' => $_FILES['files']['size'][$key],
          );
          $_FILES['upload_file'] = $file;
          $attachment_id = media_handle_upload('upload_file', $post_id);
          if (!is_wp_error($attachment_id)) {
            $attachments[] = $attachment_id;
          }
        }
      }
    }

    // 첨부파일 메타 저장
    update_post_meta($post_id, '_uw_attachments', array_slice($attachments, 0, 3)); // 최대 3개

    // 게시판 슬러그 기반 리다이렉트 URL 생성
    $board_page_url = get_permalink();
    if (empty($board_page_url) || $board_page_url === home_url('/')) {
      // AJAX 컨텍스트에서는 Referer 사용
      $referer = wp_get_referer();
      $board_page_url = $referer ? remove_query_arg(array('view', 'id', 'token'), $referer) : home_url('/' . $board_slug . '/');
    }

    wp_send_json_success(array(
      'message' => '저장되었습니다.',
      'redirect' => add_query_arg(array('view' => 'single', 'id' => $post_id), $board_page_url),
    ));
  }

  /**
   * AJAX: 비밀번호 검증
   */
  public function ajax_verify_password()
  {
    check_ajax_referer('uw_board_front_nonce', 'nonce');

    $post_id = absint($_POST['post_id']);
    $password = $_POST['password'];

    $stored_hash = get_post_meta($post_id, '_uw_password', true);

    if ($stored_hash && wp_check_password($password, $stored_hash)) {
      // 보안 토큰 생성 (1시간 유효)
      $token = wp_generate_password(32, false);
      set_transient('uw_board_auth_' . $post_id . '_' . $token, 1, HOUR_IN_SECONDS);

      wp_send_json_success(array(
        'token' => $token
      ));
    }

    wp_send_json_error('비밀번호가 일치하지 않습니다.');
  }

  /**
   * AJAX: 프론트엔드 이미지 업로드 (Summernote 에디터용)
   */
  public function ajax_front_upload_image()
  {
    check_ajax_referer('uw_board_front_nonce', 'nonce');

    if (empty($_FILES['file'])) {
      wp_send_json_error('파일이 없습니다.');
    }

    $file = $_FILES['file'];

    // 1. 파일 크기 검사 (2MB)
    if ($file['size'] > 2 * 1024 * 1024) {
      wp_send_json_error('이미지 크기는 2MB를 초과할 수 없습니다.');
    }

    // 2. 파일 타입 검사 (MIME type)
    $allowed_types = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
    $file_type = $file['type'];

    // PHP >= 5.3 fileinfo extension 권장
    if (function_exists('finfo_open')) {
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $file_type = finfo_file($finfo, $file['tmp_name']);
      finfo_close($finfo);
    }

    if (!in_array($file_type, $allowed_types)) {
      wp_send_json_error('허용되지 않는 파일 형식입니다. (JPG, PNG, GIF, WEBP만 가능)');
    }

    // WordPress 미디어 라이브러리에 업로드
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $attachment_id = media_handle_upload('file', 0);

    if (is_wp_error($attachment_id)) {
      wp_send_json_error($attachment_id->get_error_message());
    }

    wp_send_json_success(array(
      'url' => wp_get_attachment_url($attachment_id),
      'id' => $attachment_id,
    ));
  }


  /**
   * AJAX: 프론트엔드 글 삭제
   */
  public function ajax_front_delete()
  {
    check_ajax_referer('uw_board_front_nonce', 'nonce');

    $post_id = absint($_POST['post_id']);
    $board_slug = sanitize_key($_POST['board_slug']);
    $board = $this->get_board_settings($board_slug);

    if (!$board) {
      wp_send_json_error('게시판을 찾을 수 없습니다.');
    }

    $post = get_post($post_id);
    if (!$post) {
      wp_send_json_error('글을 찾을 수 없습니다.');
    }

    // 권한 체크 (can_edit_post는 토큰 검증 포함)
    if (!$this->can_edit_post($post, $board)) {
      wp_send_json_error('삭제 권한이 없습니다.');
    }

    // 삭제 실행
    $deleted = wp_delete_post($post_id, true); // 강제 삭제 (휴지통 X)

    if ($deleted) {
      // 목록으로 이동 - Referer 사용
      $referer = wp_get_referer();
      $redirect_url = $referer ? remove_query_arg(array('view', 'id', 'token'), $referer) : home_url('/' . $board_slug . '/');
      wp_send_json_success(array(
        'message' => '삭제되었습니다.',
        'redirect' => $redirect_url
      ));
    } else {
      wp_send_json_error('삭제에 실패했습니다.');
    }
  }

  /**
   * 제목만 검색 필터
   */
  public function filter_search_title($search, $wp_query)
  {
    global $wpdb;
    if (empty($search))
      return $search;
    $q = $wp_query->query_vars;
    $n = !empty($q['exact']) ? '' : '%';
    $search = $searchand = '';
    foreach ((array) $q['search_terms'] as $term) {
      $term = esc_sql($wpdb->esc_like($term));
      $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
      $searchand = ' AND ';
    }
    if (!empty($search)) {
      $search = " AND ({$search}) ";
      if (!is_user_logged_in())
        $search .= " AND ($wpdb->posts.post_password = '') ";
    }
    return $search;
  }

  /**
   * 내용만 검색 필터
   */
  public function filter_search_content($search, $wp_query)
  {
    global $wpdb;
    if (empty($search))
      return $search;
    $q = $wp_query->query_vars;
    $n = !empty($q['exact']) ? '' : '%';
    $search = $searchand = '';
    foreach ((array) $q['search_terms'] as $term) {
      $term = esc_sql($wpdb->esc_like($term));
      $search .= "{$searchand}($wpdb->posts.post_content LIKE '{$n}{$term}{$n}')";
      $searchand = ' AND ';
    }
    if (!empty($search)) {
      $search = " AND ({$search}) ";
      if (!is_user_logged_in())
        $search .= " AND ($wpdb->posts.post_password = '') ";
    }
    return $search;
  }

  /**
   * 최신글 숏코드 렌더링
   * [latest_posts id="게시판슬러그" limit="5" url="/notice/"]
   */
  public function render_latest_posts($atts)
  {
    $atts = shortcode_atts(array(
      'id' => '',       // 게시판 슬러그 (필수)
      'limit' => 5,     // 출력 개수
      'url' => '',      // 더보기/클릭 이동 URL
    ), $atts, 'latest_posts');

    $board_slug = sanitize_key($atts['id']);
    $limit = absint($atts['limit']) > 0 ? absint($atts['limit']) : 5;
    $url = esc_url($atts['url']);

    if (empty($board_slug)) {
      return '<p class="uw-latest-error">게시판 ID를 지정해주세요.</p>';
    }

    // 게시글 쿼리
    $args = array(
      'post_type' => 'uw_board',
      'posts_per_page' => $limit,
      'post_status' => 'publish',
      'orderby' => 'date',
      'order' => 'DESC',
      'tax_query' => array(
        array(
          'taxonomy' => 'uw_board_type',
          'field' => 'slug',
          'terms' => $board_slug,
        ),
      ),
    );

    $query = new WP_Query($args);

    ob_start();
    ?>
        <ul class="uw-latest-posts">
          <?php if ($query->have_posts()): ?>
              <?php while ($query->have_posts()):
                $query->the_post(); ?>
                  <?php
                  $post_id = get_the_ID();
                  $post_url = !empty($url) ? add_query_arg('view', $post_id, $url) : '#';
                  ?>
                  <li>
                    <a href="<?php echo esc_url($post_url); ?>">
                      <span class="title"><?php the_title(); ?></span>
                      <span class="date"><?php echo get_the_date('Y.m.d'); ?></span>
                    </a>
                  </li>
              <?php endwhile; ?>
              <?php wp_reset_postdata(); ?>
          <?php else: ?>
              <li class="no-posts">등록된 게시글이 없습니다.</li>
          <?php endif; ?>
        </ul>
        <?php
        return ob_get_clean();
  }
}

// Initialize
UW_Board_Shortcode::get_instance();

