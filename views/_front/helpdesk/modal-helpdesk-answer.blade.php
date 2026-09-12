<div class="modal fade" id="ticketAnswerModal" tabindex="-1" aria-labelledby="ticketAnswerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ticketAnswerModalLabel"></h5>
        <button type="button" class="btn-close" aria-label="Close" id="closeTicketAnswerModal"></button>
      </div>
      <div class="modal-body chat-container">
        <div class="position-relative mb-3" id="userDropdownContainerAnswer" style="display:none;">
          <div id="userDropdownAnswer" class="dropdown-menu p-2 shadow-sm"
            style="width: 100%; max-height: 420px; overflow-y: auto;">
            <input type="text" id="userSearchInputAnswer" placeholder="Search users..." class="form-control mb-2">
            <ul id="userListAnswer" class="list-group list-group-flush">
              <!-- User list items will be appended here -->
            </ul>
          </div>
        </div>
        <div id="chat-messages" class="chat-messages"></div>
        <div id="preview-files"></div>
      </div>
      <div class="modal-footer">
        <div class="d-flex align-items-center w-100">
          <div class="dropdown">
            <button class="btn btn-outline-secondary me-2" type="button" id="dropdownFileBtn" data-bs-toggle="dropdown"
              aria-expanded="false">
              <i class="bi bi-paperclip"></i>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownFileBtn">
              <li>
                <a class="dropdown-item" href="#" onclick="document.getElementById('imageInput').click()">
                  <i class="bi bi-image me-2"></i>
                  Image
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="#" onclick="document.getElementById('fileInput').click()">
                  <i class="bi bi-file-earmark-text me-2"></i>
                  File
                </a>
              </li>
            </ul>
          </div>

          <input type="file" id="imageInput" multiple class="d-none" accept="image/*">
          <input type="file" id="fileInput" multiple class="d-none" accept=".pdf, .docx, .txt, .zip, .rar">

          <input type="text" id="messageInput" class="form-control" placeholder="Type your message..." />

          <button type="button" class="btn btn-primary ms-2" id="sendMessageBtn">
            <i class="bi bi-send-fill"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="chatActionDropdown" class="dropdown-menu" style="display: none; position: absolute; z-index: 1100;">
  <button class="dropdown-item" id="editMessage">
    <i class="bi bi-pencil-fill"></i>
    Edit
  </button>
  <button class="dropdown-item" id="deleteMessage">
    <i class="bi bi-trash-fill"></i>
    Delete
  </button>
</div>
