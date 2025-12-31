/* ===================================
   Community Data & Logic
   =================================== */

// Mock Data for Posts
const communityPosts = [
    {
        id: 1,
        category: "공지사항",
        title: "현장 개선 아이디어 공모 참여 협조 전 (요청)",
        date: "2024-03-13",
        content: `
            <p>반갑습니다. (주)동부 경영지원팀 입니다.</p><br>
            <p>상기 제목과 같이 현장 개선 아이디어 공모 참여 부탁드립니다.</p>
            <p>공문 및 첨부파일을 참고하시어 현장 개선 아이디어 공모에 적극 참여해주시기 바랍니다.</p><br>
            <p>임직원 여러분의 창의적인 아이디어가 우리 회사의 경쟁력입니다.</p>
            <p>많은 관심과 참여 부탁드립니다.</p><br>
            <p>감사합니다.</p>
        `
    },
    {
        id: 2,
        category: "공지사항",
        title: "2024년도 신입사원 공개채용 안내",
        date: "2024-02-20",
        content: `
            <p>2024년도 신입사원 공개채용을 진행합니다.</p><br>
            <p><strong>1. 모집 분야</strong>: 경영지원, 영업, 기술연구소</p>
            <p><strong>2. 접수 기간</strong>: 2024.02.20 ~ 2024.03.05</p>
            <p><strong>3. 지원 방법</strong>: 당사 채용 홈페이지를 통한 온라인 접수</p><br>
            <p>열정적이고 창의적인 인재 여러분의 많은 지원 바랍니다.</p>
            <p>자세한 내용은 채용 공고를 확인해 주시기 바랍니다.</p>
        `
    },
    {
        id: 3,
        category: "뉴스",
        title: "스마트 틴팅 필름 신제품 출시 기념 행사",
        date: "2024-01-15",
        content: `
            <p>스마트 틴팅 필름 신제품 출시를 기념하여 런칭 행사를 개최합니다.</p><br>
            <p>혁신적인 기술을 직접 체험해 보실 수 있는 자리에 여러분을 초대합니다.</p><br>
            <p><strong>일시</strong>: 2024년 1월 25일 (목) 14:00</p>
            <p><strong>장소</strong>: 본사 대강당</p><br>
            <p>참석하신 분들께는 소정의 기념품을 드립니다.</p>
        `
    }
];

// Utility: Get URL Parameter
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

// Utility: Generate or Get User ID
function getCurrentUserId() {
    let userId = localStorage.getItem('uw_user_id');
    if (!userId) {
        userId = 'user_' + Math.random().toString(36).substr(2, 9);
        localStorage.setItem('uw_user_id', userId);
    }
    return userId;
}

// Global State
let currentPostId = null;
let isAdminMode = false;

document.addEventListener('DOMContentLoaded', () => {
    const idParam = getQueryParam('id');
    currentPostId = idParam ? parseInt(idParam) : 1; // Default to 1 if no ID

    renderPost(currentPostId);
    renderNavigation(currentPostId);
    renderComments(currentPostId);

    // Event Listeners
    document.getElementById('btnSubmitComment').addEventListener('click', handleCommentSubmit);
    document.getElementById('toggleAdmin').addEventListener('change', (e) => {
        isAdminMode = e.target.checked;
        renderComments(currentPostId); // Re-render to update buttons
    });
});

// Render Post Content
function renderPost(id) {
    const post = communityPosts.find(p => p.id === id);
    if (!post) {
        document.querySelector('.uw-detail-container').innerHTML = '<h2>게시글을 찾을 수 없습니다.</h2><a href="index.html" class="uw-btn-back">목록으로</a>';
        return;
    }

    document.getElementById('postCategory').textContent = post.category;
    document.getElementById('postTitle').textContent = post.title;
    document.getElementById('postDate').textContent = post.date;
    document.getElementById('postContent').innerHTML = post.content;
}

