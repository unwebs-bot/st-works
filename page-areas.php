<?php
/*
Template Name: Business Areas
*/
get_header(); ?>

<?php get_template_part('template-parts/sub-visual'); ?>

<section class="sub-page areas">
  <div class="uw-section-header scroll-trigger">
    <h2 class="uw-sub-page-title">사업분야</h2>
  </div>
  <div class="uw-areas-container">
    <section class="business-section">
      <div class="slider-wrapper">

        <div class="slider-viewport">
          <div class="slider-track" id="sliderTrack">

            <div class="slide-item">
              <div class="img-box">
                <img src="https://cdn.imweb.me/thumbnail/20250703/b034d6287ad63.png" alt="Windows and Doors">
              </div>
              <div class="txt-box">
                <h5 class="tit-en">Windows and Doors / Fenestration</h5>
                <p class="tit">창호</p>
              </div>
            </div>

            <div class="slide-item">
              <div class="img-box">
                <img src="https://cdn.imweb.me/thumbnail/20250703/b78136ab1f238.png" alt="Architectural Glass">
              </div>
              <div class="txt-box">
                <h5 class="tit-en">Architectural Glass</h5>
                <p class="tit">인테리어용 유리</p>
              </div>
            </div>

            <div class="slide-item">
              <div class="img-box">
                <img src="https://cdn.imweb.me/thumbnail/20250703/030256907159f.png" alt="Auto glass">
              </div>
              <div class="txt-box">
                <h5 class="tit-en">Auto glass</h5>
                <p class="tit">자동차용 유리</p>
              </div>
            </div>

          </div>
        </div>

        <button class="nav-btn prev" onclick="moveSlide(-1)" aria-label="Previous">
          <svg viewBox="0 0 24 24">
            <polyline points="15 18 9 12 15 6"></polyline>
          </svg>
        </button>
        <button class="nav-btn next" onclick="moveSlide(1)" aria-label="Next">
          <svg viewBox="0 0 24 24">
            <polyline points="9 18 15 12 9 6"></polyline>
          </svg>
        </button>

        <div class="slider-dots" id="sliderDots"></div>
      </div>
    </section>
  </div>
</section>

<script src="<?php echo get_theme_file_uri('/assets/js/business_slider.js'); ?>"></script>

<?php get_footer(); ?>