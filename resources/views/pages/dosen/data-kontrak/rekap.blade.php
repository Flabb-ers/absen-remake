<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
            border: 1px solid black;
        }

        th {
            background-color: black;
            color: white;
        }

        tr:nth-child(odd) {
            background-color: lightgray;
        }

        tr:nth-child(even) {
            background-color: white;
        }

        .presentation {
            margin-top: 20px;
            padding-left: 20px;
        }

        .percentage {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .underline {
            text-decoration: underline;
            flex-grow: 1;
            margin-left: 10px;
            margin-right: 10px;
        }

        .total {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }

        .line {
            border-top: 1px solid black;
            margin-top: -5px;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>Pertemuan</th>
                <th>Materi Kuliah</th>
                <th>Daftar Pustaka</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kontrak as $ktk)  
            <tr>
                <td>{{ $ktk->pertemuan }}</td>
                <td>{{ $ktk->materi }}</td>
                <td>{{ $ktk->pustaka }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="presentation">
        Presentasi Perkuliahan
        <ol>
            <li class="percentage">Presensi/Kehadiran: 15%</li>
            <li class="percentage">Tugas: 20%</li>
            <li class="percentage">Sikap dan Keaktifan: 15%</li>
            <li class="percentage">UTS: 25%</li>
            <li class="percentage">UAS: <span class="underline">25%</span></li>
        </ol>
    </div>
    
    <div class="total">100%</div>
</body>

</html>
