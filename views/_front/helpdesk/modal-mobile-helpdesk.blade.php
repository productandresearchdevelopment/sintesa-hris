<div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ticketModalLabel">Add Ticket</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="ticketForm" class="mb-0">
          @csrf
          <input type="hidden" id="userMentions" name="user_mentions" value="">
          <div class="mb-3">
            <label for="title" class="form-label">Title<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="title" name="title" required>
          </div>
          <div class="mb-3">
            <label for="message" class="form-label">Message<span class="text-danger">*</span></label>
            <textarea class="form-control" id="message" rows="3" name="message" required></textarea>
          </div>
          <div class="mb-3">
            <label for="category_id" class="form-label">Category<span class="text-danger">*</span></label>
            <select class="form-select" id="category_id" name="category_id" required>
              <option value="">Select Category</option>
              @foreach ($helpdesk_categories as $item)
                <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
              @endforeach
            </select>
          </div>
          <div class="position-relative mb-3" id="userDropdownContainer" style="display:none;">
            <div id="userDropdown" class="dropdown-menu p-2 shadow-sm"
              style="width: 100%; max-height: 130px; overflow-y: auto;">
              <input type="text" id="userSearchInput" placeholder="Search users..." class="form-control mb-2">
              <ul id="userList" class="list-group list-group-flush">
                <!-- User list items will be appended here -->
              </ul>
            </div>
          </div>
          <div id="file-container">
            <!-- File inputs will be dynamically added here -->
          </div>
          <button type="button" class="btn btn-outline-primary btn-sm mt-3" id="addFileBtn">Add File</button>
        </form>

        <div id="deleteForm">
          <p class="mb-0">
            Are you sure you want to <span class="fw-semibold">Close</span> this ticket?
          </p>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary fw-semibold" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary fw-semibold" id="saveTicketBtn">Save Ticket</button>
      </div>
    </div>
  </div>
</div>
