<?php
/*
Template Name: Company Location
*/
get_header(); ?>

<?php get_template_part('template-parts/sub-visual'); ?>

<section class="sub-page">
  <div class="uw-section-header scroll-trigger">
    <h2 class="uw-sub-page-title">
      에스티웍스에 <span class="uw-text-blue">오시는 길</span>을 안내해 드립니다.
    </h2>
  </div>

  <!-- 구글 지도 -->
  <div class="uw-location-map scroll-trigger">
    <!-- 
      [구글 지도 API 사용 가이드]
      1. 구글 클라우드 콘솔(https://console.cloud.google.com/) 접속
      2. 프로젝트 생성 및 'Maps Javascript API', 'Places API', 'Maps Embed API' 사용 설정
      3. 사용자 인증 정보(Credentials)에서 API 키 생성
      4. 아래 iframe의 src 주석을 해제하고 API 키를 입력하여 사용 (권장)
    -->

    <!-- API Key 방식 (권장) -->
    <iframe width="100%" height="100%" style="border:0" loading="lazy" allowfullscreen
      referrerpolicy="no-referrer-when-downgrade"
      src="https://www.google.com/maps/embed/v1/place?key=AIzaSyC-WFbtgBIXcBPsbcXG26ep50X7_HhNEvE&q=세종특별자치시+집현중앙2로+7">
    </iframe>

  </div>

  <div class="uw-location-map-header scroll-trigger delay-200">
    <h3 class="uw-location-company-name">(주)에스티웍스 본사</h3>
    <div class="uw-location-map-links">
      <a href="https://naver.me/xslXy3ME" target="_blank" class="uw-map-link naver">네이버 지도</a>
      <a href="https://kko.to/qDhcuy1VvR" target="_blank" class="uw-map-link kakao">카카오맵</a>
    </div>
  </div>

  <ul class="uw-location-info">
    <li class="uw-location-info-item addr-txt scroll-trigger">
      <div class="uw-location-info-content-wrapper">
        <h3>ADDRESS</h3>
        <p>세종특별자치시 집현중앙2로 7, 305호 (집현동, 코스모스빌딩)</p>
      </div>
    </li>
    <li class="uw-location-info-item tel-txt scroll-trigger delay-200">
      <div class="uw-location-info-content-wrapper">
        <h3>TEL</h3>
        <p>044-715-7050</p>
      </div>
    </li>
    <li class="uw-location-info-item fax-txt scroll-trigger delay-400">
      <div class="uw-location-info-content-wrapper">
        <h3>FAX</h3>
        <p>044-715-7051</p>
      </div>
    </li>
    <li class="uw-location-info-item email-txt scroll-trigger delay-600">
      <div class="uw-location-info-content-wrapper">
        <h3>E-MAIL</h3>
        <p>contact@stworks.co.kr</p>
      </div>
    </li>
  </ul>

</section>

<?php get_footer(); ?>