// Location Page - Map & Animation Script

(function() {
    'use strict';

    // Wait for DOM to be fully loaded
    window.addEventListener('DOMContentLoaded', function() {
        initializeMap();
        initializeAnimations();
    });

    // Initialize Kakao Map
    function initializeMap() {
        // Note: Replace 'YOUR_APP_KEY_HERE' in the HTML with actual Kakao Map API key
        // For now, using a fallback Google Maps embed as demonstration
        
        const mapContainer = document.getElementById('map');
        if (!mapContainer) return;

        // Company location coordinates (Cosmos Building, Sejong)
        const companyAddress = '세종특별자치시 집현중앙2로 7';
        
        // Check if Kakao Maps API is available
        if (typeof kakao !== 'undefined' && kakao.maps) {
            kakao.maps.load(function() {
                const mapOption = {
                    center: new kakao.maps.LatLng(36.4801, 127.2890), // Sejong City coordinates
                    level: 3
                };

                const map = new kakao.maps.Map(mapContainer, mapOption);
                
                // Add marker
                const markerPosition = new kakao.maps.LatLng(36.4801, 127.2890);
                const marker = new kakao.maps.Marker({
                    position: markerPosition,
                    map: map
                });

                // Add info window
                const iwContent = '<div style="padding:10px 15px; font-size:14px; text-align:center;">(주)에스티웍스<br>코스모스빌딩 305호</div>';
                const infowindow = new kakao.maps.InfoWindow({
                    content: iwContent,
                    removable: false
                });
                infowindow.open(map, marker);

                // Trigger animations after map loads
                setTimeout(triggerInfoAnimations, 500);
            });
        } else {
            // Fallback: Display static Google Maps embed
            console.warn('Kakao Maps API not loaded. Using fallback.');
            const iframe = document.createElement('iframe');
            iframe.src = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3206.5!2d127.289!3d36.4801!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzbCsDI4JzQ4LjQiTiAxMjfCsDE3JzIwLjQiRQ!5e0!3m2!1sko!2skr!4v1234567890';
            iframe.width = '100%';
            iframe.height = '100%';
            iframe.style.border = '0';
            iframe.allowFullscreen = true;
            iframe.loading = 'lazy';
            mapContainer.appendChild(iframe);

            // Trigger animations after iframe loads
            iframe.onload = function() {
                setTimeout(triggerInfoAnimations, 500);
            };
        }
    }

    // Cascade Fade-in Animation for Info Items
    function triggerInfoAnimations() {
        const infoItems = document.querySelectorAll('.uw-location-info-item');
        
        infoItems.forEach(function(item, index) {
            const delay = parseInt(item.getAttribute('data-delay')) || (index * 100);
            
            setTimeout(function() {
                item.classList.add('is-visible');
            }, delay);
        });
    }

    // Intersection Observer for scroll-triggered animations (optional enhancement)
    function initializeAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    // Animation will be triggered by map load callback
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const locationInfo = document.querySelector('.uw-location-info');
        if (locationInfo) {
            observer.observe(locationInfo);
        }
    }
})();
