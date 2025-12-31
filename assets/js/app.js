var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

const mapDiv = document.querySelector('#map')
const options = {
  center: new daum.maps.LatLng(37.57599184507025, 126.9769613271878),
  scrollwheel: false,
  level: 3
}
const imageSrc = '/assets/images/bootstrap-logo.png'
const imageSize = new kakao.maps.Size(48, 48)

const marker = new daum.maps.Marker({
  image: new kakao.maps.MarkerImage(imageSrc, imageSize),
  position: new daum.maps.LatLng(37.57599184507025, 126.9769613271878)
})

const map = new daum.maps.Map(mapDiv, options)
marker.setMap(map)
