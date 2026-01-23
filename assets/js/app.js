/**
 * App.js - Bootstrap tooltips & Kakao Map initialization
 */

// Bootstrap Tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Kakao Map initialization
(function() {
  var mapDiv = document.querySelector('#map');

  // 지도 요소와 API가 없으면 종료
  if (!mapDiv || typeof kakao === 'undefined' || !kakao.maps) {
    return;
  }

  var options = {
    center: new kakao.maps.LatLng(37.57599184507025, 126.9769613271878),
    scrollwheel: false,
    level: 3
  };

  var map = new kakao.maps.Map(mapDiv, options);

  // 마커 추가
  var marker = new kakao.maps.Marker({
    position: new kakao.maps.LatLng(37.57599184507025, 126.9769613271878)
  });
  marker.setMap(map);

  // 지도 컨트롤 추가 (선택사항)
  var zoomControl = new kakao.maps.ZoomControl();
  map.addControl(zoomControl, kakao.maps.ControlPosition.RIGHT);
})();
