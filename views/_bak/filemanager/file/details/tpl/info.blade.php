<div class="p-4">
  <div class="section mt-2">
    <div class="card mb-0" style="border-bottom: 2px solid #ccc; border-radius: 0;">
      <div class="card-body d-flex justify-content-center">
        ${content}
      </div>
    </div>
  </div>
  <div class="section mt-2">
    <div class="card mb-0">
      <div class="card-body">
        <div class="mb-2">
          <strong class="fw-bold">Filename:</strong>
          <p class="mb-0">${data.name ?? '-'}</p>
        </div>
        <div class="mb-2">
          <strong class="fw-bold">Location:</strong>
          <p class="mb-0">${data.folder_name}</p>
        </div>
        <div class="mb-2">
          <strong class="fw-bold">Type File:</strong>
          <p class="mb-0">${data.type_file}</p>
        </div>
        <div class="mb-2">
          <strong class="fw-bold">Tag:</strong>
          <p class="mb-0">${data.tags ?? '-'}</p>
        </div>
        <div class="mb-2">
          <strong class="fw-bold">Size:</strong>
          <p class="mb-0">${data.size}</p>
        </div>
        <div class="mb-2">
          <strong class="fw-bold">Created At:</strong>
          <p class="mb-0">${data.created_at}</p>
        </div>
      </div>
    </div>
  </div>
</div>
