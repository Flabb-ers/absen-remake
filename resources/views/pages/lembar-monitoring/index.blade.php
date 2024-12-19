@php
    function getRoleFromType($type)
    {
        $typeToRoleMap = [
            'App\Models\Admin' => 'Admin',
            'App\Models\Direktur' => 'Direktur',
            'App\Models\Wadir' => 'Wakil Direktur',
            'App\Models\Kaprodi' => 'Kaprodi',
            'App\Models\Dosen' => 'Dosen',
        ];

        return $typeToRoleMap[$type] ?? 'unknown';
    }
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lembar Monitoring</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            text-transform: uppercase;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px 12px;
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .table td {
            vertical-align: top;
        }

        .thread-message {
            padding-left: 20px;
        }

        .header-info {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            margin-bottom: 10px;
            width: 100%;
        }

        @media print {
            body {
                margin: 0;
                padding: 15px;
            }

            .table {
                width: 100%;
            }

            .table th,
            .table td {
                font-size: 11px;
                padding: 6px 8px;
            }

            @page {
                size: A4 portrait;
                margin: 10mm;
            }
        }

        @media screen {
            body {
                display: none;
            }
        }
    </style>
    <script>
        window.print();
    </script>
</head>

<body>

    <h3>POLITEKNIK SAWUNGGALIH AJI</h3>
    <h4 style="text-align: center;margin-top:-20px">LEMBAR MONITORING PELAKSANAAN PELAJARAN</h4>
    <h5 style="text-align: center;margin-top:-20px">TAHUN AKADEMIK {{ $messages->first()->jadwal->tahun }}</h5>
    <hr style="border: 2px solid black;">

    <div class="header-info">
        <div>
            <h5 style="display: inline-block; width: 150px; margin: 2px 0;">Mata Kuliah</h5>
            <h5 style="display: inline-block; margin-right: 5px; margin-left: -50px; margin: 2px 0;">:</h5>
            <h5 style="display: inline-block; margin: 2px 0;">{{ $messages->first()->matkul->nama_matkul }}</h5>
            <br>
            <h5 style="display: inline-block; width: 150px; margin: 2px 0;">Dosen</h5>
            <h5 style="display: inline-block; margin-right: 5px; margin-left: -50px; margin: 2px 0;">:</h5>
            <h5 style="display: inline-block; margin: 2px 0;">{{ $messages->first()->jadwal->dosen->nama }}</h5>
        </div>
        <div style="text-align: left;">
            <h5 style="display: inline-block; width: 150px; margin: 2px 0;">Program Studi</h5>
            <h5 style="display: inline-block; margin-right: 5px; margin-left: -50px; margin: 2px 0;">:</h5>
            <h5 style="display: inline-block; margin: 2px 0;">{{ $messages->first()->kelas->prodi->nama_prodi }}</h5>
            <br>
            <h5 style="display: inline-block; width: 150px; margin: 2px 0;">Kelas</h5>
            <h5 style="display: inline-block; margin-right: 5px; margin-left: -50px; margin: 2px 0;">:</h5>
            <h5 style="display: inline-block; margin: 2px 0;">{{ $messages->first()->kelas->nama_kelas }}</h5>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Pengirim</th>
                <th>Penerima</th>
                <th>Pemberitahuan</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($messages as $message)
                <tr>

                    <td>
                        {{ $message->sender->nama }}
                        @if ($message->sender_type)
                            <span>({{ getRoleFromType($message->sender_type) }})</span>
                        @endif
                    </td>
                    <td>
                        {{ $message->receiver->nama }}
                        @if ($message->receiver_type)
                            <span>({{ getRoleFromType($message->receiver_type) }})</span>
                        @endif
                    </td>
                    <td>{{ $message->message }}</td>
                    <td>{{ \Carbon\Carbon::parse($message->sent_at)->format('Y-m-d') }}</td>
                </tr>

                @include('pages.lembar-monitoring.replies', ['replies' => $message->replies])
            @endforeach
        </tbody>
    </table>
</body>

</html>
