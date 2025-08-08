<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kegiatan PKL - {{ $mahasiswa->nama }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            line-height: 1.6;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 16px;
            font-weight: normal;
            margin-bottom: 20px;
        }

        .info-mahasiswa {
            margin-bottom: 25px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 5px;
            vertical-align: top;
        }

        .info-table .label {
            width: 150px;
            font-weight: bold;
        }

        .info-table .separator {
            width: 20px;
            text-align: center;
        }

        .periode {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .kegiatan-list {
            margin-bottom: 20px;
        }

        .kegiatan-item {
            margin-bottom: 25px;
            border: 1px solid #ddd;
            padding: 15px;
            page-break-inside: avoid;
        }

        .kegiatan-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
        }

        .kegiatan-meta {
            font-size: 11px;
            color: #666;
        }

        .kegiatan-meta span {
            margin-right: 15px;
        }

        .kegiatan-content h3 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        .kegiatan-description {
            text-align: justify;
            margin-bottom: 10px;
        }

        .foto-info {
            font-size: 11px;
            color: #28a745;
            font-style: italic;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .page-break {
            page-break-before: always;
        }

        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
        }

        .summary h3 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #495057;
        }

        .summary-stats {
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .stat-item {
            flex: 1;
        }

        .stat-number {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
        }

        .stat-label {
            font-size: 11px;
            color: #6c757d;
        }

        .no-kegiatan {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }

        /* Print-specific styles */
        @media print {
            body {
                font-size: 11px;
            }

            .kegiatan-item {
                page-break-inside: avoid;
            }
        }

        /* Table styling for better organization */
        .kegiatan-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .kegiatan-table th,
        .kegiatan-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        .kegiatan-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }

        .kegiatan-table .no-col {
            width: 40px;
            text-align: center;
        }

        .kegiatan-table .date-col {
            width: 100px;
        }

        .kegiatan-table .time-col {
            width: 80px;
        }

        .kegiatan-table .day-col {
            width: 80px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Laporan Kegiatan Praktik Kerja Lapangan (PKL)</h1>
        <h2>{{ $mahasiswa->nama ?? 'Nama Mahasiswa' }}</h2>
    </div>

    <!-- Info Mahasiswa -->
    <div class="info-mahasiswa">
        <table class="info-table">
            <tr>
                <td class="label">Nama Mahasiswa</td>
                <td class="separator">:</td>
                <td>{{ $mahasiswa->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">NIM</td>
                <td class="separator">:</td>
                <td>{{ $mahasiswa->nim ?? '-' }}</td>
            </tr>
            @if($mahasiswa->program_studi)
            <tr>
                <td class="label">Program Studi</td>
                <td class="separator">:</td>
                <td>{{ $mahasiswa->program_studi }}</td>
            </tr>
            @endif
            @if($mahasiswa->fakultas)
            <tr>
                <td class="label">Fakultas</td>
                <td class="separator">:</td>
                <td>{{ $mahasiswa->fakultas }}</td>
            </tr>
            @endif
            @if($mahasiswa->angkatan)
            <tr>
                <td class="label">Angkatan</td>
                <td class="separator">:</td>
                <td>{{ $mahasiswa->angkatan }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Periode -->
    <div class="periode">
        <strong>Periode Kegiatan:</strong>
        @if($start_date && $end_date)
            {{ \Carbon\Carbon::parse($start_date)->format('d F Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}
        @elseif($start_date)
            Mulai {{ \Carbon\Carbon::parse($start_date)->format('d F Y') }}
        @elseif($end_date)
            Sampai {{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}
        @else
            Semua Kegiatan
        @endif
    </div>

    <!-- Summary -->
    <!-- @if($kegiatan->count() > 0)
    <div class="summary">
        <h3>Ringkasan</h3>
        <div class="summary-stats">
            <div class="stat-item">
                <div class="stat-number">{{ $kegiatan->count() }}</div>
                <div class="stat-label">Total Kegiatan</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $kegiatan->where('foto_dokumentasi', '!=', null)->count() }}</div>
                <div class="stat-label">Dengan Foto</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ $kegiatan->groupBy('tanggal')->count() }}</div>
                <div class="stat-label">Hari Aktif</div>
            </div>
        </div>
    </div>
    @endif -->

    <!-- Daftar Kegiatan -->
    @if($kegiatan->count() > 0)
    <h3 style="margin-bottom: 15px; font-size: 16px; color: #333;">Daftar Kegiatan:</h3>

    <table class="kegiatan-table">
        <thead>
            <tr>
                <th class="no-col">No</th>
                <th class="date-col">Tanggal</th>
                <th class="day-col">Hari</th>
                <th class="time-col">Jam</th>
                <th>Kegiatan</th>
                <!-- <th style="width: 80px;">Foto</th> -->
            </tr>
        </thead>
        <tbody>
            @foreach($kegiatan as $index => $item)
            <tr>
                <td class="no-col">{{ $index + 1 }}</td>
                <td class="date-col">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td class="day-col">{{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->isoFormat('dddd') }}</td>
                <td class="time-col">{{ $item->jam }}</td>
                <td>{{ $item->kegiatan }}</td>
                <!-- <td style="text-align: center;">
                    @if($item->foto_dokumentasi)
                        <span style="color: #28a745; font-size: 10px;">
                            <img src="{{Storage::url($item->foto_dokumentasi) ?? $item->foto_dokumentasi_url }}" alt="">
                        </span>
                    @else
                        <span style="color: #dc3545; font-size: 10px;">✗ Tidak</span>
                    @endif
                </td> -->
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-kegiatan">
        <p>Tidak ada kegiatan dalam periode yang dipilih.</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini digenerate secara otomatis pada {{ $generated_at->format('d F Y, H:i:s') }} WIB</p>
        <p>Halaman <span class="pagenum"></span></p>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(520, 820, "Hal: $PAGE_NUM dari $PAGE_COUNT", $font, 10);
            ');
        }
    </script>
</body>
</html>
