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
@if (Request::is('presensi/data/resume/*') || Request::is('presensi/data/presence/*'))
    <div class="chat-icon" onclick="toggleChat()">
        <i class="ti-comments"></i>
    </div>

    <div id="chatContainer" class="chat-container">
        <div class="chat-header">
            <img src="{{ asset('images/user.png') }}" alt="Chat" class="rounded-circle me-2" style="width:30px; height:30px">
            <div>
                <strong>{{ $jadwals->first()->dosen->nama }}</strong>
            </div>
            <button class="btn btn-link text-white ms-auto" onclick="toggleChat()">
                <i class="ti-close"></i>
            </button>
        </div>

        <div class="chat-body" id="chatMessages">
            <div class="mb-3">
                <label for="jadwal" class="form-label">Pilih Jadwal Dosen</label>
                <select id="jadwalSelect" class="form-select">
                    <option value="">Pilih Jadwal</option>
                    @foreach ($jadwals as $jadwal)
                        <option value="{{ $jadwal->id }}" data-dosen="{{ $jadwal->dosen->nama }}"
                            data-matkul="{{ $jadwal->matkul->nama_matkul }}">
                            {{ $jadwal->matkul->nama_matkul }} - {{ $jadwal->kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                <p id="dosenName" class="mt-2"></p>
                <p id="matkulName" class="mt-2"></p>
            </div>
        </div>

        <div class="chat-footer">
            <input type="text" id="chatInput" placeholder="Tulis Pemberitahuan ..." onkeypress="handleChatInput(event)">
            <button class="btn btn-success" onclick="sendMessage()">
                <i class="ti-location-arrow"></i>
            </button>
        </div>
    </div>
    <script>
        function toggleChat() {
            const chatContainer = document.getElementById('chatContainer');
            chatContainer.style.display = chatContainer.style.display === 'flex' ? 'none' : 'flex';
        }
    
        function sendMessage() {
            const input = $('#chatInput');
            const message = input.val().trim();
            const jadwalId = $('#jadwalSelect').val();
            const senderType = '{{ class_basename(auth()->user()::class) }}'; 
            const senderId = '{{ auth()->id() }}';
    
            if (message && jadwalId) {
                $.ajax({
                    url: '{{ route('send.message') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        message: message,
                        jadwal_id: jadwalId,
                        sender_id: senderId,
                        sender_type: senderType // Pastikan sender_type sesuai dengan nama kelas tanpa namespace
                    },
                    success: function(data) {
                        if (data.message === 'Pesan berhasil dikirim!') {
                            const chatMessages = $('#chatMessages');
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
                        console.error('Error:', error);
                    }
                });
            } else {
                alert('Pesan dan jadwal harus dipilih');
            }
        }
    
        function handleChatInput(event) {
            if (event.key === 'Enter') {
                sendMessage();
            }
        }
    </script>
    
    
@endif

