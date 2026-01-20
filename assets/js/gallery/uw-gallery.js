/**
 * UW Gallery Frontend JS (Vanilla JS)
 * 
 * 프론트엔드 기능: 라이트박스, 카테고리 필터, 슬라이드, 썸네일형
 * 
 * @package St-works
 * @since 2.0.0
 */

(function () {
  'use strict';

  const UWGallery = {

    init: function () {
      this.initLightbox();
      this.initCategoryFilter();
      this.initSlideLayout();
      this.initThumbnailLayout();
      this.initMasonry();
    },

    // === 라이트박스 ===
    initLightbox: function () {
      const galleries = document.querySelectorAll('.uw-gallery[data-lightbox="true"]');
      if (!galleries.length) return;

      // 라이트박스 컨테이너 생성
      if (!document.getElementById('uw-lightbox')) {
        const lightbox = document.createElement('div');
        lightbox.id = 'uw-lightbox';
        lightbox.className = 'uw-lightbox';
        lightbox.innerHTML = `
          <div class="uw-lightbox-overlay"></div>
          <div class="uw-lightbox-content">
            <button type="button" class="uw-lightbox-close" aria-label="닫기">×</button>
            <button type="button" class="uw-lightbox-prev" aria-label="이전">‹</button>
            <button type="button" class="uw-lightbox-next" aria-label="다음">›</button>
            <div class="uw-lightbox-media"></div>
            <div class="uw-lightbox-caption"></div>
          </div>
        `;
        document.body.appendChild(lightbox);

        // 이벤트
        lightbox.querySelector('.uw-lightbox-overlay').addEventListener('click', () => this.closeLightbox());
        lightbox.querySelector('.uw-lightbox-close').addEventListener('click', () => this.closeLightbox());
        lightbox.querySelector('.uw-lightbox-prev').addEventListener('click', () => this.navigateLightbox(-1));
        lightbox.querySelector('.uw-lightbox-next').addEventListener('click', () => this.navigateLightbox(1));

        // 키보드
        document.addEventListener('keydown', (e) => {
          if (!lightbox.classList.contains('is-open')) return;
          if (e.key === 'Escape') this.closeLightbox();
          if (e.key === 'ArrowLeft') this.navigateLightbox(-1);
          if (e.key === 'ArrowRight') this.navigateLightbox(1);
        });
      }

      // 링크 클릭
      document.querySelectorAll('.uw-gallery-link').forEach(link => {
        link.addEventListener('click', (e) => {
          e.preventDefault();
          this.openLightbox(link);
        });
      });
    },

    lightboxItems: [],
    lightboxIndex: 0,

    openLightbox: function (link) {
      const galleryId = link.dataset.lightbox;
      const items = document.querySelectorAll(`[data-lightbox="${galleryId}"]`);

      this.lightboxItems = Array.from(items);
      this.lightboxIndex = this.lightboxItems.indexOf(link);

      this.showLightboxItem(this.lightboxIndex);
      document.getElementById('uw-lightbox').classList.add('is-open');
      document.body.style.overflow = 'hidden';
    },

    closeLightbox: function () {
      const lightbox = document.getElementById('uw-lightbox');
      lightbox.classList.remove('is-open');
      document.body.style.overflow = '';

      // 미디어 정리 (비디오 정지)
      const media = lightbox.querySelector('.uw-lightbox-media');
      media.innerHTML = '';
    },

    navigateLightbox: function (direction) {
      this.lightboxIndex += direction;
      if (this.lightboxIndex < 0) this.lightboxIndex = this.lightboxItems.length - 1;
      if (this.lightboxIndex >= this.lightboxItems.length) this.lightboxIndex = 0;
      this.showLightboxItem(this.lightboxIndex);
    },

    showLightboxItem: function (index) {
      const item = this.lightboxItems[index];
      const lightbox = document.getElementById('uw-lightbox');
      const media = lightbox.querySelector('.uw-lightbox-media');
      const caption = lightbox.querySelector('.uw-lightbox-caption');

      const url = item.getAttribute('href');
      const type = item.dataset.type;
      const title = item.dataset.title || '';

      media.innerHTML = '';

      if (type === 'video') {
        const iframe = document.createElement('iframe');
        iframe.src = url;
        iframe.allowFullscreen = true;
        iframe.allow = 'autoplay; encrypted-media';
        media.appendChild(iframe);
      } else {
        const img = document.createElement('img');
        img.src = url;
        img.alt = title;
        media.appendChild(img);
      }

      caption.textContent = title;
    },

    // === 카테고리 필터 ===
    initCategoryFilter: function () {
      document.querySelectorAll('.uw-gallery-filter').forEach(filter => {
        const gallery = filter.closest('.uw-gallery');
        const items = gallery.querySelectorAll('.uw-gallery-item');

        filter.querySelectorAll('.uw-filter-btn').forEach(btn => {
          btn.addEventListener('click', () => {
            // 버튼 활성화
            filter.querySelectorAll('.uw-filter-btn').forEach(b => b.classList.remove('is-active'));
            btn.classList.add('is-active');

            const filterValue = btn.dataset.filter;

            items.forEach(item => {
              if (filterValue === '*') {
                item.style.display = '';
                item.classList.remove('is-hidden');
              } else {
                const cats = item.dataset.categories ? item.dataset.categories.split(',') : [];
                if (cats.includes(filterValue)) {
                  item.style.display = '';
                  item.classList.remove('is-hidden');
                } else {
                  item.style.display = 'none';
                  item.classList.add('is-hidden');
                }
              }
            });
          });
        });
      });
    },

    // === 슬라이드 레이아웃 ===
    initSlideLayout: function () {
      document.querySelectorAll('.uw-gallery-slide-layout').forEach(slider => {
        const slides = slider.querySelectorAll('.uw-gallery-slide');
        const dots = slider.querySelectorAll('.uw-slide-dot');
        const prevBtn = slider.querySelector('.uw-slide-prev');
        const nextBtn = slider.querySelector('.uw-slide-next');
        let currentIndex = 0;

        const showSlide = (index) => {
          if (index < 0) index = slides.length - 1;
          if (index >= slides.length) index = 0;
          currentIndex = index;

          slides.forEach((slide, i) => {
            slide.classList.toggle('is-active', i === index);
          });
          dots.forEach((dot, i) => {
            dot.classList.toggle('is-active', i === index);
          });
        };

        if (prevBtn) prevBtn.addEventListener('click', () => showSlide(currentIndex - 1));
        if (nextBtn) nextBtn.addEventListener('click', () => showSlide(currentIndex + 1));

        dots.forEach((dot, i) => {
          dot.addEventListener('click', () => showSlide(i));
        });

        // 터치 스와이프
        let touchStartX = 0;
        slider.addEventListener('touchstart', (e) => {
          touchStartX = e.touches[0].clientX;
        });
        slider.addEventListener('touchend', (e) => {
          const touchEndX = e.changedTouches[0].clientX;
          const diff = touchStartX - touchEndX;
          if (Math.abs(diff) > 50) {
            showSlide(currentIndex + (diff > 0 ? 1 : -1));
          }
        });
      });
    },

    // === 썸네일형 레이아웃 ===
    initThumbnailLayout: function () {
      document.querySelectorAll('.uw-gallery-thumbnail-layout').forEach(layout => {
        const mainImage = layout.querySelector('.uw-gallery-main-image img');
        const mainCaption = layout.querySelector('.uw-gallery-main-caption');
        const thumbs = layout.querySelectorAll('.uw-gallery-thumb-btn');

        thumbs.forEach(thumb => {
          thumb.addEventListener('click', () => {
            // 활성 상태 변경
            thumbs.forEach(t => t.classList.remove('is-active'));
            thumb.classList.add('is-active');

            // 메인 이미지 변경
            const fullUrl = thumb.dataset.full;
            const title = thumb.dataset.title || '';
            const desc = thumb.dataset.desc || '';

            if (mainImage) {
              mainImage.src = fullUrl;
              mainImage.alt = title;
            }

            // 캡션 변경
            if (mainCaption) {
              const titleEl = mainCaption.querySelector('.uw-gallery-main-title');
              const descEl = mainCaption.querySelector('.uw-gallery-main-desc');
              if (titleEl) titleEl.textContent = title;
              if (descEl) descEl.textContent = desc;
            }
          });
        });
      });
    },

    // === Masonry 레이아웃 (CSS Grid 기반) ===
    initMasonry: function () {
      // Masonry는 CSS Grid로 처리, JS에서는 레이지 로딩 후 재계산만 필요
      document.querySelectorAll('.uw-gallery--masonry .uw-gallery-grid').forEach(grid => {
        const images = grid.querySelectorAll('img');
        let loadedCount = 0;

        const checkAllLoaded = () => {
          loadedCount++;
          if (loadedCount === images.length) {
            grid.classList.add('is-loaded');
          }
        };

        images.forEach(img => {
          if (img.complete) {
            checkAllLoaded();
          } else {
            img.addEventListener('load', checkAllLoaded);
            img.addEventListener('error', checkAllLoaded);
          }
        });
      });
    }
  };

  // DOMContentLoaded
  document.addEventListener('DOMContentLoaded', function () {
    UWGallery.init();
  });

})();
