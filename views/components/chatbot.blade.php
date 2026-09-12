<button class="fab-button" id="chatbot-button">
  <i class="fas fa-headset"></i>
</button>

<div id="chatbot-modal" class="chatbot-modal">
  <div class="chatbot-container" id="chatbot-container">
    {{-- Header --}}
    <div class="chatbot-header">
      <div class="d-flex align-items-center">
        <div class="chatbot-avatar">
          <img src="{{ asset('images/logo-chatbot.png') }}" alt="logo chatbot">
        </div>
        <div class="chatbot-info">
          <div class="chatbot-name">Chat Bot</div>
          <div class="chatbot-status">• Online</div>
        </div>
      </div>
      <button class="chatbot-close" id="chatbot-close">
        <i class="fas fa-times"></i>
      </button>
    </div>

    {{-- Messages Area --}}
    <div class="chatbot-messages" id="chatbot-messages">

    </div>

    {{-- Input Area --}}
    <div class="chatbot-input">
      <div class="input-group">
        <input type="text" id="chatbot-input" class="form-control" placeholder="Type a message ...">
        <button class="btn btn-primary" id="send-message-chat">
          <i class="fas fa-paper-plane"></i>
        </button>
      </div>
    </div>
  </div>
</div>


<script>
  const AI_API_URL = '{{ config('app.AI_API_URL') }}';
  const chatbotButton = $('#chatbot-button');
  const chatbotModal = $('#chatbot-modal');
  const chatbotMessages = $('#chatbot-messages');
  const chatbotInput = $('#chatbot-input');
  const sendMessageButton = $('#send-message-chat');
  let currentAction = '';
  let selectedTable = '';
  let formData = {};
  let tableData = [];
  let selectedDataId = '';
  let isLoadingChatbot = false;
  const tableCRUD = ['helpdesk', 'employee', 'leave'];
  const dataToCRUDTable = [{
      table: 'helpdesk',
      data: {
        title: 'title',
        category_id: 'category_id',
        message: 'message',
        // files: 'files',
        user_mentions: 'user_mentions',
      },
    },
    {
      table: 'employee',
      data: {
        org_id: 'org_id',
        division_id: 'division_id',
        company_id: 'company_id',
        placement_id: 'placement_id',
        nik: 'nik',
        nickname: 'nickname',
        fullname: 'fullname',
        birth_place: 'birth_place',
        birth_date: 'birth_date',
        phone: 'phone',
        email: 'email',
        gender_id: 'gender_id',
        marital_id: 'marital_id',
        religion_id: 'religion_id',
        join_date: 'join_date',
        leave_saldo: 'leave_saldo',
        address: 'address',
        address_city_id: 'address_city_id',
        address_province_id: 'address_province_id',
        address_permanent: 'address_permanent',
        address_permanent_city_id: 'address_permanent_city_id',
        address_permanent_province_id: 'address_permanent_province_id',
        bank_id: 'bank_id',
        bank_account: 'bank_account',
        emergency_relation_id: 'emergency_relation_id',
        emergency_phone: 'emergency_phone',
        emergency_contact_name: 'emergency_contact_name',
        emergency_contact_phone: 'emergency_contact_phone',
        emergency_contact_address: 'emergency_contact_address',
        // fileInputGeneral: 'fileInputGeneral',
      },
    },
    {
      table: 'leave',
      data: {
        type_id: 'Type ID',
        start_date: 'Start Date',
        end_date: 'End Date',
        description: 'Description',
        // duration: 'duration',
        // file_id: 'file_id',
      },
    }
  ]

  chatbotButton.on('click', function() {
    chatbotModal.toggle('show');
    showMessage(
      "Apa yang ingin Anda lakukan?\n Pilih antara:\n1. Buat data\n2. Ubah data\n3. Hapus data\n4. Cari informasi"
    );
  });

  $('#chatbot-close').on('click', function() {
    chatbotModal.toggle('show');
    $('#chatbot-input').val('');
    resetChatbot();
  });

  function sendMessageChatBot() {
    const message = chatbotInput.val();
    if (!message.trim()) return;
    showMessage(message, true);

    if (currentAction == '' || currentAction == null) {
      switch (message.trim()) {
        case '1':
          currentAction = 'create';
          showMessage(
            `Anda ingin membuat data. Pilih tabel yang ingin Anda buat. Silahkan pilih antara:\n1. ${tableCRUD[0].charAt(0).toUpperCase() + tableCRUD[0].slice(1)}\n2. ${tableCRUD[1].charAt(0).toUpperCase() + tableCRUD[1].slice(1)}\n3. ${tableCRUD[2].charAt(0).toUpperCase() + tableCRUD[2].slice(1)}`,
            false
          );
          break;
        case '2':
          currentAction = 'update';
          showMessage(
            `Anda ingin mengubah data. Pilih tabel yang ingin Anda ubah. Silahkan pilih antara:\n1. ${tableCRUD[0].charAt(0).toUpperCase() + tableCRUD[0].slice(1)}\n2. ${tableCRUD[1].charAt(0).toUpperCase() + tableCRUD[1].slice(1)}\n3. ${tableCRUD[2].charAt(0).toUpperCase() + tableCRUD[2].slice(1)}`,
            false
          );
          break;
        case '3':
          currentAction = 'delete';
          showMessage(
            `Anda ingin menghapus data. Pilih tabel yang ingin Anda hapus. Silahkan pilih antara:\n1. ${tableCRUD[0].charAt(0).toUpperCase() + tableCRUD[0].slice(1)}\n2. ${tableCRUD[1].charAt(0).toUpperCase() + tableCRUD[1].slice(1)}\n3. ${tableCRUD[2].charAt(0).toUpperCase() + tableCRUD[2].slice(1)}`,
            false
          );
          break;
        case '4':
          currentAction = 'search';
          showMessage("Anda ingin mencari informasi. Silahkan ketik apa yang ingin Anda cari.", false);
          break;
        case '9999' || 'exit' || 'close':
          chatbotModal.toggle('show');
          chatbotMessages.empty();
          $('#chatbot-input').val('');
          resetChatbot();
          window.location.reload();
          break;
        default:
          showMessage(
            "Pilihan tidak valid. Silakan pilih antara:\n 1. Buat data\n 2. Ubah data\n 3. Hapus data\n 4. Cari informasi.",
            false
          );
      }
    } else {
      if (currentAction === 'search') {
        sendToSearchBackend(message);
      } else if (currentAction === 'create' || currentAction === 'update' || currentAction === 'delete') {
        handleCRUD(message);
      }
    }

    chatbotInput.val('');
  }

  chatbotInput.on('keypress', function(event) {
    if (event.which === 13) {
      sendMessageChatBot();
    }
  });

  sendMessageButton.on('click', sendMessageChatBot);

  function showMessage(message, isUser) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message-item ${isUser ? 'user-message-item' : ''}`;

    const senderDiv = document.createElement('div');
    senderDiv.className = 'message-sender';
    senderDiv.textContent = isUser ? 'You' : 'Assistant';
    messageDiv.appendChild(senderDiv);

    const messageContent = document.createElement('div');
    messageContent.className = `message-content ${isUser ? 'user-message' : 'bot-message'}`;
    messageContent.innerHTML = message.replace(/\n/g, '<br>');

    messageDiv.appendChild(messageContent);
    chatbotMessages.append(messageDiv);
    chatbotMessages.scrollTop(chatbotMessages[0].scrollHeight);
  }

  function sendToSearchBackend(message) {
    isLoadingChatbot = true;
    $('#chatbot-input').prop('disabled', true);
    $('#chatbot-input').prop('placeholder', 'Asisten sedang memproses...');
    $('#send-message-chat').prop('disabled', true);

    let userId = null;

    if (message.includes("saya") || message.includes("me") || message.includes("my")) {
      userId = "{{ auth()->user()->id }}";
    } else {
      userId = null;
    }

    $.ajax({
      url: `${AI_API_URL}/chat`,
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        message: message,
        userId: userId
      }),
      success: function(response) {
        showMessage(response.response, false);
        showMessage(
          "Apa yang ingin Anda lakukan? Pilih antara:\n1. Buat data\n2. Ubah data\n3. Hapus data\n4. Cari informasi\n9999. Keluar",
          false);
        currentAction = '';
      },
      error: function(xhr) {
        showMessage("Terjadi kesalahan: " + xhr.statusText, false);
      },
      complete: function() {
        isLoadingChatbot = false;
        $('#chatbot-input').prop('disabled', false);
        $('#chatbot-input').prop('placeholder', 'Ketik pesan anda...');
        $('#send-message-chat').prop('disabled', false);
      }
    });
  }

  function handleCRUD(message) {
    if (!currentAction) {
      const actionIndex = parseInt(message.trim());
      if (actionIndex >= 1 && actionIndex <= 4) {
        const actions = ['create', 'update', 'delete', 'read'];
        currentAction = actions[actionIndex - 1];
        showMessage("Pilih tabel:\n1. Helpdesk\n2. Employee\n3. Leave", false);
      } else {
        showMessage(
          "Pilihan tidak valid. Silakan pilih:\n1. Buat data\n2. Ubah data\n3. Hapus data\n4. Cari informasi",
          false
        );
      }
      return;
    }

    if (!selectedTable) {
      const tableIndex = parseInt(message.trim()) - 1;
      if (tableIndex >= 0 && tableIndex < tableCRUD.length) {
        selectedTable = tableCRUD[tableIndex];
        if (currentAction === 'create') {
          formData[selectedTable] = {};
          showMessage(
            `Tabel yang dipilih: <b>${selectedTable.charAt(0).toUpperCase() + selectedTable.slice(1)}</b>. Sekarang, masukkan data berikut:`,
            false);
          requestNextField();
        } else if (currentAction === 'update' || currentAction === 'delete') {
          fetchTableData();
        }
      } else {
        showMessage("Pilihan tabel tidak valid. Silakan coba lagi.", false);
      }
      return;
    }

    if (!selectedDataId && currentAction === 'update' || currentAction === 'delete') {
      const dataIndex = parseInt(message.trim()) - 1;
      if (dataIndex >= 0 && dataIndex < tableData.length) {
        selectedDataId = tableData[dataIndex].id;
        if (currentAction === 'update') {
          formData[selectedTable] = {};
          showMessage(`Anda memilih data dengan ID: ${selectedDataId}. Sekarang, masukkan data baru:`, false);
          requestNextField();
        } else if (currentAction === 'delete') {
          showMessage(`Anda memilih data dengan ID: ${selectedDataId}`, false);
          showMessage("Proses penghapusan data...", false);
          confirmAndDeleteData();
        }
      } else {
        showMessage("Pilihan data tidak valid. Silakan coba lagi.", false);
      }
      return;
    }

    const currentField = getCurrentField();
    if (currentField) {
      if (currentField.includes('_id') || currentField === 'user_mentions') {
        const input = message.trim();
        const isMultiple = input.includes(',');

        if (isMultiple) {
          const selectedIndices = input.split(',').map(index => parseInt(index.trim()) - 1);
          const itemList = getItemsForField(currentField);

          if (selectedIndices.some(index => index < 0 || index >= itemList.length)) {
            showMessage("Beberapa pilihan tidak valid. Silakan coba lagi.", false);
            return;
          }

          const selectedItems = selectedIndices.map(index => itemList[index].id);
          formData[selectedTable][currentField] = selectedItems;
        } else {
          const selectedIndex = parseInt(input) - 1;
          const itemList = getItemsForField(currentField);
          if (selectedIndex >= 0 && selectedIndex < itemList.length) {
            formData[selectedTable][currentField] = itemList[selectedIndex].id;
          } else {
            showMessage("Pilihan tidak valid. Silakan coba lagi.", false);
            return;
          }
        }
      } else {
        formData[selectedTable][currentField] = message.trim();
      }
      requestNextField();
    }
  }

  function fetchTableData() {
    switch (selectedTable) {
      case 'helpdesk':
        tableData = helpdesks;
        break;
      case 'employee':
        tableData = employees;
        break;
      case 'leave':
        tableData = leaves;
        break;
      default:
        tableData = [];
    }

    const list = tableData.map((item, index) =>
      `${index + 1}. ${item.name || item.title || item.fullname || `ID: ${item.id}`}`).join('\n');
    showMessage(`Pilih data untuk ${currentAction}:\n${list}`, false);
  }

  function getItemsForField(field) {
    switch (field) {
      case 'category_id':
        return helpdeskCategories;
      case 'user_mentions':
        return users;
      case 'org_id':
        return organizations;
      case 'division_id':
        return divisions;
      case 'company_id':
        return companies;
      case 'placement_id':
        return placements;
      case 'gender_id':
        return genders;
      case 'marital_id':
        return maritals;
      case 'religion_id':
        return religions;
      case 'address_city_id':
      case 'address_permanent_city_id':
        return cities;
      case 'address_province_id':
      case 'address_permanent_province_id':
        return provinces;
      case 'bank_id':
        return banks;
      case 'emergency_relation_id':
        return emergencyRelations;
      case 'type_id':
        return leaveTypes;
      default:
        return [];
    }
  }

  function requestNextField() {
    const remainingFields = getRemainingFields();
    if (remainingFields.length > 0) {
      const fields = dataToCRUDTable.filter(table => table.table === selectedTable)[0].data;
      const field = remainingFields[0];
      const fieldValue = fields[field];

      showMessage(`Masukkan data untuk ${fieldValue}:`, false);

      if (field.includes('_id') || field === 'user_mentions') {
        const fieldName = field.replace('_id', '').replace('user_mentions', 'user mentions');
        let items = getItemsForField(field);

        const list = items.map((item, index) =>
          `${index + 1}. ${item.name || item.fullname || item.title || item.city || item.province}`).join(
          '\n');
        showMessage(`Silakan pilih ${fieldName}:\n${list}`, false);
      }
      //   else if (field === 'files' || field === 'fileInputGeneral') {
      //     showMessage('Silakan unggah file', false);
      // requestFileUpload();
      //   }
    } else {
      showMessage("Data Anda sudah lengkap, data akan segera diproses.", false);
      showMessage("Processing...", false);
      setTimeout(() => {
        sendToCRUDBackend();
      }, 300);
    }
  }

  //   function requestFileUpload() {
  //     $('.chatbot-input').hide();
  //     $('#chatbot-container').append(
  //       '<div style="padding: 10px; border-top: 1px solid #ddd; background-color: #fff;" id="file-input-container"><input type="file" class="form-control" id="file-input" multiple /></div>'
  //     );

  //     $('#file-input').on('change', function(event) {
  //       const files = event.target.files;
  //       const formDataForUpload = new FormData();

  //       for (let i = 0; i < files.length && i < 4; i++) {
  //         formDataForUpload.append(`file${i}`, files[i]);
  //       }

  //       if (selectedTable === 'helpdesk') {
  //         formData[selectedTable]['files'] = formDataForUpload;
  //       } else {
  //         formData[selectedTable]['fileInputGeneral'] = formDataForUpload;
  //       }

  //       showMessage(
  //         `Anda telah memilih ${files.length} file untuk diunggah.`,
  //         false
  //       );

  //       requestNextField();
  //       $('#chatbot-container').find('#file-input-container').remove();
  //       $('.chatbot-input').show();
  //     });
  //   }

  function getRemainingFields() {
    const fields = dataToCRUDTable.filter(table => table.table === selectedTable)[0].data;
    return Object.keys(fields).filter(field => !formData[selectedTable][field]);
  }

  function getCurrentField() {
    const remainingFields = getRemainingFields();
    return remainingFields.length > 0 ? remainingFields[0] : null;
  }

  function sendToCRUDBackend() {
    isLoadingChatbot = true;
    $('#chatbot-input').prop('disabled', true);
    $('#chatbot-input').prop('placeholder', 'Asisten sedang memproses...');
    $('#send-message-chat').prop('disabled', true);

    const formDataPayload = new FormData();

    // if (formData[selectedTable].files instanceof FormData || formData[selectedTable]
    //   .fileInputGeneral instanceof FormData) {
    //   const fileFormData = formData[selectedTable].files || formData[selectedTable].fileInputGeneral;

    //   for (let [key, value] of fileFormData.entries()) {
    //     formDataPayload.append(key, value);
    //   }

    //   if (selectedTable === 'helpdesk') {
    //     delete formData[selectedTable].files;
    //   } else {
    //     delete formData[selectedTable].fileInputGeneral;
    //   }
    // }


    if (currentAction === 'update') {
      formData[selectedTable].id = selectedDataId;
    }

    formData[selectedTable].created_by = '{{ auth()->user()->id }}';
    formData[selectedTable].updated_by = '{{ auth()->user()->id }}';

    formDataPayload.append('action', currentAction);
    formDataPayload.append('table', selectedTable);
    formDataPayload.append('data', JSON.stringify(formData[selectedTable]));

    if (selectedTable === 'leave') {
      const userEmployId = '{{ auth()->user()->employ_id }}';
      formDataPayload.append('employ_id', userEmployId);

    }

    let question = '';
    if (currentAction === 'create') {
      question = `Tolong buatkan saya data baru untuk table ${selectedTable}.`;
    } else if (currentAction === 'update') {
      question = `Tolong ubah data dengan ID ${selectedDataId} untuk table ${selectedTable}.`;
    }

    $.ajax({
      url: `${AI_API_URL}/chat/crud`,
      method: currentAction === 'create' ? 'POST' : 'PUT',
      contentType: 'application/json',
      processData: false,
      contentType: false,
      data: formDataPayload,
      success: async function(response) {
        const is_success = response.success;
        if (is_success) {
          showMessage("Data berhasil simpan.", false);
          resetChatbot();
          showMessage(
            "Apa yang ingin Anda lakukan? Pilih antara:\n1. Buat data\n2. Ubah data\n3. Hapus data\n4. Cari informasi\n9999. Keluar",
            false
          );
          await fetchAllTableData();
        } else {
          showMessage(xhr.statusText, false);
        }
      },
      error: function(xhr) {
        showMessage(`Terjadi kesalahan: ${xhr.statusText}.`, false);
      },
      complete: function() {
        isLoadingChatbot = false;
        $('#chatbot-input').prop('disabled', false);
        $('#chatbot-input').prop('placeholder', 'Ketik pesan anda...');
        $('#send-message-chat').prop('disabled', false);
      }
    });
  }

  function confirmAndDeleteData() {
    isLoadingChatbot = true;
    $('#chatbot-input').prop('disabled', true);
    $('#chatbot-input').prop('placeholder', 'Asisten sedang memproses...');
    $('#send-message-chat').prop('disabled', true);

    const formDataPayload = new FormData();

    formDataPayload.append('action', 'delete');
    formDataPayload.append('table', selectedTable);
    formDataPayload.append('data', JSON.stringify({
      id: selectedDataId
    }));

    formDataPayload.append('question',
      `Tolong hapus saya data dengan ID ${selectedDataId} untuk table ${selectedTable}.`);

    $.ajax({
      url: `${AI_API_URL}/chat/crud`,
      method: 'DELETE',
      contentType: 'application/json',
      processData: false,
      contentType: false,
      data: formDataPayload,
      success: async function(response) {
        const is_success = response.success;

        if (is_success) {
          showMessage("Data berhasil dihapus.", false);
          resetChatbot();
          showMessage(
            "Apa yang ingin Anda lakukan? Pilih antara:\n1. Buat data\n2. Ubah data\n3. Hapus data\n4. Cari informasi\n9999. Keluar",
            false
          );

          await fetchAllTableData();
        } else {}
      },
      error: function(xhr) {
        showMessage("Gagal menghapus data. Silakan coba lagi.", false);
      },
      complete: function() {
        isLoadingChatbot = false;
        $('#chatbot-input').prop('disabled', false);
        $('#chatbot-input').prop('placeholder', 'Ketik pesan anda...');
        $('#send-message-chat').prop('disabled', false);
      }
    });
  }

  function resetChatbot() {
    currentAction = '';
    selectedTable = '';
    formData = {};
    tableData = [];
    selectedDataId = '';
    chatbotMessages.empty();
  }
</script>
