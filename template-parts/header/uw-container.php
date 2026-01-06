<div class="uw-container">
  <!-- 로고 -->
  <h1 class="uw-logo">
    <a href="<?php echo home_url('/'); ?>">
      <img src="<?php echo get_theme_file_uri('/assets/images/logo.png'); ?>" alt="CreditConnect Logo">
    </a>
  </h1>

  <!-- 메인 네비게이션 (데스크탑) -->
  <nav class="uw-gnb">
    <ul class="uw-gnb-list">
      <li class="uw-gnb-item" data-target="sub-about">
        <a href="<?php echo home_url('/about/ceo/'); ?>" class="uw-gnb-link">About
          Us</a>
      </li>
      <li class="uw-gnb-item" data-target="sub-business">
        <a href="<?php echo home_url('/business/tech/'); ?>" class="uw-gnb-link">Business</a>
      </li>
      <li class="uw-gnb-item" data-target="sub-product">
        <a href="<?php echo home_url('/product/product/'); ?>" class="uw-gnb-link">Product</a>
      </li>
      <li class="uw-gnb-item" data-target="sub-support">
        <a href="<?php echo home_url('/support/notice/'); ?>" class="uw-gnb-link">Support</a>
      </li>
      <li class="uw-gnb-item" data-target="sub-contact">
        <a href="<?php echo home_url('/contact/contact/'); ?>" class="uw-gnb-link">Contact</a>
      </li>
    </ul>
  </nav>

  <!-- 유틸리티 버튼 -->
  <div class="uw-util">
    <button class="uw-btn-menu" id="uwBtnMenu" aria-label="메뉴 열기">
      <div class="uw-icon-grid">
        <span></span><span></span><span></span>
        <span></span><span></span><span></span>
        <span></span><span></span><span></span>
      </div>
    </button>
  </div>
</div>