<?php
/**
 * Template Name: Latest Posts Test
 * 최신글 숏코드 테스트 페이지
 */
get_header();
?>

<style>
  .latest-posts-test {
    max-width: 1200px;
    margin: 50px auto;
    padding: 0 20px;
  }

  .test-section {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }

  .test-section h2 {
    color: #1a365d;
    border-bottom: 2px solid #3182ce;
    padding-bottom: 10px;
    margin-bottom: 20px;
  }

  .test-section h3 {
    color: #2d3748;
    margin: 25px 0 15px;
  }

  .code-block {
    background: #1e293b;
    color: #e2e8f0;
    padding: 15px 20px;
    border-radius: 8px;
    font-family: 'Monaco', 'Menlo', monospace;
    font-size: 14px;
    overflow-x: auto;
    margin: 15px 0;
  }

  .output-preview {
    background: #f8fafc;
    border: 2px dashed #cbd5e0;
    border-radius: 8px;
    padding: 20px;
    margin: 15px 0;
  }

  .output-preview h4 {
    color: #718096;
    font-size: 12px;
    text-transform: uppercase;
    margin-bottom: 15px;
  }

  /* 최신글 리스트 스타일링 */
  .uw-latest-posts {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .uw-latest-posts li {
    border-bottom: 1px solid #e2e8f0;
  }

  .uw-latest-posts li:last-child {
    border-bottom: none;
  }

  .uw-latest-posts li a {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    color: #2d3748;
    text-decoration: none;
    transition: color 0.2s;
  }

  .uw-latest-posts li a:hover {
    color: #3182ce;
  }

  .uw-latest-posts .title {
    flex: 1;
    font-weight: 500;
  }

  .uw-latest-posts .date {
    color: #a0aec0;
    font-size: 13px;
    margin-left: 20px;
  }

  .uw-latest-posts .no-posts {
    color: #a0aec0;
    text-align: center;
    padding: 20px;
  }

  .guide-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
  }

  .guide-table th,
  .guide-table td {
    border: 1px solid #e2e8f0;
    padding: 12px 15px;
    text-align: left;
  }

  .guide-table th {
    background: #f7fafc;
    font-weight: 600;
    color: #2d3748;
  }

  .guide-table code {
    background: #edf2f7;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 13px;
  }

  .note {
    background: #ebf8ff;
    border-left: 4px solid #3182ce;
    padding: 15px 20px;
    border-radius: 0 8px 8px 0;
    margin: 20px 0;
  }

  .note strong {
    color: #2b6cb0;
  }
</style>

<div class="latest-posts-test">
  <h1>📋 최신글 숏코드 사용 가이드</h1>

  <!-- 사용법 가이드 -->
  <div class="test-section">
    <h2>🚀 기본 사용법</h2>

    <p>다양한 페이지(메인, 사이드바 등)에서 게시판의 최신 글을 호출할 수 있는 숏코드입니다.</p>

    <h3>숏코드 형식</h3>
    <div class="code-block">
      [latest_posts id="게시판슬러그" limit="출력개수" url="이동페이지경로"]
    </div>

    <h3>파라미터 설명</h3>
    <table class="guide-table">
      <thead>
        <tr>
          <th>파라미터</th>
          <th>설명</th>
          <th>필수</th>
          <th>기본값</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><code>id</code></td>
          <td>게시판 슬러그 (게시판 설정에서 확인)</td>
          <td>✅ 필수</td>
          <td>-</td>
        </tr>
        <tr>
          <td><code>limit</code></td>
          <td>출력할 게시글 개수</td>
          <td>선택</td>
          <td>5</td>
        </tr>
        <tr>
          <td><code>url</code></td>
          <td>게시글 클릭 시 이동할 URL</td>
          <td>선택</td>
          <td>-</td>
        </tr>
      </tbody>
    </table>

    <div class="note">
      <strong>💡 Tip:</strong> 게시판 설정 페이지에서 "최신글 숏코드(Shortcode)" 섹션에서 쉽게 숏코드를 생성할 수 있습니다!
    </div>
  </div>

  <!-- 실제 테스트 -->
  <div class="test-section">
    <h2>📌 실제 테스트 - 공지사항 최신글 3개</h2>

    <h3>사용한 숏코드</h3>
    <div class="code-block">
      [latest_posts id="notice" limit="3" url="/support/notice/"]
    </div>

    <h3>출력 결과</h3>
    <div class="output-preview">
      <h4>▼ 최신글 리스트</h4>
      <?php echo do_shortcode('[latest_posts id="notice" limit="3" url="/support/notice/"]'); ?>
    </div>
  </div>

  <!-- 다양한 예시 -->
  <div class="test-section">
    <h2>📝 다양한 사용 예시</h2>

    <h3>예시 1: 기본 사용 (5개)</h3>
    <div class="code-block">
      [latest_posts id="notice"]
    </div>

    <h3>예시 2: 3개만 표시</h3>
    <div class="code-block">
      [latest_posts id="notice" limit="3"]
    </div>

    <h3>예시 3: URL 지정과 함께</h3>
    <div class="code-block">
      [latest_posts id="notice" limit="5" url="/support/notice/"]
    </div>

    <h3>예시 4: 메인페이지 사이드바용</h3>
    <div class="code-block">
      [latest_posts id="news" limit="3" url="/support/news/"]
    </div>
  </div>

  <!-- HTML 출력 구조 -->
  <div class="test-section">
    <h2>🔧 출력 HTML 구조</h2>

    <p>숏코드는 다음과 같은 구조로 렌더링됩니다:</p>

    <div class="code-block" style="white-space: pre;">&lt;ul class="uw-latest-posts"&gt;
      &lt;li&gt;
      &lt;a href="이동URL?view=게시글ID"&gt;
      &lt;span class="title"&gt;게시글 제목&lt;/span&gt;
      &lt;span class="date"&gt;2026.01.08&lt;/span&gt;
      &lt;/a&gt;
      &lt;/li&gt;
      ...
      &lt;/ul&gt;</div>

    <div class="note">
      <strong>💡 스타일링:</strong> 위 HTML 구조에 맞춰 CSS를 커스터마이징하면 다양한 디자인을 적용할 수 있습니다.
    </div>
  </div>
</div>

<?php get_footer(); ?>