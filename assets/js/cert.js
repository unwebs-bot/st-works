// [Lightbox Logic]
const lightbox = document.getElementById('lightbox');
const lightboxImg = document.getElementById('lightbox-img');

function openLightbox(element) {
  if (!lightbox || !lightboxImg) return;
  const img = element.querySelector('img');
  if (img) {
    lightboxImg.src = img.src;
    lightbox.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
}

function closeLightbox(event) {
  if (!lightbox || !lightboxImg) return;
  if (event.target !== lightboxImg) {
    lightbox.classList.remove('active');
    document.body.style.overflow = '';
  }
}

// [Scroll Animation (Fade In Up)]
// Moved to common.js (initScrollAnimations)

