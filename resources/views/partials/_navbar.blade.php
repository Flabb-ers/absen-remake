<style>
    .nav-item .nav-profile p {
        margin-top: 5px;
        margin-bottom: 0;
    }

    .nav-item.nav-profile {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chat-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 300px;
        height: 500px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: none;
        flex-direction: column;
        z-index: 1000;
    }

    .chat-header {
        background-color: #008069;
        color: white;
        padding: 10px;
        display: flex;
        align-items: center;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .chat-body {
        flex-grow: 1;
        overflow-y: auto;
        padding: 10px;
    }

    .chat-footer {
        display: flex;
        padding: 10px;
        background-color: #f0f0f0;
    }

    .chat-footer input {
        flex-grow: 1;
        margin-right: 10px;
        border: 1px solid #ddd;
        border-radius: 20px;
        padding: 8px;
    }

    .chat-icon {
        cursor: pointer;
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #008069;
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }

    .chat-message {
        margin: 5px 0;
    }

    .chat-message.sent {
        text-align: right;
    }

    .chat-message.sent .message-bubble {
        background-color: #007bff;
        color: white;
        border-radius: 15px 15px 0 15px;
        display: inline-block;
        padding: 10px;
    }

    .chat-message.received {
        text-align: left;
    }

    .chat-message.received .message-bubble {
        background-color: #f1f1f1;
        color: black;
        border-radius: 15px 15px 15px 0;
        display: inline-block;
        padding: 10px;
    }

    .message-time {
        font-size: 0.75em;
        color: gray;
    }

    .text-end .bg-primary {
        border-top-right-radius: 15px;
        border-top-left-radius: 15px;
    }

    .text-start .bg-secondary {
        border-top-right-radius: 15px;
        border-top-left-radius: 15px;
    }
    .chat-body .jadwal-select-container {
        width: 100%;
        max-width: 100%;
        position: sticky; 
        top: 0; 
        left: 0;
        right: 0;
        z-index: 10; 
        background-color: white;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 0;
        margin: 0;
    }

    .chat-body .jadwal-select-container .form-select {
        width: 100%;
        max-width: 100%;
    }
    .count-indicator .badge {
        font-size: 0.6rem;
        padding: 0.2rem 0.4rem;
    }

    .max-height-300 {
        max-height: 300px;
        overflow-y: auto;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #f8f9fa;
    }
</style>
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <a class="navbar-brand brand-logo me-5" href="index.html">
            <img src="{{ asset('/images/logo.png') }}" class="me-2" alt="logo" />
        </a>
        <a class="navbar-brand brand-logo-mini" href="index.html">
            <img src="{{ asset('/images/logomini.png') }}" alt="logo" style="padding-right: 2px;padding-left:2px" />
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right d-flex align-items-center">
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle position-relative" id="notificationDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="icon-bell mx-0"></i>
                    <span class="badge bg-danger rounded-circle position-absolute top-0 start-100 translate-middle" 
                          style="font-size: 0.5rem; padding: 0.1rem 0.3rem; min-width: 16px; height: 16px; display: flex; align-items: center; justify-content: center;" 
                          id="notificationCount">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list shadow-sm" aria-labelledby="notificationDropdown">
                    <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                        Notifications 
                        <span class="badge bg-secondary" style="font-size: 0.6rem; padding: 0.1rem 0.1rem;" id="totalNotificationCount">0</span>
                    </h6>
                    <div id="notificationList" style="max-height: 250px; overflow-y: auto;">
                    </div>
                    <div class="dropdown-footer text-center py-2">
                        <a href="#" class="text-muted small">Mark all as read</a>
                    </div>
                </div>
            </li>
            <li class="nav-item nav-profile dropdown">
                <p class="d-flex align-items-center mr-2 mb-0">{{ session()->get('user.nama') }}</p>
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                    <img src="{{ asset('/images/user.png') }}" alt="profile" style="width:25px; height:25px"
                        class="border border-dark rounded-circle" />
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <a class="dropdown-item">
                        <i class="ti-settings text-primary"></i> Settings </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="ti-power-off text-primary"></i> Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>

@if (Request::is('presensi/data/resume/*') ||
        Request::is('presensi/data/presence/*') ||
        Request::is('presensi/data/contract/*') ||
        Request::is('presensi/data-presensi') ||
        Request::is('presensi/data-kontrak'))
    @if (Auth::guard('direktur')->check() || Auth::guard('wakil_direktur')->check())
        <div class="chat-icon" onclick="toggleChat()"> 
            <i class="ti-comments"></i> 
            <span id="unreadMessageBadge" class="badge bg-danger" style="display:none; position: absolute; top: -8px; right: -8px;">0</span>
        </div>
    @elseif(Auth::guard('dosen')->check())
        <div class="chat-icon" onclick="toggleContact()"> 
            <i class="ti-comments"></i> 
            <span id="unreadMessageBadge" class="badge bg-danger" style="display:none; position: absolute; top: -8px; right: -8px;">0</span>
        </div>
    @endif

    @if (Auth::guard('direktur')->check() || Auth::guard('wakil_direktur')->check())
        <div id="chatContainer" class="chat-container" style="display:none;">
            <div class="chat-header"> <img src="{{ asset('images/user.png') }}" alt="Chat"
                    class="rounded-circle me-2" style="width:30px; height:30px">
                <div> <strong> {{ $jadwals->first()->dosen->nama ?? 'Pilih Jadwal' }} </strong> </div> <button
                    class="btn btn-link text-white ms-auto" onclick="toggleChat()"> <i class="ti-close"></i> </button>
            </div>
            <div class="chat-body" id="chatMessages">
                <div class="mb-3 jadwal-select-container"> <label for="jadwal" class="form-label">Pilih Jadwal Dosen</label> <select
                        id="jadwalSelect" class="form-select">
                        <option value="">Pilih Jadwal</option>
                        @foreach ($jadwals as $jadwal)
                            <option value="{{ $jadwal->id }}" data-dosen="{{ $jadwal->dosen->nama }}"
                                data-matkul="{{ $jadwal->matkul->nama_matkul }}"> {{ $jadwal->matkul->nama_matkul }} -
                                {{ $jadwal->kelas->nama_kelas }} </option>
                        @endforeach
                    </select> 
                </div>
            </div>
            <div class="chat-footer">
                <input type="text" id="chatInput" placeholder="Tulis Pemberitahuan ..." onkeypress="handleChatInput(event)">
                <button class="btn btn-success" onclick="sendMessage('#chatContainer')">
                    <i class="ti-location-arrow"></i>
                </button>
            </div>
        </div>
    @elseif(Auth::guard('dosen')->check())
    <style>
        .contact-list {
            max-height: 500px;
            overflow-y: auto;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
        }
        
        .contact-item:hover {
            background-color: #f1f3f5;
            transform: translateX(5px);
        }
        
        .contact-item:last-child {
            border-bottom: none;
        }
        
        .contact-avatar {
            margin-right: 15px;
        }
        
        .contact-avatar img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e0e0e0;
        }
        
        .contact-info {
            flex-grow: 1;
        }
        
        .contact-info strong {
            font-size: 16px;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }
        
        .contact-info p {
            font-size: 12px;
            color: #6c757d;
            margin: 0;
        }
        
        .contact-item .badge {
            position: absolute;
            top: 10px;
            right: 10px;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            padding: 0 5px;
        }
        
        .contact-item[data-contact-type*="Direktur"] .contact-avatar img {
            border-color: #007bff;
        }
        
        .contact-item[data-contact-type*="Wadir"] .contact-avatar img {
            border-color: #28a745;
        }
        
        .contact-item.active {
            background-color: #e9ecef;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        @media (max-width: 768px) {
            .contact-item {
                padding: 10px;
            }
            
            .contact-avatar img {
                width: 40px;
                height: 40px;
            }
            
            .contact-info strong {
                font-size: 14px;
            }
            
            .contact-info p {
                font-size: 11px;
            }
        }
        </style>
        <div id="chatContact" class="chat-container" style="display:none;"> 
            <div class="chat-header"> 
                <img src="{{ asset('images/user.png') }}" alt="Chat" class="rounded-circle me-2" style="width:30px; height:30px"> 
                <div> 
                    <strong> Pemberitahuan </strong> 
                </div> 
                <button class="btn btn-link text-white ms-auto" onclick="toggleContact()"> 
                    <i class="ti-close"></i> 
                </button> 
            </div> 
            <div class="chat-body" id="chatMessages"> 
                @foreach($pesans as $pesan)
                    <div class="contact-item" 
                        data-contact-id="{{ $pesan->sender_id }}" 
                        data-contact-type="{{ $pesan->sender_type }}"
                        onclick="startChat({{ $pesan->sender_id }}, '{{ $pesan->sender_type }}', '{{ $pesan->sender->nama }}')">
                        <div class="contact-avatar"> 
                            <img src="{{ asset('images/user.png') }}" alt="Profile" class="rounded-circle" style="width: 50px; height: 50px;"> 
                        </div> 
                        <div class="contact-info"> 
                            <strong>{{ $pesan->sender->nama }}</strong> 
                            @if($pesan->sender_type == 'App\Models\Direktur') 
                            <p>Direktur</p> 
                            @elseif($pesan->sender_type == 'App\Models\Wadir') 
                            <p>Wakil Direktur</p> 
                            @endif 
                        </div>
                        <span class="badge bg-danger ms-auto" style="display: none; position: absolute; right: 10px;">
                            0
                        </span>
                    </div>
                @endforeach
            </div> 
        </div> 
    @endif 
        <div id="chatStart" class="chat-container" style="display:none;"> 
            <div class="chat-header"> 
                <img src="{{ asset('images/user.png') }}" alt="Chat" class="rounded-circle me-2" style="width:30px; height:30px"> 
                <div> 
                    <strong> Pilih Jadwal </strong> 
                </div> 
                <button class="btn btn-link text-white ms-auto" onclick="closeChat()"> 
                    <i class="ti-close"></i> 
                </button> 
            </div> 
            <div class="chat-body" id="chatMessages"> 
                <div class="mb-3 jadwal-select-container"> 
                    <label for="jadwal" class="form-label">Pilih Jadwal Dosen</label> 
                    <select id="jadwalSelectDosen" class="form-select"> 
                        <option value="">Pilih Jadwal</option> 
                        @foreach ($jadwals as $jadwal) 
                        <option value="{{ $jadwal->id }}" data-dosen="{{ $jadwal->dosen->nama }}" data-matkul="{{ $jadwal->matkul->nama_matkul }}"> 
                            {{ $jadwal->matkul->nama_matkul }} - {{ $jadwal->kelas->nama_kelas }} 
                        </option> 
                        @endforeach 
                    </select> 
                </div> 
            </div> 
            <div class="chat-footer"> 
                <input type="text" id="chatInput" placeholder="Tulis Pemberitahuan ..." onkeypress="handleChatInput(event)"> 
                <button class="btn btn-success" onclick="sendMessage('#chatStart')"> <i class="ti-location-arrow"></i> 
                </button> 
            </div> 
        </div>

        <script>
            $(document).ready(function() {
                $('#chatContainer, #chatStart, #chatContact').hide();
                
                const userType = '{{ class_basename(auth()->user()::class) }}';
                
                switch(userType) {
                    case 'Dosen':
                        setupAlternativeJadwalSelect('#jadwalSelectDosen');
                        break;
                    case 'Direktur':
                    case 'Wadir':
                        setupJadwalSelect('#jadwalSelect');
                        break;
                    default:
                        console.log('Tipe user tidak dikenali');
                }
                
                if ($('#jadwalSelectDosen').length) {
                    setupAlternativeJadwalSelect('#jadwalSelectDosen');
                }
                
                updateUnreadMessageCount();
                setInterval(updateUnreadMessageCount, 30000);
            });

            function updateAllContactsUnreadCount() {
                $('.contact-item').each(function() {
                    const contactId = $(this).data('contact-id');
                    let contactType = $(this).data('contact-type');
                    contactType = contactType.split('\\').pop();
                    updateContactUnreadCount(contactId, contactType);
                });
            }

            $(document).ready(function() {
                updateAllContactsUnreadCount();

                setInterval(updateAllContactsUnreadCount, 30000);
            });


            setInterval(updateUnreadMessageCount, 30000); 
                $(document).ready(function() {
                updateUnreadMessageCount();
            });
        
            function toggleChat() {
                const chatContainer = document.getElementById('chatContainer');
                if (chatContainer.style.display === 'flex') {
                    chatContainer.style.display = 'none';
                    resetJadwalDropdown('#jadwalSelect');
                } else {
                    chatContainer.style.display = 'flex';
                    updateUnreadMessageCount();
                }
            }

            function toggleContact() {
                const chatContact = document.getElementById('chatContact');
                if (chatContact.style.display === 'flex') {
                    chatContact.style.display = 'none';
                    resetJadwalDropdown('#jadwalSelectDosen');
                } else {
                    chatContact.style.display = 'flex';
                    updateUnreadMessageCount();
                }
            }

            function closeChat() {
                const chatStart = document.getElementById('chatStart');
                const chatContact = document.getElementById('chatContact');
                
                $('#jadwalSelectDosen').val('');
                
                $('#chatStart #chatMessages').html(`
                    <div class="mb-3 jadwal-select-container">
                        <label for="jadwal" class="form-label">Pilih Jadwal Dosen</label>
                        <select id="jadwalSelectDosen" class="form-select">
                            <option value="">Pilih Jadwal</option>
                            @foreach ($jadwals as $jadwal)
                                <option value="{{ $jadwal->id }}" 
                                    data-dosen="{{ $jadwal->dosen->nama }}" 
                                    data-matkul="{{ $jadwal->matkul->nama_matkul }}">
                                    {{ $jadwal->matkul->nama_matkul }} - {{ $jadwal->kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                `);
                
                $('#jadwalSelectDosen')
                    .removeAttr('data-receiver-id')
                    .removeAttr('data-receiver-type')
                    .removeAttr('data-sender-name');
                
                $('#chatStart .chat-header strong').text('Pilih Jadwal');
                
                chatStart.style.display = 'none';
                chatContact.style.display = 'flex';
                
                setupAlternativeJadwalSelect('#jadwalSelectDosen');
            }

            function resetJadwalDropdown(selectId) {
                $(selectId).val('');
                
                $(selectId).closest('.chat-container').find('#chatMessages').html(`
                    <div class="mb-3 jadwal-select-container">
                        <label for="jadwal" class="form-label">Pilih Jadwal</label>
                        <select id="${selectId.replace('#', '')}" class="form-select">
                            <option value="">Pilih Jadwal</option>
                            @foreach ($jadwals as $jadwal)
                                <option value="{{ $jadwal->id }}" 
                                    data-dosen="{{ $jadwal->dosen->nama }}" 
                                    data-matkul="{{ $jadwal->matkul->nama_matkul }}">
                                    {{ $jadwal->matkul->nama_matkul }} - {{ $jadwal->kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                `);

                $(selectId)
                    .removeAttr('data-receiver-id')
                    .removeAttr('data-receiver-type')
                    .removeAttr('data-sender-name');

                const userType = '{{ class_basename(auth()->user()::class) }}';
                switch(userType) {
                    case 'Direktur':
                    case 'Wadir':
                        setupJadwalSelect(selectId);
                        break;
                    case 'Dosen':
                        setupAlternativeJadwalSelect(selectId);
                        break;
                    default:
                        console.log('Tipe user tidak dikenali');
                }
            }

            function startChat(senderId, senderType, senderName) {
                const chatStart = document.getElementById('chatStart');
                const chatContact = document.getElementById('chatContact');
                $('#chatStart .chat-header strong').text(senderName);
                $('#jadwalSelectDosen')
                    .attr('data-receiver-id', senderId)
                    .attr('data-receiver-type', senderType)
                    .attr('data-sender-name', senderName);
                if (chatStart.style.display === 'flex') {
                    chatStart.style.display = 'none';
                    chatContact.style.display = 'flex';
                } else {
                    chatStart.style.display = 'flex';
                    chatContact.style.display = 'none';
                }
            }
        
            function setupJadwalSelect(selectId) {
                $(selectId).off('change').on('change', function() {
                    const jadwalId = $(this).val();
                    const chatMessages = $(this).closest('.chat-container').find('#chatMessages');
                    const currentUserType = '{{ class_basename(auth()->user()::class) }}';
                    
                    if (jadwalId) {
                        const selectedOption = $(this).find('option:selected');
                        const dosenNama = selectedOption.data('dosen');
                        const matkulNama = selectedOption.data('matkul');
                        
                        $(this).closest('.chat-container')
                            .find('.chat-header strong')
                            .text(`${dosenNama} - ${matkulNama}`);

                        $.ajax({
                            url: '{{ route('get.messages') }}',
                            type: 'GET',
                            data: {
                                jadwal_id: jadwalId
                            },
                            success: function(messages) {
                                chatMessages.find('.message-container').remove();

                                const messageContainer = $('<div class="message-container"></div>');
                                
                                messages.forEach(message => {
                                    const position = message.sender_type !== 'App\\Models\\Dosen' 
                                        ? 'text-end' 
                                        : 'text-start';
                                    
                                    const bgColor = message.sender_type !== 'App\\Models\\Dosen'
                                        ? 'bg-primary text-white' 
                                        : 'bg-secondary text-dark';
                                    
                                    const messageElement = `
                                        <div class="mb-2 ${position}">
                                            <div class="${bgColor} p-2 rounded d-inline-block">
                                                ${message.message}
                                            </div>
                                            <small class="d-block text-muted">
                                                ${new Date(message.sent_at).toLocaleTimeString()}
                                            </small>
                                        </div>
                                    `;
                                    
                                    messageContainer.append(messageElement);
                                });

                                chatMessages.append(messageContainer);
                                
                                chatMessages.scrollTop(chatMessages[0].scrollHeight);
                            },
                            error: function(xhr) {
                                console.error('Error fetching messages:', xhr.responseText);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Kesalahan',
                                    text: 'Gagal mengambil pesan. Silakan coba lagi.'
                                });
                            }
                        });
                    } else {
                        $(this).closest('.chat-container')
                            .find('.chat-header strong')
                            .text('Pilih Jadwal');
                        
                        chatMessages.find('.message-container').remove();
                    }
                });
            }

            function setupAlternativeJadwalSelect(selectId) {
                $(selectId).off('change').on('change', function() {
                    const jadwalId = $(this).val();
                    const senderId = $(this).attr('data-receiver-id');
                    const senderType = $(this).attr('data-receiver-type');
                    const senderName = $(this).attr('data-sender-name');
                    const chatMessages = $(this).closest('.chat-container').find('#chatMessages');
                    const currentUserType = '{{ class_basename(auth()->user()::class) }}';
                    
                    if (jadwalId) {
                        const selectedOption = $(this).find('option:selected');
                        const dosenNama = selectedOption.data('dosen');
                        const matkulNama = selectedOption.data('matkul');
                        
                        $(this).closest('.chat-container')
                            .find('.chat-header strong')
                            .text(`${senderName}`);

                        $.ajax({
                            url: '{{ route('get.messages.alternative') }}',
                            type: 'GET',
                            data: {
                                jadwal_id: jadwalId,
                                sender_id: senderId,
                                sender_type: senderType
                            },
                            success: function(messages) {
                                chatMessages.find('.message-container').remove();
                                const messageContainer = $('<div class="message-container"></div>');
                                
                                messages.forEach(message => {
                                    const position = message.sender_type === 'App\\Models\\Dosen' 
                                        ? 'text-end' 
                                        : 'text-start';
                                    
                                    const bgColor = message.sender_type === 'App\\Models\\Dosen'
                                        ? 'bg-primary text-white' 
                                        : 'bg-secondary text-dark';
                                    
                                    const messageElement = `
                                        <div class="mb-2 ${position}">
                                            <div class="${bgColor} p-2 rounded d-inline-block">
                                                ${message.message}
                                            </div>
                                            <small class="d-block text-muted">
                                                ${new Date(message.sent_at).toLocaleTimeString()}
                                            </small>
                                        </div>
                                    `;
                                    
                                    messageContainer.append(messageElement);
                                });

                                chatMessages.append(messageContainer);
                                chatMessages.scrollTop(chatMessages[0].scrollHeight);
                            },
                            error: function(xhr) {
                                console.error('Error fetching messages:', xhr.responseText);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Kesalahan',
                                    text: 'Gagal mengambil pesan. Silakan coba lagi.'
                                });
                            }
                        });
                    } else {
                        $(this).closest('.chat-container')
                            .find('.chat-header strong')
                            .text('Pilih Jadwal');
                        
                        chatMessages.find('.message-container').remove();
                    }
                });
            }

            function sendMessage(containerId) {
                const container = $(containerId);
                const input = container.find('#chatInput');
                const message = input.val().trim();
                const jadwalSelect = container.find('select[id^="jadwalSelect"]');
                const jadwalId = jadwalSelect.val();
                const senderType = '{{ class_basename(auth()->user()::class) }}';
                const senderId = '{{ auth()->id() }}';
                const receiverType = $('#jadwalSelectDosen').attr('data-receiver-type');
                const receiverId = $('#jadwalSelectDosen').attr('data-receiver-id');
                if (!jadwalId) {
                    alert('Silakan pilih jadwal terlebih dahulu');
                    return;
                }
                if (message) {
                    $.ajax({
                        url: '{{ route('send.message') }}',
                        type: 'POST',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        },
                        data: {
                            message: message,
                            jadwal_id: jadwalId,
                            sender_id: senderId,
                            sender_type: senderType,
                            receiver_id: receiverId,
                            receiver_type: receiverType
                        },
                        success: function(response) {
                            if (response.message === 'Pesan berhasil dikirim!') {
                                const chatMessages = container.find('#chatMessages');
                                const messageElement = `
                                    <div class="mb-2 text-end">
                                        <div class="bg-primary text-white p-2 rounded d-inline-block">
                                            ${message}
                                        </div>
                                        <small class="d-block text-muted">${new Date().toLocaleTimeString()}</small>
                                    </div>
                                `;
                                chatMessages.find('.message-container').append(messageElement);
                                input.val('');
                                chatMessages.scrollTop(chatMessages[0].scrollHeight);
                            }
                            updateUnreadMessageCount();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat mengirim pesan');
                        }
                    });
                } else {
                    alert('Pesan tidak boleh kosong');
                }
            }

            function handleChatInput(event) {
                const containerId = $(event.target).closest('.chat-container').attr('id');
                if (event.key === 'Enter') {
                    sendMessage(`#${containerId}`);
                }
            }

            function updateUnreadMessageCount() {
                const guard = '{{ Session::get('user.role') }}';
                let dosenId = null;

                if (guard === 'dosen') {
                    dosenId = null;
                } else if (guard === 'wakil_direktur' || guard === 'direktur') {
                    dosenId = {{ request()->segment(4) ?? 'null' }};
                }

                $.ajax({
                    url: '{{ route('get.unread.count') }}',
                    type: 'GET',
                    data: {
                        dosen_id: dosenId
                    },
                    success: function(response) {
                        const unreadCount = response.unread_count;
                        const unreadMessages = response.unread_get;

                        if (unreadCount > 0) {
                            $('#unreadMessageBadge')
                                .text(unreadCount)
                                .show();
                        } else {
                            $('#unreadMessageBadge').hide();
                        }
                    },
                    error: function(xhr) {
                        console.error('Gagal mengambil jumlah pesan belum dibaca');
                    }
                });
            }

            
            function updateContactUnreadCount(contactId, contactType) {
                const url = `{{ route('get.unread.count.by.contact', ['contactId' => '__contactId__', 'contactType' => '__contactType__']) }}`;
                const finalUrl = url.replace('__contactId__', contactId).replace('__contactType__', contactType);

                $.ajax({
                    url: finalUrl,
                    type: 'GET',
                    success: function(response) {
                        const unreadCount = response.unread_count;
                        const badgeSelector = `.contact-item[data-contact-id="${contactId}"] .badge`;

                        if (unreadCount > 0) {
                            $(badgeSelector)
                                .text(unreadCount)
                                .show();
                        } else {
                            $(badgeSelector).hide();
                        }
                    },
                    error: function(xhr) {
                        console.error('Gagal mengambil jumlah pesan belum dibaca');
                    }
                });
            }

        </script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    function fetchNotifications() {
        fetch('/presensi/get-notif').then(response => response.json())
            .then(data => {
                const notificationCountElements = document.querySelectorAll('#notificationCount');
                notificationCountElements.forEach(el => {
                    el.textContent = data.unread_count;
                    el.classList.toggle('d-none', data.unread_count === 0);
                });

                const totalNotificationCount = document.getElementById('totalNotificationCount');
                totalNotificationCount.textContent = data.unread_count;
                
                const notificationList = document.getElementById('notificationList');
                notificationList.innerHTML = '';
                
                if (data.notifications.length === 0) {
                    notificationList.innerHTML = `
                        <div class="text-center text-muted py-3">
                            <small>No new notifications</small>
                        </div>
                    `;
                }

                data.notifications.forEach(notification => {
                    const notificationItem = document.createElement('a');
                    notificationItem.className = 'dropdown-item preview-item';
                    notificationItem.setAttribute('data-notification-id', notification.id);
                    
                    const notificationIcon = getNotificationIcon(notification.data.notification_type);
                    
                    if(notification.data.notification_type == 'pemberitahuan'){
                        notificationItem.innerHTML = `
                            <div class="preview-thumbnail">
                                <div class="preview-icon ${notificationIcon.bgClass}">
                                    <i class="${notificationIcon.iconClass} mx-0"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal">
                                    ${notification.data.title}
                                </h6>
                                <h6 class="preview-subject font-weight-light text-muted" style="font-size: 0.8em;">
                                    ${notification.data.sender_name} · ${notification.data.matkul} · ${notification.data.class}
                                </h6>
                                <p class="font-weight-light small-text mb-0 text-muted">
                                    ${notification.data.message_content} · ${formatTimeAgo(notification.created_at)}
                                    ${!notification.read_at ? '<span class="badge bg-primary ms-2">New</span>' : ''}
                                </p>
                            </div>
                        `;
                    }else if(notification.data.notification_type == 'pembayaran'){
                        if(notification.data.keterangan == null && notification.data.status == 0){
                            notificationItem.innerHTML = `
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-success">
                                        <i class="mdi mdi-cash-multiple mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">
                                        ${notification.data.title}
                                    </h6>
                                    <h6 class="preview-subject font-weight-light text-muted" style="font-size: 0.8em;">
                                        ${notification.data.class} || ${notification.data.prodi}
                                    </h6>
                                    <p class="font-weight-light small-text mb-0 text-muted">
                                        ${notification.data.name} · ${formatTimeAgo(notification.created_at)}
                                        ${!notification.read_at ? '<span class="badge bg-primary ms-2">New</span>' : ''}
                                    </p>
                                </div>
                            `;
                        }if(notification.data.keterangan == 'Sudah' && notification.data.status == 1){
                            notificationItem.innerHTML = `
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-success">
                                        <i class="ti-check mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">
                                        ${notification.data.title}
                                    </h6>
                                    <p class="font-weight-light small-text mb-0 text-muted">
                                        ${notification.data.message_content} · ${formatTimeAgo(notification.created_at)}
                                        ${!notification.read_at ? '<span class="badge bg-primary ms-2">New</span>' : ''}
                                    </p>
                                </div>
                            `;
                        }else if(notification.data.keterangan == 'Belum' && notification.data.status == 0){
                            notificationItem.innerHTML = `
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-danger">
                                        <i class="ti-close mx-0"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <h6 class="preview-subject font-weight-normal">
                                        ${notification.data.title}
                                    </h6>
                                    <p class="font-weight-light small-text mb-0 text-muted">
                                        ${notification.data.message_content} · ${formatTimeAgo(notification.created_at)}
                                        ${!notification.read_at ? '<span class="badge bg-primary ms-2">New</span>' : ''}
                                    </p>
                                </div>
                            `;
                        }
                    }else if(notification.data.notification_type == 'krs'){
                        notificationItem.innerHTML = `
                            <div class="preview-thumbnail">
                                @if(Auth::guard('admin')->check() || Auth::guard('mahasiswa')->check())
                                    <div class="preview-icon bg-success">
                                        <i class="ti-check mx-0"></i>
                                    </div>
                                @else
                                    <div class="preview-icon bg-info">
                                        <i class="${notificationIcon.iconClass} mx-0"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal">
                                    ${notification.data.title}
                                    </h6>
                                    @if(Auth::guard('admin')->check())
                                        <h6 class="preview-subject font-weight-light text-muted" style="font-size: 0.8em;">
                                        ${notification.data.name} | ${notification.data.kelas}
                                        </h6>
                                    @endif
                                <p class="font-weight-light small-text mb-0 text-muted">
                                    ${notification.data.message_content} · ${formatTimeAgo(notification.created_at)}
                                    ${!notification.read_at ? '<span class="badge bg-primary ms-2">New</span>' : ''}
                                </p>
                            </div>
                        `; 
                    }else if(notification.data.notification_type == 'presensi'){
                        notificationItem.innerHTML = `
                            <div class="preview-thumbnail">
                                @if(Auth::guard('dosen')->check())
                                <div class="preview-icon bg-success">
                                    <i class="ti-check mx-0"></i>
                                </div>
                                @else
                                <div class="preview-icon bg-info">
                                    <i class="ti-bell mx-0"></i>
                                </div>
                                @endif
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal">
                                    ${notification.data.title}
                                </h6>
                                <h6 class="preview-subject font-weight-light text-muted" style="font-size: 0.8em;">
                                    ${notification.data.class || ''} · ${notification.data.matkul}
                                    @if(Auth::guard('dosen')->check())
                                    @else
                                     · ${notification.data.name}
                                     @endif
                                </h6>
                                <p class="font-weight-light small-text mb-0 text-muted">
                                    ${notification.data.message_content} · ${formatTimeAgo(notification.created_at)}
                                    ${!notification.read_at ? '<span class="badge bg-primary ms-2">New</span>' : ''}
                                </p>
                            </div>
                        `;
                    }else if(notification.data.notification_type == 'resume'){
                        notificationItem.innerHTML = `
                            <div class="preview-thumbnail">
                                @if(Auth::guard('dosen')->check())
                                <div class="preview-icon bg-success">
                                    <i class="ti-check mx-0"></i>
                                </div>
                                @else
                                <div class="preview-icon bg-info">
                                    <i class="ti-bell mx-0"></i>
                                </div>
                                @endif
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal">
                                    ${notification.data.title}
                                </h6>
                                <h6 class="preview-subject font-weight-light text-muted" style="font-size: 0.8em;">
                                    ${notification.data.class || ''} · ${notification.data.matkul}
                                    @if(Auth::guard('dosen')->check())
                                    @else
                                     · ${notification.data.name}
                                     @endif
                                </h6>
                                <p class="font-weight-light small-text mb-0 text-muted">
                                    ${notification.data.message_content} · ${formatTimeAgo(notification.created_at)}
                                    ${!notification.read_at ? '<span class="badge bg-primary ms-2">New</span>' : ''}
                                </p>
                            </div>
                        `;
                    }else if(notification.data.notification_type == 'kontrak'){
                        notificationItem.innerHTML = `
                            <div class="preview-thumbnail">
                                @if(Auth::guard('dosen')->check())
                                <div class="preview-icon bg-success">
                                    <i class="ti-book mx-0"></i>
                                </div>
                                @else
                                <div class="preview-icon bg-info">
                                    <i class="ti-bell mx-0"></i>
                                </div>
                                @endif
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal">
                                    ${notification.data.title}
                                </h6>
                                <h6 class="preview-subject font-weight-light text-muted" style="font-size: 0.8em;">
                                    ${notification.data.class || ''} · ${notification.data.matkul}
                                    @if(Auth::guard('dosen')->check())
                                    @else
                                     · ${notification.data.name}
                                     @endif
                                </h6>
                                <p class="font-weight-light small-text mb-0 text-muted">
                                    ${notification.data.message_content} · ${formatTimeAgo(notification.created_at)}
                                    ${!notification.read_at ? '<span class="badge bg-primary ms-2">New</span>' : ''}
                                </p>
                            </div>
                        `;
                    }else if(notification.data.notification_type == 'nilai'){
                        @if (Auth::guard('dosen')->check() || Auth::guard('admin')->check())
                        notificationItem.innerHTML = `
                            <div class="preview-thumbnail">
                                @if(Auth::guard('dosen')->check())
                                <div class="preview-icon bg-success">
                                    <i class="ti-star mx-0"></i>
                                </div>
                                @else
                                <div class="preview-icon bg-info">
                                    <i class="ti-bell mx-0"></i>
                                </div>
                                @endif
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal">
                                    ${notification.data.title}
                                </h6>
                                <h6 class="preview-subject font-weight-light text-muted" style="font-size: 0.8em;">
                                    ${notification.data.class || ''} · ${notification.data.matkul}
                                    @if(Auth::guard('dosen')->check())
                                    @else
                                     · ${notification.data.name}
                                     @endif
                                </h6>
                                <p class="font-weight-light small-text mb-0 text-muted">
                                    ${notification.data.message_content} · ${formatTimeAgo(notification.created_at)}
                                    ${!notification.read_at ? '<span class="badge bg-primary ms-2">New</span>' : ''}
                                </p>
                            </div>
                        `;
                        @elseif(Auth::guard('mahasiswa')->check() )
                        notificationItem.innerHTML = `
                            <div class="preview-thumbnail">
                                <div class="preview-icon bg-success">
                                    <i class="ti-cup mx-0"></i>
                                </div>
                            </div>
                            <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal">
                                    Pemberitahuan Nilai
                                </h6>
                                <h6 class="preview-subject font-weight-light text-muted" style="font-size: 0.8em;">
                                    ${notification.data.matkul}
                                </h6>
                                <p class="font-weight-light small-text mb-0 text-muted">
                                    Anda Mendapatkan Nilai <b>${notification.data.nilai}</b> · ${formatTimeAgo(notification.created_at)}
                                    ${!notification.read_at ? '<span class="badge bg-primary ms-2">New</span>' : ''}
                                </p>
                            </div>
                        `;
                        @endif
                    }
                    notificationItem.addEventListener('click', function() {
                        markNotificationAsRead(notification.id);
                    });
                    
                    notificationList.appendChild(notificationItem);
                });
            }).catch(error => {
                console.error('Error fetching notifications:', error);
        });
    }

    function getNotificationIcon(type) {
        switch(type) {
            case 'pemberitahuan':
                return {
                    bgClass: 'bg-info', 
                    iconClass: 'ti-info'
                };
            case 'peringatan':
                return {
                    bgClass: 'bg-warning', 
                    iconClass: 'ti-warning'
                };
            case 'presensi':
                return {
                    bgClass: 'bg-success', 
                    iconClass: 'ti-check'
                };
            default:
                return {
                    bgClass: 'bg-secondary', 
                    iconClass: 'ti-bell'
                };
        }
    }


        function markNotificationAsRead(notificationId = null) {
            const payload = notificationId 
                ? { notification_id: notificationId }
                : {};

            fetch('/presensi/mark-notif-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                fetchNotifications();
            })
            .catch(error => {
                console.error('Error marking notifications as read:', error);
            });
        }


        document.querySelector('.dropdown-footer a').addEventListener('click', function(e) {
            e.preventDefault();
            markNotificationAsRead(); 
        });

            function formatTimeAgo(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffInSeconds = Math.floor((now - date) / 1000);
                
                if (diffInSeconds < 60) return 'Baru saja';
                if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} menit yang lalu`;
                if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} jam yang lalu`;
                return `${Math.floor(diffInSeconds / 86400)} hari yang lalu`;
            }

            fetchNotifications();

            setInterval(fetchNotifications, 60000);
        });

</script>
