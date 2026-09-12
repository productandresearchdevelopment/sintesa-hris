@extends('templates.mobile')

@section('head')
  <style>
    .card {
      border: none;
    }

    .btn-gray {
      background-color: #f8f9fa;
      color: var(--bs-secondary-color);
      border: 1px solid #ddd;
      font-weight: 500;
      font-size: 1rem
    }

    .btn-gray:hover {
      background: #ddd
    }

    .btn-primary {
      font-size: 1rem;
      font-weight: 500;
      border: none
    }

    #sendMessageBtn {
      width: 46px;
      height: 46px;
      border-radius: 50%;
      background: #0056b3;
      border: none;
    }

    #sendMessageBtn i {
      color: #fff;
    }

    #dropdownFileBtn {
      border: none;
      background: none;
    }

    #dropdownFileBtn i {
      font-size: 1.7rem;
      color: black;
      font-weight: 700
    }

    .input-message {
      width: 100%;
      padding-right: 50px;
      border-radius: 20px;
      border: 1px solid #ced4da;
      margin-right: 5px
    }

    .ticket .card-body {
      border: 1px solid #ddd;
      border-radius: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .ticket .card-text {
      color: #6c757d;
      font-size: 1rem;
      font-weight: 300;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .ticket .detail-icon {
      font-weight: 500;
    }

    .ticket .ticket-status {
      font-size: 1rem;
      font-weight: 500;
      padding: 6px 1.4rem;
      border-radius: 20px;
      color: #fff;
      align-items: center
    }

    .ticket .dropdown .action-dropdown {
      border: none;
      background-color: #D5E6FA;
      height: 36px;
      width: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0;
    }

    .removeFileBtn {
      width: 38px !important;
      height: 38px !important;
      padding: 0 !important;
      font-size: 14px !important;
      font-weight: 700 !important;
      background-color: red !important;
      color: white !important;
      border: none !important;
      cursor: pointer !important;
    }

    /* chat */
    .chat-avatar {
      width: 30px;
      height: 30px;
      border-radius: 50%;
    }

    .chat-messages {
      width: 100%;
      display: flex;
      flex-direction: column;
    }

    .chat-message {
      width: 100%;
      display: flex;
      gap: 0.5rem;
    }

    .chat-message-right {
      justify-content: flex-end;
      text-align: right;
    }

    .chat-message-left {
      justify-content: flex-start;
      text-align: left;
    }

    .chat-message-left .message-bubble,
    .chat-message-right .message-bubble {
      width: 70%;
    }

    .message-bubble {
      padding: 5px 10px;
      border-radius: 10px 10px 0 10px;
      background-color: transparent;
      color: #007bff;
      position: relative;
      box-shadow: none;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .chat-message-right .message-bubble {
      align-items: flex-end;
    }

    .message-bubble span {
      background-color: #007bff;
      color: white;
      padding: 5px 10px;
      border-radius: 10px 10px 0 10px;
      display: inline-block;
    }

    .chat-message-right .message-bubble span {
      background-color: #0056b3;
    }

    .chat-message-right .message-bubble {
      align-self: flex-end;
    }

    .chat-message-left .message-bubble {
      align-self: flex-start;
    }

    .message-sender {
      color: var(--gray-medium);
      font-size: 0.875rem;
      font-weight: bold;
    }

    .chat-message-right .message-bubble .message-text-bubble {
      background-color: #0056b3;
      color: white;
      padding: 10px;
      border-radius: 12px 12px 0 12px;
      margin-bottom: 5px;
      display: inline-block;
      max-width: calc(100% - 5px);
      word-wrap: break-word;
      text-align: left
    }

    .chat-message-right .message-bubble .message-time {
      text-align: right;
      align-self: flex-end;
    }

    .chat-message-left .message-bubble .message-time {
      text-align: left;
      align-self: flex-start;
    }

    .chat-message-right .message-file-bubble {
      background-color: #e3f2fd;
      align-self: flex-end;
    }

    .chat-message-left .message-file-bubble {
      background-color: #ffffff;
      align-self: flex-start;
    }

    .chat-message-left .message-bubble .message-text-bubble {
      background-color: #4f4f4f;
      color: white;
      padding: 10px;
      border-radius: 0 12px 12px 12px;
      margin-bottom: 5px;
      display: inline-block;
      max-width: calc(100% - 5px);
      word-wrap: break-word;

    }

    .message-file-bubble {
      background-color: #f8f9fa;
      padding: 10px;
      border: 1px solid #ced4da;
      border-radius: 10px;
      word-wrap: break-word;
      overflow: hidden;
    }

    .input-message {
      padding: 10px 10px
    }

    #preview-files {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .preview-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      border: 1px solid #ddd;
      border-radius: 5px;
      background-color: #f9f9f9;
      max-width: 100px;
      overflow: hidden;
    }

    .preview-item img {
      object-fit: cover;
      width: 100%;
      height: auto;
      border-radius: 5px;
    }

    .preview-item p {
      font-size: 12px;
      word-wrap: break-word;
      text-align: center;
      margin-top: 5px;
      color: #555;
    }

    .custom-backdrop {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      z-index: 1040;
    }

    .focused-bubble {
      z-index: 1060;
      position: relative;
      background-color: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }
  </style>
@endsection

@section('content')
  <div class="px-3" style="min-height: 100%; min-width: 100%; padding-bottom: 100px;">
    <div class="py-3">
      <div class="d-flex align-items-center gap-4" style="cursor: pointer;" onclick="history.back()">
        <i class="bi bi-chevron-left" style="font-size: 1.2rem;font-weight: 500;"></i>
        <p class="m-0" style="font-size: 1.4rem; font-weight: 500;">Helpdesk</p>
      </div>
    </div>

    <div class="col">
      <div class="row align-items-center my-3">
        <div class="col-4">
          <div class="dropdown filter-container">
            <button class="btn btn-light dropdown-toggle w-100" style="border: 1px solid #ddd;" type="button"
              id="statusFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              Status
            </button>
            <ul class="dropdown-menu" aria-labelledby="statusFilterDropdown" id="filterDropdown">
              <li><a class="dropdown-item" data-filter="all" href="#">All</a></li>
              <li><a class="dropdown-item" data-filter="pending" href="#">Pending</a></li>
              <li><a class="dropdown-item" data-filter="discussed" href="#">Discussed</a></li>
              <li><a class="dropdown-item" data-filter="closed" href="#">Closed</a></li>
            </ul>
          </div>
        </div>
        <div class="col-8">
          <button class="btn btn-primary" id="button-add-ticket">
            <i class="bi bi-plus-lg"></i> New Ticket
          </button>
        </div>
      </div>

      <div class="position-relative my-3">
        <input id="search" type="text" class="form-control bg-light h border-light rounded" placeholder="Search..."
          style="height: 44px; font-size: 1.2rem;">
      </div>

      <div class="d-flex gap-3 mb-3" id="ticket-filter">
        <button class="btn btn-primary btn-sm" id="button-helpdesk">Helpdesk</button>
        <button class="btn btn-primary btn-sm" id="button-mentions">Mentions</button>
      </div>

      <div id="ticket-list">
      </div>

      <div class="text-center">
        <button class="btn btn-primary mt-4" id="load-more" style="display: none;">Load More</button>
      </div>

    </div>
  </div>



  <!-- Modal Ticket -->
  @require('modal-mobile-helpdesk')

  <!-- Modal Answer Ticket -->
  @require('modal-mobile-helpdesk-answer')

  <script>
    $(document).ready(function() {
      const user = @json($user);
      const userMentions = new Set();
      let allTickets = [];
      let allAnswerTickets = [];
      let visibleTickets = [];
      let query = '';
      let filter = 'helpdesk';
      let statusFilter = 'all';
      let loadLimit = 10;
      let currentIndex = 0;
      let currentTicket = null;
      let maxFiles = 4;
      let fileCount = 0;
      let userListData = [];
      let existingFiles = [];
      let isPreviewFilesAnswer = false;
      let isAnswerModal = false;
      let newFilesAnswerTicket = [];
      let filesToRemoveAnswerTicket = [];

      const mapStatusToColor = (status) => {
        const statusColors = {
          pending: "warning",
          discussed: "success",
          closed: "danger",
        };
        return statusColors[status?.toLowerCase()] || "secondary";
      };

      const formatTime = (timestamp) => {
        const now = new Date();
        const createdDate = new Date(timestamp);
        const diffTime = now - createdDate;

        const minutes = Math.floor(diffTime / (1000 * 60));
        const hours = Math.floor(diffTime / (1000 * 60 * 60));
        const days = Math.floor(diffTime / (1000 * 60 * 60 * 24));

        if (minutes < 60) return `${minutes} minute${minutes > 1 ? "s" : ""} ago`;
        if (hours < 24) return `${hours} hour${hours > 1 ? "s" : ""} ago`;
        if (days <= 3) return `${days} day${days > 1 ? "s" : ""} ago`;

        return createdDate.toLocaleDateString("en-US", {
          year: "numeric",
          month: "short",
          day: "numeric",
        });
      };

      const parseRelativeTime = (relativeTime) => {
        const now = new Date();
        const [value, unit] = relativeTime.split(" ");

        let parsedDate = new Date(now);
        const timeValue = parseInt(value);

        switch (unit) {
          case "minute":
          case "minutes":
            parsedDate.setMinutes(now.getMinutes() - timeValue);
            break;
          case "hour":
          case "hours":
            parsedDate.setHours(now.getHours() - timeValue);
            break;
          case "day":
          case "days":
            parsedDate.setDate(now.getDate() - timeValue);
            break;
          default:
            parsedDate = new Date(relativeTime);
            break;
        }

        return parsedDate;
      };


      $('#button-add-ticket').on('click', function() {
        currentTicket = null;
        openTicketModal('', 'add');
      });

      $(document).on('input', '#message, #messageInput, #editMessageInputAnswerTicket', function() {
        const cursorPos = this.selectionStart;
        const messageValue = this.value;

        if (messageValue.charAt(cursorPos - 1) === '@') {
          $('#userDropdownContainer, #userDropdownContainerAnswer, #userDropdownContainerAnswerEdit').show();
          $('#userDropdown, #userDropdownAnswer, #userDropdownAnswerEdit').show().css({
            top: 0,
            left: 0
          });
          $('#userSearchInput, #userSearchInputAnswer, #userSearchInputAnswerEdit').focus();

          $.ajax({
            url: `{{ route('auth.user.data') }}`,
            method: 'GET',
            success: function(data) {
              const userMentionsIds = (currentTicket?.user_mentions || []).map((mention) => mention.id);
              userListData = currentTicket && isAnswerModal ?
                data.data.filter((user) => userMentionsIds.includes(user.id)) :
                data.data;

              displayUserList('');
            },
          });
        }

        const mentionsInText = new Set();
        messageValue.match(/@(\w+)/g)?.forEach(match => {
          const name = match.slice(1).replace(/\s+/g, '');
          const userDataMatches = userListData.filter(user => user.name.replace(/\s+/g, '') === name);

          if (userDataMatches.length > 0) {
            userDataMatches.forEach(user => mentionsInText.add(user.id));
          }
        });
        userMentions.clear();
        mentionsInText.forEach(id => userMentions.add(id));

        $('#user_mentions').val(JSON.stringify(Array.from(userMentions)));
      });

      $(document).on('focus', '#message, #messageInput, #editMessageInputAnswerTicket', function() {
        $('#userDropdownContainer, #userDropdownContainerAnswer, #userDropdownContainerAnswerEdit').hide();
        $('#userDropdown, #userDropdownAnswer, #userDropdownAnswerEdit').hide();

        $('#userSearchInput, #userSearchInputAnswer, #userSearchInputAnswerEdit').val('');
      });

      $('#userSearchInput, #userSearchInputAnswer, #userSearchInputAnswerEdit').on('input', function() {
        const query = $(this).val().toLowerCase();
        displayUserList(query);
      });

      function displayUserList(query) {
        $('#userList, #userListAnswer, #userListAnswerEdit').empty();
        userListData.forEach(user => {
          if (user.name.toLowerCase().includes(query) && !userMentions.has(user.id)) {
            $('#userList, #userListAnswer, #userListAnswerEdit').append(
              `<li class="list-group-item list-group-item-action" data-user-id="${user.id}" data-name="${user.name}">${user.name}</li>`
            );
          }
        });
      }

      $(document).on('click', '#userList li, #userListAnswer li, #userListAnswerEdit li', function() {
        const userId = $(this).data('user-id');
        const name = $(this).data('name').replace(/\s+/g, '');

        if (!userMentions.has(userId)) {
          userMentions.add(userId);

          $('#message, #messageInput, #editMessageInputAnswerTicket').val(function(i, text) {
            const cursorPos = $('#message, #messageInput, #editMessageInputAnswerTicket')[0].selectionStart;
            return text.slice(0, cursorPos) + `${name} ` + text.slice(cursorPos);
          });

          $('#user_mentions').val(JSON.stringify(Array.from(userMentions)));

          $('#userSearchInput, #userSearchInputAnswer, #userSearchInputAnswerEdit').val('');
          $('#userDropdownContainer, #userDropdownContainerAnswer, #userDropdownContainerAnswerEdit').hide();
          $('#message, #messageInput, #editMessageInputAnswerTicket').focus();
        }
      });

      $(document).on('click', function(e) {
        if (!$(e.target).closest(
            '#userDropdown, #userDropdownAnswer, #userDropdownAnswerEdit, #message, #messageInput, #editMessageInputAnswerTicket'
          ).length) {
          $('#userDropdownContainer, #userDropdownContainerAnswer, #userDropdownContainerAnswerEdit').hide();
        }
      });

      function createFileInput(index, fileId = '', fileName = '', isRemovable = true) {
        return `
        <div>
          <label class="form-label" data-label="${index}">File ${index + 1}:</label>
          <div class="d-flex align-items-center justify-content-between gap-1 file-input mb-2" data-index="${index}" data-id="${fileId}">
            ${fileName
                ? `<span class="w-100 padding-0">${fileName}</span>`
                : `<input type="file" class="form-control" name="files[]" accept="image/*,application/pdf">`}
            ${isRemovable ? '<button type="button" class="btn btn-sm btn-danger  removeFileBtn">X</button>' : ''}
          </div>
        </div>`;
      }

      function renderFileInputs(files = []) {
        $('#file-container').empty();
        existingFiles = files.map(file => file.id);
        fileCount = files.length || 1;

        if (fileCount === 1 && files.length === 0) {
          const isRemovable = false;
          $('#file-container').append(createFileInput(0, '', '', isRemovable));
        } else {
          files.forEach((file, index) => {
            const isRemovable = true;
            $('#file-container').append(createFileInput(index, file.id, file.filename_origin, isRemovable));
          });
        }

        updateAddFileButton();
      }

      function updateAddFileButton() {
        $('#addFileBtn').css('display', fileCount < maxFiles ? 'block' : 'none');
      }

      $('#addFileBtn').on('click', function() {
        if (fileCount < maxFiles) {
          $('#file-container').append(createFileInput(fileCount));
          fileCount++;
          updateAddFileButton();
        }
      });

      $(document).on('click', '.removeFileBtn', function() {
        const parentDiv = $(this).closest('.file-input');
        const index = parentDiv.data('index');
        const fileId = parentDiv.data('id');

        existingFiles = existingFiles.filter(id => id !== fileId);

        parentDiv.prev('label[data-label="' + index + '"]').remove();
        parentDiv.remove();
        fileCount--;
        updateAddFileButton();

        $('#file-container .file-input').each(function(newIndex) {
          const label = $(this).prev('label');
          label.text(`File ${newIndex + 1}:`);
          label.attr('data-label', newIndex);
          $(this).attr('data-index', newIndex);
        });
      });

      window.openTicketModal = function(ticket = null, mode = 'add') {
        isAnswerModal = false;
        currentTicket = ticket ? JSON.parse(ticket) : null;
        const parseTicket = currentTicket ? currentTicket : null;

        const modalTitle = mode === 'delete' ? 'Close Ticket' : (ticket ? 'Edit Ticket' : 'Add Ticket');
        $('#ticketModalLabel').text(modalTitle);

        $('#ticketForm')[0].reset();

        // Ubah kelas modal-dialog sesuai mode
        if (mode === 'delete') {
          $('.modal-dialog').removeClass('modal-fullscreen modal-dialog-scrollable').addClass(
            'modal-dialog-centered');
          $('#ticketForm').hide();
          $('#deleteForm').show();
          $('#saveTicketBtn').text('Close Ticket');
        } else {
          $('.modal-dialog').removeClass('modal-dialog-centered').addClass(
            'modal-fullscreen modal-dialog-scrollable');
          $('#ticketForm').show();
          $('#deleteForm').hide();

          if (parseTicket) {
            $('#saveTicketBtn').text('Update Ticket');
            $('#title').val(parseTicket.title);
            $('#message').val(parseTicket.message);
            renderFileInputs(parseTicket.uploads || []);
            if (parseTicket.user_mentions.length > 0) {
              parseTicket.user_mentions.forEach(userMention => {
                userMentions.add(userMention.id);
              });
            }
          } else {
            $('#saveTicketBtn').text('Add Ticket');
            renderFileInputs();
          }
        }

        $('#ticketModal').modal('show');
      };


      window.openAnswerTicketModal = function(ticket) {
        isAnswerModal = true;
        currentTicket = JSON.parse(ticket);
        fetchAnswerTickets();
        $('#ticketAnswerModalLabel').text(currentTicket.title);
        $('#ticketAnswerModal #messageInput').val('');
        newFilesAnswerTicket = [];
        filesToRemoveAnswerTicket = [];
        $('#ticketAnswerModal').modal('show');
      }

      $('#saveTicketBtn').on('click', function(e) {
        e.preventDefault();

        const buttonText = $(this).text().trim();

        if (buttonText === 'Close Ticket') {
          const url = '{{ route('helpdesk.destroy') }}';

          const formData = new FormData();
          formData.append('_method', 'DELETE');
          formData.append('_token', '{{ csrf_token() }}');
          formData.append('data', JSON.stringify([currentTicket.id]));

          $.ajax({
            url: url,
            method: 'POST',
            contentType: false,
            processData: false,
            data: formData,
            success: function(response) {
              showAlert('success', response.message);
              $('#ticketModal').modal('hide');
              fetchTickets();
            },
            error: function(xhr) {
              console.error(xhr.responseText);
            }
          });
        } else {
          const formData = new FormData(document.getElementById('ticketForm'));

          $('#file-container .file-input input[type="file"]').each(function(index) {
            if (this.files[0]) {
              formData.append(`files[${index}]`, this.files[0]);
            }
          });

          if (userMentions.size > 0) {
            const userMentionIds = Array.from(userMentions);
            formData.set('user_mentions', JSON.stringify(userMentionIds));
          } else {
            formData.set('user_mentions', JSON.stringify([]));
          }

          const url = currentTicket ?
            '{{ route('helpdesk.update', ':id') }}'.replace(':id', currentTicket.id) :
            '{{ route('helpdesk.store') }}';

          if (currentTicket) {
            formData.append('_method', 'PUT');
          }

          if (existingFiles.length > 0) {
            existingFiles.forEach((fileId, index) => {
              formData.append(`existing_files[]`, fileId);
            });
          }

          $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              showAlert('success', response.message);
              $('#ticketModal').modal('hide');
              fetchTickets();
            },
            error: function(xhr) {
              console.error(xhr.responseText);
            }
          });
        }
      });

      function initializeFilter() {
        filterTickets();
        $('#button-helpdesk').removeClass('btn-gray').addClass('btn-primary active');
        $('#button-mentions').removeClass('btn-primary active').addClass('btn-gray');
      }

      $('#button-helpdesk').on('click', function() {
        currentIndex = 0;
        filter = 'helpdesk';
        filterTickets();
        $(this).removeClass('btn-gray').addClass('btn-primary active');
        $('#button-mentions').removeClass('btn-primary active').addClass('btn-gray');
      });

      $('#button-mentions').on('click', function() {
        currentIndex = 0;
        filter = 'mentions';
        filterTickets();
        $(this).removeClass('btn-gray').addClass('btn-primary active');
        $('#button-helpdesk').removeClass('btn-primary active').addClass('btn-gray');
      });

      function filterTickets() {
        const filtered = allTickets.filter(ticket => {
          const matchesQuery = query === '' || ticket.title.toLowerCase().includes(query.toLowerCase());

          let matchesStatus = false;
          if (filter === 'helpdesk') {
            matchesStatus = ticket.created_by?.id === user.id;
          } else if (filter === 'mentions') {
            matchesStatus = ticket.user_mentions?.some(mention => mention.id === user.id);
          }

          if (statusFilter !== 'all') {
            matchesStatus = matchesStatus && (ticket?.status?.toLowerCase() === statusFilter.toLowerCase());
          }

          return matchesQuery && matchesStatus;
        });

        if (currentIndex === 0) {
          visibleTickets = [];
        }

        const newTickets = filtered.slice(currentIndex, Math.min(currentIndex + loadLimit, filtered.length));

        if (newTickets.length > 0) {
          visibleTickets = visibleTickets.concat(newTickets);
        }

        renderTickets(newTickets);

        $('#load-more').toggle(newTickets.length > 0 && visibleTickets.length < filtered.length);
      }

      function fetchTickets() {
        showLoading();

        $.ajax({
          url: '{{ route('helpdesk.data') }}',
          method: 'GET',
          data: {
            query: query,
            trash: 1
          },
          success: function(response) {
            allTickets = response.data.filter(ticket => {
              const isCreatedByUser = ticket.created_by?.id === user.id;
              const isMentioned = ticket.user_mentions?.some(mention => mention.id === user.id);
              return isCreatedByUser || isMentioned;
            });
            currentIndex = 0;
            filterTickets();
          },
          error: function() {
            console.error('Failed to fetch tickets.');
          },
          complete: function() {
            hideLoading();
          }
        });
      }

      function renderTickets(tickets) {
        const ticketList = $('#ticket-list');

        if (currentIndex === 0) {
          ticketList.empty();
        }

        if (tickets.length === 0 && currentIndex === 0) {
          ticketList.append(`
            <div class="card-body">
              <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                <img src="{{ asset('/images/nodata.png') }}" alt="No Tickets Found" class="img-fluid" style="max-width: 300px; max-height: 300px;" />
                <h3 class="text-center font-bold text-black mb-0">No Tickets Found</h3>
              </div>
            </div>
          `);
          return;
        }

        tickets.forEach(ticket => {
          const ticketData = encodeURIComponent(JSON.stringify(ticket));

          const card = `
            <div class="ticket card mb-3 fade-in">
              <div class="card-body">
                <div class="ticket-title-container ">
                  <h5 class="card-title text-black mb-0">${ticket.title}</h5>
                </div>
                <p class="card-text my-2">${ticket.message}</p>
                <div class="row align-items-center">
                    <div class="d-flex flex-row flex-wrap justify-content-between my-2">
                        <div class="detail-icon d-flex align-items-center gap-2">
                            <i class="bi bi-person text-muted"></i>
                            <span class="text-muted small">${ticket.user_mentions.length} Mentions</span>
                        </div>
                        <div class="detail-icon d-flex align-items-center gap-2">
                            <i class="bi bi-chat text-muted"></i>
                            <span class="text-muted small">${ticket.answers.length} Answers</span>
                        </div>
                        <div class="detail-icon d-flex align-items-center gap-2">
                            <i class="bi bi-clock text-muted"></i>
                            <span class="text-muted small">${formatTime(ticket.created_at)}</span>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <div class="d-flex justify-content-between">
                            <div class="status">
                                <div class="bg-${mapStatusToColor(ticket?.status)} ticket-status">
                                    ${ticket?.status?.toUpperCase()}
                                </div>
                            </div>
                            <div class="dropdown ms-2">
                                ${ticket.created_by.id === user.id ?
                                `
                                                                                                <button class="action-dropdown" type="button" id="dropdownMenuButton-${ticket.id}" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                                    <i class="bi bi-three-dots" style="font-size: 1.5rem; font-weight: 500;"></i>
                                                                                                </button>
                                                                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-${ticket.id}">
                                                                                                    ${ticket.created_by.id === user.id ?
                                                                                                    `<li>
                                <button class="dropdown-item" onclick="openAnswerTicketModal(decodeURIComponent('${ticketData}'))">
                                    <i class="bi bi-eye"></i> View
                                </button>
                                </li>
                                <li>
                                    <button class="dropdown-item" onclick="openTicketModal(decodeURIComponent('${ticketData}'), 'edit')">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item" onclick="openTicketModal(decodeURIComponent('${ticketData}'), 'delete')">
                                        <i class="bi bi-trash3"></i> Close
                                    </button>
                                </li>
                                ` : `
                                <li><button class="dropdown-item disabled">No Actions Available</button></li>`}
                                                                                                </ul>
                                                                                                `: `
                                                                                                <button class="action-dropdown" type="button" aria-expanded="false" onclick="openAnswerTicketModal(decodeURIComponent('${ticketData}'))">
                                                                                                    <i class="bi bi-chat" style="font-size: 1.3rem; font-weight: 500;"></i>
                                                                                                </button>`}
                            </div>
                        </div>
                    </div>

                </div>
              </div>
            </div>`;
          ticketList.append(card);

          const newElement = ticketList.children('.fade-in').last();
          newElement.get(0).offsetWidth;
          newElement.on('animationend', function() {
            $(this).removeClass('fade-in');
          });
        });
      }

      function fetchAnswerTickets() {
        showLoading();

        const url = '{{ route('helpdesk.answer.get.helpdesk', ':id') }}'.replace(':id', currentTicket.id);

        $.ajax({
          url: url,
          method: 'GET',
          success: function(response) {
            allAnswerTickets = response.data;
            renderAnswerTickets(response.data);
          },
          error: function(xhr) {
            console.error('Failed to fetch tickets:', xhr.responseText);
          },
          complete: function() {
            hideLoading();
          },
        });
      }

      function renderAnswerTickets(answer) {
        const $chatMessages = $("#chat-messages");
        $chatMessages.empty();

        $.each(answer, function(index, msg) {
          const isOwnMessage = msg.created_by.id === user.id;
          const positionClass = isOwnMessage ? "chat-message-right" : "chat-message-left";

          let fileContent = "";
          let chatBubbleVisible = true;

          if (msg.uploads.length > 0) {
            if (msg.uploads.length === 1) {
              const file = msg.uploads[0];
              const extension = file.filename_origin.split('.').pop().toLowerCase();
              const fileUrl = `{{ route('file', ':id') }}`.replace(':id', file.id);

              if (["jpg", "jpeg", "png", "gif"].includes(extension)) {
                fileContent += `
              <div class='d-flex justify-content-center'>
                  <img src="${fileUrl}" class="img-fluid clickable-file" style="width: 100px; max-width: 100%;" alt="Image" data-url="${fileUrl}">
              </div>
          `;
              } else {
                fileContent += `
              <div class="d-flex align-items-center gap-3">
                  <i class="bi bi-file-earmark-fill" style="font-size: 24px; color: #007bff;"></i>
                  <a href="${fileUrl}" download class="text-muted" style="text-align:left" >
                      ${file.filename_origin}
                  </a>
              </div>
          `;
              }
            } else {
              fileContent += `<div class="row w-100">`;
              $.each(msg.uploads, function(_, file) {
                const extension = file.filename_origin.split('.').pop().toLowerCase();
                const fileUrl = `{{ route('file', ':id') }}`.replace(':id', file.id);

                if (["jpg", "jpeg", "png", "gif"].includes(extension)) {
                  fileContent += `
                <div class="col-md-6" style="padding: 12px; box-sizing: border-box;">
                    <img src="${fileUrl}" class="img-fluid rounded clickable-file" style="height: 150px; width: 100%; object-fit: cover;" alt="Image" data-url="${fileUrl}">
                </div>
            `;
                } else {
                  fileContent += `
                <div class="col-md-6 d-flex align-items-center gap-3" style="padding: 12px; box-sizing: border-box;">
                    <i class="bi bi-file-earmark-fill" style="font-size: 24px; color: #007bff;"></i>
                    <a href="${fileUrl}" download class="text-muted" style="text-align:left" >
                        ${file.filename_origin}
                    </a>
                </div>
            `;
                }
              });
              fileContent += `</div>`;
            }
          }

          const avatarUrl = msg.created_by.photo_id ?
            `{{ route('file', ':id') }}`.replace(':id', msg.created_by.photo_id) :
            '{{ asset('images/nouser.png') }}';

          const nameSender = isOwnMessage ? '' : `
                <p class="message-sender font-bold text-uppercase mb-2">
                    ${msg.created_by.username}
                </p>
            `;

          const messageInfoSender = isOwnMessage ? '' : `
                <div class="message-info">
                    <img src="${avatarUrl}" alt="Avatar" class="chat-avatar">
                </div>
            `;

          const messageTemplate = `
            <div class="chat-message ${positionClass}">
                ${messageInfoSender}
                <div class="message-bubble" data-id="${msg.id}">
                    ${nameSender}
                    ${fileContent ? `<div class="message-file-bubble">${fileContent}</div>` : ""}
                    ${msg.message ? `<div class="message-text-bubble mt-1">${msg.message}</div>` : ""}
                    <div class="message-time text-muted small">${formatTime(msg.created_at)}</div>
                </div>
            </div>
        `;

          $chatMessages.append(messageTemplate);
        });

        $chatMessages.scrollTop($chatMessages[0].scrollHeight);

        $(".clickable-file").on("click", function() {
          const imageUrl = $(this).data("url");
          window.open(imageUrl, '_blank');
        });

      }

      $('#imageInput, #fileInput').on('change', function() {
        const files = this.files;
        const previewContent = $('#preview-files');
        const chatMessages = $('#chat-messages');
        const dropdownFileBtn = $('#dropdownFileBtn');

        if (files.length > 0) {
          isPreviewFilesAnswer = true;

          $('#ticketAnswerModalLabel').text('Preview File');

          chatMessages.hide();
          dropdownFileBtn.hide();

          previewContent.empty().show();

          Array.from(files).forEach((file) => {
            const fileReader = new FileReader();

            fileReader.onload = function(e) {
              const filePreview = file.type.startsWith('image/') ?
                `<div class="preview-item"><img src="${e.target.result}" class="img-fluid" style="padding: 12px" alt="${file.name}"></div>` :
                `<div class="preview-item text-center" style="padding: 24px">
                  <i class="bi bi-file-earmark-fill d-flex justify-content-center align-items-center" style="font-size: 30px; color: #007bff;" title="${file.name}"></i>
                  <p class="small text-muted mt-3 mb-0 text-center">${file.name}</p>
               </div>`;

              previewContent.append(filePreview);
            };

            fileReader.readAsDataURL(file);
          });
        }
      });

      function sendMessage() {
        const message = $('#messageInput').val();
        const files = $('#imageInput')[0].files.length > 0 ? $('#imageInput')[0].files : $('#fileInput')[0].files;

        if (!message && files.length === 0) {
          alert('Please enter a message or select a file.');
          return;
        }

        const formData = new FormData();
        formData.append('message', message);
        formData.append('helpdesk_id', currentTicket.id);
        formData.append('_token', '{{ csrf_token() }}');

        Array.from(files).forEach((file) => {
          formData.append('files[]', file);
        });

        if (userMentions.size > 0) {
          const userMentionIds = Array.from(userMentions);
          formData.set('user_mentions', JSON.stringify(userMentionIds));
        } else {
          formData.set('user_mentions', JSON.stringify([]));
        }

        $.ajax({
          url: '{{ route('helpdesk.answer.store') }}',
          method: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function(response) {
            showAlert('success', response.message);
            resetToChatState();
            fetchAnswerTickets();
          },
          error: function(error) {
            console.error('Error sending message:', error);
          },
        });
      }

      $('#sendMessageBtn').on('click', function() {
        sendMessage();
      });

      $('#closeTicketAnswerModal').on('click', function() {
        if (isPreviewFilesAnswer) {
          isPreviewFilesAnswer = false;
          resetToChatState();
        } else {
          resetToChatState();
          $('#ticketAnswerModal').modal('hide');
        }
      });

      function resetToChatState() {
        $('#ticketAnswerModalLabel').text(currentTicket.title);
        $('#chat-messages').show();
        $('#dropdownFileBtn').show();
        $('#preview-files').empty().hide();
        $('#imageInput').val('');
        $('#fileInput').val('');
        $('#messageInput').val('');
      }

      $(document).on("contextmenu", ".chat-message", function(e) {
        e.preventDefault();

        const messageId = $(this).find(".message-bubble").data("id");
        const isOwnMessage = $(this).hasClass("chat-message-right");
        const messageCreatedAt = $(this).find(".message-time").text();

        if (isOwnMessage) {
          const createdAtTime = parseRelativeTime(messageCreatedAt);
          const currentTime = new Date();
          const timeDiffInMinutes = (currentTime - createdAtTime) / 1000 / 60;

          const $dropdown = $("#chatActionDropdown");

          $dropdown.css({
            top: e.pageY,
            left: e.pageX,
          }).show();

          if (timeDiffInMinutes > 180) {
            $dropdown.find("#editMessage, #deleteMessage").addClass("disabled").attr("disabled", true).css(
              "cursor", "not-allowed");
          } else {
            $dropdown.find("#editMessage, #deleteMessage").removeClass("disabled").attr("disabled", false).css(
              "cursor", "pointer"
            );
          }

          $dropdown.data("messageId", messageId);
        }
      });

      $("#chatActionDropdown").on("click", "#editMessage", function() {
        const messageId = $("#chatActionDropdown").data("messageId");
        const $messageBubble = $(`.message-bubble[data-id="${messageId}"]`);
        const $messageBubbleData = allAnswerTickets.filter((ticket) => ticket.id === messageId)[0];
        const currentMessage = $messageBubbleData.message;
        const currentFiles = $messageBubbleData.uploads;

        $("#ticketAnswerModal .modal-dialog").removeClass("modal-dialog-scrollable");
        $("#ticketAnswerModal .modal-body").css("overflow", "hidden");
        $("#chat-messages").append('<div id="backdrop" class="custom-backdrop"></div>');
        $('#ticketAnswerModal .modal-footer').hide();
        $messageBubble.closest(".message-bubble").addClass("focused-bubble");

        let filePreviewHtml = "";
        const maxFiles = 4;

        currentFiles.forEach(function(file) {
          const fileUrl = `{{ route('file', ':id') }}`.replace(':id', file.id);
          const fileName = file.filename_origin;

          filePreviewHtml += `
            <div class="file-preview-item d-flex justify-content-between align-items-center">
                <a href="${fileUrl}" target="_blank" class="text-primary" data-id="${file.id}">${fileName}</a>
                <i class="bi bi-x text-danger remove-file" style="cursor: pointer;" data-file="${fileUrl}" data-filename="${fileName}"></i>
            </div>
        `;
        });

        $messageBubble.html(`
            <form id="editMessageForm" class="d-flex flex-column gap-2">
                <div class="position-relative mb-3" id="userDropdownContainerAnswerEdit" style="display:none;">
                    <div id="userDropdownAnswerEdit" class="dropdown-menu p-2 shadow-sm"
                        style="width: 100%; max-height: 420px; overflow-y: auto;">
                        <input type="text" id="userSearchInputAnswerEdit" placeholder="Search users..." class="form-control mb-2">
                        <ul id="userListAnswerEdit" class="list-group list-group-flush">
                        <!-- User list items will be appended here -->
                        </ul>
                    </div>
                </div>
                <textarea class="form-control" rows="3" id="editMessageInputAnswerTicket">${currentMessage}</textarea>
                <div id="filePreviews" class="d-flex flex-column gap-2">
                    ${filePreviewHtml || ""}
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <button type="button" id="addFileButton" class="btn btn-sm btn-primary">Add File</button>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary btn-sm" id="cancelEdit">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                    </div>
                </div>
            </form>
        `);
        updateAddFileButtonVisibility();
        $("#chatActionDropdown").hide();
      });

      $(document).on("click", "#addFileButton", function() {
        const fileInput = $('<input type="file" style="display: none;">');
        $("body").append(fileInput);

        fileInput.trigger("click");

        fileInput.on("change", function() {
          const file = fileInput[0].files[0];
          if (file) {
            const fileUrl = URL.createObjectURL(file);
            const fileName = file.name;

            $("#filePreviews").append(`
                <div class="file-preview-item d-flex justify-content-between align-items-center">
                    <span>${fileName}</span>
                    <i class="bi bi-x text-danger remove-new-file" style="cursor: pointer;" data-file-name="${fileName}" data-file-url="${fileUrl}"></i>
                </div>
            `);

            newFilesAnswerTicket.push(file);
            updateAddFileButtonVisibility();
          }

          fileInput.remove();
        });
      });

      function updateAddFileButtonVisibility() {
        const maxFiles = 4;
        const currentFilesCount = $("#filePreviews .file-preview-item").length;

        if (currentFilesCount >= maxFiles) {
          $("#addFileButton").hide();
        } else {
          $("#addFileButton").show();
        }
      }

      $(document).on("click", ".remove-new-file", function() {
        const fileName = $(this).data("file-name");

        newFilesAnswerTicket = newFilesAnswerTicket.filter(file => file.name !== fileName);

        $(this).closest(".file-preview-item").remove();
        updateAddFileButtonVisibility();
      });

      $(document).on("click", ".remove-file", function() {
        const fileUrl = $(this).data("file");
        filesToRemoveAnswerTicket.push(fileUrl);

        $(this).closest(".file-preview-item").remove();
        updateAddFileButtonVisibility();
      });

      $(document).on("submit", "#editMessageForm", function(e) {
        e.preventDefault();

        const messageId = $("#chatActionDropdown").data("messageId");
        const updatedMessage = $(this).find("textarea").val();
        const remainingFiles = $("#filePreviews .file-preview-item a").map(function() {
          return $(this).data("id");
        }).get();
        const newFilesAnswerTicket = $("#newFilesAnswerTicket")[0]?.files || [];

        const totalFiles = remainingFiles.length + newFilesAnswerTicket.length;

        if (totalFiles > 4) {
          showAlert("error", "Total files cannot exceed 4!");
          return;
        }

        const formData = new FormData();
        formData.append("helpdesk_id", currentTicket.id);
        formData.append("message", updatedMessage);
        formData.append("files_to_remove", JSON.stringify(filesToRemoveAnswerTicket));
        formData.append("_token", "{{ csrf_token() }}");
        formData.append("_method", "PUT");

        Array.from(remainingFiles).forEach((file) => {
          formData.append("existing_files[]", file);
        });

        Array.from(newFilesAnswerTicket).forEach((file) => {
          formData.append("files[]", file);
        });

        if (userMentions.size > 0) {
          const userMentionIds = Array.from(userMentions);
          formData.set('user_mentions', JSON.stringify(userMentionIds));
        } else {
          formData.set('user_mentions', JSON.stringify([]));
        }

        $.ajax({
          url: `{{ route('helpdesk.answer.update', ':id') }}`.replace(":id", messageId),
          method: "POST",
          data: formData,
          contentType: false,
          processData: false,
          success: function() {
            showAlert("success", "Message updated successfully!");
            fetchAnswerTickets();
          },
          error: function() {
            showAlert("error", "Failed to update message!");
          },
          complete: function() {
            $("#backdrop").remove();
            $(".chat-message").removeClass("focused-bubble");
            $("#ticketAnswerModal .modal-dialog").addClass("modal-dialog-scrollable");
            $("#ticketAnswerModal .modal-body").css("overflow-y", "auto");
            $('#ticketAnswerModal .modal-footer').show();
            $('#ticketAnswerModal #messageInput').val('');
            filesToRemoveAnswerTicket = [];
          },
        });
      });

      $(document).on("click", "#cancelEdit", function() {
        fetchAnswerTickets();
        $("#backdrop").remove();
        $(".chat-message").removeClass("focused-bubble");
        $("#ticketAnswerModal .modal-dialog").addClass("modal-dialog-scrollable");
        $("#ticketAnswerModal .modal-body").css("overflow-y", "auto");
        $('#ticketAnswerModal .modal-footer').show();
        $('#ticketAnswerModal #messageInput').val('');
      });

      $("#chatActionDropdown").on("click", "#deleteMessage", function() {
        const messageId = $("#chatActionDropdown").data("messageId");
        const $messageBubble = $(`.message-bubble[data-id="${messageId}"]`);

        $("#ticketAnswerModal .modal-dialog").removeClass("modal-dialog-scrollable");
        $("#ticketAnswerModal .modal-body").css("overflow", "hidden");
        $("#chat-messages").append('<div id="backdrop" class="custom-backdrop"></div>');
        $('#ticketAnswerModal .modal-footer').hide();
        $messageBubble.closest(".message-bubble").addClass("focused-bubble");

        $messageBubble.html(`
            <div class="confirmation-message">
            <p>Are you sure you want to delete this message?</p>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-danger btn-sm" id="confirmDelete">Yes</button>
                <button type="button" class="btn btn-secondary btn-sm" id="cancelDelete">No</button>
            </div>
            </div>
        `);

        $("#chatActionDropdown").hide();

        $(document).on("click", "#confirmDelete", function() {
          const formData = new FormData();
          formData.append("last_answer_id", messageId);
          formData.append("soft_delete", 1);
          formData.append("_token", "{{ csrf_token() }}");
          formData.append("_method", "DELETE");

          $.ajax({
            url: '{{ route('helpdesk.answer.destroy') }}',
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function() {
              showAlert("success", "Message deleted successfully!");
              fetchAnswerTickets();
              $("#backdrop").remove();
              $(".chat-message").removeClass("focused-bubble");
              $("#ticketAnswerModal .modal-dialog").addClass("modal-dialog-scrollable");
              $("#ticketAnswerModal .modal-body").css("overflow-y", "auto");
              $('#ticketAnswerModal .modal-footer').show();
              $('#ticketAnswerModal #messageInput').val('');
            },
            error: function() {
              showAlert("error", "Failed to delete message!");
            },
          });
        });

        $(document).on("click", "#cancelDelete", function() {
          fetchAnswerTickets();
          $("#backdrop").remove();
          $(".chat-message").removeClass("focused-bubble");
          $("#ticketAnswerModal .modal-dialog").addClass("modal-dialog-scrollable");
          $("#ticketAnswerModal .modal-body").css("overflow-y", "auto");
          $('#ticketAnswerModal .modal-footer').show();
          $('#ticketAnswerModal #messageInput').val('');
        });
      });


      $(document).on('click', '#filterDropdown .dropdown-item', function() {
        statusFilter = $(this).data('filter');
        $(this).closest('.dropdown').find('.btn').text($(this).text());
        currentIndex = 0;
        filterTickets();
      })

      $('#search').on('input', debounce(function() {
        query = $(this).val();
        fetchTickets();
      }, 500));

      $('#load-more').on('click', function() {
        currentIndex += loadLimit;
        filterTickets();
      });


      fetchTickets();

      initializeFilter();
    });
  </script>
@endsection
