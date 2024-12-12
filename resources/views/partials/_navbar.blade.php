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
        <div class="chat-icon" onclick="toggleChat()"> <i class="ti-comments"></i> </div>
    @else
        <div class="chat-icon" onclick="toggleContact()"> <i class="ti-comments"></i> </div>
    @endif

    @if (Auth::guard('direktur')->check() || Auth::guard('wakil_direktur')->check())
        <div id="chatContainer" class="chat-container" style="display:none;">
            <div class="chat-header"> <img src="{{ asset('images/user.png') }}" alt="Chat"
                    class="rounded-circle me-2" style="width:30px; height:30px">
                <div> <strong> {{ $jadwals->first()->dosen->nama ?? 'Pilih Jadwal' }} </strong> </div> <button
                    class="btn btn-link text-white ms-auto" onclick="toggleChat()"> <i class="ti-close"></i> </button>
            </div>
            <div class="chat-body" id="chatMessages">
                <div class="mb-3"> <label for="jadwal" class="form-label">Pilih Jadwal Dosen</label> <select
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
        @else 
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
                @foreach ($pesans as $pesan ) 
                <div class="contact-item" onclick="startChat({{ $pesan->sender_id }}, '{{ $pesan->sender_type }}', '{{ $pesan->sender->nama }}')"> 
                    <div class="contact-avatar"> 
                        <img src="{{ asset('images/user.png') }}" alt="Profile" class="rounded-circle" style="width: 50px; height: 50px;"> 
                    </div> 
                    <div class="contact-info"> 
                        <strong>{{ $pesan->sender->nama }}</strong> 
                        @if($pesan->sender_type == 'App\Models\Direktur') 
                        <p>Role: Direktur</p> 
                        @elseif($pesan->sender_type == 'App\Models\Wadir') 
                        <p>Role: Wakil Direktur</p> 
                        @endif 
                    </div> 
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
                <button class="btn btn-link text-white ms-auto" onclick="startChat()"> 
                    <i class="ti-close"></i> 
                </button> 
            </div> 
            <div class="chat-body" id="chatMessages"> 
                <div class="mb-3"> 
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
                setupJadwalSelect('#jadwalSelect');
                setupAlternativeJadwalSelect('#jadwalSelectDosen');
            });
        
            function toggleChat() {
                const chatContainer = document.getElementById('chatContainer');
                if (chatContainer.style.display === 'flex') {
                    chatContainer.style.display = 'none';
                } else {
                    chatContainer.style.display = 'flex';
                }
            }
        
            function toggleContact() {
                const chatContact = document.getElementById('chatContact');
                if (chatContact.style.display === 'flex') {
                    chatContact.style.display = 'none';
                } else {
                    chatContact.style.display = 'flex';
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
                $(selectId).on('change', function() {
                    const jadwalId = $(this).val();
                    const chatMessages = $(selectId).closest('.chat-container').find('#chatMessages');
                    const currentUserType = '{{ class_basename(auth()->user()::class) }}';
                    if (jadwalId) {
                        $.ajax({
                            url: '{{ route('get.messages') }}',
                            type: 'GET',
                            data: {
                                jadwal_id: jadwalId
                            },
                            success: function(messages) {
                                chatMessages.html(`
                                    <div class="mb-3">
                                        <label for="jadwal" class="form-label">Pilih Jadwal Dosen</label>
                                        <select id="jadwalSelect" class="form-select">
                                            <option value="">Pilih Jadwal</option>
                                            @foreach ($jadwals as $jadwal)
                                                <option value="{{ $jadwal->id }}" data-dosen="{{ $jadwal->dosen->nama }}" data-matkul="{{ $jadwal->matkul->nama_matkul }}" {{ old('jadwal_id') == $jadwal->id ? 'selected' : '' }}>
                                                    {{ $jadwal->matkul->nama_matkul }} - {{ $jadwal->kelas->nama_kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                `);
                                messages.forEach(message => {
                                    const position = message.sender_type === currentUserType ? 'text-end' : 'text-start';
                                    const bgColor = message.sender_type === currentUserType ? 'bg-primary' : 'bg-secondary';
                                    const textColor = message.sender_type === currentUserType ? 'text-white' : 'text-dark';
                                    const messageElement = `
                                        <div class="mb-2 ${position}">
                                            <div class="${bgColor} ${textColor} p-2 rounded d-inline-block">
                                                ${message.message}
                                            </div>
                                            <small class="d-block text-muted">${new Date(message.sent_at).toLocaleTimeString()}</small>
                                        </div>
                                    `;
                                    chatMessages.append(messageElement);
                                });
                                $(selectId).val(jadwalId);
                                chatMessages.scrollTop(chatMessages[0].scrollHeight);
                            },
                            error: function(xhr) {
                                console.error('Error fetching messages:', xhr.responseText);
                            }
                        });
                    }
                });
            }
        
            function setupAlternativeJadwalSelect(selectId) {
                $(selectId).on('change', function() {
                    const jadwalId = $(this).val();
                    const senderId = $(this).attr('data-receiver-id');
                    const senderType = $(this).attr('data-receiver-type');
                    const senderName = $(this).attr('data-sender-name');
                    const chatMessages = $(selectId).closest('.chat-container').find('#chatMessages');
                    if (jadwalId) {
                        $.ajax({
                            url: '{{ route('get.messages.alternative') }}',
                            type: 'GET',
                            data: {
                                jadwal_id: jadwalId,
                                sender_id: senderId,
                                sender_type: senderType
                            },
                            success: function(messages) {
                                chatMessages.html(`
                                                                <div class="mb-3">
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
                        `);
                        $('#jadwalSelectDosen')
                            .attr('data-receiver-id', senderId)
                            .attr('data-receiver-type', senderType)
                            .attr('data-sender-name', senderName);
                        messages.forEach(message => {
                            const position = message.sender_type === senderType ? 'text-end' : 'text-start';
                            const bgColor = message.sender_type === senderType ? 'bg-primary' : 'bg-secondary';
                            const textColor = message.sender_type === senderType ? 'text-white' : 'text-dark';
                            const messageElement = `
                                <div class="mb-2 ${position}">
                                    <div class="${bgColor} ${textColor} p-2 rounded d-inline-block">
                                        ${message.message}
                                    </div>
                                    <small class="d-block text-muted">${new Date(message.sent_at).toLocaleTimeString()}</small>
                                </div>
                            `;
                            chatMessages.append(messageElement);
                        });
                        $(selectId).val(jadwalId);
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
                    chatMessages.html(`
                        <div class="mb-3">
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
                    `);
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
                            chatMessages.append(messageElement);
                            input.val('');
                            chatMessages.scrollTop(chatMessages[0].scrollHeight);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Error handling
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
</script>
@endif
