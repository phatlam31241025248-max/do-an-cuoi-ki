const postForm = async (url, payload) => {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
  if (payload instanceof FormData && csrfToken && !payload.has('_token')) { payload.append('_token', csrfToken); }
  const response = await fetch(url, {
    method: 'POST',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json'
    },
    body: payload
  });
  return response.json();
};

const showToast = (message) => {
  const box = document.createElement('div');
  box.className = 'position-fixed top-0 end-0 p-3';
  box.style.zIndex = 1080;
  box.innerHTML = `<div class="toast align-items-center text-bg-dark border-0 show"><div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto"></button></div></div>`;
  document.body.appendChild(box);
  box.querySelector('.btn-close').addEventListener('click', () => box.remove());
  setTimeout(() => box.remove(), 2500);
};

document.addEventListener('click', async (event) => {
  const likeBtn = event.target.closest('.js-like-btn');
  if (likeBtn) {
    const formData = new FormData();
    const result = await postForm(likeBtn.dataset.url, formData);
    showToast(result.message);
    if (result.status === 'success') {
      const card = likeBtn.closest('.review-card');
      card.querySelector('.js-helpful-count').textContent = result.data.helpful_count;
      likeBtn.classList.toggle('btn-primary', result.data.liked);
      likeBtn.classList.toggle('btn-light', !result.data.liked);
      likeBtn.querySelector('span').textContent = result.data.liked ? 'Liked' : 'Like';
    }
  }

  const followBtn = event.target.closest('.js-follow-btn');
  if (followBtn) {
    const result = await postForm(followBtn.dataset.url, new FormData());
    showToast(result.message);
    if (result.status === 'success') {
      followBtn.textContent = result.data.following ? 'Unfollow' : 'Follow';
      const count = document.getElementById('followers-count');
      if (count) count.textContent = result.data.followers_count;
    }
  }

  const bookmarkBtn = event.target.closest('.js-bookmark-btn');
  if (bookmarkBtn) {
    const result = await postForm(bookmarkBtn.dataset.url, new FormData());
    showToast(result.message);
    if (result.status === 'success') {
      bookmarkBtn.classList.toggle('btn-dark', result.data.bookmarked);
    }
  }

  const collectionBtn = event.target.closest('.js-collection-toggle-btn');
  if (collectionBtn) {
    const formData = new FormData();
    formData.append('place_id', collectionBtn.dataset.placeId);
    const result = await postForm(collectionBtn.dataset.url, formData);
    showToast(result.message);
    if (result.status === 'success') {
      collectionBtn.classList.toggle('btn-dark', result.data.in_collection);
      collectionBtn.classList.toggle('btn-light', !result.data.in_collection);
    }
  }

  const reportBtn = event.target.closest('.js-report-btn');
  if (reportBtn) {
    const reason = prompt('Nhập lý do report review này');
    if (!reason) return;
    const formData = new FormData();
    formData.append('reason', reason);
    const result = await postForm(reportBtn.dataset.url, formData);
    showToast(result.message);
  }

  const readBtn = event.target.closest('.js-read-btn');
  if (readBtn) {
    const formData = new FormData();
    formData.append('notification_id', readBtn.dataset.notificationId);
    const result = await postForm(readBtn.dataset.url, formData);
    showToast(result.message);
    if (result.status === 'success') {
      readBtn.closest('.notification-item').classList.remove('notification-unread');
      readBtn.remove();
    }
  }

  const readAllBtn = event.target.closest('.js-read-all-btn');
  if (readAllBtn) {
    const result = await postForm(readAllBtn.dataset.url, new FormData());
    showToast(result.message);
    if (result.status === 'success') {
      document.querySelectorAll('.notification-item').forEach(el => el.classList.remove('notification-unread'));
      document.querySelectorAll('.js-read-btn').forEach(el => el.remove());
    }
  }
});

document.addEventListener('submit', async (event) => {
  const commentForm = event.target.closest('.ajax-comment-form');
  if (!commentForm) return;
  event.preventDefault();
  const formData = new FormData(commentForm);
  const result = await postForm(commentForm.action, formData);
  showToast(result.message);
  if (result.status === 'success') {
    const reviewId = commentForm.dataset.reviewId;
    const list = document.querySelector(`[data-review-comments="${reviewId}"]`);
    const comment = result.data.comment;
    const item = document.createElement('div');
    item.className = 'd-flex gap-2 mb-2';
    item.innerHTML = `
      <img src="${(comment.avatar && /^(https?:)?\/\//.test(comment.avatar)) ? comment.avatar : `${window.FOODSPACE_BASE_URL}/${comment.avatar || 'assets/images/avatar-default.svg'}`}" class="avatar avatar-xs" alt="avatar">
      <div>
        <div class="small"><strong>@${comment.username || 'you'}</strong> · <span class="text-secondary">Vừa xong</span></div>
        <div>${comment.content}</div>
      </div>`;
    if (list.textContent.includes('Chưa có bình luận')) list.innerHTML = '';
    list.appendChild(item);
    const card = commentForm.closest('.review-card');
    card.querySelector('.js-comment-count').textContent = result.data.comment_count;
    commentForm.reset();
  }
});

const syncReviewStudioMode = () => {
  const newMode = document.getElementById('mode-new');
  if (!newMode) return;
  document.body.classList.toggle('mode-new-active', newMode.checked);
};

document.addEventListener('change', (event) => {
  if (event.target.matches('input[name="place_mode"]')) {
    syncReviewStudioMode();
  }
});



const bindImagePreviews = () => {
  document.querySelectorAll('.js-image-input').forEach((input) => {
    if (input.dataset.previewBound === '1') return;
    input.dataset.previewBound = '1';
    input.addEventListener('change', () => {
      const target = document.querySelector(input.dataset.previewTarget || '');
      if (!target) return;
      const file = input.files && input.files[0];
      if (!file) {
        target.src = '';
        target.classList.add('d-none');
        return;
      }
      const reader = new FileReader();
      reader.onload = (e) => {
        target.src = e.target?.result || '';
        target.classList.remove('d-none');
      };
      reader.readAsDataURL(file);
    });
  });
};

document.addEventListener('DOMContentLoaded', () => {
  syncReviewStudioMode();
  bindImagePreviews();
});
