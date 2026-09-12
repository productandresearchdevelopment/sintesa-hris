@include('headers.head')

@php
  $helpdeskOrganization = $helpdesk->organization ?? null;
  $helpdeskCategory = $helpdesk->category ?? null;
  $helpdeskUser = $helpdesk->createdBy ?? null;
  $helpdeskUploads = $helpdesk->uploads ?? [];

  if (!function_exists('randomColor')) {
      function randomColor()
      {
          do {
              $color = '#' . str_pad(dechex(mt_rand(0, 0xffffff)), 6, '0', STR_PAD_LEFT);
              [$r, $g, $b] = sscanf($color, '#%02x%02x%02x');
              $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
          } while ($brightness > 230);

          return $color;
      }
  }

  if (!function_exists('highlightMentions')) {
      function highlightMentions($text)
      {
          return preg_replace('/@(\w+)/', '<span style="color: #007bff;">@$1</span>', $text);
      }
  }
@endphp

<style>
  html {
    scroll-behavior: smooth;
    background: #fff;
  }

  .text-clamp {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .expanded {
    -webkit-line-clamp: unset;
    white-space: normal;
  }

  #userDropdownContainer {
    position: relative;
    z-index: 1000;
  }

  #userDropdown {
    max-height: 185px;
    width: 100%;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    overflow-y: auto;
  }

  #userSearchInput {
    border-radius: 5px;
  }

  #userList .list-group-item {
    cursor: pointer;
  }

  #userList .list-group-item:hover {
    background-color: #f8f9fa;
  }
</style>

