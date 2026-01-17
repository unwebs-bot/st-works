<?php get_header(); ?>

<!-- 메인 컨텐츠 시작 -->

<!-- Main Content -->
<main class="uw-main">
  <!-- 메인 비주얼 섹션 시작 -->
  <!-- Main Visual Section -->
  <section class="uw-visual">
    <ul class="uw-visual-list">
      <!-- Slide 1 -->
      <li class="uw-visual-item">
        <div class="uw-visual-bg"
          style="background-image: url('<?php echo get_theme_file_uri('/assets/images/main_visual_01.png'); ?>')"></div>
        <div class="uw-visual-content">
          <div class="uw-visual-text-wrap">
            <div class="uw-visual-pagination">
              <button class="uw-visual-page" data-index="0">01</button>
              <button class="uw-visual-page" data-index="1">02</button>
              <button class="uw-visual-page" data-index="2">03</button>
            </div>
            <h2 class="uw-visual-title">Switch on To delight people</h2>
            <p class="uw-visual-desc">에너지 절감과 새로운 공간을 창조하는 스마트 틴팅 기술의 글로벌 기업이 되겠습니다.</p>
          </div>
        </div>
      </li>
      <!-- Slide 2 -->
      <li class="uw-visual-item">
        <div class="uw-visual-bg"
          style="background-image: url('<?php echo get_theme_file_uri('/assets/images/hero_slide_02.jpg'); ?>')"></div>
        <div class="uw-visual-content">
          <div class="uw-visual-text-wrap">
            <div class="uw-visual-pagination">
              <button class="uw-visual-page" data-index="0">01</button>
              <button class="uw-visual-page" data-index="1">02</button>
              <button class="uw-visual-page" data-index="2">03</button>
            </div>
            <h2 class="uw-visual-title">Connect Space To Smart World</h2>
            <p class="uw-visual-desc">단순한 유리를 넘어 공간의 가치를 높이는 독보적인 기술력으로 최상의 경험을 선사합니다.</p>
          </div>
        </div>
      </li>
      <!-- Slide 3 -->
      <li class="uw-visual-item is-active">
        <div class="uw-visual-bg"
          style="background-image: url('<?php echo get_theme_file_uri('/assets/images/hero_slide_03.jpg'); ?>')"></div>
        <div class="uw-visual-content">
          <div class="uw-visual-text-wrap">
            <div class="uw-visual-pagination">
              <button class="uw-visual-page" data-index="0">01</button>
              <button class="uw-visual-page" data-index="1">02</button>
              <button class="uw-visual-page is-active" data-index="2">03</button>
            </div>
            <h2 class="uw-visual-title">Innovation For Better Future</h2>
            <p class="uw-visual-desc">지속 가능한 성장과 신뢰를 바탕으로 더 나은 내일을 만들어가는 글로벌 리더가 되겠습니다.</p>
          </div>
        </div>
      </li>
    </ul>
  </section>
  <!-- 메인 비주얼 섹션 끝 -->

  <!-- 사업 안내 섹션 시작 -->
  <section class="uw-business">
    <div class="uw-business-header">
      <span class="uw-sub-title" data-animate="fade-up">Business</span>
      <h2 class="uw-title delay-200" data-animate="fade-up">주요 사업 안내</h2>
    </div>
    <div class="uw-container">
      <ul class="uw-business-list">
        <!-- Item 1 -->
        <li class="uw-business-item delay-200" data-animate="fade-up">
          <a href="<?php echo site_url('/business/areas/'); ?>" class="uw-business-link">
            <div class="uw-business-bg"
              style="background-image: url('<?php echo get_theme_file_uri('/assets/images/business_01.png'); ?>')">
            </div>
            <div class="uw-business-content">
              <div class="uw-business-text-wrapper">
                <span class="uw-business-num">01</span>
                <h3 class="uw-business-name">창호용 스마트틴팅 필름</h3>
                <div class="uw-business-hover">
                  <p class="uw-business-desc">고효율 기자재 인증 기술로<br>건물의 에너지 소비를 혁신하고 냉방 비용을 절감합니다.</p>
                  <span class="uw-business-arrow"></span>
                </div>
              </div>
            </div>
          </a>
        </li>
        <!-- Item 2 -->
        <li class="uw-business-item delay-400" data-animate="fade-up">
          <a href="<?php echo site_url('/business/areas/'); ?>" class="uw-business-link">
            <div class="uw-business-bg"
              style="background-image: url('<?php echo get_theme_file_uri('/assets/images/business_02.png'); ?>')">
            </div>
            <div class="uw-business-content">
              <div class="uw-business-text-wrapper">
                <span class="uw-business-num">02</span>
                <h3 class="uw-business-name">자동차용 스마트틴팅 필름</h3>
                <div class="uw-business-hover">
                  <p class="uw-business-desc">즉각적인 투과도 제어로 완벽한 프라이버시를 보호하고<br>자외선을 99% 이상 차단합니다.</p>
                  <span class="uw-business-arrow"></span>
                </div>
              </div>
            </div>
          </a>
        </li>
        <!-- Item 3 -->
        <li class="uw-business-item delay-600" data-animate="fade-up">
          <a href="<?php echo site_url('/business/areas/'); ?>" class="uw-business-link">
            <div class="uw-business-bg"
              style="background-image: url('<?php echo get_theme_file_uri('/assets/images/business_03.png'); ?>')">
            </div>
            <div class="uw-business-content">
              <div class="uw-business-text-wrapper">
                <span class="uw-business-num">03</span>
                <h3 class="uw-business-name">인테리어용 스마트틴팅 필름</h3>
                <div class="uw-business-hover">
                  <p class="uw-business-desc">하나의 유리를 투명과 불투명 모드로 즉시 전환하여<br>공간의 기능성과 효율을 극대화합니다.</p>
                  <span class="uw-business-arrow"></span>
                </div>
              </div>
            </div>
          </a>
        </li>
      </ul>
    </div>
  </section>
  <!-- 사업 안내 섹션 끝 -->

  <!-- 회사 소개 섹션 시작 -->
  <section class="uw-about" id="about">
    <div class="uw-about-container">
      <!-- Left Image -->
      <div class="uw-about-image">
        <div class="uw-about-image-wrapper" data-animate="reveal">
          <img src="<?php echo get_theme_file_uri('/assets/images/company_building.png'); ?>" alt="Company Building">
        </div>
      </div>

      <!-- Right Content -->
      <div class="uw-about-content">
        <span class="uw-about-label">About Us</span>
        <h2 class="uw-about-main-title" data-animate="fade-up">Global leaders<br>Smart Tinting Film</h2>
        <p class="uw-about-description" data-animate="fade-up">스마트 틴팅 기술로 에너지 절감과 새로운 공간을 창조하는 글로벌 기업이 되겠습니다.</p>
      </div>
    </div>
  </section>
  <!-- 회사 소개 섹션 끝 -->

  <!-- 제품 소개 섹션 시작 -->
  <section class="uw-product" id="product">
    <div class="uw-product-header">
      <span class="uw-sub-title" data-animate="fade-up">Product</span>
      <h2 class="uw-title delay-200" data-animate="fade-up">제품소개</h2>
    </div>
    <div class="uw-container">
      <ul class="uw-main-product-list">
        <!-- Item 1: General Type -->
        <li class="uw-product-item delay-200" data-animate="fade-up">
          <a href="<?php echo site_url('/product/'); ?>" class="uw-product-link">
            <div class="uw-main-product-bg"
              style="background-image: url('<?php echo get_theme_file_uri('/assets/images/product-1.png'); ?>')"></div>
            <div class="uw-main-product-content">
              <div class="uw-product-text-wrapper">
                <h3 class="uw-product-name">일반 타입</h3>
                <p class="uw-product-desc">인테리어용에 적합한 제품으로 높은 자외선 차단율(99% 이상)과 빠른 반응 속도를 제공합니다.</p>
              </div>
            </div>
          </a>
        </li>
        <!-- Item 2: High Efficiency Type -->
        <li class="uw-product-item delay-400" data-animate="fade-up">
          <a href="<?php echo site_url('/product/'); ?>" class="uw-product-link">
            <div class="uw-main-product-bg"
              style="background-image: url('<?php echo get_theme_file_uri('/assets/images/product-2.png'); ?>')"></div>
            <div class="uw-main-product-content">
              <div class="uw-product-text-wrapper">
                <h3 class="uw-product-name">고효율 타입</h3>
                <p class="uw-product-desc">냉방용 창호 및 차량 유리에 최적화된 제품으로 전력 소비는 낮고 자외선 차단율은 99% 이상입니다.</p>
              </div>
            </div>
          </a>
        </li>
      </ul>
    </div>
  </section>
  <!-- 제품 소개 섹션 끝 -->
  <!-- 기술 및 인증 섹션 시작 -->
  <section class="uw-tech">
    <!-- Background -->
    <div class="uw-tech-bg"></div>

    <!-- Content -->
    <div class="uw-tech-content">
      <div class="uw-tech-header">
        <h2 class="uw-tech-title" data-animate="fade-up">Technologies & Certifications</h2>
        <p class="uw-tech-desc delay-200" data-animate="fade-up">특허 인증으로 증명된 혁신과 기술 우위<br>독보적인 기술력으로 차별화된 솔루션을
          제공합니다.</p>
      </div>

      <!-- Marquee -->
      <div class="uw-tech-marquee">
        <div class="uw-tech-track">
          <!-- Set 1 -->
          <div class="uw-tech-item"><img src="<?php echo get_theme_file_uri('/assets/images/patent_01.png'); ?>"
              alt="Patent 1"></div>
          <div class="uw-tech-item"><img src="<?php echo get_theme_file_uri('/assets/images/patent_02.png'); ?>"
              alt="Patent 2"></div>
          <div class="uw-tech-item"><img src="<?php echo get_theme_file_uri('/assets/images/patent_03.png'); ?>"
              alt="Patent 3"></div>
          <div class="uw-tech-item"><img src="<?php echo get_theme_file_uri('/assets/images/patent_04.png'); ?>"
              alt="Patent 4"></div>
          <div class="uw-tech-item"><img src="<?php echo get_theme_file_uri('/assets/images/patent_05.png'); ?>"
              alt="Patent 5"></div>

          <!-- Set 2 (Duplicate for loop) -->
          <div class="uw-tech-item"><img src="<?php echo get_theme_file_uri('/assets/images/patent_01.png'); ?>"
              alt="Patent 1"></div>
          <div class="uw-tech-item"><img src="<?php echo get_theme_file_uri('/assets/images/patent_02.png'); ?>"
              alt="Patent 2"></div>
          <div class="uw-tech-item"><img src="<?php echo get_theme_file_uri('/assets/images/patent_03.png'); ?>"
              alt="Patent 3"></div>
          <div class="uw-tech-item"><img src="<?php echo get_theme_file_uri('/assets/images/patent_04.png'); ?>"
              alt="Patent 4"></div>
          <div class="uw-tech-item"><img src="<?php echo get_theme_file_uri('/assets/images/patent_05.png'); ?>"
              alt="Patent 5"></div>
        </div>
      </div>
    </div>
  </section>
  <!-- 기술 및 인증 섹션 끝 -->

  <!-- 커뮤니티 섹션 시작 -->
  <section class="uw-community">
    <div class="uw-community-header">
      <span class="uw-sub-title" data-animate="fade-up">Community</span>
      <h2 class="uw-title delay-200" data-animate="fade-up">커뮤니티</h2>
    </div>
    <div class="uw-container">

      <!-- WP: 동적 콘텐츠 시작 (커뮤니티 최신글) -->
      <?php echo do_shortcode('[latest_posts id="notice" url="/support/notice/" limit="3"]'); ?>

    </div>
  </section>
  <!-- 커뮤니티 섹션 끝 -->

  <!-- 컨택트 섹션 시작 -->
  <section class="uw-contact" id="contact">
    <div class="uw-container">
      <div class="uw-contact-left">
        <h2 class="uw-contact-slogan" data-animate="fade-up">
          스마트 틴팅의 기준,<br>
          <span>글로벌 표준 기술력으로</span>
          당신의 일상에 새로운 변화를 선사합니다.
        </h2>
      </div>
      <div class="uw-contact-right">
        <!-- Notice Card -->
        <a href="<?php echo site_url('/support/notice/'); ?>" class="uw-contact-card delay-200" data-animate="fade-up">
          <span class="uw-contact-card-icon notice"></span>
          <span class="uw-contact-card-title">공지사항</span>
          <span class="uw-contact-card-desc">에스티웍스의 새로운 소식과<br>공지사항을 확인하세요.</span>
          <span class="uw-contact-card-link">More view</span>
        </a>
        <!-- Inquiry Card -->
        <a href="<?php echo site_url('/contact/'); ?>" class="uw-contact-card" data-animate="fade-up"
          class="delay-1000">
          <span class="uw-contact-card-icon inquiry"></span>
          <span class="uw-contact-card-title">온라인 문의</span>
          <span class="uw-contact-card-desc">회사의 제품정보, 채용정보 등 궁금한 사항을 문의주시면<br>성실히 답변드리겠습니다.</span>
          <div class="uw-contact-info">
            <span class="uw-contact-email">contact@st-works.co.kr</span>
            <span class="uw-contact-tel">044-715-7050</span>
          </div>
        </a>
      </div>
    </div>
  </section>
  <!-- 컨택트 섹션 끝 -->

</main>
<!-- 메인 컨텐츠 끝 -->

<?php get_footer(); ?>