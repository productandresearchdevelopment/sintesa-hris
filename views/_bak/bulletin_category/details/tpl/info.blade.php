<div style="margin: 20px;">
  <div class="card border mb-0">
    <div class="card-body">
      <div class="d-flex align-items-center mb-3">
        <div class="me-3">
          <div class="rounded-circle d-flex align-items-center justify-content-center"
            style="width: 70px; height: 70px; background-color: ${data.background_color};">
            <h1 class="m-0" style="color: #${data.color};">T</h1>
          </div>
        </div>
        <div class="w-100">
          <h3 class="fw-bold mb-1" style="font-size: 18px;">Travels</h3>
          <div class="d-flex align-items-center">
            <div class="rounded-circle me-2"
              style="width: 10px; height: 10px; background-color: ${data.background_color};">
            </div>
            <span class="text-muted" style="color: #${data.color};">TV</span>
          </div>
        </div>
        <div>
          <a href="#" class="btn btn-outline-primary btn-sm">
            <strong>${data.bulletins ? data.bulletins.length : 0}</strong> Bulletin
          </a>
        </div>
      </div>

      <div class="mt-3">
        <h5>Description:</h5>
        <p class="fw-bold">${data.description || '-'}</p>
      </div>
    </div>
  </div>

  <hr class="my-4">

  <div class="section">
    <h5 class="fw-bold text-center">Bulletin List</h5>
    ${data.bulletins && data.bulletins.length > 0 ? data.bulletins.map(r => `
    <a href="#" class="text-decoration-none">
      <div class="card mb-3 border">
        <div class="card-body d-flex align-items-center">
          <div class="me-3">
            <img src="${r.image_url}" alt="avatar" class="rounded" style="width: 60px; height: 60px;">
          </div>
          <div class="flex-grow-1">
            <h6 class="mb-1" style="font-size: 14px;">${r.title || '-'}</h6>
            <div class="d-flex justify-content-between text-secondary">
              <div>${r.created_by ? r.created_by.name : '-'}</div>
              <div class="fst-italic">${r.created_at ? new Date(r.created_at).toLocaleDateString() : '-'}
              </div>
            </div>
          </div>
        </div>
      </div>
    </a>
    `).join('') : '<p class="text-muted">No bulletins available</p>'}
  </div>
</div>