<div id="appCapsule" style="padding: 10px" class="bg-white">
  <div class="section bg-white">
    {{-- Helpdesk --}}
    <div class="">
      <div class="card border">
        <h2 class="card-header bg-dark text-white text-center fw-bold">Helpdesk</h2>
        <div class="card-body">
          <div class="card border mt-4 mb-0">
            <h4 class="card-header bg-primary text-white text-center fw-bold">Status</h4>
            <div
              class="card-body d-flex {{ $helpdesk->closed_at ? 'justify-content-between' : 'justify-content-center' }} align-items-center">
              <div class="d-flex align-items-center mt-4 gap-2">
                @switch($helpdesk->status)
                  @case('pending')
                    <i class="bi bi-hourglass-split text-warning fs-1 me-2 d-flex align-items-center"></i>
                    <h3 class="font-weight-bold mb-0 badge bg-warning fs-6 px-2 py-2">Pending</h3>
                  @break

                  @case('discussed')
                    <i class="bi bi-chat-dots-fill text-success fs-1 me-2 d-flex align-items-center"></i>
                    <h3 class="font-weight-bold mb-0 badge bg-success fs-6 px-2 py-2">Discussed</h3>
                  @break

                  @case('closed')
                    <i class="bi bi-x-circle-fill text-danger fs-1 me-2 d-flex align-items-center"></i>
                    <h3 class="font-weight-bold mb-0 badge bg-danger fs-6 px-2 py-2">Closed</h3>
                  @break

                  @default
                    <i class="bi bi-question-circle-fill text-secondary fs-1 me-2 d-flex align-items-center"></i>
                    <h3 class="font-weight-bold mb-0 badge bg-secondary fs-6 px-2 py-2">Unknown Status</h3>
                @endswitch
              </div>

              <!-- Closed At Section -->
              @if ($helpdesk->closed_at)
                <div class="text-end mt-4">
                  <h5 class="text-muted m-0 fs-6">Closed At:</h5>
                  <p class="font-weight-bold m-0" style="font-size: 12px">
                    {{ $helpdesk->closed_at ? date('d/m/Y H:i', strtotime($helpdesk->closed_at)) : '-' }}</p>
                </div>
              @endif
            </div>
          </div>

          <div class="card border mt-4 mb-0">
            <h4 class="card-header bg-primary text-white text-center fw-bold">General Information</h4>
            <div class="card-body">
              <!-- General Information Section -->
              <div class="pb-3 border-b-2 border-dark">
                <p class="font-weight-bold fs-4 mb-1 text-center mt-4">{{ $helpdesk->title }}</p>
                <div class="text-clamp" id="messageHelpdeskInformation">
                  {!! highlightMentions($helpdesk->message) !!}
                </div>
                @if (strlen($helpdesk->message) > 130)
                  <span class="read-more text-primary" style="cursor: pointer;" id="readMoreHelpdesk"
                    onclick="toggleReadMore('messageHelpdeskInformation', 'readMoreHelpdeskInformation')">Read
                    More
                  </span>
                @endif
              </div>

              @if ($helpdeskUser || $helpdeskOrganization)
                <div class="row">
                  @if ($helpdeskUser)
                    <div class="col-6">
                      <h5 class="text-muted font-weight-bold fs-6">Created By:</h5>
                      <div class="mb-2">
                        <div class="d-flex flex-column gap-1">
                          <p class="text-muted m-0">Name:</p>
                          <p class="font-weight-bold m-0">{{ $helpdeskUser->name ?? 'N/A' }}</p>
                        </div>
                      </div>
                      <div>
                        <p class="text-muted m-0">Date:</p>
                        <p class="font-weight-bold m-0">{{ date('d/m/Y H:i', strtotime($helpdesk->created_at)) }}</p>
                      </div>
                    </div>
                  @endif
                  @if ($helpdeskOrganization)
                    <div class="col-6">
                      <h5 class="text-muted font-weight-bold">Intended For:</h5>
                      <div class="mb-2">
                        <div class="d-flex flex-column gap-1">
                          <p class="text-muted m-0">Organization Name:</p>
                          <p class="font-weight-bold m-0">{{ strtoupper($helpdeskOrganization->name ?? 'N/A') }}</p>
                        </div>
                      </div>
                      @if ($helpdesk->category_id)
                        <div class="mb-2">
                          <div class="d-flex flex-column gap-1">
                            <p class="text-muted m-0">Category Name:</p>
                            <p class="font-weight-bold m-0">{{ $helpdeskCategory->name }}</p>
                          </div>
                        </div>
                      @endif
                    </div>
                  @endif
                </div>
              @endif

            </div>
          </div>

          @if (!$helpdeskUploads->isEmpty())
            <div class="accordion mt-4 mb-0" id="accordionFileUploads">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingFileUploads">
                  <button class="accordion-button collapsed font-weight-bold" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseFileUploads" aria-expanded="false" aria-controls="collapseFileUploads">
                    File Uploads
                  </button>
                </h2>
                <div id="collapseFileUploads" class="accordion-collapse collapse" aria-labelledby="headingFileUploads"
                  data-bs-parent="#accordionFileUploads">
                  <div class="accordion-body p-0">
                    @foreach ($helpdeskUploads as $upload)
                      @php
                        $extension = strtolower(pathinfo($upload->filename_origin, PATHINFO_EXTENSION));
                      @endphp
                      <div class="m-3 border rounded p-3">
                        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                          <div class="d-flex justify-content-center">
                            <img src="{{ route('file', $upload->id) }}" class="img-fluid" alt="Image">
                          </div>
                          <p class="mt-2 text-center text-muted">{{ $upload->filename_origin }}</p>
                        @else
                          <div class="file-icon text-center">
                            @if (in_array($extension, ['pdf']))
                              <i class="bi bi-file-earmark-pdf-fill" style="color: #e10a0a; font-size: 60px"></i>
                            @elseif(in_array($extension, ['doc', 'docx']))
                              <i class="bi bi-file-earmark-word-fill" style="color: #145adc; font-size: 60px"></i>
                            @elseif(in_array($extension, ['ppt', 'pptx']))
                              <i class="bi bi-file-earmark-ppt-fill" style="color: #ff9000; font-size: 60px"></i>
                            @elseif(in_array($extension, ['zip']))
                              <i class="bi bi-file-earmark-zip-fill" style="color: #8100ce; font-size: 60px"></i>
                            @else
                              <i class="bi bi-file-earmark-code-fill" style="color: #0075f3; font-size: 60px"></i>
                            @endif
                            <p class="mt-2 text-muted">{{ $upload->filename_origin }}</p>
                            <a href="{{ route('file', $upload->id) }}" target="_blank" class="btn btn-primary">View</a>
                          </div>
                        @endif
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Helpdesk Answer --}}
    <div>
      <div class="card border">
        <h2 class="card-header bg-dark text-white text-center fw-bold">Helpdesk Answer</h2>
        <div class="card-body">
          @if ($helpdesk->last_answer)
            <div class="card border mt-4 mb-0">
              <h4 class="card-header bg-primary text-white text-center fw-bold">Last Answer</h4>
              <div class="card-body mt-4">
                <div class="mb-3">
                  <h5 class="text-muted font-weight-bold fs-6 ">Message :</h5>
                  <p class="font-weight-bold text-clamp" id="lastAnswer">{{ $helpdesk->last_answer->message }}</p>
                  @if (strlen($helpdesk->last_answer->message) > 130)
                    <span class="read-more text-primary" style="cursor: pointer;" id="readMoreLastAnswer"
                      onclick="toggleReadMore('lastAnswer', 'readMoreLastAnswer')">Read
                      More
                    </span>
                  @endif
                </div>
                <div class="mb-0">
                  <h5 class="text-muted font-weight-bold fs-6">Date :</h5>
                  <p class="font-weight-bold mb-0">
                    {{ date('d/m/Y H:i', strtotime($helpdesk->last_answer->created_at)) }}
                  </p>
                </div>
              </div>
              <div class="card-footer">
                <div class="d-flex justify-content-center align-items-center gap-2">
                  <button type="button" onclick="editAnswer({{ $helpdesk->last_answer->id }})"
                    class="btn btn-primary">Edit
                    Answer</button>
                  <button type="button" onclick="openDeleteConfirmationModal({{ $helpdesk->last_answer->id }})"
                    class="btn btn-danger">Delete Answer</button>
                </div>
              </div>
            </div>
          @endif

          <div class="card border mt-4 mb-0">
            <h4 class="card-header bg-primary text-white text-center fw-bold">Discussion</h4>
            <div class="card-body mt-4">
              <div class="timeline-cus">
                <div class="dot-start" style="top:0;">Start</div>

                <!-- Helpdesk Section -->
                <div class="item">
                  @php $color = randomColor(); @endphp
                  <div class="dot" style="background: {{ $color }}"></div>
                  <div class="content d-flex flex-column gap-2 my-2">
                    <!-- Helpdesk Information -->
                    <div class="d-flex flex-column align-items-start gap-2 w-100">
                      <div class="d-flex align-items-start gap-2">
                        @if ($helpdesk->createdBy?->photo_id)
                          <img src="{{ route('file', $helpdesk->createdBy->photo_id) }}" class="rounded-circle"
                            width="40" height="40" alt="Avatar">
                        @else
                          <div class="rounded-circle d-flex justify-content-center align-items-center font-weight-bold"
                            style="width: 40px; height: 40px; background: {{ $color }}; color: white;">
                            {{ $helpdesk->createdBy ? substr($helpdesk->createdBy->name, 0, 1) : '' }}
                          </div>
                        @endif

                        <div class="w-100 d-flex flex-column gap-1">
                          <div class="fw-bold" style="color: {{ $color }};">
                            {{ $helpdesk->title }}
                          </div>
                          <div class="text-muted small">
                            {{ date('d/m/Y H:i', strtotime($helpdesk->created_at)) }} -
                            <span class="font-weight-bold">
                              ( {{ $helpdesk->createdBy ? $helpdesk->createdBy->name : '' }} )
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="text-clamp" id="messageHelpdesk">
                        {!! highlightMentions($helpdesk->message) !!}
                      </div>
                      @if (strlen($helpdesk->message) > 130)
                        <span class="read-more text-primary" style="cursor: pointer;" id="readMoreHelpdesk"
                          onclick="toggleReadMore('messageHelpdesk', 'readMoreHelpdesk')">Read
                          More
                        </span>
                      @endif
                    </div>
                  </div>
                </div>

                <!-- Answer Section -->
                @forelse ($answer as $item)
                  @php $color = randomColor(); @endphp
                  <div class="item">
                    <div class="dot" style="background: {{ $color }}"></div>
                    <div class="content d-flex flex-column gap-2 my-2">
                      <div class="d-flex flex-column align-items-start gap-2 w-100">
                        <div class="d-flex align-items-center gap-2">
                          @if ($item->createdBy?->photo_id)
                            <img src="{{ route('file', $item->createdBy->photo_id) }}" class="rounded-circle"
                              width="40" height="40" alt="Avatar">
                          @else
                            <div class="rounded-circle d-flex justify-content-center align-items-center"
                              style="width: 40px; height: 40px; background: {{ $color }}; color: white;">
                              {{ $item->createdBy ? substr($item->createdBy->name, 0, 1) : '' }}
                            </div>
                          @endif

                          <div class="w-100 d-flex flex-column gap-1">
                            <div class="text-muted small">
                              {{ date('d/m/Y H:i', strtotime($item->created_at)) }} -
                              <span class="font-weight-bold">
                                ({{ $item->createdBy ? $item->createdBy->name : '' }})
                              </span>
                            </div>
                          </div>
                        </div>

                        <div class="w-100 d-flex flex-column gap-1">
                          <span class="text-clamp" id="messageAnswer{{ $item->id }}">
                            {!! highlightMentions($item->message) !!}
                          </span>
                          @if (strlen($item->message) > 130)
                            <span class="read-more text-primary" style="cursor: pointer;"
                              id="readMoreAnswer{{ $item->id }}"
                              onclick="toggleReadMore('messageAnswer{{ $item->id }}', 'readMoreAnswer{{ $item->id }}')">Read
                              More</span>
                          @endif
                        </div>

                        @if (count($item->uploads) > 0)
                          <!-- Bootstrap Accordion for File Uploads -->
                          <div class="accordion w-100" id="accordionFileUploads-{{ $item->id }}">
                            <div class="accordion-item">
                              <h2 class="accordion-header" id="headingFileUploads-{{ $item->id }}">
                                <button class="accordion-button collapsed text-muted" type="button"
                                  data-bs-toggle="collapse" data-bs-target="#collapseFileUploads-{{ $item->id }}"
                                  aria-expanded="false" aria-controls="collapseFileUploads-{{ $item->id }}">
                                  File Uploads
                                </button>
                              </h2>
                              <div id="collapseFileUploads-{{ $item->id }}" class="accordion-collapse collapse"
                                aria-labelledby="headingFileUploads-{{ $item->id }}"
                                data-bs-parent="#accordionFileUploads-{{ $item->id }}">
                                <div class="accordion-body">
                                  @foreach ($item->uploads as $upload)
                                    <!-- Loop through uploads for the current item -->
                                    @php
                                      $extension = strtolower(pathinfo($upload->filename_origin, PATHINFO_EXTENSION));
                                    @endphp
                                    <div class="mb-3 border rounded p-3">
                                      @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                        <div class="d-flex justify-content-center">
                                          <img src="{{ route('file', $upload->id) }}" class="img-fluid"
                                            alt="Image">
                                        </div>
                                        <p class="mt-2 text-center text-muted">{{ $upload->filename_origin }}</p>
                                      @else
                                        <div class="file-icon text-center">
                                          @if (in_array($extension, ['pdf']))
                                            <i class="bi bi-file-earmark-pdf-fill"
                                              style="color: #e10a0a; font-size: 60px"></i>
                                          @elseif(in_array($extension, ['doc', 'docx']))
                                            <i class="bi bi-file-earmark-word-fill"
                                              style="color: #145adc; font-size: 60px"></i>
                                          @elseif(in_array($extension, ['ppt', 'pptx']))
                                            <i class="bi bi-file-earmark-ppt-fill"
                                              style="color: #ff9000; font-size: 60px"></i>
                                          @elseif(in_array($extension, ['zip']))
                                            <i class="bi bi-file-earmark-zip-fill"
                                              style="color: #8100ce; font-size: 60px"></i>
                                          @else
                                            <i class="bi bi-file-earmark-code-fill"
                                              style="color: #0075f3; font-size: 60px"></i>
                                          @endif
                                          <p class="mt-2 text-muted">{{ $upload->filename_origin }}</p>
                                          <a href="{{ route('file', $upload->id) }}" target="_blank"
                                            class="btn btn-primary">View</a>
                                        </div>
                                      @endif
                                    </div>
                                  @endforeach
                                </div>
                              </div>
                            </div>
                          </div>
                        @endif
                      </div>
                    </div>
                  </div>
                @empty
                  <div class="item">
                    <div class="dot" style="background: #FF3300"></div>
                    <div class="content">
                      <span style="color: #FF3300; cursor: default !important;">No answer available</span>
                    </div>
                  </div>
                @endforelse

                <div class="dot-start" style="bottom:0">End</div>
              </div>
            </div>
          </div>

          {{-- Form Answer --}}
          <div class="card border mt-4 mb-0">
            <h4 class="card-header bg-primary text-white text-center fw-bold">Form Answer</h4>
            <div class="card-body mt-4">
              <form id="answerForm" action="{{ route('helpdesk.answer.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="answerId" name="answer_id">
                <input type="hidden" id="helpdeskId" name="helpdesk_id" value="{{ $helpdesk->id }}">
                <input type="hidden" id="userMentions" name="user_mentions" value="">

                <div class="mb-3">
                  <label for="answerText" class="form-label fw-bold">Answer</label>
                  <textarea class="form-control" id="answerText" name="message" rows="4"></textarea>
                </div>


                <div class="position-relative mb-3" id="userDropdownContainer" style="display:none;">
                  <div id="userDropdown" class="dropdown-menu p-2 shadow-sm"
                    style="width: 100%; max-height: 185px; overflow-y: auto;">
                    <input type="text" id="userSearchInput" placeholder="Search users..."
                      class="form-control mb-2">
                    <ul id="userList" class="list-group list-group-flush">
                      <!-- User list items will be appended here -->
                    </ul>
                  </div>
                </div>

                <div id="filePreviewContainer"></div>

                <div class="d-flex flex-column gap-2 align-items-center mt-3">
                  <button type="button" id="addFileBtn" class="btn btn-success w-100">Add File</button>
                  <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                  <button type="button" id="cancelEditBtn" class="btn btn-danger d-none w-100">Cancel</button>
                </div>

                <input type="file" id="fileInput" name="files[]" multiple class="d-none">
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Modal Delete Answer --}}
  <div class="modal fade" id="deleteConfirmationModal" data-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <form id="deleteForm" method="POST" class="mb-0">
          @csrf
          @method('DELETE')
          <input type="hidden" name="last_answer_id" id="deleteActionId">
          <input type="hidden" name="soft_delete" value="0">

          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalTitle">
              Confirmation Delete
            </h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <i class="bi bi-x-lg text-primary font-bold"></i>
            </button>
          </div>
          <div class="modal-body">
            <p class="text-dark mb-0">
              Are you sure you want to delete this item?
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
              <i class="bx bx-x"></i>
              <span class="d-block">No</span>
            </button>
            <button type="submit" class="btn btn-primary ms-1" data-bs-dismiss="modal">
              <i class="bx bx-check"></i>
              <span class="d-block">Yes</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      document.querySelectorAll('.accordion-cus').forEach(button => {
        button.addEventListener('click', () => {
          const panel = button.nextElementSibling.nextElementSibling;
          const chevron = button.querySelector('.fa-chevron-right');

          if (panel.style.display === 'block') {
            panel.style.height = '0px';
            panel.addEventListener('transitionend', () => {
              panel.style.display = 'none';
            }, {
              once: true
            });

            chevron.style.transform = 'rotate(0deg)';
          } else {
            panel.style.display = 'block';
            setTimeout(() => {
              panel.style.height = panel.scrollHeight + 'px';
            }, 10);

            chevron.style.transform = 'rotate(90deg)';
            chevron.style.transition = 'transform 0.3s ease';
          }
        });
      });

      window.openDeleteConfirmationModal = function(id) {
        $("#deleteActionId").val(id);
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        deleteModal.show();
      }

      $("#deleteForm").submit(function(e) {
        e.preventDefault();
        var $form = $(this);
        console.log($form.serializeArray());
        var serializedData = $form.serializeArray();

        $.ajax({
          url: '{{ route('helpdesk.answer.destroy') }}',
          type: "POST",
          data: serializedData,
          beforeSend: function() {
            $("#loader").show();
          },
          success: function(response) {
            if (response.success) {
              alert(response.message);
              setTimeout(function() {
                window.location.reload();
              }, 1000);
            } else {
              alert(response.message);
            }
          },
          error: function(xhr) {
            console.error('Error response:', xhr);
            alert("An error occurred: " + xhr.status + " " + xhr.statusText);
          },
          complete: function() {
            $("#loader").hide();
          }
        });
      });
    });
  </script>

  <script>
    $(document).ready(function() {
      const fileInput = $('#fileInput');
      const addFileBtn = $('#addFileBtn');
      const filePreviewContainer = $('#filePreviewContainer');
      const maxFiles = 4;
      const userMentions = new Set();
      let newFiles = [];
      let existingFiles = [];
      let totalFileCount = 0;
      let userListData = [];

      // --- Functionality for searchable mentions dropdown ---
      $('#answerText').on('input', function() {
        const cursorPos = this.selectionStart;
        const answerTextValue = this.value;

        if (answerTextValue.charAt(cursorPos - 1) === '@') {
          $('#userDropdownContainer').show();
          $('#userDropdown').show().css({
            top: 0,
            left: 0
          });
          $('#userSearchInput').focus();

          if (userListData.length === 0) {
            $.ajax({
              url: `{{ route('auth.user.data') }}`,
              method: 'GET',
              success: function(data) {
                userListData = data.data;
                displayUserList('');
              }
            });
          } else {
            displayUserList('');
          }
        }

        // Clear and update userMentions based on real-time input
        const mentionsInText = new Set();
        answerTextValue.match(/@(\w+)/g)?.forEach(match => {
          const name = match.slice(1).replace(/\s+/g, ''); // Remove extra spaces
          const userDataMatches = userListData.filter(user => user.name.replace(/\s+/g, '') === name);

          if (userDataMatches.length > 0) {
            userDataMatches.forEach(user => mentionsInText.add(user.id));
          }
        });
        userMentions.clear();
        mentionsInText.forEach(id => userMentions.add(id));

        $('#user_mentions').val(JSON.stringify(Array.from(userMentions)));
      });

      $('#answerText').on('focus', function() {
        $('#userDropdownContainer').hide();
        $('#userDropdown').hide();

        $('#userSearchInput').val('');
      });

      // Display filtered users in the dropdown
      $('#userSearchInput').on('input', function() {
        const query = $(this).val().toLowerCase();
        displayUserList(query);
      });

      // Function to display user list with filtering
      function displayUserList(query) {
        $('#userList').empty();
        userListData.forEach(user => {
          if (user.name.toLowerCase().includes(query) && !userMentions.has(user.id)) {
            $('#userList').append(
              `<li class="list-group-item list-group-item-action" data-user-id="${user.id}" data-name="${user.name}">${user.name}</li>`
            );
          }
        });
      }

      // Select a user from the dropdown
      $('#userList').on('click', 'li', function() {
        const userId = $(this).data('user-id');
        const name = $(this).data('name').replace(/\s+/g, '');

        if (!userMentions.has(userId)) {
          userMentions.add(userId);

          $('#answerText').val(function(i, text) {
            const cursorPos = $('#answerText')[0].selectionStart;
            return text.slice(0, cursorPos) + `${name} ` + text.slice(cursorPos);
          });

          $('#user_mentions').val(JSON.stringify(Array.from(userMentions)));

          $('#userSearchInput').val('');
          $('#userDropdownContainer').hide();
          $('#answerText').focus();
        }
      });

      // Hide dropdown when clicking outside
      $(document).on('click', function(e) {
        if (!$(e.target).closest('#userDropdown, #answerText').length) {
          $('#userDropdownContainer').hide();
        }
      });

      // Trigger file input click when "Add File" button is clicked
      addFileBtn.on('click', function() {
        if (totalFileCount < maxFiles) {
          fileInput.click();
        }
      });

      // Handle file selection
      fileInput.on('change', function(event) {
        const files = event.target.files;
        if (totalFileCount + files.length > maxFiles) {
          alert('You can only upload up to 4 files.');
          return;
        }
        $.each(files, function(index, file) {
          if (!newFiles.some(f => f.name === file.name && f.size === file.size)) {
            newFiles.push(file);
            addFilePreview(file);
            totalFileCount++;
          }
        });
        fileInput.val('');
        updateFilePreviews();
      });

      // Add file preview to the container
      function addFilePreview(file, mode = 'add', index) {
        const filePreview = $('<div class="mb-3"></div>');
        let fileLabel = '';

        if (mode === 'add') {
          fileLabel = `<strong>File ${existingFiles.length + index + 1}:</strong>`;
          filePreview.html(`
            <div class="d-flex justify-content-between align-items-center">
                ${fileLabel}
                <button type="button" style="background:transparent; border:none;" data-file="${file.name}">
                    <i class="bi bi-x text-danger cursor-pointer" style="font-size: 16px;" title="Remove File"></i>
                </button>
            </div>
            <p class="font-weight-bold">${file.name}</p>
        `);
        } else if (mode === 'edit') {
          filePreview.html(`
            <div class="d-flex justify-content-between align-items-center mb-1">
                <p class="font-weight-bold mb-0">${file.name}</p>
                <button type="button" style="background:transparent; border:none;" data-file="${file.name}">
                    <i class="bi bi-x text-danger cursor-pointer" style="font-size: 16px;" title="Remove File"></i>
                </button>
            </div>
        `);
        }

        filePreviewContainer.append(filePreview);
      }

      filePreviewContainer.on('click', 'button', function() {
        const fileName = $(this).data('file');
        const fileId = $(this).data('fileId');

        if (fileId) {
          existingFiles = existingFiles.filter(file => file.id !== fileId);
          totalFileCount--;
        } else if (fileName) {
          newFiles = newFiles.filter(file => file.name !== fileName);
          totalFileCount--;
        }

        updateFilePreviews();
        toggleAddFileButton();
      });

      function updateFilePreviews() {
        filePreviewContainer.empty();

        if (existingFiles.length > 0) {
          renderExistingFiles(existingFiles);
        }

        renderFilePreviews(newFiles, existingFiles.length > 0 ? 'edit' : 'add');
        toggleAddFileButton();
      }

      function renderFilePreviews(files, mode) {
        $.each(files, function(index, file) {
          addFilePreview(file, mode, index);
        });
      }

      function renderExistingFiles(uploads) {
        const filesTitle = $(
          `<div class="d-flex justify-content-between align-items-center mb-1">
            <strong>Files:</strong>
        </div>`
        );
        filePreviewContainer.append(filesTitle);

        $.each(uploads, function(index, upload) {
          const filePreview = $(
            `<div class="d-flex justify-content-between align-items-center mb-1"></div>`
          );
          filePreview.html(`
            <p class="font-weight-bold mb-0">${upload.filename_origin}</p>
            <button type="button" style="background:transparent; border:none;" data-file-id="${upload.id}">
                <i class="bi bi-x text-danger cursor-pointer" style="font-size: 16px;" title="Remove File"></i>
            </button>
        `);
          filePreviewContainer.append(filePreview);
        });
      }

      function toggleAddFileButton() {
        if (totalFileCount >= maxFiles) {
          addFileBtn.hide();
        } else {
          addFileBtn.show();
        }
      }

      $('#answerForm').on('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        $.each(newFiles, function(index, file) {
          formData.append('files[]', file);
        });

        if (existingFiles.length > 0) {
          const existingFileIds = existingFiles.map(file => file.id);
          formData.append('existing_files', JSON.stringify(existingFileIds));
        }

        if (userMentions.size > 0) {
          const userMentionIds = Array.from(userMentions);
          formData.set('user_mentions', JSON.stringify(userMentionIds));
        } else {
          formData.set('user_mentions', JSON.stringify([]));
        }

        const answerId = $('#answerId').val();
        const url = answerId ? `{{ route('helpdesk.answer.update', ['id' => ':answerId']) }}`.replace(
          ':answerId', answerId) : $(this).attr('action');

        if (answerId) {
          formData.set('_method', 'PUT');
        }

        $.ajax({
          url: url,
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          beforeSend: function() {
            $("#loader").show();
          },
          success: function(response) {
            if (response.success) {
              alert(response.message);
              setTimeout(function() {
                window.location.reload();
              }, 1000);
            } else {
              alert(response.message);
            }
          },
          error: function(error) {
            console.error('Error response:', error);
            alert("An error occurred: " + error.status + " " + error.statusText);
          },
          complete: function() {
            $("#loader").hide();
          }
        });
      });


      // Cancel Edit
      $('#cancelEditBtn').on('click', function() {
        $('#answerId').val('');
        $('#answerText').val('');
        $('#helpdeskId').val({{ $helpdesk->id }});

        existingFiles = [];
        newFiles = [];
        totalFileCount = 0;
        updateFilePreviews();
        userMentions.clear();

        $('#cancelEditBtn').addClass('d-none');
      });

      // Edit Answer
      window.editAnswer = function(answerId) {
        $.ajax({
          url: `/helpdesk/answer/get/${answerId}`,
          method: 'GET',
          success: function(data) {
            $('#answerId').val(data.id);
            $('#answerText').val(data.message);
            $('#helpdeskId').val(data.helpdesk_id);
            existingFiles = data.uploads;
            totalFileCount = existingFiles.length;
            updateFilePreviews();
            toggleAddFileButton();
            $('#userMentions').val(JSON.stringify(data.user_mentions.map(user => user.id)));

            if (data.user_mentions.length > 0) {
              data.user_mentions.forEach(userMention => {
                userMentions.add(userMention.id);
              });
            }

            const answerFormElement = document.getElementById('answerForm');
            if (answerFormElement) {
              answerFormElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
              });
            }
            $('#cancelEditBtn').removeClass('d-none');
          },
          error: function(error) {
            console.error('Error:', error);
          }
        });
      };

      // Function to handle toggling read more
      window.toggleReadMore = function(contentId, buttonId) {
        var content = document.getElementById(contentId);
        var button = document.getElementById(buttonId);

        if (content.classList.contains('text-clamp')) {
          content.classList.remove('text-clamp');
          content.classList.add('expanded');
          button.textContent = 'Read Less';
        } else {
          content.classList.remove('expanded');
          content.classList.add('text-clamp');
          button.textContent = 'Read More';
        }
      };

    });
  </script>
