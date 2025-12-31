<?php
/*
Template Name: Company Location
*/
get_header(); ?>

<section class="sub-visual about">
  <div class="sub-visual-bg"
    style="background-image: url('<?php echo get_theme_file_uri('/assets/images/company_building.png'); ?>');"></div>
  <div class="sub-visual-content">
    <h1 class="sub-page-title">오시는길</h1>
  </div>

  <div class="sub-lnb">
    <div class="uw-container">
      <ul class="sub-lnb-list">
        <li class="sub-lnb-item"><a href="<?php echo home_url('/about/ceo/'); ?>">CEO 인사말</a></li>
        <li class="sub-lnb-item"><a href="<?php echo home_url('/about/history/'); ?>">연혁</a></li>
        <li class="sub-lnb-item"><a href="<?php echo home_url('/about/vision/'); ?>">비전</a></li>
        <li class="sub-lnb-item active"><a href="<?php echo home_url('/about/location/'); ?>">오시는길</a></li>
      </ul>
    </div>
  </div>
</section>

<section class="sub-page">
  <div class="uw-section-header scroll-trigger">
    <h2 class="uw-sub-page-title">
      에스티웍스에 <span class="uw-text-blue">오시는 길</span>을 안내해 드립니다.
    </h2>
  </div>

  <!-- 네이버 지도 -->
  <div class="uw-location-map scroll-trigger">
    <!-- * 카카오맵 - 지도퍼가기 -->
    <!-- 1. 지도 노드 -->
    <div id="daumRoughmapContainer1735108039757" class="root_daum_roughmap root_daum_roughmap_landing"
      style="width:100%;"></div>

    <!-- 2. 설치 스크립트 (이미 enqueued 되어 있다고 가정하거나 여기서 직접 로드) -->
    <script charset="UTF-8" class="daum_roughmap_loader_script"
      src="https://ssl.daumcdn.net/dmaps/map_js_init/roughmapLoader.js"></script>

    <!-- 3. 실행 스크립트 -->
    <script charset="UTF-8">
      new daum.roughmap.Lander({
        "timestamp": "1735108039757",
        "key": "2mmkm",
        "mapWidth": "100%",
        "mapHeight": "500"
      }).render();
    </script>
  </div>

  <div class="uw-location-map-header scroll-trigger delay-200">
    <h3 class="uw-location-company-name">(주)에스티웍스 본사</h3>
    <div class="uw-location-map-links">
      <a href="https://naver.me/xv3iR1zO" target="_blank" class="uw-map-link naver">네이버 지도</a>
      <a href="https://kko.to/1X2Y3Z4A5" target="_blank" class="uw-map-link kakao">카카오맵</a>
    </div>
  </div>

  <ul class="uw-location-info scroll-trigger delay-400">
    <li class="uw-location-info-item addr-txt">
      <div class="uw-location-info-content-wrapper">
        <h3>ADDRESS</h3>
        <p>세종특별자치시 집현중앙2로 7, 305호 (집현동, 코스모스빌딩)</p>
      </div>
    </li>
    <li class="uw-location-info-item tel-txt">
      <div class="uw-location-info-content-wrapper">
        <h3>TEL</h3>
        <p>044-715-7050</p>
      </div>
    </li>
    <li class="uw-location-info-item fax-txt">
      <div class="uw-location-info-content-wrapper">
        <h3>FAX</h3>
        <p>044-715-7051</p>
      </div>
    </li>
    <li class="uw-location-info-item email-txt">
      <div class="uw-location-info-content-wrapper">
        <h3>E-MAIL</h3>
        <p>contact@stworks.co.kr</p>
      </div>
    </li>
  </ul>

</section>

<?php get_footer(); ?>