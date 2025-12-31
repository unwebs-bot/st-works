document.addEventListener('DOMContentLoaded', () => {
  const track = document.getElementById('sliderTrack');
  // If slider elements don't exist, exit to avoid errors
  if (!track) return;

  const slides = document.querySelectorAll('.slide-item');
  const dotsContainer = document.getElementById('sliderDots');
  let currentIndex = 0;
  const totalSlides = slides.length;
  let autoPlayInterval;

  // [Init] Dots
  if (dotsContainer) {
    dotsContainer.innerHTML = ''; // Clear if any
    slides.forEach((_, index) => {
      const dot = document.createElement('div');
      dot.classList.add('dot');
      if (index === 0) dot.classList.add('active');
      dot.addEventListener('click', () => goToSlide(index));
      dotsContainer.appendChild(dot);
    });
  }
  const dots = document.querySelectorAll('.dot');

  // [Main Move Function]
  function updateSlider() {
    if (!track) return;
    track.style.transform = `translateX(-${currentIndex * 100}%)`;

    dots.forEach((dot, index) => {
      dot.classList.toggle('active', index === currentIndex);
    });
  }

  // Expose moveSlide globally if used in inline HTML onclick
  window.moveSlide = function (direction) {
    currentIndex += direction;
    if (currentIndex < 0) {
      currentIndex = totalSlides - 1;
    } else if (currentIndex >= totalSlides) {
      currentIndex = 0;
    }
    updateSlider();
  };

  function goToSlide(index) {
    currentIndex = index;
    updateSlider();
  }

  // [Auto Play]
  function startAutoPlay() {
    stopAutoPlay();
    autoPlayInterval = setInterval(() => moveSlide(1), 5000);
  }
  function stopAutoPlay() {
    clearInterval(autoPlayInterval);
  }

  // [Touch & Drag Logic]
  let isDragging = false;
  let startPos = 0;
  let currentTranslate = 0;
  let prevTranslate = 0;
  let animationID;
  const minSwipeDistance = 50;
  let initialTransition = track.style.transition;

  track.addEventListener('touchstart', touchStart, { passive: true });
  track.addEventListener('touchend', touchEnd);
  track.addEventListener('touchmove', touchMove, { passive: true });

  track.addEventListener('mousedown', touchStart);
  track.addEventListener('mouseup', touchEnd);
  track.addEventListener('mouseleave', () => {
    if (isDragging) touchEnd();
  });
  track.addEventListener('mousemove', touchMove);

  function touchStart(event) {
    stopAutoPlay();
    isDragging = true;
    startPos = getPositionX(event);
    animationID = requestAnimationFrame(animation);
    track.style.transition = 'none';
  }

  function touchMove(event) {
    if (!isDragging) return;
    // In a real implementation we would visually drag the track here
  }

  function touchEnd(event) {
    isDragging = false;
    cancelAnimationFrame(animationID);
    track.style.transition = 'transform 0.5s cubic-bezier(0.25, 1, 0.5, 1)';

    const endPos = (event.type.includes('mouse') || event.type === 'mouseleave')
      ? event.pageX
      : (event.changedTouches ? event.changedTouches[0].clientX : startPos);

    const diff = endPos - startPos;

    if (Math.abs(diff) > minSwipeDistance) {
      if (diff < 0) moveSlide(1);
      else moveSlide(-1);
    } else {
      updateSlider();
    }
    startAutoPlay();
  }

  function getPositionX(event) {
    return event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
  }

  function animation() {
    if (isDragging) requestAnimationFrame(animation);
  }

  // Init
  updateSlider();
  startAutoPlay();

  // Mouse Hover Pause
  const wrapper = document.querySelector('.slider-wrapper');
  if (wrapper) {
    wrapper.addEventListener('mouseenter', stopAutoPlay);
    wrapper.addEventListener('mouseleave', startAutoPlay);
  }
});
