<?php
/**
 * Template Part: Sub-Visual (Hero Section with LNB)
 * 
 * 동적으로 카테고리별 배경 이미지, 페이지 타이틀, LNB 메뉴를 출력합니다.
 */

// 사이트맵 데이터 정의
$site_map = array(
  'about' => array(
    'bg_class' => 'about',
    'items' => array(
      array('slug' => '/about/ceo/', 'label' => 'CEO 인사말'),
      array('slug' => '/about/history/', 'label' => '연혁'),
      array('slug' => '/about/vision/', 'label' => '비전'),
      array('slug' => '/about/location/', 'label' => '오시는길'),
    )
  ),
  'business' => array(
    'bg_class' => 'business',
    'items' => array(
      array('slug' => '/business/tech/', 'label' => '핵심기술'),
      array('slug' => '/business/areas/', 'label' => '사업분야'),
      array('slug' => '/business/cert/', 'label' => '특허&인증'),
    )
  ),
  'product' => array(
    'bg_class' => 'product',
    'items' => array(
      array('slug' => '/product/product/', 'label' => '제품소개'),
    )
  ),
  'support' => array(
    'bg_class' => 'support',
    'items' => array(
      array('slug' => '/support/notice/', 'label' => '공지사항'),
      array('slug' => '/support/news/', 'label' => '뉴스'),
    )
  ),
  'contact' => array(
    'bg_class' => 'contact',
    'items' => array(
      array('slug' => '/contact/contact/', 'label' => '상담 문의'),
    )
  ),
);

// 현재 URL 경로에서 카테고리 추출
$current_uri = $_SERVER['REQUEST_URI'];
$current_category = '';

foreach ($site_map as $category => $data) {
  if (strpos($current_uri, '/' . $category . '/') !== false || $current_uri === '/' . $category . '/') {
    $current_category = $category;
    break;
  }
}

// 카테고리가 없으면 기본값
if (empty($current_category)) {
  $current_category = 'about';
}

$category_data = $site_map[$current_category];
$bg_class = $category_data['bg_class'];
$lnb_items = $category_data['items'];

// 페이지 타이틀 가져오기
$page_title = get_the_title();
?>

<section class="sub-visual <?php echo esc_attr($bg_class); ?>">
  <div class="sub-visual-bg"></div>
  <div class="sub-visual-content">
    <h1 class="sub-page-title">
      <?php echo esc_html($page_title); ?>
    </h1>
  </div>

  <?php if (count($lnb_items) > 1): ?>
    <div class="sub-lnb">
      <div class="uw-container">
        <ul class="sub-lnb-list">
          <?php foreach ($lnb_items as $item):
            $item_url = home_url($item['slug']);
            $is_active = (trim($current_uri, '/') === trim($item['slug'], '/')) ? 'active' : '';
            ?>
            <li class="sub-lnb-item <?php echo esc_attr($is_active); ?>">
              <a href="<?php echo esc_url($item_url); ?>">
                <?php echo esc_html($item['label']); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  <?php endif; ?>
</section>