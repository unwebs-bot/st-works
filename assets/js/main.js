document.addEventListener('DOMContentLoaded', () => {
    // 회사 소개 섹션 스크롤 애니메이션
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;

                element.classList.add('is-visible');
                observer.unobserve(element);
            }
        });
    }, observerOptions);

    // 모든 애니메이션 요소 관찰
    const animatedElements = document.querySelectorAll('[data-animate]');

    animatedElements.forEach(el => {
        observer.observe(el);
    });
});
