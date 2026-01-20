<!-- 전체 메뉴 오버레이 (모바일/전체보기) -->
<div class="uw-overlay-menu" id="uwOverlayMenu">
  <button class="uw-btn-close" id="uwBtnClose" aria-label="메뉴 닫기"></button>

  <div class="uw-overlay-left">
    <img src="<?php echo get_theme_file_uri('/assets/images/overlay_visual_v2.jpg'); ?>" alt="Visual">
  </div>

  <div class="uw-overlay-right">
    <ul class="uw-full-gnb">
      <li class="uw-full-item">
        <a href="<?php echo home_url('/about/ceo/'); ?>" class="uw-full-link">About Us</a>
        <div class="uw-full-sub">
          <a href="<?php echo home_url('/about/ceo/'); ?>">CEO 인사말</a>
          <a href="<?php echo home_url('/about/history/'); ?>">연혁</a>
          <a href="<?php echo home_url('/about/vision/'); ?>">비전</a>
          <a href="<?php echo home_url('/about/location/'); ?>">오시는길</a>
        </div>
      </li>
      <li class="uw-full-item">
        <a href="<?php echo home_url('/business/tech/'); ?>" class="uw-full-link">Business</a>
        <div class="uw-full-sub">
          <a href="<?php echo home_url('/business/tech/'); ?>">핵심기술</a>
          <a href="<?php echo home_url('/business/areas/'); ?>">사업분야</a>
          <a href="<?php echo home_url('/business/cert/'); ?>">특허&인증</a>
        </div>
      </li>
      <li class="uw-full-item">
        <a href="<?php echo home_url('/product'); ?>" class="uw-full-link">Product</a>
        <div class="uw-full-sub">
          <a href="<?php echo home_url('/product/product/'); ?>">제품소개</a>
        </div>
      </li>
      <li class="uw-full-item">
        <a href="<?php echo home_url('/support/notice/'); ?>" class="uw-full-link">Support</a>
        <div class="uw-full-sub">
          <a href="<?php echo home_url('/support/notice/'); ?>">공지사항</a>
          <a href="<?php echo home_url('/support/news/'); ?>">뉴스</a>
        </div>
      </li>
      <li class="uw-full-item">
        <a href="<?php echo home_url('/contact/contact/'); ?>" class="uw-full-link">Contact</a>
        <div class="uw-full-sub">
          <a href="<?php echo home_url('/contact/contact/'); ?>">상담 문의</a>
        </div>
      </li>
    </ul>
  </div>
</div>