// Render Navigation (Next/Prev)
function renderNavigation(id) {
    const currentIndex = communityPosts.findIndex(p => p.id === id);
    const prevPost = communityPosts[currentIndex - 1];
    const nextPost = communityPosts[currentIndex + 1];

    const navContainer = document.getElementById('postNavigation');
    let html = '';

    if (prevPost) {
        html += `<a href="community_view.html?id=${prevPost.id}" class="uw-nav-link prev">
                    <span class="uw-nav-label">이전글</span>
                    <span class="uw-nav-title">${prevPost.title}</span>
                 </a>`;
    } else {
        html += `<div class="uw-nav-link disabled"><span class="uw-nav-label">이전글</span><span class="uw-nav-title">이전 글이 없습니다.</span></div>`;
    }

    if (nextPost) {
        html += `<a href="community_view.html?id=${nextPost.id}" class="uw-nav-link next">
                    <span class="uw-nav-label">다음글</span>
                    <span class="uw-nav-title">${nextPost.title}</span>
                 </a>`;
    } else {
        html += `<div class="uw-nav-link disabled"><span class="uw-nav-label">다음글</span><span class="uw-nav-title">다음 글이 없습니다.</span></div>`;
    }

    navContainer.innerHTML = html;
}

// --- Comment System ---

function getComments(postId) {
    const comments = localStorage.getItem(`uw_comments_${postId}`);
    return comments ? JSON.parse(comments) : [];
}

function saveComments(postId, comments) {
    localStorage.setItem(`uw_comments_${postId}`, JSON.stringify(comments));
}

function renderComments(postId) {
    const comments = getComments(postId);
    const listContainer = document.getElementById('commentList');
    const currentUserId = getCurrentUserId();

    listContainer.innerHTML = '';

    if (comments.length === 0) {
        listContainer.innerHTML = '<li class="uw-no-comment">첫 번째 댓글을 남겨보세요!</li>';
        return;
    }

    comments.forEach(comment => {
        const isOwner = comment.userId === currentUserId;
        const canEdit = isOwner || isAdminMode;

        const li = document.createElement('li');
        li.className = 'uw-comment-item';
        li.innerHTML = `
            <div class="uw-comment-header">
                <span class="uw-comment-author">${isOwner ? '나' : '익명'}</span>
                <span class="uw-comment-date">${comment.date}</span>
            </div>
            <div class="uw-comment-body">
                <p class="uw-comment-text">${comment.text.replace(/\n/g, '<br>')}</p>
                ${canEdit ? `
                    <div class="uw-comment-actions">
                        ${isOwner ? `<button onclick="enableEditComment(${comment.id})" class="uw-btn-text">수정</button>` : ''}
                        <button onclick="handleDeleteComment(${comment.id})" class="uw-btn-text delete">삭제</button>
                    </div>
                ` : ''}
            </div>
            <div class="uw-comment-edit-form" id="editForm_${comment.id}" style="display:none;">
                <textarea class="uw-input-textarea" id="editText_${comment.id}">${comment.text}</textarea>
                <div class="uw-edit-btns">
                    <button onclick="handleUpdateComment(${comment.id})" class="uw-btn-sm primary">저장</button>
                    <button onclick="cancelEditComment(${comment.id})" class="uw-btn-sm">취소</button>
                </div>
            </div>
        `;
        listContainer.appendChild(li);
    });
}

function handleCommentSubmit() {
    const input = document.getElementById('commentInput');
    const text = input.value.trim();

    if (!text) {
        alert('댓글 내용을 입력해주세요.');
        return;
    }

    const newComment = {
        id: Date.now(),
        userId: getCurrentUserId(),
        text: text,
        date: new Date().toLocaleString()
    };

    const comments = getComments(currentPostId);
    comments.push(newComment);
    saveComments(currentPostId, comments);

    input.value = '';
    renderComments(currentPostId);
}

// Exposed globally for inline onclick handlers
window.handleDeleteComment = function (commentId) {
    if (!confirm('정말 삭제하시겠습니까?')) return;

    let comments = getComments(currentPostId);
    comments = comments.filter(c => c.id !== commentId);
    saveComments(currentPostId, comments);
    renderComments(currentPostId);
};

window.enableEditComment = function (commentId) {
    document.getElementById(`editForm_${commentId}`).style.display = 'block';
    document.querySelector(`#editForm_${commentId} textarea`).focus();
};

window.cancelEditComment = function (commentId) {
    document.getElementById(`editForm_${commentId}`).style.display = 'none';
};

window.handleUpdateComment = function (commentId) {
    const newText = document.getElementById(`editText_${commentId}`).value.trim();
    if (!newText) {
        alert('내용을 입력해주세요.');
        return;
    }

    let comments = getComments(currentPostId);
    const comment = comments.find(c => c.id === commentId);
    if (comment) {
        comment.text = newText;
        saveComments(currentPostId, comments);
        renderComments(currentPostId);
    }
};
