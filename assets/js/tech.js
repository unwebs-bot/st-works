function toggleCard(btn) {
  // 1. 버튼 상태 토글
  btn.classList.toggle('checked');

  // 2. 부모 카드 찾기
  const card = btn.closest('.tech-card');
  const currentPower = card.getAttribute('data-power');

  // 3. 상태 반전 (on <-> off)
  const newPower = currentPower === 'off' ? 'on' : 'off';
  card.setAttribute('data-power', newPower);

  // 4. 텍스트 라벨 활성화 변경
  const labels = btn.parentElement.querySelectorAll('.switch-txt');
  labels.forEach(label => label.classList.toggle('active'));
}
