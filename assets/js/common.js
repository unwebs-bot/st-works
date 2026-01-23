document.addEventListener('DOMContentLoaded', () => {
  initHeader();
  initMainVisual();
  initHistoryAnimation();
  initScrollAnimations();
});

/* 1. 헤더 로직 */
function initHeader() {
  const header = document.getElementById('uwHeader');
  const gnbItems = document.querySelectorAll('.uw-gnb-item');
  const subLists = document.querySelectorAll('.uw-sub-list');
  const btnMenu = document.getElementById('uwBtnMenu');
  const btnClose = document.getElementById('uwBtnClose');
  const overlayMenu = document.getElementById('uwOverlayMenu');
  const fullItems = document.querySelectorAll('.uw-full-item');

  if (!header) return; // 예외 처리

  // 스티키 헤더
  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      header.classList.add('is-scrolled');
    } else {
      header.classList.remove('is-scrolled');
    }
  });

  // GNB 호버 효과
  gnbItems.forEach(item => {
    item.addEventListener('mouseenter', () => {
      const targetId = item.getAttribute('data-target');

      // 모든 서브 리스트 숨김
      subLists.forEach(list => {
        list.style.display = 'none';
        list.style.opacity = '0';
      });

      // 타켓 서브 리스트 표시
      const targetList = document.getElementById(targetId);
      if (targetList) {
        targetList.style.display = 'flex';
        setTimeout(() => {
          targetList.style.opacity = '1';
          targetList.style.transform = 'translateY(0)';
        }, 10);
      }

      header.classList.add('is-hover');
    });
  });

  header.addEventListener('mouseleave', () => {
    header.classList.remove('is-hover');
    subLists.forEach(list => {
      list.style.opacity = '0';
      list.style.transform = 'translateY(-10px)';
    });
  });

  // 전체 메뉴 토글
  if (btnMenu && overlayMenu) {
    // 메뉴 닫기 함수
    function closeOverlayMenu() {
      overlayMenu.classList.remove('is-active');
      fullItems.forEach((item) => {
        item.classList.remove('is-visible');
      });
      document.body.style.overflow = '';
    }

    // 메뉴 열기 함수
    function openOverlayMenu() {
      overlayMenu.classList.add('is-active');
      fullItems.forEach((item) => {
        item.classList.add('is-visible');
      });
      document.body.style.overflow = 'hidden';
    }

    btnMenu.addEventListener('click', openOverlayMenu);
    btnClose.addEventListener('click', closeOverlayMenu);

    // ESC 키로 메뉴 닫기 (접근성 개선)
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && overlayMenu.classList.contains('is-active')) {
        closeOverlayMenu();
      }
    });

    // 오버레이 배경 클릭으로 메뉴 닫기
    overlayMenu.addEventListener('click', (e) => {
      if (e.target === overlayMenu) {
        closeOverlayMenu();
      }
    });
  }
}

/* 2. 메인 비주얼 로직 */
function initMainVisual() {
  const visualSection = document.querySelector('.uw-visual');
  if (!visualSection) return;

  const slides = document.querySelectorAll('.uw-visual-item');
  const paginations = document.querySelectorAll('.uw-visual-page');
  const titles = document.querySelectorAll('.uw-visual-title');

  let currentIndex = 0;
  const slideCount = slides.length;
  const intervalTime = 4000; // 3초 유지 + 1초 전환 버퍼
  let sliderInterval;

  // 1. 웨이브 애니메이션을 위한 텍스트 분리
  titles.forEach(title => {
    const text = title.innerText; // "Switch on To delight people"
    const words = text.split(' ');
    title.innerHTML = ''; // 텍스트 초기화

    let charIndex = 0;
    words.forEach(word => {
      const wordSpan = document.createElement('span');
      wordSpan.classList.add('uw-visual-word');

      [...word].forEach(char => {
        const charSpan = document.createElement('span');
        charSpan.classList.add('uw-visual-char');
        charSpan.innerText = char;
        charSpan.style.transitionDelay = `${charIndex * 0.05}s`; // 순차적 지연
        wordSpan.appendChild(charSpan);
        charIndex++;
      });

      title.appendChild(wordSpan);
    });
  });

  // 2. 슬라이더 기능
  function changeSlide(index) {
    // 모든 슬라이드 활성화 해제
    slides.forEach(slide => slide.classList.remove('is-active'));

    // 모든 페이지네이션 버튼 활성화 해제
    paginations.forEach(page => page.classList.remove('is-active'));

    // 현재 슬라이드 활성화
    if (slides[index]) slides[index].classList.add('is-active');

    // 해당 페이지네이션 버튼 활성화
    const activePages = document.querySelectorAll(`.uw-visual-page[data-index="${index}"]`);
    activePages.forEach(page => page.classList.add('is-active'));

    currentIndex = index;
  }

  // 3. 자동 재생
  function startAutoPlay() {
    sliderInterval = setInterval(() => {
      let nextIndex = (currentIndex + 1) % slideCount;
      changeSlide(nextIndex);
    }, intervalTime);
  }

  function stopAutoPlay() {
    clearInterval(sliderInterval);
  }

  // 4. 이벤트 리스너
  paginations.forEach(page => {
    page.addEventListener('click', (e) => {
      const index = parseInt(e.currentTarget.getAttribute('data-index'));
      stopAutoPlay();
      changeSlide(index);
      startAutoPlay(); // 타이머 재시작
    });
  });

  // 초기화
  changeSlide(0);
  startAutoPlay();
}

/* 3. 연혁 애니메이션 로직 (스크롤 기반 연도 활성화) */
function initHistoryAnimation() {
  const yearGroups = document.querySelectorAll('.uw-history-year-group');
  if (yearGroups.length === 0) return;

  function updateActiveYear() {
    const windowHeight = window.innerHeight;
    const triggerPoint = windowHeight / 2;

    yearGroups.forEach(group => {
      const rect = group.getBoundingClientRect();
      const groupTop = rect.top;
      const groupBottom = rect.bottom;

      // 그룹이 뷰포트 중앙 근처에 있는지 확인
      if (groupTop < triggerPoint && groupBottom > triggerPoint) {
        group.classList.add('is-active');
      } else {
        group.classList.remove('is-active');
      }
    });
  }

  // 스크롤 이벤트 리스너
  window.addEventListener('scroll', () => {
    requestAnimationFrame(updateActiveYear);
  });

  // 초기 호출
  updateActiveYear();
}

/* 4. 공통 스크롤 트리거 애니메이션 (Fade-In Up) */
function initScrollAnimations() {
  const observerOptions = {
    threshold: 0.15
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('active');
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  document.querySelectorAll('.scroll-trigger').forEach(el => {
    observer.observe(el);
  });
}
