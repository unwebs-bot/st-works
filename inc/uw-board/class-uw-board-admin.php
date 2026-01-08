<?php
/**
 * UW Board Admin Interface
 * 
 * 관리자 메뉴 및 페이지 처리
 * 
 * @package St-works
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class UW_Board_Admin
{

    private static $instance = null;

    /**
     * 게시판 설정 옵션 키
     */
    const OPTION_KEY = 'uw_board_settings';

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('wp_ajax_uw_board_save_settings', array($this, 'ajax_save_settings'));
        add_action('wp_ajax_uw_board_delete_board', array($this, 'ajax_delete_board'));
        add_action('wp_ajax_uw_board_save_post', array($this, 'ajax_save_post'));
        add_action('wp_ajax_uw_board_delete_post', array($this, 'ajax_delete_post'));
        add_action('wp_ajax_uw_board_upload_image', array($this, 'ajax_upload_image'));
        add_action('wp_ajax_uw_board_bulk_empty_posts', array($this, 'ajax_bulk_empty_posts'));
        add_action('wp_ajax_uw_board_bulk_delete_boards', array($this, 'ajax_bulk_delete_boards'));
    }

    /**
     * 관리자 메뉴 등록
     */
    public function add_admin_menu()
    {
        // 메인 메뉴: 게시판
        add_menu_page(
            'UW Board Center',
            '게시판',
            'manage_options',
            'uw-board',
            array($this, 'render_dashboard_page'),
            'dashicons-welcome-write-blog',
            25
        );

        // 서브메뉴 1: 게시판 관리 (대시보드)
        add_submenu_page(
            'uw-board',
            '게시판 관리',
            '게시판 관리',
            'manage_options',
            'uw-board',
            array($this, 'render_dashboard_page')
        );

        // 서브메뉴 2: 게시판 생성
        add_submenu_page(
            'uw-board',
            '게시판 생성',
            '게시판 생성',
            'manage_options',
            'uw-board-settings',
            array($this, 'render_settings_page')
        );

        // 동적 서브메뉴: 각 게시판별
        $boards = $this->get_all_boards();
        foreach ($boards as $slug => $board) {
            add_submenu_page(
                'uw-board',
                $board['name'],
                $board['name'],
                'manage_options',
                'uw-board-' . $slug,
                array($this, 'render_board_manager_page')
            );
        }
    }

    /**
     * 관리자 에셋 로드
     */
    public function enqueue_admin_assets($hook)
    {
        if (strpos($hook, 'uw-board') === false) {
            return;
        }

        // WordPress Media Uploader
        wp_enqueue_media();

        // Xeicon (아이콘 폰트) - GitHub 기반 jsDelivr CDN
        wp_enqueue_style('xeicon', 'https://cdn.jsdelivr.net/gh/xpressengine/XEIcon@2.3.3/xeicon.min.css');

        // Summernote for editor
        wp_enqueue_style('summernote', 'https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css');
        wp_enqueue_script('summernote', 'https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js', array('jquery'), '0.8.18', true);

        // Custom admin styles
        wp_enqueue_style('uw-board-admin', get_theme_file_uri('/assets/css/uw-board-admin.css'), array(), '1.0.2');
        wp_enqueue_script('uw-board-admin', get_theme_file_uri('/assets/js/uw-board-admin.js'), array('jquery', 'summernote', 'media-upload'), '1.0.1', true);

        wp_localize_script('uw-board-admin', 'uwBoardAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('uw_board_admin_nonce'),
        ));
    }

    /**
     * 모든 게시판 설정 가져오기
     */
    public function get_all_boards()
    {
        return get_option(self::OPTION_KEY, array());
    }

    /**
     * 특정 게시판 설정 가져오기
     */
    public function get_board_settings($slug)
    {
        $boards = $this->get_all_boards();
        return isset($boards[$slug]) ? $boards[$slug] : null;
    }

    /**
     * 게시판 설정 저장
     */
    public function save_board_settings($slug, $settings)
    {
        $boards = $this->get_all_boards();
        $boards[$slug] = $settings;
        update_option(self::OPTION_KEY, $boards);

        // Taxonomy term 생성/업데이트
        if (!term_exists($slug, 'uw_board_type')) {
            wp_insert_term($settings['name'], 'uw_board_type', array('slug' => $slug));
        }
    }

    /**
     * 대시보드 페이지 렌더링
     */
    public function render_dashboard_page()
    {
        $boards = $this->get_all_boards();
        ?>
        <div class="wrap uw-board-admin">
            <h1>게시판 관리</h1>

            <div class="uw-board-dashboard">
                <?php if (empty($boards)): ?>
                    <div class="uw-board-empty">
                        <p>등록된 게시판이 없습니다.</p>
                        <a href="<?php echo admin_url('admin.php?page=uw-board-settings'); ?>" class="button button-primary">
                            새 게시판 만들기
                        </a>
                    </div>
                <?php else: ?>
                    <div class="tablenav top">
                        <div class="alignleft actions bulkactions">
                            <select name="bulk_action" id="bulk-action-selector">
                                <option value="">일괄 동작</option>
                                <option value="empty_posts">모든 게시글 비우기</option>
                                <option value="delete_boards">영구적으로 삭제하기</option>
                            </select>
                            <button type="button" id="doaction" class="button action">적용</button>
                        </div>
                    </div>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <td class="manage-column column-cb check-column" style="width: 30px;">
                                    <input type="checkbox" id="cb-select-all" />
                                </td>
                                <th>게시판명</th>
                                <th>숏코드</th>
                                <th>읽기 권한</th>
                                <th>쓰기 권한</th>
                                <th>글 수</th>
                                <th>관리</th>
                            </tr>
                        </thead>
                        <tbody id="the-list">
                            <?php foreach ($boards as $slug => $board):
                                $post_count = $this->get_board_post_count($slug);
                                ?>
                                <tr>
                                    <th scope="row" class="check-column">
                                        <input type="checkbox" name="board_slugs[]" value="<?php echo esc_attr($slug); ?>"
                                            class="board-checkbox" />
                                    </th>
                                    <td>
                                        <strong>
                                            <a href="<?php echo admin_url('admin.php?page=uw-board-settings&edit=' . $slug); ?>">
                                                <?php echo esc_html($board['name']); ?>
                                            </a>
                                        </strong>
                                    </td>
                                    <td>
                                        <code>[uw_board name="<?php echo esc_attr($slug); ?>"]</code>
                                    </td>
                                    <td><?php echo $board['read_permission'] === 'all' ? '전체' : '로그인 사용자'; ?></td>
                                    <td><?php echo $board['write_permission'] === 'all' ? '전체' : '로그인 사용자'; ?></td>
                                    <td><?php echo $post_count; ?>개</td>
                                    <td>
                                        <a href="<?php echo admin_url('admin.php?page=uw-board-settings&edit=' . $slug); ?>"
                                            class="button button-small">설정</a>
                                        <a href="<?php echo admin_url('admin.php?page=uw-board-' . $slug); ?>"
                                            class="button button-small">글 관리</a>
                                        <button type="button" class="button button-small uw-delete-board"
                                            data-slug="<?php echo esc_attr($slug); ?>">삭제</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <p style="margin-top: 20px;">
                        <a href="<?php echo admin_url('admin.php?page=uw-board-settings'); ?>" class="button button-primary">
                            + 새 게시판 추가
                        </a>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /**
     * 설정 페이지 렌더링
     */
    public function render_settings_page()
    {
        $edit_slug = isset($_GET['edit']) ? sanitize_key($_GET['edit']) : '';
        $board = $edit_slug ? $this->get_board_settings($edit_slug) : null;
        $is_edit = !empty($board);
        ?>
        <div class="wrap uw-board-admin">
            <h1><?php echo $is_edit ? '게시판 수정' : '새 게시판 만들기'; ?></h1>

            <form id="uw-board-settings-form" class="uw-board-form">
                <input type="hidden" name="original_slug" value="<?php echo esc_attr($edit_slug); ?>">

                <table class="form-table">
                    <tr>
                        <th><label for="board_name">게시판 이름 *</label></th>
                        <td>
                            <input type="text" id="board_name" name="name" class="regular-text"
                                value="<?php echo esc_attr($board['name'] ?? ''); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="board_slug">슬러그 (영문) *</label></th>
                        <td>
                            <input type="text" id="board_slug" name="slug" class="regular-text"
                                value="<?php echo esc_attr($edit_slug); ?>" pattern="[a-z0-9_-]+" <?php echo $is_edit ? 'readonly' : ''; ?> required>
                            <p class="description">영문 소문자, 숫자, 밑줄, 하이픈만 사용 가능</p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="per_page">페이지당 글 수</label></th>
                        <td>
                            <select id="per_page" name="per_page">
                                <?php foreach (array(5, 10, 15, 20, 30) as $num): ?>
                                    <option value="<?php echo $num; ?>" <?php selected(($board['per_page'] ?? 10), $num); ?>>
                                        <?php echo $num; ?>개
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="read_permission">읽기 권한</label></th>
                        <td>
                            <select id="read_permission" name="read_permission">
                                <option value="all" <?php selected(($board['read_permission'] ?? 'all'), 'all'); ?>>
                                    제한 없음
                                </option>
                                <option value="logged_in" <?php selected(($board['read_permission'] ?? ''), 'logged_in'); ?>>
                                    로그인 사용자만
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="write_permission">쓰기 권한</label></th>
                        <td>
                            <select id="write_permission" name="write_permission">
                                <option value="all" <?php selected(($board['write_permission'] ?? 'all'), 'all'); ?>>
                                    제한 없음 (비회원 포함)
                                </option>
                                <option value="logged_in" <?php selected(($board['write_permission'] ?? ''), 'logged_in'); ?>>
                                    로그인 사용자만
                                </option>
                            </select>
                            <p class="description">비회원 쓰기 허용 시 비밀번호 입력이 필수가 됩니다.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>개인정보 동의</th>
                        <td>
                            <label>
                                <input type="checkbox" name="require_privacy" value="1" <?php checked($board['require_privacy'] ?? true); ?>>
                                글쓰기 시 개인정보 수집 동의 필수
                            </label>
                        </td>
                    </tr>
                </table>

                <?php if ($is_edit): ?>
                <h2>최신글 숏코드(Shortcode)</h2>
                <table class="form-table">
                    <tr>
                        <th>숏코드 미리보기</th>
                        <td>
                            <input type="text" id="latest-shortcode-preview" 
                                value='[latest_posts id="<?php echo esc_attr($slug); ?>" url="" limit="5"]' 
                                readonly class="regular-text code" style="width: 100%; max-width: 500px; background: #f0f0f1;">
                            <p class="description">최신글 리스트를 생성합니다. <strong>url</strong> 부분에 게시판이 설치된 페이지의 전체 URL을 입력하고 이 숏코드를 메인페이지 또는 사이드바에 입력하세요.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>이동 페이지 URL</th>
                        <td>
                            <input type="text" id="latest-shortcode-url" 
                                placeholder="/support/notice/" 
                                class="regular-text" style="max-width: 300px;">
                            <p class="description">게시글 클릭 시 이동할 페이지 경로 (예: /support/notice/)</p>
                        </td>
                    </tr>
                    <tr>
                        <th>출력 개수</th>
                        <td>
                            <input type="number" id="latest-shortcode-limit" 
                                value="5" min="1" max="20" 
                                style="width: 80px;">
                            <p class="description">메인페이지 등에 보여줄 최신글 개수</p>
                        </td>
                    </tr>
                </table>

                <script>
                (function() {
                    var boardSlug = '<?php echo esc_js($slug); ?>';
                    var urlInput = document.getElementById('latest-shortcode-url');
                    var limitInput = document.getElementById('latest-shortcode-limit');
                    var preview = document.getElementById('latest-shortcode-preview');

                    function updatePreview() {
                        var url = urlInput.value || '';
                        var limit = limitInput.value || '5';
                        preview.value = '[latest_posts id="' + boardSlug + '" url="' + url + '" limit="' + limit + '"]';
                    }

                    urlInput.addEventListener('input', updatePreview);
                    limitInput.addEventListener('input', updatePreview);
                })();
                </script>
                <?php endif; ?>

                <p class="submit">
                    <button type="submit" class="button button-primary">
                        <?php echo $is_edit ? '저장하기' : '게시판 생성'; ?>
                    </button>
                    <a href="<?php echo admin_url('admin.php?page=uw-board'); ?>" class="button">취소</a>
                </p>
            </form>
        </div>
        <?php
    }

    /**
     * 개별 게시판 관리 페이지 렌더링
     */
    public function render_board_manager_page()
    {
        $page = isset($_GET['page']) ? sanitize_key($_GET['page']) : '';
        $slug = str_replace('uw-board-', '', $page);
        $board = $this->get_board_settings($slug);

        if (!$board) {
            echo '<div class="wrap"><h1>게시판을 찾을 수 없습니다.</h1></div>';
            return;
        }

        $view = isset($_GET['view']) ? sanitize_key($_GET['view']) : 'list';
        $post_id = isset($_GET['post_id']) ? absint($_GET['post_id']) : 0;

        ?>
        <div class="wrap uw-board-admin uw-board-manager" data-board-slug="<?php echo esc_attr($slug); ?>">
            <?php
            switch ($view) {
                case 'single':
                    $this->render_board_single($slug, $board, $post_id);
                    break;
                case 'write':
                case 'edit':
                    $this->render_board_editor($slug, $board, $post_id);
                    break;
                default:
                    $this->render_board_list($slug, $board);
                    break;
            }
            ?>
        </div>
        <?php
    }

    /**
     * 게시판 리스트 뷰 렌더링
     */
    private function render_board_list($slug, $board)
    {
        $paged = isset($_GET['paged']) ? max(1, absint($_GET['paged'])) : 1;
        $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
        $search_type = isset($_GET['search_type']) ? sanitize_key($_GET['search_type']) : 'title';

        $per_page = $board['per_page'] ?? 10;

        $args = array(
            'post_type' => 'uw_board',
            'posts_per_page' => $per_page,
            'paged' => $paged,
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'uw_board_type',
                    'field' => 'slug',
                    'terms' => $slug,
                ),
            ),
            'orderby' => 'date',
            'order' => 'DESC',
        );

        // 검색
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
                    // 비회원 등 검색 불가 시 결과 없음 처리
                    $args['post__in'] = array(0);
                }
            } else {
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

        ?>
        <h1><?php echo esc_html($board['name']); ?> <span class="uw-total-count">(Total: <?php echo $total; ?>)</span></h1>

        <div class="uw-board-list-view">
            <table class="wp-list-table widefat fixed striped uw-board-table">
                <thead>
                    <tr>
                        <th class="column-num" style="width: 60px;">번호</th>
                        <th class="column-title">제목</th>
                        <th class="column-author" style="width: 100px;">작성자</th>
                        <th class="column-date" style="width: 100px;">등록일</th>
                        <th class="column-views" style="width: 60px;">조회</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $num = $total - (($paged - 1) * $per_page);
                    while ($query->have_posts()):
                        $query->the_post();
                        $post_id = get_the_ID();
                        $is_pinned = get_post_meta($post_id, '_uw_is_pinned', true);
                        $views = get_post_meta($post_id, '_uw_views', true) ?: 0;
                        $is_new = (time() - get_the_time('U')) < 86400; // 24시간
                        $single_url = admin_url('admin.php?page=uw-board-' . $slug . '&view=single&post_id=' . $post_id);
                        // 실제 첨부파일만 체크 (썸네일 제외)
                        $attachments = get_post_meta($post_id, '_uw_attachments', true);
                        $has_attachment = !empty($attachments);
                        ?>
                        <tr class="<?php echo $is_pinned ? 'uw-pinned-row' : ''; ?>">
                            <td class="column-num">
                                <?php echo $is_pinned ? '<span class="uw-notice-badge">공지</span>' : $num--; ?>
                            </td>
                            <td class="column-title">
                                <a href="<?php echo esc_url($single_url); ?>">
                                    <?php the_title(); ?>
                                </a>
                                <?php if ($is_new): ?>
                                    <span class="uw-new-badge">N</span>
                                <?php endif; ?>
                                <?php if ($has_attachment): ?>
                                    <i class="xi-attachment uw-has-attachment" title="첨부파일"></i>
                                <?php endif; ?>
                            </td>
                            <td class="column-author"><?php
                            $guest_name = get_post_meta($post_id, '_uw_guest_name', true);
                            if ($guest_name) {
                                echo esc_html($guest_name);
                            } else {
                                $author_id = get_the_author_meta('ID');
                                $author_user = get_userdata($author_id);
                                echo ($author_user && in_array('administrator', $author_user->roles)) ? '관리자' : get_the_author();
                            }
                            ?></td>
                            <td class="column-date"><?php echo get_the_date('Y.m.d'); ?></td>
                            <td class="column-views"><?php echo number_format($views); ?></td>
                        </tr>
                    <?php endwhile;
                    wp_reset_postdata(); ?>

                    <?php if (!$query->have_posts()): ?>
                        <tr>
                            <td colspan="5" class="uw-no-posts">등록된 글이 없습니다.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- 검색바 & 페이지네이션 -->
            <div class="uw-board-footer">
                <form class="uw-search-form" method="get">
                    <input type="hidden" name="page" value="uw-board-<?php echo esc_attr($slug); ?>">
                    <select name="search_type">
                        <option value="title" <?php selected($search_type, 'title'); ?>>제목</option>
                        <option value="content" <?php selected($search_type, 'content'); ?>>내용</option>
                        <option value="author" <?php selected($search_type, 'author'); ?>>작성자</option>
                    </select>
                    <input type="text" name="s" value="<?php echo esc_attr($search); ?>" placeholder="검색어 입력">
                    <button type="submit" class="button">검색</button>
                </form>

                <div class="uw-pagination">
                    <?php
                    $base_url = admin_url('admin.php?page=uw-board-' . $slug);
                    if ($total_pages > 1):
                        if ($paged > 1):
                            echo '<a href="' . esc_url($base_url . '&paged=1') . '" class="button">«</a>';
                            echo '<a href="' . esc_url($base_url . '&paged=' . ($paged - 1)) . '" class="button">‹</a>';
                        endif;

                        for ($i = max(1, $paged - 2); $i <= min($total_pages, $paged + 2); $i++):
                            $class = ($i === $paged) ? 'button button-primary' : 'button';
                            echo '<a href="' . esc_url($base_url . '&paged=' . $i) . '" class="' . $class . '">' . $i . '</a>';
                        endfor;

                        if ($paged < $total_pages):
                            echo '<a href="' . esc_url($base_url . '&paged=' . ($paged + 1)) . '" class="button">›</a>';
                            echo '<a href="' . esc_url($base_url . '&paged=' . $total_pages) . '" class="button">»</a>';
                        endif;
                    endif;
                    ?>
                </div>

                <a href="<?php echo admin_url('admin.php?page=uw-board-' . $slug . '&view=write'); ?>"
                    class="button button-primary uw-write-btn">글쓰기</a>
            </div>
        </div>
        <?php
    }

    /**
     * 게시판 싱글(상세) 뷰 렌더링 - KBoard 스타일
     */
    private function render_board_single($slug, $board, $post_id)
    {
        $post = get_post($post_id);

        if (!$post || $post->post_type !== 'uw_board') {
            echo '<div class="notice notice-error"><p>게시글을 찾을 수 없습니다.</p></div>';
            return;
        }

        // 조회수 증가
        $views = (int) get_post_meta($post_id, '_uw_views', true);
        update_post_meta($post_id, '_uw_views', $views + 1);

        // 첨부파일
        $attachments = get_post_meta($post_id, '_uw_attachments', true) ?: array();

        // 이전/다음 글 가져오기
        $prev_post = $this->get_adjacent_board_post($post_id, $slug, 'prev');
        $next_post = $this->get_adjacent_board_post($post_id, $slug, 'next');

        // URL
        $list_url = admin_url('admin.php?page=uw-board-' . $slug);
        $edit_url = admin_url('admin.php?page=uw-board-' . $slug . '&view=edit&post_id=' . $post_id);

        ?>
        <div class="uw-board-single-view">
            <!-- 제목 영역 -->
            <div class="uw-single-header">
                <h1 class="uw-single-title"><?php echo esc_html($post->post_title); ?></h1>
                <div class="uw-single-meta">
                    <span class="meta-item">
                        <strong>작성자</strong>
                        <?php
                        $author_user = get_userdata($post->post_author);
                        echo ($author_user && in_array('administrator', $author_user->roles)) ? '관리자' : esc_html(get_the_author_meta('display_name', $post->post_author));
                        ?>
                    </span>
                    <span class="meta-item">
                        <strong>작성일</strong> <?php echo get_the_date('Y-m-d H:i', $post_id); ?>
                    </span>
                    <span class="meta-item">
                        <strong>조회</strong> <?php echo number_format($views + 1); ?>
                    </span>
                </div>
            </div>

            <!-- 본문 영역 -->
            <div class="uw-single-content">
                <?php
                // 본문 이미지 자동 리사이징을 위한 필터
                $content = $post->post_content;
                $content = preg_replace('/<img([^>]+)>/i', '<img$1 style="max-width:100%;height:auto;">', $content);
                echo wp_kses_post(wpautop($content));
                ?>
            </div>

            <!-- 인쇄 버튼 -->
            <div class="uw-single-print">
                <button type="button" class="button uw-print-btn" onclick="window.print();">
                    <span class="dashicons dashicons-printer"></span> 인쇄
                </button>
            </div>

            <!-- 첨부파일 섹션 -->
            <?php if (!empty($attachments)): ?>
                <div class="uw-single-attachments">
                    <h3>첨부파일</h3>
                    <ul class="attachment-list">
                        <?php foreach ($attachments as $attachment_id):
                            $file_path = get_attached_file($attachment_id);
                            $file_name = basename($file_path);
                            $file_url = wp_get_attachment_url($attachment_id);
                            ?>
                            <li>
                                <a href="<?php echo esc_url($file_url); ?>" download>
                                    <span class="dashicons dashicons-media-default"></span>
                                    <?php echo esc_html($file_name); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- 이전/다음 글 네비게이션 -->
            <div class="uw-single-navigation">
                <?php if ($next_post): ?>
                    <?php $next_url = admin_url('admin.php?page=uw-board-' . $slug . '&view=single&post_id=' . $next_post->ID); ?>
                    <div class="nav-next">
                        <span class="nav-label">다음 글</span>
                        <a href="<?php echo esc_url($next_url); ?>"><?php echo esc_html($next_post->post_title); ?></a>
                    </div>
                <?php else: ?>
                    <div class="nav-next nav-empty">
                        <span class="nav-label">다음 글</span>
                        <span class="no-post">다음 글이 없습니다.</span>
                    </div>
                <?php endif; ?>

                <?php if ($prev_post): ?>
                    <?php $prev_url = admin_url('admin.php?page=uw-board-' . $slug . '&view=single&post_id=' . $prev_post->ID); ?>
                    <div class="nav-prev">
                        <span class="nav-label">이전 글</span>
                        <a href="<?php echo esc_url($prev_url); ?>"><?php echo esc_html($prev_post->post_title); ?></a>
                    </div>
                <?php else: ?>
                    <div class="nav-prev nav-empty">
                        <span class="nav-label">이전 글</span>
                        <span class="no-post">이전 글이 없습니다.</span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- 액션 버튼 -->
            <div class="uw-single-actions">
                <div class="actions-left">
                    <a href="<?php echo esc_url($list_url); ?>" class="button button-secondary">목록보기</a>
                </div>
                <div class="actions-right">
                    <a href="<?php echo esc_url($edit_url); ?>" class="button button-primary">글수정</a>
                    <button type="button" class="button uw-delete-post" data-post-id="<?php echo $post_id; ?>"
                        data-board-slug="<?php echo esc_attr($slug); ?>">글삭제</button>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * 이전/다음 게시글 가져오기 (ID 기반 정렬)
     */
    private function get_adjacent_board_post($post_id, $slug, $direction = 'prev')
    {
        global $wpdb;

        $current_post = get_post($post_id);
        if (!$current_post) {
            return null;
        }

        // 게시판 taxonomy term ID 가져오기
        $term = get_term_by('slug', $slug, 'uw_board_type');
        if (!$term) {
            return null;
        }

        // 이전 글: 현재보다 날짜가 이전인 글 중 가장 최근 것
        // 다음 글: 현재보다 날짜가 이후인 글 중 가장 오래된 것
        if ($direction === 'prev') {
            // 이전 글 = 더 오래된 글 (날짜 DESC, 같은 날짜면 ID ASC)
            $compare = '<';
            $order = 'DESC';
        } else {
            // 다음 글 = 더 최근 글 (날짜 ASC, 같은 날짜면 ID DESC)
            $compare = '>';
            $order = 'ASC';
        }

        $query = $wpdb->prepare("
            SELECT p.ID, p.post_title
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
            WHERE p.post_type = 'uw_board'
            AND p.post_status = 'publish'
            AND tr.term_taxonomy_id = %d
            AND p.post_date {$compare} %s
            ORDER BY p.post_date {$order}, p.ID {$order}
            LIMIT 1
        ", $term->term_taxonomy_id, $current_post->post_date);

        return $wpdb->get_row($query);
    }

    /**
     * 게시판 에디터 뷰 렌더링
     */
    private function render_board_editor($slug, $board, $post_id = 0)
    {
        $post = $post_id ? get_post($post_id) : null;
        $is_edit = !empty($post);

        $title = $post ? $post->post_title : '';
        $content = $post ? $post->post_content : '';
        $is_pinned = $post ? get_post_meta($post_id, '_uw_is_pinned', true) : false;
        $attachments = $post ? get_post_meta($post_id, '_uw_attachments', true) : array();

        ?>
        <h1><?php echo $is_edit ? '글 수정' : '글쓰기'; ?> - <?php echo esc_html($board['name']); ?></h1>

        <form id="uw-board-editor-form" class="uw-board-editor">
            <input type="hidden" name="board_slug" value="<?php echo esc_attr($slug); ?>">
            <input type="hidden" name="post_id" value="<?php echo esc_attr($post_id); ?>">

            <div class="uw-editor-field">
                <label for="post_title">제목 *</label>
                <input type="text" id="post_title" name="title" value="<?php echo esc_attr($title); ?>" required>

                <label class="uw-checkbox-inline">
                    <input type="checkbox" name="is_pinned" value="1" <?php checked($is_pinned); ?>>
                    상단 공지사항으로 지정
                </label>
            </div>

            <div class="uw-editor-field">
                <label>본문</label>
                <textarea id="uw-summernote" name="content"><?php echo esc_textarea($content); ?></textarea>
            </div>

            <div class="uw-editor-field">
                <label>대표 이미지</label>
                <div class="uw-thumbnail-upload">
                    <input type="hidden" name="thumbnail_id" id="thumbnail_id"
                        value="<?php echo get_post_thumbnail_id($post_id); ?>">
                    <button type="button" class="button uw-select-thumbnail">이미지 선택</button>
                    <span class="uw-thumbnail-name">
                        <?php
                        if (has_post_thumbnail($post_id)) {
                            echo get_the_post_thumbnail_url($post_id, 'thumbnail') ? '이미지 선택됨' : '선택된 파일 없음';
                        } else {
                            echo '선택된 파일 없음';
                        }
                        ?>
                    </span>
                </div>
            </div>

            <div class="uw-editor-field">
                <label>첨부파일 (최대 3개)</label>
                <div class="uw-attachments-upload">
                    <?php for ($i = 0; $i < 3; $i++):
                        $att_id = isset($attachments[$i]) ? $attachments[$i] : '';
                        $att_name = $att_id ? basename(get_attached_file($att_id)) : '';
                        ?>
                        <div class="uw-attachment-slot">
                            <input type="hidden" name="attachments[]" value="<?php echo esc_attr($att_id); ?>">
                            <button type="button" class="button uw-select-file">파일 선택</button>
                            <span class="uw-file-name"><?php echo $att_name ?: '선택된 파일 없음'; ?></span>
                            <?php if ($att_id): ?>
                                <button type="button" class="button uw-remove-file">삭제</button>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="uw-editor-actions">
                <a href="<?php echo admin_url('admin.php?page=uw-board-' . $slug); ?>" class="button">목록으로</a>
                <button type="submit" class="button button-primary">
                    <?php echo $is_edit ? '저장하기' : '작성하기'; ?>
                </button>
                <?php if ($is_edit): ?>
                    <button type="button" class="button uw-delete-post" data-post-id="<?php echo $post_id; ?>">
                        삭제
                    </button>
                <?php endif; ?>
            </div>
        </form>
        <?php
    }

    /**
     * 게시판 글 개수 조회
     */
    private function get_board_post_count($slug)
    {
        $args = array(
            'post_type' => 'uw_board',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'tax_query' => array(
                array(
                    'taxonomy' => 'uw_board_type',
                    'field' => 'slug',
                    'terms' => $slug,
                ),
            ),
        );
        $query = new WP_Query($args);
        return $query->found_posts;
    }

    /**
     * AJAX: 게시판 설정 저장
     */
    public function ajax_save_settings()
    {
        check_ajax_referer('uw_board_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('권한이 없습니다.');
        }

        $slug = sanitize_key($_POST['slug']);
        $settings = array(
            'name' => sanitize_text_field($_POST['name']),
            'per_page' => absint($_POST['per_page']),
            'read_permission' => sanitize_key($_POST['read_permission']),
            'write_permission' => sanitize_key($_POST['write_permission']),
            'require_privacy' => !empty($_POST['require_privacy']),
        );

        $this->save_board_settings($slug, $settings);

        wp_send_json_success(array(
            'message' => '저장되었습니다.',
            'redirect' => admin_url('admin.php?page=uw-board'),
        ));
    }

    /**
     * AJAX: 게시판 삭제
     */
    public function ajax_delete_board()
    {
        check_ajax_referer('uw_board_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('권한이 없습니다.');
        }

        $slug = sanitize_key($_POST['slug']);
        $boards = $this->get_all_boards();

        if (isset($boards[$slug])) {
            unset($boards[$slug]);
            update_option(self::OPTION_KEY, $boards);

            // 관련 글 삭제 (선택적)
            // TODO: 글 삭제 옵션 추가?

            wp_send_json_success(array('message' => '삭제되었습니다.'));
        }

        wp_send_json_error('게시판을 찾을 수 없습니다.');
    }

    /**
     * AJAX: 글 저장
     */
    public function ajax_save_post()
    {
        check_ajax_referer('uw_board_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('권한이 없습니다.');
        }

        $board_slug = sanitize_key($_POST['board_slug']);
        $post_id = absint($_POST['post_id']);

        $post_data = array(
            'post_title' => sanitize_text_field($_POST['title']),
            'post_content' => wp_kses_post($_POST['content']),
            'post_type' => 'uw_board',
            'post_status' => 'publish',
        );

        if ($post_id) {
            $post_data['ID'] = $post_id;
            wp_update_post($post_data);
        } else {
            $post_id = wp_insert_post($post_data);
            wp_set_object_terms($post_id, $board_slug, 'uw_board_type');
        }

        // Meta 저장
        update_post_meta($post_id, '_uw_is_pinned', !empty($_POST['is_pinned']) ? '1' : '');

        // 썸네일
        if (!empty($_POST['thumbnail_id'])) {
            set_post_thumbnail($post_id, absint($_POST['thumbnail_id']));
        } else {
            delete_post_thumbnail($post_id);
        }

        // 첨부파일
        $attachments = isset($_POST['attachments']) ? array_filter(array_map('absint', $_POST['attachments'])) : array();
        update_post_meta($post_id, '_uw_attachments', $attachments);

        wp_send_json_success(array(
            'message' => '저장되었습니다.',
            'redirect' => admin_url('admin.php?page=uw-board-' . $board_slug),
        ));
    }

    /**
     * AJAX: 글 삭제
     */
    public function ajax_delete_post()
    {
        check_ajax_referer('uw_board_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('권한이 없습니다.');
        }

        $post_id = absint($_POST['post_id']);
        $board_slug = sanitize_key($_POST['board_slug']);

        if ($post_id && get_post_type($post_id) === 'uw_board') {
            wp_delete_post($post_id, true);
            wp_send_json_success(array(
                'message' => '삭제되었습니다.',
                'redirect' => admin_url('admin.php?page=uw-board-' . $board_slug),
            ));
        }

        wp_send_json_error('글을 찾을 수 없습니다.');
    }

    /**
     * AJAX: 이미지 업로드 (Summernote 에디터용)
     */
    public function ajax_upload_image()
    {
        check_ajax_referer('uw_board_admin_nonce', 'nonce');

        if (!current_user_can('upload_files')) {
            wp_send_json_error('업로드 권한이 없습니다.');
        }

        if (empty($_FILES['file'])) {
            wp_send_json_error('파일이 없습니다.');
        }

        // WordPress 미디어 라이브러리에 업로드
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('file', 0);

        if (is_wp_error($attachment_id)) {
            wp_send_json_error($attachment_id->get_error_message());
        }

        $url = wp_get_attachment_url($attachment_id);

        wp_send_json_success(array(
            'url' => $url,
            'id' => $attachment_id,
        ));
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
        }
        return $search;
    }

    /**
     * 일괄 동작: 모든 게시글 비우기
     */
    public function ajax_bulk_empty_posts()
    {
        check_ajax_referer('uw_board_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('권한이 없습니다.');
        }

        // board_slugs 처리 - 배열 또는 문자열 모두 처리
        $raw_slugs = isset($_POST['board_slugs']) ? $_POST['board_slugs'] : array();

        if (!is_array($raw_slugs)) {
            $raw_slugs = array($raw_slugs);
        }

        $board_slugs = array_map('sanitize_key', $raw_slugs);
        $board_slugs = array_filter($board_slugs); // 빈 값 제거

        if (empty($board_slugs)) {
            wp_send_json_error('게시판을 선택해주세요.');
        }

        $deleted_count = 0;

        foreach ($board_slugs as $slug) {
            $args = array(
                'post_type' => 'uw_board',
                'posts_per_page' => -1,
                'post_status' => 'any',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'uw_board_type',
                        'field' => 'slug',
                        'terms' => $slug,
                    ),
                ),
            );

            $posts = get_posts($args);

            foreach ($posts as $post) {
                wp_delete_post($post->ID, true);
                $deleted_count++;
            }
        }

        wp_send_json_success(array(
            'message' => $deleted_count . '개의 게시글이 삭제되었습니다.',
        ));
    }

    /**
     * 일괄 동작: 게시판 영구 삭제
     */
    public function ajax_bulk_delete_boards()
    {
        check_ajax_referer('uw_board_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('권한이 없습니다.');
        }

        // board_slugs 처리 - 배열 또는 문자열 모두 처리
        $raw_slugs = isset($_POST['board_slugs']) ? $_POST['board_slugs'] : array();

        if (!is_array($raw_slugs)) {
            $raw_slugs = array($raw_slugs);
        }

        $board_slugs = array_map('sanitize_key', $raw_slugs);
        $board_slugs = array_filter($board_slugs); // 빈 값 제거

        if (empty($board_slugs)) {
            wp_send_json_error('게시판을 선택해주세요.');
        }

        $boards = $this->get_all_boards();

        foreach ($board_slugs as $slug) {
            // 해당 게시판의 모든 글 삭제
            $args = array(
                'post_type' => 'uw_board',
                'posts_per_page' => -1,
                'post_status' => 'any',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'uw_board_type',
                        'field' => 'slug',
                        'terms' => $slug,
                    ),
                ),
            );

            $posts = get_posts($args);

            foreach ($posts as $post) {
                wp_delete_post($post->ID, true);
            }

            // 택소노미 term 삭제
            $term = get_term_by('slug', $slug, 'uw_board_type');
            if ($term && !is_wp_error($term)) {
                wp_delete_term($term->term_id, 'uw_board_type');
            }

            // 게시판 설정 삭제
            if (isset($boards[$slug])) {
                unset($boards[$slug]);
            }
        }

        update_option('uw_boards', $boards);

        wp_send_json_success(array(
            'message' => count($board_slugs) . '개의 게시판이 삭제되었습니다.',
        ));
    }
}

// Initialize
UW_Board_Admin::get_instance();
